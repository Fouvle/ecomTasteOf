<?php
session_start();
require_once "../Settings/core.php";
require_once "../Controllers/category_controller.php";

// Make sure the user is logged in
if (!isLoggedIn()) {
    header("Location: ../Login/login_register.php");
    exit();
}

$user_id = $_SESSION['id']; // the currently logged-in user's ID

// Fetch categories from the controller
$categories = getUserCategories($user_id);

// Return the data
header("Content-Type: application/json");
if ($categories) {
    echo json_encode([
        "status" => "success",
        "data" => $categories
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "No categories found for this user."
    ]);
}
exit();
