<?php
require_once dirname(__FILE__) . '/../settings/connection.php';

// 1. Add Review
function add_review_ctr($customer_id, $vendor_id, $booking_id, $rating, $review_text) {
    global $conn;
    $sql = "INSERT INTO reviews (customer_id, vendor_id, product_id, rating, review_text, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())";
    // Using product_id column to store booking_id for now as per schema or assuming link
    // Ideally, schema should have booking_id in reviews table. 
    // Based on provided schema: `product_id INT(11) DEFAULT NULL`. 
    // If you want to link to booking specifically, we might need to alter table or assume product_id can store it conceptually, 
    // but better to stick to schema. Let's assume we link to vendor primarily.
    // If schema allows, we should add booking_id. Assuming standard schema from earlier prompts.
    
    // ADJUSTMENT: The provided schema for `reviews` has `product_id`. 
    // If we want to link a review to a booking, we might need to add a column or just track via vendor/customer.
    // For this implementation, I will insert into `reviews` and assume we are reviewing the VENDOR service.
    
    $sql = "INSERT INTO reviews (customer_id, vendor_id, rating, review_text, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiis", $customer_id, $vendor_id, $rating, $review_text);
    return $stmt->execute();
}

// 2. Get Reviews by Customer
function get_customer_reviews_ctr($customer_id) {
    global $conn;
    $sql = "SELECT r.*, v.business_name 
            FROM reviews r 
            JOIN vendors v ON r.vendor_id = v.vendor_id 
            WHERE r.customer_id = ? 
            ORDER BY r.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// 3. Get Reviews for Vendor
function get_vendor_reviews_ctr($vendor_id) {
    global $conn;
    $sql = "SELECT r.*, c.customer_name 
            FROM reviews r 
            JOIN customer c ON r.customer_id = c.customer_id 
            WHERE r.vendor_id = ? 
            ORDER BY r.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $vendor_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// 4. Update Review
function update_review_ctr($review_id, $rating, $review_text) {
    global $conn;
    $sql = "UPDATE reviews SET rating = ?, review_text = ? WHERE review_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $rating, $review_text, $review_id);
    return $stmt->execute();
}

// 5. Delete Review
function delete_review_ctr($review_id) {
    global $conn;
    $sql = "DELETE FROM reviews WHERE review_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $review_id);
    return $stmt->execute();
}

// 6. Get Single Review
function get_review_ctr($review_id) {
    global $conn;
    $sql = "SELECT * FROM reviews WHERE review_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $review_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}
?>