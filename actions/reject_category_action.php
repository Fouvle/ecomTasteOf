<?php
session_start();
require_once '../classes/category_class.php';

if (!isset($_SESSION['customer_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cat_id'])) {
    $category = new Category();
    $catId = intval($_POST['cat_id']);

    if ($category->rejectCategory($catId)) {
        $_SESSION['message'] = "Category rejected. User can edit and resubmit.";
    } else {
        $_SESSION['message'] = "Error rejecting category.";
    }
    header('Location: ../views/admin_categories.php');
    exit();
}
?>
