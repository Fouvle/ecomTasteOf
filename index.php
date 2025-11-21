<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TasteConnect | Discover Authentic Flavors</title>
  <link rel="stylesheet" href="assets/style.css"> <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"> <style>
    :root {
      --primary-orange: #ea580c; /* The TasteConnect Orange */
      --primary-hover: #c2410c;
      --dark-text: #111827;
      --gray-text: #6b7280;
      --light-bg: #f9fafb;
      --white: #ffffff;
      --border-radius: 8px;
    }

    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: var(--light-bg);
      margin: 0;
      padding: 0;
      color: var(--dark-text);
    }

    /* --- NAVBAR --- */
    .navbar {
      background: var(--white);
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #e5e7eb;
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .nav-left {
      display: flex;
      align-items: center;
      gap: 2rem;
    }

    .logo {
      display: flex;
      align-items: center;
      font-weight: bold;
      font-size: 1.2rem;
      color: var(--primary-orange);
      text-decoration: none;
    }

    .logo span {
      background: var(--primary-orange);
      color: white;
      padding: 5px 10px;
      border-radius: 6px;
      margin-right: 8px;
    }

    .nav-links a {
      text-decoration: none;
      color: var(--gray-text);
      font-weight: 500;
      margin-right: 1.5rem;
      transition: 0.2s;
    }

    .nav-links a:hover {
      color: var(--primary-orange);
    }

    /* Search Bar in Nav */
    .nav-search {
      background: #f3f4f6;
      border-radius: 6px;
      padding: 0.5rem 1rem;
      display: flex;
      align-items: center;
      width: 300px;
    }
    
    .nav-search input {
      border: none;
      background: transparent;
      outline: none;
      width: 100%;
      margin-left: 0.5rem;
      color: var(--dark-text);
    }

    .nav-right {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .btn {
      padding: 0.5rem 1.2rem;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 600;
      font-size: 0.9rem;
      cursor: pointer;
      border: none;
    }

    .btn-outline {
      border: 1px solid #d1d5db;
      color: var(--dark-text);
      background: white;
    }

    .btn-primary {
      background: var(--primary-orange);
      color: white;
    }

    .btn-primary:hover {
      background: var(--primary-hover);
    }

    /* --- HERO SECTION --- */
    .hero {
      background-color: var(--primary-orange);
      padding: 4rem 2rem;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      min-height: 500px;
    }

    .hero-container {
      max-width: 1200px;
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 2rem;
    }

    .hero-text {
      flex: 1;
    }

    .hero-text h1 {
      font-size: 3rem;
      line-height: 1.2;
      margin-bottom: 1rem;
    }

    .hero-text p {
      font-size: 1.1rem;
      margin-bottom: 2rem;
      opacity: 0.9;
      max-width: 500px;
    }

    .hero-buttons {
      display: flex;
      gap: 1rem;
    }

    .hero-btn {
      padding: 0.8rem 1.5rem;
      background: white;
      color: var(--primary-orange);
      font-weight: bold;
      border-radius: 6px;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }

    .hero-image {
      flex: 1;
      display: flex;
      justify-content: flex-end;
    }

    .hero-image img {
      width: 100%;
      max-width: 500px;
      border-radius: 20px;
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    /* --- FEATURED VENDORS --- */
    .section-container {
      max-width: 1200px;
      margin: 3rem auto;
      padding: 0 2rem;
    }

    .section-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      margin-bottom: 2rem;
    }

    .section-header h2 {
      font-size: 2rem;
      margin: 0 0 0.5rem 0;
      color: var(--dark-text);
    }

    .section-header p {
      color: var(--gray-text);
      margin: 0;
    }

    .view-all-btn {
      border: 1px solid #d1d5db;
      background: white;
      padding: 0.5rem 1rem;
      border-radius: 6px;
      text-decoration: none;
      color: var(--dark-text);
      font-size: 0.9rem;
    }

    /* Card Grid */
    .vendor-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 2rem;
    }

    .card {
      background: white;
      border-radius: 12px;
      overflow: hidden;
      border: 1px solid #e5e7eb;
      transition: transform 0.2s;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .card-image {
      height: 200px;
      background-color: #ddd;
      position: relative;
    }

    .card-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .rating-badge {
      position: absolute;
      top: 10px;
      right: 10px;
      background: white;
      padding: 4px 8px;
      border-radius: 4px;
      font-weight: bold;
      font-size: 0.85rem;
      display: flex;
      align-items: center;
      gap: 4px;
    }

    .star { color: #f59e0b; }

    .card-body {
      padding: 1.5rem;
    }

    .card-title {
      font-size: 1.1rem;
      font-weight: bold;
      margin: 0 0 0.5rem 0;
    }

    .card-desc {
      font-size: 0.9rem;
      color: var(--gray-text);
      margin-bottom: 1rem;
      line-height: 1.4;
    }

    .card-meta {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
      font-size: 0.9rem;
      color: var(--gray-text);
    }

    .price-symbol {
      color: var(--primary-orange);
      font-weight: bold;
    }

    .tags {
      display: flex;
      gap: 0.5rem;
    }

    .tag {
      background: #fff7ed; /* Light orange */
      color: #c2410c;
      padding: 4px 10px;
      border-radius: 4px;
      font-size: 0.75rem;
      font-weight: 600;
    }

    /* Dropdown for logged in users */
    .user-menu {
        position: relative;
        display: inline-block;
    }
    .user-dropdown {
        display: none;
        position: absolute;
        right: 0;
        background-color: #fff;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
        border-radius: 6px;
        margin-top: 10px;
    }
    .user-dropdown a, .user-dropdown button {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        width: 100%;
        text-align: left;
        background: none;
        border: none;
        cursor: pointer;
    }
    .user-dropdown a:hover, .user-dropdown button:hover {background-color: #f1f1f1; border-radius: 6px;}
    .user-menu:hover .user-dropdown {display: block;}

    /* Mobile Responsiveness */
    @media (max-width: 768px) {
      .navbar { flex-direction: column; gap: 1rem; }
      .nav-search { width: 100%; }
      .hero-container { flex-direction: column-reverse; text-align: center; }
      .hero-image img { width: 100%; }
      .hero-buttons { justify-content: center; }
    }
  </style>
</head>
<body>

  <nav class="navbar">
    <div class="nav-left">
      <a href="index.php" class="logo">
        <span>TC</span> TasteConnect
      </a>
      <div class="nav-links" style="display: flex;"> <a href="index.php">Home</a>
        <a href="all_product.php">Discover</a>
        <a href="#">Vendors</a>
        <a href="#">Book Experience</a>
        <a href="#">About Us</a>
      </div>
    </div>

    <div class="nav-search">
        <i class="fas fa-search" style="color: #9ca3af;"></i>
        <form action="all_product.php" method="get" style="width:100%; display:flex;">
            <input type="text" name="q" placeholder="Search for restaurants, dishes...">
        </form>
    </div>

    <div class="nav-right">
      <div style="display:flex; align-items:center; gap:5px; margin-right:10px; cursor:pointer;">
        <i class="fas fa-globe"></i> English
      </div>

      <?php if (!isset($_SESSION['customer_id'])): ?>
        <a href="login/login.php" class="btn btn-outline">Login</a>
        <a href="login/register.php" class="btn btn-primary">Register</a>
      <?php else: ?>
        <div class="user-menu">
            <button class="btn btn-outline">
                <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['customer_name']); ?> ▼
            </button>
            <div class="user-dropdown">
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <a href="admin/category.php">Manage Categories</a>
                    <a href="admin/brand.php">Manage Brands</a>
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

  <section class="hero">
    <div class="hero-container">
      <div class="hero-text">
        <?php if (isset($_SESSION['customer_name'])): ?>
             <h1>Welcome back, <br> <?= htmlspecialchars($_SESSION['customer_name']); ?>!</h1>
             <p>Ready to find your next great meal in Accra?</p>
        <?php else: ?>
             <h1>Discover Authentic <br>Ghanaian Flavors Near You</h1>
             <p>Connect with local vendors, explore traditional dishes, and experience the rich culinary heritage of Ghana.</p>
        <?php endif; ?>
        
        <div class="hero-buttons">
          <a href="all_product.php" class="hero-btn">
            Explore Now <i class="fas fa-arrow-right"></i>
          </a>
          <div style="background:rgba(255,255,255,0.2); border-radius:6px; width:100px;"></div> </div>
      </div>
      <div class="hero-image">
        <img src="https://images.unsplash.com/photo-1543364195-077a16c30ff3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Ghanaian Food">
      </div>
    </div>
  </section>

  <section class="section-container">
    <div class="section-header">
        <div>
            <h2>Featured Vendors</h2>
            <p>Top-rated local restaurants and food vendors</p>
        </div>
        <a href="all_product.php" class="view-all-btn">View All &rarr;</a>
    </div>

    <div class="vendor-grid">
        <div class="card">
            <div class="card-image">
                <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Waakye">
                <div class="rating-badge"><span class="star">★</span> 4.8</div>
            </div>
            <div class="card-body">
                <h3 class="card-title">Mama Esi's Kitchen</h3>
                <p class="card-desc">Experience authentic Ghanaian home cooking with our signature waakye and red-red.</p>
                <div class="card-meta">
                    <span>Accra</span>
                    <span class="price-symbol">₵₵</span>
                </div>
                <div class="tags">
                    <span class="tag">Traditional</span>
                    <span class="tag">Street Food</span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-image">
                <img src="https://images.unsplash.com/photo-1604382355076-af4b0eb60143?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Jollof">
                <div class="rating-badge"><span class="star">★</span> 4.9</div>
            </div>
            <div class="card-body">
                <h3 class="card-title">Jollof Paradise</h3>
                <p class="card-desc">Award-winning jollof rice prepared with our secret family recipe passed down for generations.</p>
                <div class="card-meta">
                    <span>Kumasi</span>
                    <span class="price-symbol">₵₵₵</span>
                </div>
                <div class="tags">
                    <span class="tag">Traditional</span>
                    <span class="tag">Continental</span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-image">
                <img src="https://images.unsplash.com/photo-1574484284008-109b70d95bdb?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Kelewele">
                <div class="rating-badge"><span class="star">★</span> 4.6</div>
            </div>
            <div class="card-body">
                <h3 class="card-title">Street Bites Accra</h3>
                <p class="card-desc">Your favorite Ghanaian street foods in one place - kelewele, khebab, and more!</p>
                <div class="card-meta">
                    <span>Accra</span>
                    <span class="price-symbol">₵</span>
                </div>
                <div class="tags">
                    <span class="tag">Street Food</span>
                </div>
            </div>
        </div>
    </div>
  </section>

</body>
</html>