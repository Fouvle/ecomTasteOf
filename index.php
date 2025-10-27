<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TasteConnect | Home</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f6f9;
      margin: 0;
      padding: 0;
    }

    /* Navbar */
    .navbar {
      background: #2c3e50;
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }

    .navbar-left, .navbar-right {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .navbar a, .navbar button {
      text-decoration: none;
      padding: 0.5rem 1rem;
      background: #3498db;
      color: #fff;
      border-radius: 5px;
      transition: 0.3s;
      border: none;
      cursor: pointer;
      font-size: 14px;
    }

    .navbar a:hover, .navbar button:hover {
      background: #2980b9;
    }

    /* Dropdown */
    .dropdown {
      position: relative;
      display: inline-block;
    }

    .dropdown-content {
      display: none;
      position: absolute;
      right: 0;
      background-color: #3498db;
      min-width: 180px;
      box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
      z-index: 10;
      border-radius: 5px;
    }

    .dropdown-content a, .dropdown-content form button {
      color: white;
      padding: 10px 16px;
      text-decoration: none;
      display: block;
      background: #3498db;
      border: none;
      text-align: left;
      width: 100%;
      box-sizing: border-box;
    }

    .dropdown-content a:hover, .dropdown-content form button:hover {
      background: #2980b9;
    }

    .dropdown:hover .dropdown-content {
      display: block;
    }

    /* Search & Filters */
    .search-bar {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .search-bar input, .search-bar select {
      padding: 0.5rem;
      border-radius: 5px;
      border: 1px solid #ccc;
      font-size: 14px;
    }

    .search-bar button {
      background: #1abc9c;
    }

    .search-bar button:hover {
      background: #16a085;
    }

    /* Welcome Section */
    .container {
      text-align: center;
      margin-top: 8%;
    }

    h2 {
      color: #2c3e50;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar">
    <div class="navbar-left">
      <a href="index.php" style="background:#1abc9c;">TasteConnect</a>
      <a href="all_product.php">All Products</a>
    </div>

    <div class="search-bar">
      <form action="all_product.php" method="get" style="display:flex;gap:0.5rem;">
        <input type="text" name="q" placeholder="Search products..." aria-label="Search products">
        <select name="cat_id">
          <option value="">All Categories</option>
          <!-- You can dynamically populate these using PHP -->
          <option value="1">Street Food</option>
          <option value="2">Fine Dining</option>
        </select>
        <select name="brand_id">
          <option value="">All Brands</option>
          <!-- You can dynamically populate these using PHP -->
          <option value="1">Auntie Muni</option>
          <option value="2">Chez Clarisse</option>
        </select>
        <button type="submit">Search</button>
      </form>
    </div>

    <div class="navbar-right">
      <?php if (!isset($_SESSION['customer_id'])): ?>
        <!-- Not logged in -->
        <a href="login/register.php">Register</a>
        <a href="login/login.php">Login</a>
      <?php else: ?>
        <!-- Logged in users -->
        <div class="dropdown">
          <button>Hello, <?= htmlspecialchars($_SESSION['customer_name']); ?> ▼</button>
          <div class="dropdown-content">
            <?php if ($_SESSION['role'] === 'admin'): ?>
              <a href="admin/category.php">Category</a>
              <a href="admin/brand.php">Brand</a>
              <a href="admin/product.php">Add Product</a>
            <?php endif; ?>
            <form action="views/logout.php" method="POST">
              <button type="submit">Logout</button>
            </form>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </nav>

  <!-- Welcome Section -->
  <div class="container">
    <?php if (isset($_SESSION['customer_name'])): ?>
      <h2>Welcome, <?= htmlspecialchars($_SESSION['customer_name']); ?>!</h2>
      <p>Explore authentic food experiences across Ghana with TasteConnect.</p>
    <?php else: ?>
      <h2>Welcome to <span style="color:#3498db;">TasteConnect</span></h2>
      <p>Discover, share, and experience Ghanaian cuisine. Register or log in to begin.</p>
    <?php endif; ?>
  </div>
</body>
</html>
