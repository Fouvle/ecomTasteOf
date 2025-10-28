<?php
ini_set('display_errors', 1);
session_start();
require_once '../classes/category_class.php';

// Ensure user is logged in and is an admin
if (!isset($_SESSION['customer_id']) || (int)$_SESSION['role'] !== 1) {
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
</head>
<body class="p-4 bg-light">

    <div class="container">
        <h1 class="mb-4 text-primary">Admin Dashboard</h1>
        <h4 class="text-secondary mb-3">Category Management</h4>

        <!-- Flash messages -->
        <div id="messageBox" class="alert d-none"></div>

        <!-- Pending Categories -->
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

        <!-- Approved Categories -->
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

        <!-- Rejected Categories -->
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
