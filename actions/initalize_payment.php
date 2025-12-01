<?php
session_start();
require_once "../settings/db_cred.php";
require_once "../settings/paystack_config.php";

header('Content-Type: application/json');

if (!isset($_SESSION['customer_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Please login.']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$booking_id = $input['booking_id'] ?? 0;
$amt = $input['amt'] ?? 0;

if (!$booking_id || !$amt) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid booking details.']);
    exit();
}

// 1. Get Customer Email
$user_id = $_SESSION['customer_id'];
$sql = "SELECT customer_email FROM customer WHERE customer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$email = $user['customer_email'];

// 2. Generate Reference
$reference = 'TC-BK-' . $booking_id . '-' . time();

// 3. Define Callback URL (Absolute Path required by Paystack)
$callback_url = "http://169.239.251.102:442/~nana.nkrumah/views/payment_callback.php"; 

// 4. Initialize Paystack
try {
    $response = paystack_init($email, $amt, $reference, $callback_url);

    if ($response['status']) {
        // Save temp reference in session for security check later
        $_SESSION['pending_pay_ref'] = $reference;
        $_SESSION['pending_booking_id'] = $booking_id;

        echo json_encode([
            'status' => 'success', 
            'authorization_url' => $response['data']['authorization_url']
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => $response['message']]);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Connection error.']);
}
?>