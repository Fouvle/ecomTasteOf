<?php
session_start();
require_once "../settings/db_cred.php";
require_once "../settings/paystack_config.php";

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$reference = $input['reference'] ?? '';

if (!$reference) {
    echo json_encode(['status' => 'error', 'message' => 'No reference provided']);
    exit();
}

// 1. Verify with Paystack API
$result = paystack_verify($reference);

if ($result['status'] && $result['data']['status'] === 'success') {
    
    // Payment is valid! Extract details
    $amount = $result['data']['amount'] / 100; // Convert back to GHS
    $channel = $result['data']['channel'];
    $paid_at = $result['data']['paid_at']; // ISO Date
    
    // 2. Identify the Booking (stored in session or via metadata if you passed it)
    // Here relying on session for simplicity, but robust apps use metadata
    $booking_id = $_SESSION['pending_booking_id'] ?? 0;
    $customer_id = $_SESSION['customer_id'] ?? 0;

    if (!$booking_id) {
        // Fallback: Try to find booking if you stored reference in DB beforehand (optional)
        // For this flow, we assume session is active.
        echo json_encode(['status' => 'error', 'message' => 'Session expired. Contact support with Ref: ' . $reference]);
        exit();
    }

    $conn->begin_transaction();

    try {
        // 3. Create Order Record
        $inv = "INV-" . time();
        $oSql = "INSERT INTO orders (customer_id, booking_id, invoice_no, order_date, order_status) 
                 VALUES (?, ?, ?, NOW(), 'completed')";
        $stmt1 = $conn->prepare($oSql);
        $stmt1->bind_param("iis", $customer_id, $booking_id, $inv);
        $stmt1->execute();
        $order_id = $stmt1->insert_id;

        // 4. Create Payment Record
        $pSql = "INSERT INTO payment (order_id, customer_id, amount, currency, payment_date, payment_method, payment_channel, transaction_ref, payment_status) 
                 VALUES (?, ?, ?, 'GHS', ?, 'paystack', ?, ?, 'success')";
        $stmt2 = $conn->prepare($pSql);
        // Date formatting
        $payment_date = date("Y-m-d H:i:s", strtotime($paid_at));
        $stmt2->bind_param("iidsss", $order_id, $customer_id, $amount, $payment_date, $channel, $reference);
        $stmt2->execute();

        // 5. Update Booking Status to Confirmed (Approved)
        $uSql = "UPDATE bookings SET booking_status = 'confirmed' WHERE booking_id = ?";
        $stmt3 = $conn->prepare($uSql);
        $stmt3->bind_param("i", $booking_id);
        $stmt3->execute();

        $conn->commit();
        
        // Clean session
        unset($_SESSION['pending_booking_id']);
        unset($_SESSION['pending_pay_ref']);

        echo json_encode(['status' => 'success', 'message' => 'Payment verified']);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Database update failed']);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Payment verification failed at gateway']);
}
?>