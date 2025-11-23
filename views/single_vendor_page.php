<?php
session_start();
require_once "../settings/connection.php";

// Get Vendor/Product ID (Assuming we are viewing a vendor profile based on product click)
$product_id = $_GET['id'] ?? 1; 

// Fetch Vendor Details based on Product ID linkage
$sql = "SELECT v.*, c.customer_city, c.customer_image, c.customer_contact 
        FROM products p 
        JOIN vendors v ON p.vendor_id = v.vendor_id 
        JOIN customer c ON v.customer_id = c.customer_id
        WHERE p.product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$vendor = $stmt->get_result()->fetch_assoc();

// Fetch Menu Items
$menuSql = "SELECT * FROM products WHERE vendor_id = ?";
$mStmt = $conn->prepare($menuSql);
$mStmt->bind_param("i", $vendor['vendor_id']);
$mStmt->execute();
$menuItems = $mStmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $vendor['business_name'] ?> | TasteConnect</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header (Reused from your existing code) -->
    <?php include '../includes/header.php'; // Assuming you have this ?>

    <div style="max-width:1200px; margin:0 auto;">
        <a href="../all_product.php" style="display:inline-block; margin:1rem; color:var(--gray-text);"><i class="fas fa-arrow-left"></i> Back to Discover</a>

        <!-- Vendor Header (Screenshot Match) -->
        <div class="vendor-header" style="border-radius:12px; margin:0 1rem;">
            <img src="../<?= $vendor['customer_image'] ?? 'assets/default_vendor.png' ?>" class="vendor-img">
            <div class="vendor-info" style="flex:1;">
                <h1><?= htmlspecialchars($vendor['business_name']) ?></h1>
                <div style="color:#f59e0b; margin-bottom:0.5rem;">
                    <i class="fas fa-star"></i> 4.8 (234 reviews) <span style="color:var(--primary-orange);">₵₵</span>
                </div>
                <div class="vendor-meta"><i class="fas fa-map-marker-alt"></i> <?= $vendor['business_address'] ?>, <?= $vendor['customer_city'] ?></div>
                <div class="vendor-meta"><i class="fas fa-clock"></i> 7:00 AM - 9:00 PM</div>
                <div class="vendor-meta"><i class="fas fa-phone"></i> <?= $vendor['customer_contact'] ?></div>
            </div>
            <div class="vendor-actions">
                <button class="btn btn-primary" onclick="alert('Booking logic here')">Book a Table / Experience</button>
                <p style="font-size:0.8rem; text-align:center; margin-top:0.5rem; color:gray;">Reserve your spot at <?= $vendor['business_name'] ?></p>
            </div>
        </div>

        <!-- Tabs -->
        <div class="menu-tabs">
            <div class="menu-tab-item active">Menu</div>
            <div class="menu-tab-item">Reviews</div>
            <div class="menu-tab-item">About</div>
            <div class="menu-tab-item">Gallery</div>
        </div>

        <!-- Menu Grid -->
        <div style="padding:1rem;">
            <h2>Our Menu</h2>
            <?php foreach($menuItems as $item): ?>
            <div style="display:flex; background:white; border:1px solid #eee; border-radius:8px; padding:1rem; margin-bottom:1rem; align-items:center;">
                <img src="../<?= $item['product_image'] ?>" style="width:80px; height:80px; object-fit:cover; border-radius:8px; margin-right:1rem;">
                <div style="flex:1;">
                    <h3 style="margin:0; font-size:1.1rem;"><?= htmlspecialchars($item['product_title']) ?></h3>
                    <p style="color:gray; font-size:0.9rem; margin:0.5rem 0;"><?= htmlspecialchars($item['product_desc']) ?></p>
                    <span style="background:#f3f4f6; font-size:0.8rem; padding:4px 8px; border-radius:4px;"><?= $item['product_keywords'] ?></span>
                </div>
                <div style="font-weight:bold; color:var(--primary-orange); font-size:1.2rem;">
                    ₵<?= $item['product_price'] ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>