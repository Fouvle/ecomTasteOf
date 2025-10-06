<?php
header('Content-Type: application/json');
$response = array();

// Check if customer is already logged in
if (isset($_SESSION['customer_id'])) {
    $response['status'] = 'error';
    $response['message'] = 'You are already logged in';
    echo json_encode($response);
    exit();
}

require_once '../controllers/customer_controller.php';

// Validate and sanitize input
$name = isset($_POST['customer_name']) ? trim($_POST['customer_name']) : null;
$email = isset($_POST['customer_email']) ? trim($_POST['customer_email']) : null;
$password = isset($_POST['customer_pass']) ? trim($_POST['customer_pass']) : null;
$country = isset($_POST['customer_country']) ? trim($_POST['customer_country']) : null;
$city = isset($_POST['customer_city']) ? trim($_POST['customer_city']) : null;
$phone_number = isset($_POST['customer_contact']) ? trim($_POST['customer_contact']) : null;

// Ensure all fields are provided
if (!$name || !$email || !$password || !$country || !$city || !$phone_number) {
    $response['status'] = 'error';
    $response['message'] = 'All fields are required';
    echo json_encode($response);
    exit();
}

// Ensure role is either 0 (customer) or 1 (admin)
$role = 2; // Default role for customers

// Call the controller function
$customer_id = register_customer_ctr($name, $email, $password, $country, $city, $phone_number, $role);

if ($customer_id) {
    $response['status'] = 'success';
    $response['message'] = 'Registered successfully';
    $response['customer_id'] = $customer_id;
    $response['role'] = $role;
} else {
    $response['status'] = 'error';
    $response['message'] = 'Email already exists or failed to register';
}
echo json_encode($response);