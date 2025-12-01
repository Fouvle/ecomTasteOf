<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once "../settings/db_cred.php";

// Fetch Categories for the Filter Sidebar
$catSql = "SELECT * FROM categories ORDER BY cat_name ASC";
$catResult = $conn->query($catSql);
$categories = $catResult->fetch_all(MYSQLI_ASSOC);

// Fetch Distinct Locations (Cities) from Vendors for the Filter Sidebar
$locSql = "SELECT DISTINCT c.customer_city 
           FROM vendors v 
           JOIN customer c ON v.customer_id = c.customer_id 
           ORDER BY c.customer_city ASC";
$locResult = $conn->query($locSql);
$locations = $locResult->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Discover Authentic Flavors | TasteConnect</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
        /* =========================================
           1. Global Styles (Formerly assets/style.css)
           ========================================= */
        :root {
            --primary-orange: #ea580c;
            --primary-hover: #c2410c;
            --dark-text: #111827;
            --gray-text: #6b7280;
            --light-bg: #f9fafb;
            --white: #ffffff;
            --border-color: #e5e7eb;
            --font-main: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        * { box-sizing: border-box; }

        body {
            font-family: var(--font-main);
            background-color: var(--light-bg);
            color: var(--dark-text);
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        a { text-decoration: none; color: inherit; }
        ul { list-style: none; padding: 0; margin: 0; }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.6rem 1.2rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: var(--primary-orange);
            color: var(--white);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        .btn-outline {
            border: 1px solid #d1d5db;
            background: var(--white);
            color: var(--dark-text);
        }

        .btn-outline:hover {
            background: #f3f4f6;
            border-color: #9ca3af;
        }

        /* Navbar */
        .navbar {
            background: var(--white);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: var(--shadow-sm);
        }

        .nav-left { display: flex; align-items: center; gap: 2rem; }

        .logo {
            display: flex;
            align-items: center;
            font-weight: bold;
            font-size: 1.2rem;
            color: var(--primary-orange);
        }

        .logo span {
            background: var(--primary-orange);
            color: white;
            padding: 4px 8px;
            border-radius: 6px;
            margin-right: 8px;
            font-size: 1rem;
        }

        .nav-links { display: flex; gap: 1.5rem; }
        .nav-links a { color: var(--gray-text); font-weight: 500; transition: color 0.2s; }
        .nav-links a:hover { color: var(--primary-orange); }
        .nav-right { display: flex; align-items: center; gap: 1rem; }

        /* =========================================
           2. Page Specific Layout (Discovery)
           ========================================= */
        .discover-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 2rem;
            margin: 2rem auto;
            max-width: 1200px;
            padding: 0 1rem;
            align-items: start;
        }

        .filters-sidebar {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            position: sticky;
            top: 100px; /* Offset for sticky navbar */
        }

        .filter-group {
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #f3f4f6;
            padding-bottom: 1rem;
        }

        .filter-group:last-child { border-bottom: none; }
        
        .filter-label {
            font-weight: 600;
            margin-bottom: 0.8rem;
            display: block;
            color: var(--dark-text);
        }

        .filter-input {
            width: 100%;
            padding: 0.6rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            margin-bottom: 0.5rem;
            font-family: inherit;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            color: var(--gray-text);
            cursor: pointer;
            font-size: 0.95rem;
        }
        
        .checkbox-item input { 
            accent-color: var(--primary-orange); 
            width: 16px; 
            height: 16px; 
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 2rem;
        }

        /* Product Card Styling */
        .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            transition: 0.2s;
            display: flex;
            flex-direction: column;
        }
        .product-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); 
        }
        
        .card-img { 
            height: 180px; 
            width: 100%; 
            object-fit: cover; 
            background: #eee; 
        }
        
        .card-content { 
            padding: 1rem; 
            flex: 1; 
            display: flex; 
            flex-direction: column; 
        }
        
        .card-title { 
            font-weight: bold; 
            font-size: 1.1rem; 
            margin: 0 0 0.5rem 0; 
            color: var(--dark-text);
        }
        
        .card-vendor { 
            font-size: 0.85rem; 
            color: var(--gray-text); 
            margin-bottom: 0.3rem; 
            display: flex; 
            align-items: center; 
            gap: 0.4rem; 
        }

        .card-price { 
            color: var(--primary-orange); 
            font-weight: bold; 
            font-size: 1.1rem; 
            margin-top: auto; 
            padding-top: 1rem;
        }
        
        @media(max-width: 768px) {
            .navbar { flex-direction: column; gap: 1rem; padding: 1rem; }
            .nav-left, .nav-right, .nav-links { width: 100%; justify-content: center; }
            .nav-links { display: none; } /* Simplified mobile menu */
            
            .discover-layout { grid-template-columns: 1fr; }
            .filters-sidebar { position: static; margin-bottom: 2rem; }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <div class="nav-left">
      <a href="../index.php" class="logo"><span>TC</span> TasteConnect</a>
      <div class="nav-links">
        <a href="../index.php">Home</a>
        <a href="all_products.php" style="color:var(--primary-orange);">Discover</a>
        <a href="#">Vendors</a>
      </div>
    </div>

    <div class="nav-right">
      <?php if (!isset($_SESSION['customer_id'])): ?>
        <a href="../login/login.php" class="btn btn-outline">Login</a>
        <a href="../login/register.php" class="btn btn-primary">Register</a>
      <?php else: ?>
        <a href="#" class="btn btn-outline"><i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['customer_name']); ?></a>
      <?php endif; ?>
    </div>
</nav>

<div class="discover-layout">
    <!-- Sidebar Filters -->
    <aside class="filters-sidebar">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
            <h3>Filters</h3>
            <a href="#" id="reset-filters" style="font-size:0.85rem; color:var(--primary-orange);">Reset</a>
        </div>

        <!-- 1. Search Query -->
        <div class="filter-group">
            <label class="filter-label">Search</label>
            <input type="text" id="searchInput" class="filter-input" placeholder="Dish, restaurant, or keyword...">
        </div>

        <!-- 2. Location (Dynamic from DB) -->
        <div class="filter-group">
            <label class="filter-label">Location</label>
            <select id="locationFilter" class="filter-input">
                <option value="">All Locations</option>
                <?php foreach ($locations as $loc): ?>
                    <option value="<?= htmlspecialchars($loc['customer_city']) ?>">
                        <?= htmlspecialchars($loc['customer_city']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- 3. Price Range -->
        <div class="filter-group">
            <label class="filter-label">Max Price (₵)</label>
            <input type="range" id="priceRange" min="1" max="500" value="500" style="width:100%; accent-color:var(--primary-orange);">
            <div style="display:flex; justify-content:space-between; font-size:0.85rem; color:gray;">
                <span>₵0</span>
                <span id="priceValue">₵500</span>
            </div>
        </div>

        <!-- 4. Categories (Dynamic from DB) -->
        <div class="filter-group">
            <label class="filter-label">Categories</label>
            <div style="max-height: 200px; overflow-y: auto;">
                <?php foreach ($categories as $cat): ?>
                <label class="checkbox-item">
                    <input type="checkbox" class="cat-checkbox" value="<?= $cat['cat_id'] ?>"> 
                    <?= htmlspecialchars($cat['cat_name']) ?>
                </label>
                <?php endforeach; ?>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main>
        <h1 style="margin-top:0;">Discover Vendors & Menu Items</h1>
        <p style="color:gray; margin-bottom:2rem;">Showing results based on your preferences</p>
        
        <div id="products-container" class="product-grid">
            <!-- JS will populate this -->
            <div style="grid-column:1/-1; text-align:center; padding:3rem;">
                <i class="fas fa-spinner fa-spin fa-2x" style="color:var(--primary-orange);"></i>
            </div>
        </div>
    </main>
</div>

<script src="../js/discovery.js"></script>

</body>
</html>