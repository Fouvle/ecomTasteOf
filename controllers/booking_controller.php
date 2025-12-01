<?php
require_once dirname(__FILE__) . '/../settings/db_cred.php';

// Create a new booking
function create_booking_ctr($customer_id, $vendor_id, $datetime, $number_of_people) {
    global $conn;
    
    $sql = "INSERT INTO bookings (customer_id, vendor_id, booking_datetime, number_of_people, booking_status) 
            VALUES (?, ?, ?, ?, 'pending')";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        error_log('Prepare failed: ' . $conn->error);
        return false;
    }
    
    $stmt->bind_param('iisi', $customer_id, $vendor_id, $datetime, $number_of_people);
    
    if (!$stmt->execute()) {
        error_log('Execute failed: ' . $stmt->error);
        return false;
    }
    
    $stmt->close();
    return true;
}

// 1. Get Single Booking Details (For Modal)
function get_booking_details_ctr($booking_id) {
    global $conn;
    $sql = "SELECT b.*, v.business_name, v.business_address, v.business_phone, v.business_email
            FROM bookings b 
            JOIN vendors v ON b.vendor_id = v.vendor_id 
            WHERE b.booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// 2. Add Review
function add_review_ctr($customer_id, $vendor_id, $booking_id, $rating, $review_text) {
    global $conn;
    $sql = "INSERT INTO reviews (customer_id, vendor_id, product_id, rating, review_text) 
            VALUES (?, ?, NULL, ?, ?)"; // Product ID NULL for general vendor/booking review
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiis", $customer_id, $vendor_id, $rating, $review_text);
    return $stmt->execute();
}

// 3. Mark Booking as Paid (Simple simulation)
function mark_booking_paid_ctr($booking_id) {
    global $conn;
    // In a real app, this would be tied to the 'orders' and 'payment' tables.
    // For this prototype, we'll assume 'confirmed' status implies payment success or we add a flag.
    // Let's create a dummy order record to simulate payment flow.
    
    // First, verify booking exists
    $check = $conn->query("SELECT * FROM bookings WHERE booking_id = $booking_id");
    if($check->num_rows == 0) return false;
    $bk = $check->fetch_assoc();

    // Insert Order
    $inv = "INV-" . time();
    $oSql = "INSERT INTO orders (customer_id, booking_id, invoice_no, order_date, order_status) 
             VALUES (?, ?, ?, NOW(), 'completed')";
    $stmt = $conn->prepare($oSql);
    $stmt->bind_param("iis", $bk['customer_id'], $booking_id, $inv);
    
    if($stmt->execute()) {
        // Create Payment Record
        $order_id = $stmt->insert_id;
        $amount = 50.00; // Placeholder amount
        $pSql = "INSERT INTO payment (order_id, customer_id, amount, payment_method, payment_date) 
                 VALUES (?, ?, ?, 'card', NOW())";
        $pStmt = $conn->prepare($pSql);
        $pStmt->bind_param("iid", $order_id, $bk['customer_id'], $amount);
        $pStmt->execute();
        
        // Update Booking Status to Confirmed
        $uSql = "UPDATE bookings SET booking_status = 'confirmed' WHERE booking_id = ?";
        $uStmt = $conn->prepare($uSql);
        $uStmt->bind_param("i", $booking_id);
        $uStmt->execute();
        
        return true;
    }
    return false;
}
?>