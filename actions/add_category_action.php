<?php
session_start();
require_once "../Settings/core.php";
require_once "../Controllers/category_controller.php";

// Ensure user is logged in
if (!isLoggedIn()) {
    header("Location: ../Login/login_register.php");
    exit();
}

// Handle add category
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = trim($_POST['category_name']);
    $user_id = $_SESSION['id'];

    if (empty($category_name)) {
        echo json_encode([
            "status" => "error",
            "message" => "Category name cannot be empty."
        ]);
        exit();
    }

    $result = addCategory($user_id, $category_name);

    if ($result === true) {
        echo json_encode([
            "status" => "success",
            "message" => "Category added successfully."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => $result
        ]);
    }
    exit();
}
?>