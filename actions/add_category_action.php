<?php
session_start();
require_once __DIR__ . "/../settings/core.php";
require_once __DIR__ . "/../controllers/category_controller.php";
require_once  "../classes/category_class.php";

// Ensure user is logged in
if (!isLoggedIn()) {
    header("Location: ../login/login_register.php");
    exit();
}

// Handle add category
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // $cat_id = "";
    $category_name = trim($_POST['category_name']);
    $user_id = $_SESSION['id']; // Retrieve user ID from the session

    $catId = intval($_POST['cat_id']);


    if (empty($category_name)) {
        echo json_encode([
            "status" => "error",
            "message" => "Category name cannot be empty."
        ]);
        exit();
    }

    // Call the addCategory function
    $result -> addCategory($catId, $category_name);

    if ($result === true) {
        echo json_encode([
            "status" => "success",
            "message" => "Category added successfully."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to add category."
        ]);
    }
    exit();
}

