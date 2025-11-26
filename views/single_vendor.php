<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../settings/db_cred.php";
require_once "../controllers/vendor_controller.php"; // For fetching events

$product_id = $_GET['id'] ?? 0;
$vendor_id_param = $_GET['vendor_id'] ?? 0; // Allow direct vendor access too

$vendor = null;

if($product_id) {
    $sql = "SELECT v.*, c.customer_city, c.customer_image, c.customer_contact 
            FROM products p 
            JOIN vendors v ON p.vendor_id = v.vendor_id 
            JOIN customer c ON v.customer_id = c.customer_id
            WHERE p.product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $vendor = $stmt->get_result()->fetch_assoc();
} elseif($vendor_id_param) {
    $sql = "SELECT v.*, c.customer_city, c.customer_image, c.customer_contact 
            FROM vendors v 
            JOIN customer c ON v.customer_id = c.customer_id
            WHERE v.vendor_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $vendor_id_param);
    $stmt->execute();
    $vendor = $stmt->get_result()->fetch_assoc();
}

if (!$vendor) die("Vendor not found");

// Fetch Menu Items
$menuSql = "SELECT * FROM products WHERE vendor_id = ?";
$mStmt = $conn->prepare($menuSql);
$mStmt->bind_param("i", $vendor['vendor_id']);
$mStmt->execute();
$menuItems = $mStmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch Events (NEW)
$vendorEvents = get_vendor_events_ctr($vendor['vendor_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($vendor['business_name']) ?> | TasteConnect</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Styles for tabs and content */
        .tab-content-section { display: none; padding: 1rem; }
        .tab-content-section.active { display: block; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="../index.php" class="logo"><span>TC</span> TasteConnect</a>
        <div class="nav-links">
            <a href="all_product.php">Discover</a>
            <a href="all_vendors.php">Vendors</a>
        </div>
    </nav>

    <div class="container" style="margin-top:2rem;">
        <a href="all_product.php" style="color:var(--gray-text);"><i class="fas fa-arrow-left"></i> Back</a>

        <!-- Header -->
        <div class="vendor-header" style="margin-top:1rem; border-radius:12px; border:1px solid #e5e7eb;">
            <img src="../<?= $vendor['customer_image'] ?? 'assets/default_vendor.png' ?>" class="vendor-img">
            <div class="vendor-info" style="flex:1;">
                <h1><?= htmlspecialchars($vendor['business_name']) ?></h1>
                <div style="color:#f59e0b; margin-bottom:0.5rem;">
                    <i class="fas fa-star"></i> 4.8 <span style="color:var(--primary-orange);">Verified</span>
                </div>
                <div class="vendor-meta"><i class="fas fa-map-marker-alt"></i> <?= $vendor['business_address'] ?>, <?= $vendor['customer_city'] ?></div>
                <div class="vendor-meta"><i class="fas fa-phone"></i> <?= $vendor['customer_contact'] ?></div>
            </div>
            <div class="vendor-actions">
                <a href="booking.php?vendor_id=<?= $vendor['vendor_id'] ?>" class="btn btn-primary">Book a Table</a>
            </div>
        </div>

        <!-- Tabs -->
        <div class="menu-tabs">
            <div class="menu-tab-item active" onclick="switchTab('menu')">Menu</div>
            <div class="menu-tab-item" onclick="switchTab('events')">Events</div>
            <div class="menu-tab-item" onclick="switchTab('about')">About</div>
        </div>

        <!-- Menu Tab -->
        <div id="menu" class="tab-content-section active">
            <h2>Our Menu</h2>
            <?php foreach($menuItems as $item): ?>
            <div style="display:flex; background:white; border:1px solid #eee; border-radius:8px; padding:1rem; margin-bottom:1rem; align-items:center;">
                <img src="../<?= $item['product_image'] ?>" style="width:80px; height:80px; object-fit:cover; border-radius:8px; margin-right:1rem;">
                <div style="flex:1;">
                    <h3 style="margin:0; font-size:1.1rem;"><?= htmlspecialchars($item['product_title']) ?></h3>
                    <p style="color:gray; font-size:0.9rem; margin:0.5rem 0;"><?= htmlspecialchars($item['product_desc']) ?></p>
                </div>
                <div style="font-weight:bold; color:var(--primary-orange); font-size:1.2rem;">
                    ₵<?= $item['product_price'] ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Events Tab -->
        <div id="events" class="tab-content-section">
            <h2>Upcoming Events</h2>
            <?php if(empty($vendorEvents)): ?>
                <p style="color:gray;">No upcoming events scheduled.</p>
            <?php else: ?>
                <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap:1.5rem;">
                    <?php foreach($vendorEvents as $evt): ?>
                    <div style="background:white; border:1px solid #eee; border-radius:12px; overflow:hidden;">
                        <div style="background:var(--primary-orange); color:white; padding:1rem; text-align:center;">
                            <h3 style="margin:0;"><?= date('d', strtotime($evt['event_date'])) ?></h3>
                            <small><?= date('M', strtotime($evt['event_date'])) ?></small>
                        </div>
                        <div style="padding:1.5rem;">
                            <h3 style="margin-top:0;"><?= htmlspecialchars($evt['event_title']) ?></h3>
                            <p style="color:gray; font-size:0.9rem;"><i class="fas fa-clock"></i> <?= date('h:i A', strtotime($evt['event_date'])) ?></p>
                            <p><?= htmlspecialchars($evt['event_description']) ?></p>
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:1rem;">
                                <span style="font-weight:bold;">₵<?= $evt['price'] ?></span>
                                <!-- Link to booking page with pre-filled date -->
                                <a href="booking.php?vendor_id=<?= $vendor['vendor_id'] ?>&date=<?= date('Y-m-d', strtotime($evt['event_date'])) ?>&time=<?= date('H:i', strtotime($evt['event_date'])) ?>" class="btn btn-outline" style="font-size:0.9rem;">Book Spot</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- About Tab -->
        <div id="about" class="tab-content-section">
            <h2>About Us</h2>
            <p><?= $vendor['business_description'] ?></p>
        </div>

    </div>

    <script>
        function switchTab(tabId) {
            // Remove active class
            $('.menu-tab-item').removeClass('active');
            $('.tab-content-section').removeClass('active');
            
            // Add active class (finding element by text content matches is tricky in vanilla JS without ID, using jQuery index)
            // Simplified logic: assuming exact order or passed 'this'
            // For now, just relying on CSS class toggle logic via JS
            $(`.menu-tab-item:contains('${tabId.charAt(0).toUpperCase() + tabId.slice(1)}')`).addClass('active');
            
            // Show content
            $('#'+tabId).addClass('active');
            
            // Fix visual tab active state manually for this simplified script
            const tabs = document.querySelectorAll('.menu-tab-item');
            const contents = document.querySelectorAll('.tab-content-section');
            
            tabs.forEach(t => t.classList.remove('active'));
            contents.forEach(c => c.classList.remove('active'));
            
            document.getElementById(tabId).classList.add('active');
            
            // Find the button that was clicked (this logic is a bit circular with the inline onclick, sticking to the DOM ID method is safest)
            event.target.classList.add('active');
        }
    </script>
</body>
</html>