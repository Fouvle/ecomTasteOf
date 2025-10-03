<?php
session_start();
require_once '../classes/db_class.php';

if (!isset($_SESSION['customer_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cat_id'])) {
    $db = new db_connection();
    $catId = intval($_POST['cat_id']);

    // Example: Add a column `is_approved` in categories table
    $db->db_write_query("UPDATE categories SET is_approved = 1 WHERE cat_id = $catId");

    header('Location: customer_view.php');
    exit();
}
?>
