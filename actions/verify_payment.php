<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once "../settings/db_cred.php";
require_once "../settings/paystack_config.php";

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$reference = $input['reference'] ?? '';

error_log('Verify Payment: Reference=' . $reference);
error_log('Verify Payment: Session customer_id=' . ($_SESSION['customer_id'] ?? 'NOT SET'));
error_log('Verify Payment: Session pending_booking_id=' . ($_SESSION['pending_booking_id'] ?? 'NOT SET'));

if (!$reference) {
    error_log('Verify Payment: No reference provided');
    echo json_encode(['status' => 'error', 'message' => 'No reference provided']);
    exit();
}

// 1. Verify with Paystack API
$result = paystack_verify($reference);

error_log('Verify Payment: Paystack result=' . json_encode($result));

if ($result && $result['status'] === true && $result['data']['status'] === 'success') {
    
    // Payment is valid! Extract details
    $amount = $result['data']['amount'] / 100; // Convert back to GHS
    $channel = $result['data']['channel'];
    $paid_at = $result['data']['paid_at']; // ISO Date
    
    error_log('Verify Payment: Amount=' . $amount . ', Channel=' . $channel . ', Paid_at=' . $paid_at);
    
    // 2. Identify the Booking (stored in session or via metadata if you passed it)
    // Here relying on session for simplicity, but robust apps use metadata
    $booking_id = $_SESSION['pending_booking_id'] ?? 0;
    $customer_id = $_SESSION['customer_id'] ?? 0;

    if (!$booking_id || !$customer_id) {
        error_log('Verify Payment: Missing booking_id or customer_id - booking_id=' . $booking_id . ', customer_id=' . $customer_id);
        echo json_encode(['status' => 'error', 'message' => 'Session expired or invalid. Contact support with Ref: ' . $reference]);
        exit();
    }

    try {
        $conn->begin_transaction();

        // 3. Create Order Record
        $inv = "INV-" . time();
        $oSql = "INSERT INTO orders (customer_id, booking_id, invoice_no, order_date, order_status) 
                 VALUES (?, ?, ?, NOW(), 'completed')";
        $stmt1 = $conn->prepare($oSql);
        
        if (!$stmt1) {
            error_log('Verify Payment: Prepare order failed - ' . $conn->error);
            throw new Exception('Database error: ' . $conn->error);
        }
        
        $stmt1->bind_param("iis", $customer_id, $booking_id, $inv);
        
        if (!$stmt1->execute()) {
            error_log('Verify Payment: Execute order failed - ' . $stmt1->error);
            throw new Exception('Order creation failed: ' . $stmt1->error);
        }
        
        $order_id = $stmt1->insert_id;
        error_log('Verify Payment: Order created - order_id=' . $order_id);

        // 4. Create Payment Record
        $pSql = "INSERT INTO payment (order_id, customer_id, amount, currency, payment_date, payment_method, payment_channel, transaction_ref, payment_status) 
                 VALUES (?, ?, ?, 'GHS', ?, 'paystack', ?, ?, 'success')";
        $stmt2 = $conn->prepare($pSql);
        
        if (!$stmt2) {
            error_log('Verify Payment: Prepare payment failed - ' . $conn->error);
            throw new Exception('Database error: ' . $conn->error);
        }
        
        // Date formatting
        $payment_date = date("Y-m-d H:i:s", strtotime($paid_at));
        $stmt2->bind_param("iidsss", $order_id, $customer_id, $amount, $payment_date, $channel, $reference);
        
        if (!$stmt2->execute()) {
            error_log('Verify Payment: Execute payment failed - ' . $stmt2->error);
            throw new Exception('Payment record failed: ' . $stmt2->error);
        }
        
        error_log('Verify Payment: Payment record created');

        // 5. Update Booking Status to Confirmed (Approved)
        $uSql = "UPDATE bookings SET booking_status = 'confirmed' WHERE booking_id = ?";
        $stmt3 = $conn->prepare($uSql);
        
        if (!$stmt3) {
            error_log('Verify Payment: Prepare booking update failed - ' . $conn->error);
            throw new Exception('Database error: ' . $conn->error);
        }
        
        $stmt3->bind_param("i", $booking_id);
        
        if (!$stmt3->execute()) {
            error_log('Verify Payment: Execute booking update failed - ' . $stmt3->error);
            throw new Exception('Booking update failed: ' . $stmt3->error);
        }
        
        error_log('Verify Payment: Booking updated to confirmed');

        $conn->commit();
        
        // Clean session
        unset($_SESSION['pending_booking_id']);
        unset($_SESSION['pending_pay_ref']);

        error_log('Verify Payment: SUCCESS - All records created');

        echo json_encode(['status' => 'success', 'message' => 'Payment verified']);

    } catch (Exception $e) {
        error_log('Verify Payment: Exception - ' . $e->getMessage());
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Database update failed: ' . $e->getMessage()]);
    }

} else {
    error_log('Verify Payment: Paystack verification failed - ' . json_encode($result));
    echo json_encode(['status' => 'error', 'message' => 'Payment verification failed at gateway: ' . ($result['message'] ?? 'Unknown error')]);
}
?>