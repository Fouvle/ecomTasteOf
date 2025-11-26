<?php
// controllers/booking_controller.php
require_once dirname(__FILE__) . '/../settings/db_cred.php';

// 1. Create a New Booking
function create_booking_ctr($customer_id, $vendor_id, $datetime, $people) {
    global $conn;
    $sql = "INSERT INTO bookings (customer_id, vendor_id, booking_datetime, number_of_people, booking_status) 
            VALUES (?, ?, ?, ?, 'pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisi", $customer_id, $vendor_id, $datetime, $people);
    return $stmt->execute();
}

// 2. Get Bookings for Customer
function get_customer_bookings_ctr($customer_id) {
    global $conn;
    $sql = "SELECT b.*, v.business_name, v.business_address 
            FROM bookings b 
            JOIN vendors v ON b.vendor_id = v.vendor_id 
            WHERE b.customer_id = ? 
            ORDER BY b.booking_datetime DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// 3. Get ALL Bookings for Vendor (Dashboard)
function get_all_vendor_bookings_ctr($vendor_id) {
    global $conn;
    $sql = "SELECT b.*, c.customer_name, c.customer_contact, c.customer_image
            FROM bookings b 
            JOIN customer c ON b.customer_id = c.customer_id
            WHERE b.vendor_id = ? 
            ORDER BY b.booking_datetime DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $vendor_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// 4. Update Booking Status (Approve/Reject)
function update_booking_status_ctr($booking_id, $status) {
    global $conn;
    $sql = "UPDATE bookings SET booking_status = ? WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $booking_id);
    return $stmt->execute();
}

// 5. Delete Booking
function delete_booking_ctr($booking_id) {
    global $conn;
    $sql = "DELETE FROM bookings WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);
    return $stmt->execute();
}

// 6. Update Booking Details (Edit)
function update_booking_details_ctr($booking_id, $datetime, $people) {
    global $conn;
    $sql = "UPDATE bookings SET booking_datetime = ?, number_of_people = ? WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $datetime, $people, $booking_id);
    return $stmt->execute();
}
?>