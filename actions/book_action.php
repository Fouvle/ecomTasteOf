<?php
// actions/book_action.php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors to client, log them instead
require_once "../controllers/booking_controller.php";

header('Content-Type: application/json');

// 1. Check Authentication
if (!isset($_SESSION['customer_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Please login to book a table.']);
    exit();
}

$customer_id = $_SESSION['customer_id'];

// 2. Validate Inputs
if (isset($_POST['vendor_id'], $_POST['date'], $_POST['time'], $_POST['people'])) {
    $vendor_id = (int)$_POST['vendor_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $people = (int)$_POST['people'];

    // Combine Date and Time for DB
    $datetime = date('Y-m-d H:i:s', strtotime("$date $time"));

    if (empty($date) || empty($time) || $people < 1) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid booking details.']);
        exit();
    }

    // 3. Create Booking
    try {
        $result = create_booking_ctr($customer_id, $vendor_id, $datetime, $people);
        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Booking request sent successfully!']);
        } else {
            error_log('Booking creation failed for customer ' . $customer_id);
            echo json_encode(['status' => 'error', 'message' => 'Failed to place booking. Please try again.']);
        }
    } catch (Exception $e) {
        error_log('Booking error: ' . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'System error: ' . $e->getMessage()]);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
}
?>