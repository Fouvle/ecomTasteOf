<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TasteConnect | Home</title>
  
  <!-- Styles -->
  <link rel="stylesheet" href="assets/style.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  
  <style>
    /* --- Navigation Specific Overrides to Match Screenshot --- */
    .navbar {
        display: flex;
        align-items: center;
        justify-content: space-between; /* Spreads Logo/Links, Search, Actions */
        padding: 0.8rem 2rem;
        background: #fff;
        border-bottom: 1px solid #e5e7eb;
        font-family: 'Segoe UI', sans-serif;
    }

    /* 1. Left Section: Logo + Nav Links */
    .nav-left-section {
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .logo {
        display: flex;
        align-items: center;
        font-weight: 600;
        font-size: 1.4rem;
        color: #ea580c; /* Primary Orange */
        text-decoration: none;
        gap: 8px;
    }

    .logo-badge {
        background-color: #ea580c;
        color: white;
        padding: 6px 8px;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: bold;
    }

    .nav-links {
        display: flex;
        gap: 1.5rem;
    }

    .nav-links a {
        text-decoration: none;
        color: #4b5563; /* Gray-600 */
        font-weight: 500;
        font-size: 1rem;
        transition: color 0.2s;
    }

    .nav-links a:hover, .nav-links a.active {
        color: #ea580c;
    }

    /* 2. Middle Section: Search Bar */
    .nav-search-container {
        flex: 1; /* Takes up remaining space */
        max-width: 400px; /* Limit width */
        margin: 0 2rem;
    }

    .search-wrapper {
        display: flex;
        align-items: center;
        background-color: #f3f4f6; /* Light gray bg */
        border-radius: 8px;
        padding: 0.6rem 1rem;
        color: #9ca3af;
    }

    .search-wrapper input {
        border: none;
        background: transparent;
        outline: none;
        margin-left: 0.5rem;
        width: 100%;
        font-size: 0.95rem;
        color: #1f2937;
    }

    /* 3. Right Section: Actions */
    .nav-right-section {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .lang-selector {
        display: flex;
        align-items: center;
        gap: 6px;
        font-weight: 600;
        color: #1f2937;
        margin-right: 1rem;
        cursor: pointer;
    }

    /* Button Styles from Screenshot */
    .btn-nav {
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        cursor: pointer;
        white-space: nowrap;
    }

    .btn-vendor {
        background-color: white;
        border: 1px solid #d1d5db;
        color: #111827;
    }
    .btn-vendor:hover { background-color: #f9fafb; }

    .btn-login {
        background-color: white;
        border: 1px solid #d1d5db;
        color: #111827;
    }
    .btn-login:hover { background-color: #f9fafb; }

    .btn-register {
        background-color: #ea580c;
        border: 1px solid #ea580c;
        color: white;
    }
    .btn-register:hover { background-color: #c2410c; }

    /* Mobile Responsiveness */
    @media (max-width: 1100px) {
        .nav-links { display: none; } /* Hide links on smaller screens */
        .nav-search-container { margin: 0 1rem; }
    }
    @media (max-width: 768px) {
        .navbar { flex-direction: column; gap: 1rem; padding: 1rem; }
        .nav-left-section, .nav-search-container, .nav-right-section { width: 100%; justify-content: center; }
        .search-wrapper { width: 100%; }
        .nav-right-section { flex-wrap: wrap; }
    }

    /* Hero Section Styling Override (to match your previous request) */
    .hero { background-color: #ea580c; color: white; padding: 4rem 0; }
    .hero-content { display: flex; align-items: center; gap: 2rem; max-width: 1200px; margin: 0 auto; padding: 0 2rem; }
    .hero-text { flex: 1; }
    .hero-text h1 { font-size: 3rem; line-height: 1.2; margin-bottom: 1rem; }
    .hero-image { flex: 1; display: flex; justify-content: flex-end; }
    .hero-image img { border-radius: 20px; max-width: 100%; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
  </style>
</head>
<body>

  <!-- Navbar matching Screenshot -->
  <nav class="navbar">
    <!-- Left: Logo & Menu Links -->
    <div class="nav-left-section">
      <a href="index.php" class="logo">
        <span class="logo-badge">TC</span> TasteConnect
      </a>
      <div class="nav-links">
        <a href="index.php" class="active" style="color:#ea580c;">Home</a>
        <a href="views/all_product.php">Discover</a>
        <a href="views/all_vendors.php">Vendors</a>
        <a href="#">Book Experience</a>
        <a href="#">About Us</a>
      </div>
    </div>

    <!-- Middle: Search Bar -->
    <div class="nav-search-container">
        <form action="views/all_product.php" method="get" class="search-wrapper">
            <i class="fas fa-search"></i>
            <input type="text" name="q" placeholder="Search for restaurants, cuisines...">
        </form>
    </div>

    <!-- Right: Actions -->
    <div class="nav-right-section">
      <div class="lang-selector">
        <i class="fas fa-globe"></i> English
      </div>

      <?php if (!isset($_SESSION['customer_id'])): ?>
        <!-- Guest Buttons (Matches Screenshot) -->
        <a href="login/vendor_register.php" class="btn-nav btn-vendor">Become a Vendor</a>
        <a href="login/login.php" class="btn-nav btn-login">Login</a>
        <a href="login/register.php" class="btn-nav btn-register">Register</a>
      <?php else: ?>
        <!-- Logged In User Dropdown -->
        <div class="user-menu" style="position:relative; display:inline-block;">
            <button class="btn-nav btn-login" style="display:flex; align-items:center; gap:5px; background-color: #f3f4f6; border: none;">
                <i class="fas fa-user-circle" style="font-size: 1.2rem;"></i> 
                <span><?= htmlspecialchars(explode(' ', $_SESSION['customer_name'])[0]); ?></span> <!-- First Name Only -->
                <i class="fas fa-chevron-down" style="font-size: 0.8rem;"></i>
            </button>
            
            <!-- Enhanced Dropdown Menu -->
            <div class="user-dropdown-content" style="display:none; position:absolute; right:0; top:120%; background:white; box-shadow:0 4px 20px rgba(0,0,0,0.15); z-index:100; min-width:220px; border-radius:12px; padding:0.5rem 0; border:1px solid #f3f4f6;">
                
                <!-- Header in Dropdown -->
                <div style="padding: 10px 20px; border-bottom: 1px solid #f3f4f6; margin-bottom: 5px;">
                    <span style="font-size: 0.85rem; color: #9ca3af; font-weight: 600;">Signed in as</span><br>
                    <span style="font-weight: 600; color: #1f2937;"><?= htmlspecialchars($_SESSION['customer_email'] ?? 'User') ?></span>
                </div>

                <!-- Vendor Specific Link -->
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'vendor'): ?>
                    <a href="admin/vendor_dashboard.php" style="display:flex; align-items:center; gap:10px; padding:12px 20px; color:#111827; text-decoration:none; transition: background 0.2s;">
                        <i class="fas fa-store" style="color: #ea580c;"></i> Vendor Dashboard
                    </a>
                <?php endif; ?>

                <!-- Customer Links (Always visible) -->
                <a href="views/customer_dashboard.php" style="display:flex; align-items:center; gap:10px; padding:12px 20px; color:#111827; text-decoration:none; transition: background 0.2s;">
                    <i class="fas fa-calendar-alt" style="color: #4b5563;"></i> User Dashboard
                </a>
                
                <a href="#" style="display:flex; align-items:center; gap:10px; padding:12px 20px; color:#111827; text-decoration:none; transition: background 0.2s;">
                    <i class="fas fa-heart" style="color: #4b5563;"></i> Favorites
                </a>

                <div style="border-top: 1px solid #f3f4f6; margin: 5px 0;"></div>

                <!-- Logout -->
                <a href="views/logout.php" style="display:flex; align-items:center; gap:10px; padding:12px 20px; color:#dc2626; text-decoration:none; transition: background 0.2s; font-weight: 500;">
                    <i class="fas fa-sign-out-alt"></i> Log Out
                </a>
            </div>
        </div>
      <?php endif; ?>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content">
      <div class="hero-text">
        <?php if (isset($_SESSION['customer_name'])): ?>
             <h1>Welcome back, <br> <?= htmlspecialchars($_SESSION['customer_name']); ?>!</h1>
             <p>Ready to find your next great meal? Check out our latest vendors below.</p>
        <?php else: ?>
             <h1>Discover Authentic <br>Ghanaian Flavors Near You</h1>
             <p>Connect with local vendors, explore traditional dishes, and experience the rich culinary heritage of Ghana.</p>
        <?php endif; ?>
        
        <div style="display:flex; gap:1rem; margin-top:2rem;">
          <a href="views/all_product.php" class="btn-nav btn-vendor" style="border:none; font-weight:bold;">
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
  <section class="section-container" style="margin-top:4rem; max-width:1200px; margin:4rem auto; padding:0 2rem;">
    <div style="display:flex; justify-content:space-between; align-items:end; margin-bottom:2rem;">
        <div>
            <h2 style="font-size:2rem; margin:0; color:#111827;">Featured Vendors</h2>
            <p style="color:#6b7280; margin:0.5rem 0 0 0;">Top-rated local restaurants and food vendors</p>
        </div>
        <a href="views/all_vendors.php" class="btn-nav btn-vendor">View All <i class="fas fa-arrow-right"></i></a>
    </div>

    <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap:2rem;">
        <!-- Static Card Example 1 -->
        <div style="border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:white; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);">
            <div style="height:200px; background:#eee; position:relative;">
                <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=500&q=60" style="width:100%; height:100%; object-fit:cover;">
                <span style="position:absolute; top:10px; right:10px; background:white; padding:4px 8px; border-radius:6px; font-weight:bold; font-size:0.9rem;">★ 4.8</span>
            </div>
            <div style="padding:1.5rem;">
                <h3 style="margin:0 0 0.5rem 0; font-size:1.2rem;">Mama Esi's Kitchen</h3>
                <p style="color:#6b7280; font-size:0.9rem; margin-bottom:1rem;">Authentic Ghanaian home cooking with our signature waakye.</p>
                <div style="display:flex; justify-content:space-between; font-size:0.9rem; color:#4b5563;">
                    <span>Accra</span>
                    <span style="color:#ea580c; font-weight:bold;">₵₵</span>
                </div>
            </div>
        </div>

        <!-- Static Card Example 2 -->
        <div style="border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:white; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);">
            <div style="height:200px; background:#eee; position:relative;">
                <img src="https://images.unsplash.com/photo-1604382355076-af4b0eb60143?auto=format&fit=crop&w=500&q=60" style="width:100%; height:100%; object-fit:cover;">
                <span style="position:absolute; top:10px; right:10px; background:white; padding:4px 8px; border-radius:6px; font-weight:bold; font-size:0.9rem;">★ 4.9</span>
            </div>
            <div style="padding:1.5rem;">
                <h3 style="margin:0 0 0.5rem 0; font-size:1.2rem;">Jollof Paradise</h3>
                <p style="color:#6b7280; font-size:0.9rem; margin-bottom:1rem;">Award-winning smoky jollof rice.</p>
                <div style="display:flex; justify-content:space-between; font-size:0.9rem; color:#4b5563;">
                    <span>Kumasi</span>
                    <span style="color:#ea580c; font-weight:bold;">₵₵₵</span>
                </div>
            </div>
        </div>
        
        <!-- Static Card Example 3 -->
        <div style="border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:white; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);">
            <div style="height:200px; background:#eee; position:relative;">
                <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=500&q=60" style="width:100%; height:100%; object-fit:cover;">
                <span style="position:absolute; top:10px; right:10px; background:white; padding:4px 8px; border-radius:6px; font-weight:bold; font-size:0.9rem;">★ 4.6</span>
            </div>
            <div style="padding:1.5rem;">
                <h3 style="margin:0 0 0.5rem 0; font-size:1.2rem;">Street Bites</h3>
                <p style="color:#6b7280; font-size:0.9rem; margin-bottom:1rem;">Your favorite street snacks in one place.</p>
                <div style="display:flex; justify-content:space-between; font-size:0.9rem; color:#4b5563;">
                    <span>Accra</span>
                    <span style="color:#ea580c; font-weight:bold;">₵</span>
                </div>
            </div>
        </div>
    </div>
  </section>

  <!-- Dropdown Script -->
  <script>
      const btn = document.querySelector('.user-menu button');
      const menu = document.querySelector('.user-dropdown-content');
      
      if(btn) {
          btn.addEventListener('click', (e) => {
              e.stopPropagation(); // Prevent immediate closing
              const isVisible = menu.style.display === 'block';
              menu.style.display = isVisible ? 'none' : 'block';
          });
          
          // Close when clicking outside
          document.addEventListener('click', (e) => {
              if (!btn.contains(e.target) && !menu.contains(e.target)) {
                  menu.style.display = 'none';
              }
          });
          
          // Hover effect for dropdown items (JS fallback for inline styles)
          const links = menu.querySelectorAll('a');
          links.forEach(link => {
              link.addEventListener('mouseenter', () => {
                  if(!link.style.color.includes('dc2626')) // Don't change red for logout
                    link.style.backgroundColor = '#f3f4f6';
              });
              link.addEventListener('mouseleave', () => {
                  link.style.backgroundColor = 'transparent';
              });
          });
      }
  </script>

</body>
</html>