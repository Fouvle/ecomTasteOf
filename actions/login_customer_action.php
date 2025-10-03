<?php
header('Content-Type: application/json');
session_start();
$response = array();

// check if customer is already logged in
if (isset($_SESSION['customer_id'])) {
    $response['status'] = 'success';
    $response['message'] = 'Customer is already logged in.';
    echo json_encode($response);
    exit();
}

require_once '../controllers/customer_controller.php';

// collect login form data
$email = $_POST['email'];
$password = $_POST['password'];

//fetch customer by email
$customer = get_customer_by_email_ctr($email);

if ($customer && password_verify($password, $customer['password'])) {
    // set session variables
    $_SESSION['customer_id'] = $customer['customer_id'];
    $_SESSION['name'] = $customer['name'];
    $_SESSION['email'] = $customer['email'];
    $_SESSION['phone_number'] = $customer['phone_number'];
    $_SESSION['role'] = $customer['role'];

    $response['status'] = 'success';
    $response['message'] = 'Login successful';
    $response['customer_id'] = $customer['customer_id'];
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid email or password';
} else {
    $response['status'] = 'error';
    $response['message'] = 'No account found with that email';
}
echo json_encode($response);
exit();
?>