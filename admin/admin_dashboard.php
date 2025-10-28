<?php
// error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once '../classes/category_class.php';
require_once '../settings/core.php'; // Ensure core.php is loaded for isAdmin()

// Ensure user is logged in and is an admin
if (!isLoggedIn() || !isAdmin()) {
    header('Location: ../login/login.php');
    exit();
}

// Instantiate Category safely
$category = new Category();
$pendingCategories = $category->getPendingCategories() ?? [];
$approvedCategories = $category->getApprovedCategories() ?? [];
$rejectedCategories = $category->getRejectedCategories() ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Category Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Minimal style for prominent navigation buttons */
        .nav-btn {
            background-color: #3498db;
            color: white;
            transition: background-color 0.2s;
            font-weight: bold;
        }
        .nav-btn:hover {
            background-color: #2980b9;
            color: white;
        }
    </style>
</head>
<body class="p-4 bg-light">

    <div class="container">
        <h1 class="mb-4 text-primary">Admin Dashboard</h1>
        
        <div class="row mb-5 g-3">
            <div class="col-md-4">
                <a href="category.php" class="btn nav-btn w-100 p-3">
                    Categories
                </a>
            </div>
            <div class="col-md-4">
                <a href="brand.php" class="btn nav-btn w-100 p-3">
                    Brands
                </a>
            </div>
            <div class="col-md-4">
                <a href="product.php" class="btn nav-btn w-100 p-3">
                    Products
                </a>
            </div>
        </div>
        <h4 class="text-secondary mb-3">Category Management</h4>

        <div id="messageBox" class="alert d-none"></div>

        <h5 class="mt-4">Pending Categories</h5>
        <ul class="list-group mb-4" id="pendingList">
            <?php if (empty($pendingCategories)): ?>
                <li class="list-group-item text-muted">No pending categories.</li>
            <?php else: ?>
                <?php foreach ($pendingCategories as $cat): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <?= htmlspecialchars($cat['cat_name']); ?> 
                            <small class="text-muted">(User ID: <?= htmlspecialchars($cat['created_by']); ?>)</small>
                        </span>
                        <div>
                            <button class="btn btn-success btn-sm approve-btn" data-id="<?= $cat['cat_id']; ?>">Approve</button>
                            <button class="btn btn-danger btn-sm reject-btn" data-id="<?= $cat['cat_id']; ?>">Reject</button>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <h5>Approved Categories</h5>
        <ul class="list-group mb-4" id="approvedList">
            <?php if (empty($approvedCategories)): ?>
                <li class="list-group-item text-muted">No approved categories yet.</li>
            <?php else: ?>
                <?php foreach ($approvedCategories as $cat): ?>
                    <li class="list-group-item"><?= htmlspecialchars($cat['cat_name']); ?></li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <h5>Rejected Categories</h5>
        <ul class="list-group mb-4" id="rejectedList">
            <?php if (empty($rejectedCategories)): ?>
                <li class="list-group-item text-muted">No rejected categories.</li>
            <?php else: ?>
                <?php foreach ($rejectedCategories as $cat): ?>
                    <li class="list-group-item">
                        <?= htmlspecialchars($cat['cat_name']); ?> 
                        <small class="text-muted">(User can resubmit)</small>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>

    <script src="../js/admindashboard.js"></script>
</body>
</html>