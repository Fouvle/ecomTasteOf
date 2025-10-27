<?php
session_start();
require_once '../settings/core.php';
require_once '../controllers/product_controller.php';
require_once '../controllers/brand_controller.php';
require_once '../controllers/category_controller.php';

// Security Check: Redirect if not logged in or not admin
redirectIfNotLoggedIn();
if (!isAdmin()) {
    header('Location: ../login/login.php');
    exit();
}

// Fetch categories and brands for the dropdowns
$categories = get_all_categories_ctr();
$brands = get_all_brands_ctr();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Products</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #f8f9fa; }
        .card { border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header-bg { background: linear-gradient(135deg, #d77a61, #f6bd60); color: white; border-radius: 15px 15px 0 0; padding: 20px; }
        .product-img { max-width: 80px; height: auto; border-radius: 5px; }
        .table-container { max-height: 70vh; overflow-y: auto; }
    </style>
</head>
<body>
    <div class="container my-5">
        <div class="card">
            <div class="header-bg">
                <h1 class="mb-0">Product Management</h1>
                <p class="text-white-50">Add and Edit Experiences/Products for TasteConnect.</p>
            </div>
            <div class="card-body">

                <h3 class="mb-3" id="form-title">Add New Product</h3>
                <form id="productForm" class="row g-3 mb-5" enctype="multipart/form-data">
                    <input type="hidden" name="product_id" id="productId" value="">
                    
                    <div class="col-md-6">
                        <label for="productTitle" class="form-label">Product Title</label>
                        <input type="text" name="product_title" id="productTitle" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="productPrice" class="form-label">Product Price (GHS)</label>
                        <input type="number" step="0.01" name="product_price" id="productPrice" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label for="productCategory" class="form-label">Product Category</label>
                        <select name="product_cat" id="productCategory" class="form-select" required>
                            <option value="">Select Category...</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['cat_id'] ?>"><?= htmlspecialchars($cat['cat_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="productBrand" class="form-label">Product Brand</label>
                        <select name="product_brand" id="productBrand" class="form-select" required>
                            <option value="">Select Brand...</option>
                            <?php foreach ($brands as $brand): ?>
                                <option value="<?= $brand['brand_id'] ?>"><?= htmlspecialchars($brand['brand_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="productKeywords" class="form-label">Keywords (e.g., 'jollof, tour')</label>
                        <input type="text" name="product_keywords" id="productKeywords" class="form-control">
                    </div>

                    <div class="col-md-12">
                        <label for="productDescription" class="form-label">Product Description</label>
                        <textarea name="product_desc" id="productDescription" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="col-md-8">
                        <label for="productImage" class="form-label">Product Image (Upload new image)</label>
                        <input type="file" name="product_image" id="productImage" class="form-control" accept="image/*" required>
                        <small class="text-muted" id="currentImageHint">Existing image will be replaced on update.</small>
                    </div>
                    
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100" id="submitBtn">Add Product</button>
                        <button type="button" class="btn btn-secondary w-100 ms-2 d-none" id="cancelEditBtn">Cancel Edit</button>
                    </div>
                </form>

                <h3 class="mb-4">Current Products/Experiences</h3>
                <div class="table-container">
                    <table class="table table-striped table-hover" id="productTable">
                        <thead class="table-dark">
                            <tr>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Price</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td colspan="6" class="text-center">Loading products...</td></tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    
    <script src="../js/product.js"></script>
</body>
</html>