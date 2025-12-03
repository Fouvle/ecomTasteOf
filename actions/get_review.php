<?php
session_start();
require_once "../controllers/review_controller.php";

header('Content-Type: application/json');

if (!isset($_SESSION['customer_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Please login']);
    exit();
}

if (isset($_GET['review_id'])) {
    $review_id = $_GET['review_id'];
    $customer_id = $_SESSION['customer_id'];

    // Use the controller to get the review
    $review = get_review_ctr($review_id);

    if ($review) {
        // Security Check: Ensure this review belongs to the logged-in user
        if ($review['customer_id'] == $customer_id) {
            echo json_encode(['status' => 'success', 'data' => $review]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized access to this review']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Review not found']);
    }
} else {
    // No specific review_id provided: return all reviews for this customer
    $customer_id = $_SESSION['customer_id'];
    $reviews = get_customer_reviews_ctr($customer_id);
    echo json_encode(['status' => 'success', 'data' => $reviews]);
}
?>