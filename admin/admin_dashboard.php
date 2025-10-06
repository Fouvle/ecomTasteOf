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
<html>
<head>
    <title>Admin - Category Management</title>
</head>
<body>
    <h1>Category Management (Admin)</h1>

    <?php if (isset($_SESSION['message'])): ?>
        <p><?= $_SESSION['message']; unset($_SESSION['message']); ?></p>
    <?php endif; ?>

    <h2>Pending Categories</h2>
    <ul>
        <?php foreach ($pendingCategories as $cat): ?>
            <li>
                <?= htmlspecialchars($cat['cat_name']); ?> 
                (submitted by User ID: <?= $cat['user_id']; ?>)
                <form action="../actions/approve_category_action.php" method="POST" style="display:inline;">
                    <input type="hidden" name="cat_id" value="<?= $cat['cat_id']; ?>">
                    <button type="submit">Approve</button>
                </form>
                <form action="../actions/reject_category_action.php" method="POST" style="display:inline;">
                    <input type="hidden" name="cat_id" value="<?= $cat['cat_id']; ?>">
                    <button type="submit">Reject</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Approved Categories</h2>
    <ul>
        <?php foreach ($approvedCategories as $cat): ?>
            <li><?= htmlspecialchars($cat['cat_name']); ?></li>
        <?php endforeach; ?>
    </ul>

    <h2>Rejected Categories</h2>
    <ul>
        <?php foreach ($rejectedCategories as $cat): ?>
            <li><?= htmlspecialchars($cat['cat_name']); ?> (User can resubmit)</li>
        <?php endforeach; ?>
    </ul>

</body>
</html>
