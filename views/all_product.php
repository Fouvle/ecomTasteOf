<?php
session_start();
require_once '../settings/core.php';
require_once '../controllers/brand_controller.php';
require_once '../controllers/category_controller.php';

// Fetch categories and brands for the filters
$categories = get_all_categories_ctr();
$brands = get_all_brands_ctr();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TasteConnect | Discover Vendors</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Use your custom CSS instead of Bootstrap for the matching look -->
    <link rel="stylesheet" href="assets/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- jQuery for AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    
    <!-- Navbar (Consistent with Index) -->
    <nav class="navbar">
        <div class="nav-left">
          <a href="index.php" class="logo">
            <span>TC</span> TasteConnect
          </a>
          <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="all_product.php" style="color:var(--primary-orange);">Discover</a>
            <a href="#">Vendors</a>
            <a href="#">Book Experience</a>
            <a href="#">About Us</a>
          </div>
        </div>

        <div class="nav-search">
            <i class="fas fa-search"></i>
            <form action="product_search_result.php" method="get" style="width:100%; display:flex;">
                <input type="text" name="query" placeholder="Search for restaurants, dishes...">
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

    <!-- Main Content -->
    <div class="container">
        
        <!-- Page Header -->
        <div class="page-header">
            <h1>Discover Vendors</h1>
            <p>Explore authentic culinary experiences across Ghana</p>
        </div>

        <!-- Layout: Sidebar + Grid -->
        <div class="discover-layout">
            
            <!-- Left Sidebar Filters -->
            <aside class="filters-sidebar">
                <div class="filter-header">
                    <i class="fas fa-filter"></i> Filters
                </div>

                <!-- 1. Location Filter -->
                <div class="filter-group">
                    <label>Location</label>
                    <select id="locationFilter" class="filter-select">
                        <option value="">All Locations</option>
                        <option value="Accra">Accra</option>
                        <option value="Kumasi">Kumasi</option>
                        <option value="Takoradi">Takoradi</option>
                        <option value="Tamale">Tamale</option>
                    </select>
                </div>

                <!-- 2. Cuisine Type (Categories) -->
                <div class="filter-group">
                    <label>Cuisine Type</label>
                    <div class="checkbox-list">
                        <!-- 'All' option logic handled in JS, just visual here -->
                        <?php if(!empty($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                            <label class="checkbox-item">
                                <input type="checkbox" class="category-checkbox" value="<?= $cat['cat_id'] ?>">
                                <?= htmlspecialchars($cat['cat_name']) ?>
                            </label>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="font-size:0.8rem; color:gray;">No categories found.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- 3. Price Range -->
                <div class="filter-group">
                    <label>Price Range</label>
                    <select id="priceFilter" class="filter-select">
                        <option value="">All Prices</option>
                        <option value="low">₵ (Budget)</option>
                        <option value="medium">₵₵ (Standard)</option>
                        <option value="high">₵₵₵ (Premium)</option>
                    </select>
                </div>

                <!-- 4. Brands (Hidden/Optional if you want to use Brand ID for filtering via select) -->
                <input type="hidden" id="brandFilter" value="0"> 

                <button id="resetFilters" class="btn btn-outline btn-full">Clear All Filters</button>
            </aside>

            <!-- Right Content Grid -->
            <main>
                <!-- Product List Container (Populated by AJAX) -->
                <div id="product-list" class="grid-container">
                    <!-- AJAX content will load cards here. 
                         The CSS class .card, .card-image, etc. in style.css 
                         will style the injected HTML. -->
                         
                     <!-- Loading Placeholder -->
                     <div style="grid-column: 1/-1; text-align: center; padding: 2rem; color: var(--gray-text);">
                        <i class="fas fa-spinner fa-spin fa-2x"></i><br>Loading vendors...
                     </div>
                </div>

                <!-- Pagination -->
                <nav>
                    <ul class="pagination" id="pagination-links">
                        <!-- AJAX will populate pagination -->
                    </ul>
                </nav>
            </main>

        </div>
    </div>

    <!-- JS Logic -->
    <script>
        // Pass any initial PHP variables to JS if needed
        const initialCategory = 0; 
        const initialBrand = 0;
    </script>
    <script src="../js/product_frontend.js"></script>
</body>
</html>