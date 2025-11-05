<?php
session_start();
require_once "../Settings/core.php";
require_once "../Controllers/category_controller.php";

// Ensure user is logged in
if (!isLoggedIn()) {
    header("Location: ../Login/login_register.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = $_POST['category_id'] ?? '';
    $user_id = $_SESSION['id'];

    if (empty($category_id)) {
        echo json_encode([
            "status" => "error",
            "message" => "Category ID is required."
        ]);
        exit();
    }

    $result -> deleteCategory($user_id, $category_id);

    if ($result === true) {
        echo json_encode([
            "status" => "success",
            "message" => "Category deleted successfully."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => $result
        ]);
    }
    exit();
}
