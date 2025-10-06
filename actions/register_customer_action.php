<?php
header('Content-Type: application/json');
session_start();
$response = array();

// check if customer is already logged in
if (isset($_SESSION['customer_id'])) {
    $response['status'] = 'error';
    $response['message'] = 'You are already logged in';
    echo json_encode($response);
    exit();
}

require_once '../controllers/customer_controller.php';

$name = $_POST['customer_name'];
$email = $_POST['customer_email'];
$password = $_POST['customer_pass'];
$country = $_POST['customer_country'];
$city = $_POST['customer_city'];
$phone_number = $_POST['customer_contact'];

// Ensure role is either 0 (customer) or 1 (admin)
$role = 2;

$customer_id = register_customer_ctr($name, $email, $password, $country, $city, $phone_number, $role);

if (true) {
    $response['status'] = 'success';
    $response['message'] = 'Registered successfully';
    $response['customer_id'] = $customer_id;
    $response['role'] = $role;
} else {
    $response['status'] = 'error';
    $response['message'] = 'Email already exists or failed to register';
}
echo json_encode($response);
// exit();
?>
