<?php
session_start();
require_once '../classes/category_class.php';

if (!isset($_SESSION['customer_id']) || $_SESSION['role'] !== 1) {
    header('Location: ../login/login.php');
    exit();
}

$category = new Category();
$pendingCategories = $category->getPendingCategories();
$approvedCategories = $category->getApprovedCategories();
$rejectedCategories = $category->getRejectedCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Category Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="p-4">

    <h1 class="mb-4">Admin Dashboard - Category Management</h1>

    <!-- Message container -->
    <div id="messageBox" class="alert d-none"></div>

    <!-- Pending Categories -->
    <h2>Pending Categories</h2>
    <ul class="list-group mb-4" id="pendingList">
        <?php foreach ($pendingCategories as $cat): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>
                    <?= htmlspecialchars($cat['cat_name']); ?> 
                    <small class="text-muted">(User ID: <?= $cat['user_id']; ?>)</small>
                </span>
                <div>
                    <button class="btn btn-success btn-sm approve-btn" data-id="<?= $cat['cat_id']; ?>">Approve</button>
                    <button class="btn btn-danger btn-sm reject-btn" data-id="<?= $cat['cat_id']; ?>">Reject</button>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Approved Categories -->
    <h2>Approved Categories</h2>
    <ul class="list-group mb-4" id="approvedList">
        <?php foreach ($approvedCategories as $cat): ?>
            <li class="list-group-item"><?= htmlspecialchars($cat['cat_name']); ?></li>
        <?php endforeach; ?>
    </ul>

    <!-- Rejected Categories -->
    <h2>Rejected Categories</h2>
    <ul class="list-group mb-4" id="rejectedList">
        <?php foreach ($rejectedCategories as $cat): ?>
            <li class="list-group-item"><?= htmlspecialchars($cat['cat_name']); ?> <small class="text-muted">(User can resubmit)</small></li>
        <?php endforeach; ?>
    </ul>

    <script src="../js/admindashboard.js"></script>
</body>
</html>
