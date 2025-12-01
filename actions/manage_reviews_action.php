<?php
session_start();
require_once "../controllers/review_controller.php";

header('Content-Type: application/json');

// Check Login
if (!isset($_SESSION['customer_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Please login.']);
    exit();
}

$action = $_POST['action'] ?? '';

// 1. Add Review
if ($action === 'add') {
    $customer_id = $_SESSION['customer_id'];
    $vendor_id = $_POST['vendor_id'];
    $booking_id = $_POST['booking_id']; // Optional reference
    $rating = $_POST['rating'];
    $text = $_POST['review_text'];

    if (empty($rating) || empty($text)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields required']);
        exit;
    }

    if (add_review_ctr($customer_id, $vendor_id, $booking_id, $rating, $text)) {
        echo json_encode(['status' => 'success', 'message' => 'Review submitted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add review']);
    }
}

// 2. Update Review
elseif ($action === 'edit') {
    $review_id = $_POST['review_id'];
    $rating = $_POST['rating'];
    $text = $_POST['review_text'];

    // Verify ownership (optional but recommended)
    $review = get_review_ctr($review_id);
    if ($review['customer_id'] != $_SESSION['customer_id']) {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        exit;
    }

    if (update_review_ctr($review_id, $rating, $text)) {
        echo json_encode(['status' => 'success', 'message' => 'Review updated']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update']);
    }
}

// 3. Delete Review
elseif ($action === 'delete') {
    $review_id = $_POST['review_id'];

    // Verify ownership
    $review = get_review_ctr($review_id);
    if ($review['customer_id'] != $_SESSION['customer_id']) {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        exit;
    }

    if (delete_review_ctr($review_id)) {
        echo json_encode(['status' => 'success', 'message' => 'Review deleted']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete']);
    }
}

else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
}
?>