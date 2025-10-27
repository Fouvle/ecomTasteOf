<?php
session_start();
require_once '../settings/db_class.php'; 

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
    header('Location: ../login/login.php');
    exit();
}

// Check if user is admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../login/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// --- HANDLE CREATE ---
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_brand'])) {
    $brand_name = trim($_POST['brand_name']);
    $category_id = intval($_POST['category_id']);

    // Check for empty fields
    if ($brand_name === '' || !$category_id) {
        $errors[] = "Brand name and category are required.";
    } else {
        // Check uniqueness
        $stmt = $db->prepare("SELECT COUNT(*) FROM brands WHERE name = ? AND category_id = ? AND user_id = ?");
        $stmt->execute([$brand_name, $category_id, $user_id]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "This brand already exists in the selected category.";
        }
    }

    // Insert if no errors
    if (empty($errors)) {
        $stmt = $db->prepare("INSERT INTO brands (name, category_id, user_id) VALUES (?, ?, ?)");
        $stmt->execute([$brand_name, $category_id, $user_id]);
        header('Location: brand.php');
        exit;
    }
}

// --- HANDLE UPDATE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_brand'])) {
    $brand_id = intval($_POST['brand_id']);
    $brand_name = trim($_POST['brand_name']);

    // Fetch current brand
    $stmt = $db->prepare("SELECT * FROM brands WHERE id = ? AND user_id = ?");
    $stmt->execute([$brand_id, $user_id]);
    $brand = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($brand && $brand_name !== '') {
        // Check uniqueness
        $stmt = $db->prepare("SELECT COUNT(*) FROM brands WHERE name = ? AND category_id = ? AND user_id = ? AND id != ?");
        $stmt->execute([$brand_name, $brand['category_id'], $user_id, $brand_id]);
        if ($stmt->fetchColumn() == 0) {
            $stmt = $db->prepare("UPDATE brands SET name = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$brand_name, $brand_id, $user_id]);
            header('Location: brand.php');
            exit;
        } else {
            $errors[] = "This brand already exists in the selected category.";
        }
    } else {
        $errors[] = "Invalid brand or name.";
    }
}

// --- HANDLE DELETE ---
if (isset($_GET['delete'])) {
    $brand_id = intval($_GET['delete']);
    $stmt = $db->prepare("DELETE FROM brands WHERE id = ? AND user_id = ?");
    $stmt->execute([$brand_id, $user_id]);
    header('Location: brand.php');
    exit;
}

// --- FETCH CATEGORIES ---
$stmt = $db->prepare("SELECT * FROM categories ORDER BY name ASC");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// --- FETCH BRANDS GROUPED BY CATEGORY ---
$stmt = $db->prepare("
    SELECT b.*, c.name AS category_name
    FROM brands b
    JOIN categories c ON b.category_id = c.id
    WHERE b.user_id = ?
    ORDER BY c.name, b.name
");
$stmt->execute([$user_id]);
$brands = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Organize brands by category
$brands_by_category = [];
foreach ($brands as $brand) {
    $brands_by_category[$brand['category_name']][] = $brand;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Brands</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4">Brands Management</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?= implode('<br>', $errors) ?>
        </div>
    <?php endif; ?>

    <!-- CREATE FORM -->
    <div class="card mb-4">
        <div class="card-header">Add New Brand</div>
        <div class="card-body">
            <form method="post" class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="brand_name" class="form-control" placeholder="Brand Name" required>
                </div>
                <div class="col-md-4">
                    <select name="category_id" class="form-select" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="create_brand" class="btn btn-primary w-100">Add Brand</button>
                </div>
            </form>
        </div>
    </div>

    <!-- BRANDS LIST -->
    <?php foreach ($brands_by_category as $category_name => $brands): ?>
        <div class="card mb-3">
            <div class="card-header bg-secondary text-white">
                <?= htmlspecialchars($category_name) ?>
            </div>
            <ul class="list-group list-group-flush">
                <?php foreach ($brands as $brand): ?>
                    <li class="list-group-item d-flex align-items-center justify-content-between">
                        <!-- Inline edit form -->
                        <form method="post" class="d-flex align-items-center gap-2" style="flex:1;">
                            <input type="hidden" name="brand_id" value="<?= $brand['id'] ?>">
                            <input type="text" name="brand_name" value="<?= htmlspecialchars($brand['name']) ?>" class="form-control form-control-sm" style="max-width:200px;" required>
                            <button type="submit" name="update_brand" class="btn btn-outline-success btn-sm">Update</button>
                        </form>
                        <a href="?delete=<?= $brand['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this brand?')">Delete</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>

</div>
</body>
</html>