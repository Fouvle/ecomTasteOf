<?php
header('Content-Type: application/json');
session_start();
$response = array();

// prevent logged in users from registering again
if (isset($_SESSION['customer_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'You are already logged in'
    ]);
    exit();
}

require_once '../controllers/customer_controller.php';

// Collect input safely
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$country = trim($_POST['country'] ?? '');
$city = trim($_POST['city'] ?? '');
$phone_number = trim($_POST['phone_number'] ?? '');
$role = $_POST['role'] ?? 2; // default to customer (2)

// Validate input
if (empty($name) || empty($email) || empty($password) || empty($country) || empty($city) || empty($phone_number)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'All fields are required'
    ]);
    exit();
}

// Encrypt password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Handle image upload (optional)
$imagePath = null;
if (!empty($_FILES['image']['name'])) {
    $targetDir = "../uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $imagePath = $targetDir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath);
}

// Call controller function
$customer_id = register_customer_ctr($name, $email, $hashedPassword, $country, $city, $phone_number, $role, $imagePath);

if ($customer_id) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Registered successfully',
        'customer_id' => $customer_id
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to register. Email may already exist.'
    ]);
}
exit();
?>
