<?php
require_once '../Classes/customer_class.php';

// Login controller function
function login_customer_ctr($email, $password){
    $customer = new Customer();
    $user = $customer->getUserByEmail($email);
    if ($user && password_verify($password, $user['customer_pass'])) {
        return $user;
    }
    return false;
}

// Register customer controller function
function register_customer_ctr($name, $email, $password, $country, $city, $phone_number, $role = 2, $imagePath = null){
    $customer = new Customer();
    return $customer->registerCustomer($name, $email, $password, $country, $city, $phone_number, $role, $imagePath);
}
?>
