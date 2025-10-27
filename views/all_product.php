<?php
session_start();
require_once '../settings/core.php';
require_once '../controllers/brand_controller.php';
require_once '../controllers/category_controller.php';

// Fetch categories and brands for the filter dropdowns
$categories = get_all_categories_ctr();
$brands = get_all_brands_ctr();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TasteConnect - All Products & Experiences</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { background-color: #f8f9fa; }
        .product-card { border: none; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); transition: transform 0.2s; }
        .product-card:hover { transform: translateY(-5px); }
        .product-image { height: 200px; object-fit: cover; border-radius: 10px 10px 0 0; }
        .price { font-size: 1.5rem; color: #d77a61; font-weight: bold; }
        .filter-section { background-color: #fff; padding: 20px; border-radius: 10px; margin-bottom: 30px; }
    </style>
</head>
<body>
    <?php include 'header_nav.php'; // Assume a reusable navigation header ?>

    <div class="container my-5">
        <h1 class="mb-4 text-center">Discover Ghana's Finest Culinary Experiences</h1>

        <div class="filter-section row g-3">
            <div class="col-md-5">
                <input type="text" id="searchQuery" class="form-control" placeholder="Search products by title or keywords...">
            </div>
            
            <div class="col-md-3">
                <select id="categoryFilter" class="form-select">
                    <option value="0">Filter by Category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['cat_id'] ?>"><?= htmlspecialchars($cat['cat_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <select id="brandFilter" class="form-select">
                    <option value="0">Filter by Brand/Vendor</option>
                    <?php foreach ($brands as $brand): ?>
                        <option value="<?= $brand['brand_id'] ?>"><?= htmlspecialchars($brand['brand_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-1">
                <button id="resetFilters" class="btn btn-outline-secondary w-100">Reset</button>
            </div>
        </div>

        <div id="product-list" class="row row-cols-1 row-cols-md-3 g-4">
            </div>

        <div class="d-flex justify-content-center mt-5">
            <nav>
                <ul class="pagination" id="pagination-links">
                    </ul>
            </nav>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/product_frontend.js"></script>
</body>
</html>