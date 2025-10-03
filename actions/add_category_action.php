<?php
session_start();
require_once "../Settings/core.php";
require_once "../Controllers/category_controller.php";

// Ensure user is logged in
if (!isLoggedIn()) {
    header("Location: ../Login/login_register.php");
    exit();
}

// Check if form data was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = trim($_POST['category_name']);
    $user_id = $_SESSION['id']; // logged-in user ID

    if (empty($category_name)) {
        echo json_encode([
            "status" => "error",
            "message" => "Category name cannot be empty."
        ]);
        exit();
    }

    // Call the controller function to add category
    $result = addCategory($user_id, $category_name);

    if ($result === true) {
        echo json_encode([
            "status" => "success",
            "message" => "Category added successfully."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => $result // return error message from controller
        ]);
    }
    exit();
}
