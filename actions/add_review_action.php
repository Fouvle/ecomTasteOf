<?php
session_start();
require_once "../controllers/booking_controller.php";

header('Content-Type: application/json');

if (!isset($_SESSION['customer_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Login required']);
    exit;
}

if (isset($_POST['vendor_id'], $_POST['rating'], $_POST['review_text'])) {
    $cust_id = $_SESSION['customer_id'];
    $ven_id = $_POST['vendor_id'];
    $bk_id = $_POST['booking_id']; // Optional link
    $rating = $_POST['rating'];
    $text = $_POST['review_text'];

    if (add_review_ctr($cust_id, $ven_id, $bk_id, $rating, $text)) {
        echo json_encode(['status' => 'success', 'message' => 'Review added']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }
}
?>