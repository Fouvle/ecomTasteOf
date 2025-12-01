<?php
session_start();
// Robust check for connection file
$connPath = "../settings/db_cred.php";
if (!file_exists($connPath)) {
    die("Error: Connection file not found at $connPath");
}
require_once $connPath;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Vendors | TasteConnect</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Icons & jQuery -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
        /* =========================================
           Reusing Global Styles for Consistency
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
        }

        body {
            font-family: var(--font-main);
            background-color: var(--light-bg);
            color: var(--dark-text);
            margin: 0;
            padding: 0;
        }

        a { text-decoration: none; color: inherit; }

        /* Navbar (Same as other pages) */
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
        }

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
        .nav-links a { color: var(--gray-text); font-weight: 500; }
        .nav-links a:hover { color: var(--primary-orange); }

        .btn {
            padding: 0.6rem 1.2rem;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-primary { background: var(--primary-orange); color: white; }
        .btn-outline { border: 1px solid #d1d5db; background: white; color: var(--dark-text); }

        /* Vendor Grid Layout */
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .header-section {
            text-align: center;
            margin-bottom: 3rem;
        }
        .header-section h1 { margin-bottom: 0.5rem; font-size: 2.5rem; }
        .header-section p { color: var(--gray-text); }

        .vendor-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
        }

        /* Vendor Card Styling */
        .vendor-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--border-color);
            transition: transform 0.2s;
            display: flex;
            flex-direction: column;
        }

        .vendor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        }

        .vendor-img-wrapper {
            height: 160px;
            background: #ffe4d6; /* Fallback color */
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .vendor-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        /* Circular Logo Overlay */
        .vendor-logo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 3px solid white;
            position: absolute;
            bottom: -30px;
            left: 20px;
            background: white;
            object-fit: cover;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .vendor-content {
            padding: 2.5rem 1.5rem 1.5rem 1.5rem; /* Top padding clears the logo */
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .vendor-name {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .vendor-meta {
            font-size: 0.9rem;
            color: var(--gray-text);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .vendor-desc {
            font-size: 0.9rem;
            color: #4b5563;
            margin-bottom: 1.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .action-row {
            margin-top: auto;
            border-top: 1px solid #f3f4f6;
            padding-top: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        @media(max-width: 768px) {
            .navbar { flex-direction: column; gap: 1rem; }
            .nav-links { display: none; }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div style="display:flex; align-items:center; gap:2rem; width:100%;">
            <a href="../index.php" class="logo"><span>TC</span> TasteConnect</a>
            <div class="nav-links">
                <a href="../index.php">Home</a>
                <a href="all_products.php">Discover</a>
                <a href="all_vendors.php" style="color:var(--primary-orange);">Vendors</a>
            </div>
        </div>
        <div>
            <?php if (!isset($_SESSION['customer_id'])): ?>
                <a href="../login/login.php" class="btn btn-primary">Login</a>
            <?php else: ?>
                <a href="#" class="btn btn-outline"><i class="fas fa-user"></i> Account</a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container">
        <div class="header-section">
            <h1>Our Vendors</h1>
            <p>Explore the best local chefs, restaurants, and food artisans.</p>
        </div>

        <div id="vendor-container" class="vendor-grid">
            <!-- JS will populate vendors here -->
            <div style="grid-column:1/-1; text-align:center; padding:3rem;">
                <i class="fas fa-spinner fa-spin fa-2x" style="color:var(--primary-orange);"></i>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="../js/all_vendors.js"></script>

</body>
</html>