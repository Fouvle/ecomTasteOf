<?php
// actions/fetch_all_vendors.php
session_start();
require_once "../settings/db_cred.php";

header('Content-Type: application/json');

// Base SQL: Join vendors with customer to get profile image and city
$sql = "SELECT 
            v.vendor_id, 
            v.business_name, 
            v.business_description, 
            v.business_address,
            c.customer_image, 
            c.customer_city 
        FROM vendors v 
        JOIN customer c ON v.customer_id = c.customer_id 
        ORDER BY v.business_name ASC";

$result = $conn->query($sql);

if ($result) {
    $vendors = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode(['status' => 'success', 'data' => $vendors]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch vendors']);
}
?>