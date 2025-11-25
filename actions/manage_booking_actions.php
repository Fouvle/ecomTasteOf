<?php
// actions/manage_booking_action.php
session_start();
require_once "../controllers/booking_controller.php";

header('Content-Type: application/json');

// Check Vendor Authentication
if (!isset($_SESSION['vendor_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $booking_id = $_POST['booking_id'];

    // 1. Update Status (Approve/Reject)
    if ($action === 'update_status') {
        $status = $_POST['status'];
        if (update_booking_status_ctr($booking_id, $status)) {
            echo json_encode(['status' => 'success', 'message' => 'Booking status updated to ' . $status]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update status']);
        }
    }
    
    // 2. Delete Booking
    elseif ($action === 'delete') {
        if (delete_booking_ctr($booking_id)) {
            echo json_encode(['status' => 'success', 'message' => 'Booking deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete booking']);
        }
    }

    // 3. Edit Booking Details
    elseif ($action === 'edit_details') {
        $date = $_POST['date'];
        $time = $_POST['time'];
        $people = $_POST['people'];
        $datetime = date('Y-m-d H:i:s', strtotime("$date $time"));

        if (update_booking_details_ctr($booking_id, $datetime, $people)) {
            echo json_encode(['status' => 'success', 'message' => 'Booking details updated']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update details']);
        }
    }
}
?>