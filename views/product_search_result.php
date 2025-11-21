<?php
session_start();
require_once '../settings/core.php';
require_once '../controllers/brand_controller.php';
require_once '../controllers/category_controller.php';

// Fetch categories/brands
$categories = get_all_categories_ctr();
$brands = get_all_brands_ctr();

// Get initial query
$initialQuery = htmlspecialchars($_GET['query'] ?? '');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TasteConnect | Search Results</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-left">
          <a href="index.php" class="logo"><span>TC</span> TasteConnect</a>
          <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="all_product.php">Discover</a>
            <a href="#">Vendors</a>
          </div>
        </div>
        <div class="nav-search">
            <i class="fas fa-search"></i>
            <form action="product_search_result.php" method="get" style="width:100%; display:flex;">
                <input type="text" name="query" value="<?= $initialQuery ?>" placeholder="Search...">
            </form>
        </div>
        <div class="nav-right">
             <div style="display:flex; align-items:center; gap:5px; margin-right:15px; cursor:pointer; font-size:0.9rem;">
                <i class="fas fa-globe"></i> English
            </div>
            <?php if (!isset($_SESSION['customer_id'])): ?>
                <a href="login/login.php" class="btn btn-outline">Login</a>
                <a href="login/register.php" class="btn btn-primary">Register</a>
            <?php else: ?>
                <a href="#" class="btn btn-outline"><i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['customer_name']); ?></a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1>Search Results</h1>
            <p>Showing results for "<?= $initialQuery ?>"</p>
        </div>

        <div class="discover-layout">
            <!-- Sidebar -->
            <aside class="filters-sidebar">
                <div class="filter-header"><i class="fas fa-filter"></i> Refine Search</div>

                <div class="filter-group">
                    <label>Search Query</label>
                    <!-- ID 'searchQuery' is targeted by your JS -->
                    <input type="text" id="searchQuery" class="filter-select" value="<?= $initialQuery ?>">
                </div>

                <div class="filter-group">
                    <label>Cuisine Type</label>
                    <div class="checkbox-list">
                         <?php if(!empty($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                            <label class="checkbox-item">
                                <input type="checkbox" class="category-checkbox" value="<?= $cat['cat_id'] ?>">
                                <?= htmlspecialchars($cat['cat_name']) ?>
                            </label>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="filter-group">
                    <label>Price Range</label>
                    <select id="priceFilter" class="filter-select">
                        <option value="">All Prices</option>
                        <option value="low">₵ (Budget)</option>
                        <option value="medium">₵₵ (Standard)</option>
                        <option value="high">₵₵₵ (Premium)</option>
                    </select>
                </div>

                <!-- Hidden input to maintain compatibility with JS expecting brand filter -->
                <select id="brandFilter" style="display:none;">
                    <option value="0">All Brands</option>
                </select>

                <button id="resetFilters" class="btn btn-outline btn-full">Reset Filters</button>
            </aside>

            <!-- Grid -->
            <main>
                <div id="product-list" class="grid-container">
                     <div style="grid-column: 1/-1; text-align: center; padding: 2rem; color: var(--gray-text);">
                        <i class="fas fa-spinner fa-spin fa-2x"></i><br>Searching...
                     </div>
                </div>
                
                <nav>
                    <ul class="pagination" id="pagination-links"></ul>
                </nav>
            </main>
        </div>
    </div>

    <!-- Uses the same JS file for consistency -->
    <script src="../js/product_frontend.js"></script> 
</body>
</html>