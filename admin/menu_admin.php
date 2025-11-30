<?php
session_start();
if (!isset($_SESSION['vendor_id'])) { header("Location: ../login/vendor_login.php"); exit; }
require_once "../settings/db_cred.php";

$vendor_id = $_SESSION['vendor_id'];

// Handle Deletion
if(isset($_GET['delete_id'])) {
    $del_id = $_GET['delete_id'];
    $del_sql = "DELETE FROM products WHERE product_id = ? AND vendor_id = ?";
    $stmt = $conn->prepare($del_sql);
    $stmt->bind_param("ii", $del_id, $vendor_id);
    $stmt->execute();
}

// Fetch Products with Category Name
$sql = "SELECT p.*, c.cat_name 
        FROM products p 
        JOIN categories c ON p.product_cat = c.cat_id 
        WHERE p.vendor_id = ? 
        ORDER BY p.product_id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch Categories for Form
$cat_sql = "SELECT * FROM categories";
$cats = $conn->query($cat_sql)->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Menu | Vendor Dashboard</title>
    <link rel="stylesheet" href="../assets/dashboard_style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="dashboard-wrapper">
        <aside class="sidebar">
            <nav class="side-nav">
                <a href="vendor_dashboard.php" class="nav-item"><i class="fas fa-th-large"></i> Overview</a>
                <a href="events_admin.php" class="nav-item"><i class="fas fa-calendar-alt"></i> Events</a>
                <a href="menu_admin.php" class="nav-item active"><i class="fas fa-utensils"></i> Menu Items</a>
                <a href="bookings_admin.php" class="nav-item"><i class="fas fa-clipboard-list"></i> Bookings</a>
                <a href="payments_admin.php" class="nav-item"><i class="fas fa-wallet"></i> Payments</a>
                <a href="analytics_admin.php" class="nav-item"><i class="fas fa-chart-bar"></i> Analytics</a>
                <a href="reviews_admin.php" class="nav-item"><i class="fas fa-comment-alt"></i> Reviews</a>
                <a href="settings_admin.php" class="nav-item"><i class="fas fa-cog"></i> Settings</a>
            </nav>
        </aside>

        <main class="content-area">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem;">
                <h2>Menu Management</h2>
                <button class="btn-submit" onclick="document.getElementById('addProductModal').style.display='block'">+ Add Item</button>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($products as $p): ?>
                    <tr>
                        <td><img src="../<?= $p['product_image'] ?>" style="width:40px; height:40px; border-radius:6px; object-fit:cover;"></td>
                        <td><?= htmlspecialchars($p['product_title']) ?></td>
                        <td>₵<?= $p['product_price'] ?></td>
                        <td><span class="badge badge-gray"><?= $p['cat_name'] ?></span></td>
                        <td>
                            <a href="#" class="action-btn btn-white" style="display:inline-block; padding:0.3rem;">Edit</a>
                            <a href="menu_admin.php?delete_id=<?= $p['product_id'] ?>" class="action-btn btn-white" style="display:inline-block; padding:0.3rem; color:red;" onclick="return confirm('Delete item?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>

    <!-- Add Product Modal -->
    <div id="addProductModal" class="modal">
        <div class="modal-content">
            <h3>Add Menu Item</h3>
            <form action="../actions/add_product_action.php" method="POST" enctype="multipart/form-data">
                <div class="form-group"><label>Item Name</label><input type="text" name="productTitle" required></div>
                <div class="form-group"><label>Price (₵)</label><input type="number" name="productPrice" required></div>
                <div class="form-group"><label>Category</label>
                    <select name="productCategory" required>
                        <?php foreach($cats as $c): ?>
                            <option value="<?= $c['cat_id'] ?>"><?= $c['cat_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label>Description</label><textarea name="productDescription"></textarea></div>
                <!-- Brand logic simplified for brevity (could default to vendor's brand ID) -->
                <input type="hidden" name="productBrand" value="1"> 
                <div class="modal-actions">
                    <button type="button" onclick="document.getElementById('addProductModal').style.display='none'" class="btn-cancel">Cancel</button>
                    <button type="submit" class="btn-submit">Add Item</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>