<?php
session_start();
if (!isset($_SESSION['vendor_id'])) { header("Location: ../login/vendor_login.php"); exit; }
require_once "../settings/db_cred.php";

$vendor_id = $_SESSION['vendor_id'];

// Handle Deletion
if(isset($_GET['delete_id'])) {
    $del_id = $_GET['delete_id'];
    // Delete image file first
    $img_sql = "SELECT product_image FROM products WHERE product_id = ? AND vendor_id = ?";
    $stmt = $conn->prepare($img_sql);
    $stmt->bind_param("ii", $del_id, $vendor_id);
    $stmt->execute();
    $img_res = $stmt->get_result()->fetch_assoc();
    if ($img_res && $img_res['product_image']) {
        $file_path = "../" . $img_res['product_image'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

    $del_sql = "DELETE FROM products WHERE product_id = ? AND vendor_id = ?";
    $stmt = $conn->prepare($del_sql);
    $stmt->bind_param("ii", $del_id, $vendor_id);
    if($stmt->execute()) {
        header("Location: menu_admin.php?msg=deleted");
        exit();
    }
}

// Fetch Products
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

// Fetch Brands for Form (Assuming brands are generic or vendor specific, fetching all for now)
$brand_sql = "SELECT * FROM brands";
$brands = $conn->query($brand_sql)->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Menu | Vendor Dashboard</title>
    <link rel="stylesheet" href="../assets/dashboard_style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                <button class="btn-submit" onclick="openModal('addProductModal')">+ Add Item</button>
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
                    <?php if(empty($products)): ?>
                        <tr><td colspan="5" style="text-align:center; padding:2rem;">No menu items found.</td></tr>
                    <?php else: ?>
                        <?php foreach($products as $p): ?>
                        <tr>
                            <td>
                                <?php if($p['product_image']): ?>
                                    <img src="../<?= $p['product_image'] ?>" style="width:50px; height:50px; border-radius:6px; object-fit:cover;">
                                <?php else: ?>
                                    <div style="width:50px; height:50px; background:#eee; border-radius:6px;"></div>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($p['product_title']) ?></td>
                            <td>₵<?= number_format($p['product_price'], 2) ?></td>
                            <td><span class="badge badge-gray"><?= htmlspecialchars($p['cat_name']) ?></span></td>
                            <td>
                                <button class="action-btn btn-white" onclick="editProduct(<?= htmlspecialchars(json_encode($p)) ?>)" style="padding:0.4rem;">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="menu_admin.php?delete_id=<?= $p['product_id'] ?>" class="action-btn btn-white" style="padding:0.4rem; color:red;" onclick="return confirm('Delete this item permanently?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </main>
    </div>

    <!-- Add/Edit Product Modal -->
    <div id="addProductModal" class="modal">
        <div class="modal-content">
            <h3 id="modalTitle">Add Menu Item</h3>
            <form id="productForm" enctype="multipart/form-data">
                <input type="hidden" name="product_id" id="edit_product_id">
                <input type="hidden" name="action" id="form_action" value="add">

                <div class="form-group">
                    <label>Item Name</label>
                    <input type="text" name="productTitle" id="edit_title" required>
                </div>
                
                <div class="form-group">
                    <label>Price (₵)</label>
                    <input type="number" name="productPrice" id="edit_price" step="0.01" required>
                </div>
                
                <div class="form-group">
                    <label>Category</label>
                    <select name="productCategory" id="edit_category" required>
                        <option value="">Select Category</option>
                        <?php foreach($cats as $c): ?>
                            <option value="<?= $c['cat_id'] ?>"><?= $c['cat_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Brand / Cuisine Type</label>
                    <select name="productBrand" id="edit_brand" required>
                        <option value="">Select Type</option>
                        <?php foreach($brands as $b): ?>
                            <option value="<?= $b['brand_id'] ?>"><?= $b['brand_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="productDescription" id="edit_desc" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label>Product Image</label>
                    <input type="file" name="productImage" accept="image/*">
                    <small id="current_image_label" style="color:gray; display:none;">Current image will be kept if empty.</small>
                </div>
                
                <div class="modal-actions">
                    <button type="button" onclick="closeModal('addProductModal')" class="btn-cancel">Cancel</button>
                    <button type="submit" class="btn-submit">Save Item</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).style.display = 'block';
        }
        
        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
            resetForm();
        }

        function resetForm() {
            document.getElementById('productForm').reset();
            document.getElementById('modalTitle').innerText = 'Add Menu Item';
            document.getElementById('form_action').value = 'add';
            document.getElementById('edit_product_id').value = '';
            document.getElementById('current_image_label').style.display = 'none';
        }

        function editProduct(product) {
            document.getElementById('modalTitle').innerText = 'Edit Menu Item';
            document.getElementById('form_action').value = 'edit';
            document.getElementById('edit_product_id').value = product.product_id;
            
            document.getElementById('edit_title').value = product.product_title;
            document.getElementById('edit_price').value = product.product_price;
            document.getElementById('edit_category').value = product.product_cat;
            document.getElementById('edit_brand').value = product.product_brand;
            document.getElementById('edit_desc').value = product.product_desc;
            
            if(product.product_image) {
                document.getElementById('current_image_label').style.display = 'block';
                document.getElementById('current_image_label').innerText = "Current: " + product.product_image.split('/').pop();
            }

            openModal('addProductModal');
        }

        // AJAX Submission
        $(document).ready(function() {
            $('#productForm').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                Swal.fire({
                    title: 'Saving...',
                    didOpen: () => Swal.showLoading()
                });

                $.ajax({
                    url: '../actions/add_product_action.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(res) {
                        if(res.status === 'success') {
                            Swal.fire('Success', res.message, 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Server error.', 'error');
                    }
                });
            });
        });
    </script>
</body>
</html>