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
$role = $_POST['customer_role'];

// Ensure role is either 0 (customer) or 1 (admin)
$role = ($role == "admin") ? 1 : 0;

$customer_id = register_customer_ctr($name, $email, $password, $phone_number, $role);

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
exit();
?>
