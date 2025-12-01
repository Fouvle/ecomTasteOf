<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once "../controllers/review_controller.php";

header('Content-Type: application/json');

if (!isset($_SESSION['customer_id'])) {
    error_log('Add review: Customer not logged in');
    echo json_encode(['status' => 'error', 'message' => 'Login required']);
    exit;
}

if (isset($_POST['vendor_id'], $_POST['rating'], $_POST['review_text'])) {
    $cust_id = $_SESSION['customer_id'];
    $ven_id = (int)$_POST['vendor_id'];
    $bk_id = $_POST['booking_id'] ?? null;
    $rating = (int)$_POST['rating'];
    $text = trim($_POST['review_text']);

    error_log('Add review: cust_id=' . $cust_id . ', ven_id=' . $ven_id . ', rating=' . $rating);

    if (!$ven_id || !$rating || empty($text)) {
        error_log('Add review: Invalid inputs');
        echo json_encode(['status' => 'error', 'message' => 'All fields required']);
        exit;
    }

    if ($rating < 1 || $rating > 5) {
        echo json_encode(['status' => 'error', 'message' => 'Rating must be 1-5']);
        exit;
    }

    if (add_review_ctr($cust_id, $ven_id, $bk_id, $rating, $text)) {
        error_log('Add review: SUCCESS');
        echo json_encode(['status' => 'success', 'message' => 'Review added successfully']);
    } else {
        error_log('Add review: FAILED');
        echo json_encode(['status' => 'error', 'message' => 'Failed to save review']);
    }
} else {
    error_log('Add review: Missing fields');
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
}
?>