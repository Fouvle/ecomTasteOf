<?php
header('Content-Type: application/json');
session_start();
ob_start();

$response = array();
$response['role'] = $_SESSION['role'];

error_log('Role: ' . $_SESSION['role']); 

/**
 * Utility function: check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['customer_id']);
}

/**
 * Utility function: check if user is admin
 */
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// If user already logged in, return status
if (isLoggedIn()) {
    $response['status'] = 'success';
    $response['message'] = 'Already logged in.';
    $response['customer_id'] = $_SESSION['customer_id'];
    $response['name'] = $_SESSION['name'];
    $response['role'] = $_SESSION['role'];

    echo json_encode($response);
    exit();
}

require_once '../controllers/customer_controller.php';

// Collect login form data safely
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Validate inputs quickly
if (empty($email) || empty($password)) {
    $response['status'] = 'error';
    $response['message'] = 'Email and password are required';
    echo json_encode($response);
    exit();
}

// Fetch customer by email
$customer = login_customer_ctr($email, $password);

if ($customer) {
    if (password_verify($password, $customer['customer_pass'])) {  // NOTE: matches DB column
        // Set session variables
        $_SESSION['customer_id']   = $customer['customer_id'];
        $_SESSION['name']          = $customer['customer_name'];
        $_SESSION['email']         = $customer['customer_email'];
        $_SESSION['phone_number']  = $customer['customer_contact'];
        $_SESSION['role']          = $customer['user_role']; // numeric or 'admin' depending on DB

        $response['status']  = 'success';
        $response['message'] = 'Login successful';
        $response['customer_id'] = $customer['customer_id'];
        $response['name']    = $customer['customer_name'];
        $response['role']    = $_SESSION['role'];
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Invalid password';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'No account found with that email';
}

echo json_encode($response);
exit();
?>