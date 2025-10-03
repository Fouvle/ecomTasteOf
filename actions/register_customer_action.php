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

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$phone_number = $_POST['phone_number'];
$role = $_POST['role'];

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
