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

$customer_id = $_SESSION['customer_id'];
$message = "";

// CREATE
if (isset($_POST['create'])) {
    $cat_name = trim($_POST['cat_name']);
    if (!empty($cat_name)) {
        // Ensure unique category per user
        $stmt = $conn->prepare("SELECT * FROM categories WHERE cat_name = ? AND created_by = ?");
        $stmt->bind_param("si", $cat_name, $customer_id);
        $stmt->execute();
        $check = $stmt->get_result();

        if ($check->num_rows == 0) {
            $stmt = $conn->prepare("INSERT INTO categories (cat_name, created_by) VALUES (?, ?)");
            $stmt->bind_param("si", $cat_name, $customer_id);
            $stmt->execute();
            $message = "✅ Category created successfully.";
        } else {
            $message = "⚠️ Category name already exists.";
        }
    } else {
        $message = "⚠️ Category name cannot be empty.";
    }
}

// UPDATE
if (isset($_POST['update'])) {
    $cat_id = $_POST['cat_id'];
    $cat_name = trim($_POST['cat_name']);
    if (!empty($cat_name)) {
        $stmt = $conn->prepare("UPDATE categories SET cat_name = ? WHERE cat_id = ? AND created_by = ?");
        $stmt->bind_param("sii", $cat_name, $cat_id, $customer_id);
        $stmt->execute();
        $message = "✅ Category updated successfully.";
    } else {
        $message = "⚠️ Category name cannot be empty.";
    }
}

// DELETE
if (isset($_POST['delete'])) {
    $cat_id = $_POST['cat_id'];
    $stmt = $conn->prepare("DELETE FROM categories WHERE cat_id = ? AND created_by = ?");
    $stmt->bind_param("ii", $cat_id, $customer_id);
    $stmt->execute();
    $message = "🗑️ Category deleted successfully.";
}

// RETRIEVE
$stmt = $conn->prepare("SELECT * FROM categories WHERE created_by = ?");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$categories = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container">
        <h2>📂 Manage Categories</h2>
        <?php if ($message) echo "<p class='message'>$message</p>"; ?>

        <!-- CREATE FORM -->
        <form method="POST" class="form-box">
            <input type="text" name="cat_name" placeholder="Enter new category" required>
            <button type="submit" name="create">Create Category</button>
        </form>

        <h3>My Categories</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $categories->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['cat_id'] ?></td>
                    <td><?= htmlspecialchars($row['cat_name']) ?></td>
                    <td>
                        <!-- UPDATE FORM -->
                        <form method="POST" style="display:inline-block;">
                            <input type="hidden" name="cat_id" value="<?= $row['cat_id'] ?>">
                            <input type="text" name="cat_name" value="<?= htmlspecialchars($row['cat_name']) ?>" required>
                            <button type="submit" name="update">Update</button>
                        </form>

                        <!-- DELETE FORM -->
                        <form method="POST" style="display:inline-block;" onsubmit="return confirm('Delete this category?');">
                            <input type="hidden" name="cat_id" value="<?= $row['cat_id'] ?>">
                            <button type="submit" name="delete">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
