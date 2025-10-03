<?php
require_once '../Classes/customer_class.php';

// Login controller
function login_customer_ctr($email, $password){
    $customer = new Customer();
    $user = $customer->getUserByEmail($email);
    if ($user && password_verify($password, $user['customer_pass'])) {
        return $user;
    }
    return false;
}

// Register controller
function register_customer_ctr($name, $email, $password, $phone_number, $role){
    $customer = new Customer();
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    return $customer->registerUser($name, $email, $hashed_password, $phone_number, $role);
}
?>
