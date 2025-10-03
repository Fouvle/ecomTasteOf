<?php

require_once '../Classes/customer_class.php';

//login controller function login_customer_ctr($email, $password)
function login_customer_ctr($email, $password){
    $customer = new Customer();
    $user = $customer->getUserByEmail($email);
    if ($user && password_verify($password, $user['customer_password'])) {
        return $user;
    }
    return false;
}

?>