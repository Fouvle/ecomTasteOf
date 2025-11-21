<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TasteConnect | Discover Authentic Flavors</title>
  
  <!-- 1. Link to your new external stylesheet -->
  <link rel="stylesheet" href="assets/style.css">
  
  <!-- 2. Font Awesome for Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar">
    <div class="nav-left">
      <a href="index.php" class="logo">
        <span>TC</span> TasteConnect
      </a>
      <div class="nav-links">
        <a href="index.php" style="color:var(--primary-orange);">Home</a>
        <a href="all_product.php">Discover</a>
        <a href="#">Vendors</a>
        <a href="#">Book Experience</a>
        <a href="#">About Us</a>
      </div>
    </div>

    <div class="nav-search">
        <i class="fas fa-search"></i>
        <form action="all_product.php" method="get" style="width:100%; display:flex;">
            <input type="text" name="q" placeholder="Search for restaurants, dishes...">
        </form>
    </div>

    <div class="nav-right">
      <div style="display:flex; align-items:center; gap:5px; margin-right:15px; cursor:pointer; font-size:0.9rem;">
        <i class="fas fa-globe"></i> English
      </div>

      <?php if (!isset($_SESSION['customer_id'])): ?>
        <!-- Guest Buttons -->
        <a href="login/login.php" class="btn btn-outline">Login</a>
        <a href="login/register.php" class="btn btn-primary">Register</a>
      <?php else: ?>
        <!-- Logged In User -->
        <div class="user-menu">
            <button class="btn btn-outline">
                <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['customer_name']); ?> ▼
            </button>
            <div class="user-dropdown-content">
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

  <!-- Hero Section -->
  <section class="hero">
    <div class="container hero-content">
      <div class="hero-text">
        <?php if (isset($_SESSION['customer_name'])): ?>
             <h1>Welcome back, <br> <?= htmlspecialchars($_SESSION['customer_name']); ?>!</h1>
             <p>Ready to find your next great meal in Accra? Check out the latest vendors below.</p>
        <?php else: ?>
             <h1>Discover Authentic <br>Ghanaian Flavors Near You</h1>
             <p>Connect with local vendors, explore traditional dishes, and experience the rich culinary heritage of Ghana.</p>
        <?php endif; ?>
        
        <div style="display:flex; gap:1rem;">
          <a href="views/all_product.php" class="btn btn-outline" style="color:var(--primary-orange); border:none; font-weight:bold; background:white; padding: 0.8rem 1.5rem;">
            Explore Now <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
      <div class="hero-image">
        <img src="https://images.unsplash.com/photo-1543364195-077a16c30ff3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Ghanaian Food">
      </div>
    </div>
  </section>

  <!-- Featured Vendors Section -->
  <section class="section-container">
    <div class="grid-header">
        <div>
            <h2>Featured Vendors</h2>
            <p>Top-rated local restaurants and food vendors</p>
        </div>
        <a href="all_product.php" class="btn btn-outline">View All <i class="fas fa-arrow-right"></i></a>
    </div>

    <div class="grid-container">
        <!-- Card 1 -->
        <div class="card">
            <div class="card-image-wrapper">
                <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Waakye">
                <div class="badge-rating"><span class="star-icon">★</span> 4.8</div>
            </div>
            <div class="card-body">
                <h3 class="card-title">Mama Esi's Kitchen</h3>
                <p class="card-desc">Experience authentic Ghanaian home cooking with our signature waakye and red-red.</p>
                <div class="card-meta">
                    <span>Accra</span>
                    <span class="price-highlight">₵₵</span>
                </div>
                <div class="tag-container">
                    <span class="tag">Traditional</span>
                    <span class="tag">Street Food</span>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="card">
            <div class="card-image-wrapper">
                <img src="https://images.unsplash.com/photo-1604382355076-af4b0eb60143?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Jollof">
                <div class="badge-rating"><span class="star-icon">★</span> 4.9</div>
            </div>
            <div class="card-body">
                <h3 class="card-title">Jollof Paradise</h3>
                <p class="card-desc">Award-winning jollof rice prepared with our secret family recipe passed down for generations.</p>
                <div class="card-meta">
                    <span>Kumasi</span>
                    <span class="price-highlight">₵₵₵</span>
                </div>
                <div class="tag-container">
                    <span class="tag">Traditional</span>
                    <span class="tag">Continental</span>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="card">
            <div class="card-image-wrapper">
                <img src="https://images.unsplash.com/photo-1574484284008-109b70d95bdb?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Kelewele">
                <div class="badge-rating"><span class="star-icon">★</span> 4.6</div>
            </div>
            <div class="card-body">
                <h3 class="card-title">Street Bites Accra</h3>
                <p class="card-desc">Your favorite Ghanaian street foods in one place - kelewele, khebab, and more!</p>
                <div class="card-meta">
                    <span>Accra</span>
                    <span class="price-highlight">₵</span>
                </div>
                <div class="tag-container">
                    <span class="tag">Street Food</span>
                </div>
            </div>
        </div>
    </div>
  </section>

  <!-- Testimonials Section  -->
  <section class="section-container" style="margin-bottom: 4rem;">
      <h2 style="text-align: center; font-size: 2.5rem; margin-bottom: 3rem;">What Our Users Say</h2>
      
      <div class="grid-container">
          <!-- Testimonial 1 -->
          <div class="testimonial-card">
              <div class="stars">★★★★★</div>
              <p class="testimonial-text">"TasteConnect helped me discover amazing local vendors I never knew existed!"</p>
              <div class="testimonial-user">
                  <strong>Akosua M.</strong>
                  <span>Accra</span>
              </div>
          </div>

          <!-- Testimonial 2 -->
          <div class="testimonial-card">
              <div class="stars">★★★★★</div>
              <p class="testimonial-text">"Booking is so easy and the food is always authentic. Love this platform!"</p>
              <div class="testimonial-user">
                  <strong>Yaw O.</strong>
                  <span>Kumasi</span>
              </div>
          </div>

          <!-- Testimonial 3 -->
          <div class="testimonial-card">
              <div class="stars">★★★★★</div>
              <p class="testimonial-text">"Finally, a platform that celebrates our Ghanaian culinary heritage!"</p>
              <div class="testimonial-user">
                  <strong>Abena S.</strong>
                  <span>Takoradi</span>
              </div>
          </div>
      </div>
  </section>

  <!-- Culinary Stories Section -->
  <section class="section-container" style="margin-bottom: 4rem;">
      <div class="grid-header" style="justify-content: flex-start; gap: 1rem;">
          <i class="fas fa-chart-line" style="color: var(--primary-orange); font-size: 1.5rem;"></i> 
          <h2 style="margin: 0;">Culinary Stories</h2>
      </div>

      <div class="grid-container">
          <div class="story-card">
              <img src="https://images.unsplash.com/photo-1512058564366-18510be2db19?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Story 1">
          </div>
          <div class="story-card">
              <img src="https://images.unsplash.com/photo-1543362906-acfc16c67564?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Story 2">
          </div>
          <div class="story-card">
              <img src="https://images.unsplash.com/photo-1604382355076-af4b0eb60143?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Story 3">
          </div>
      </div>
  </section>

</body>
</html>