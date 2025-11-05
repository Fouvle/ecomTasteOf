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
    $new_name = trim($_POST['category_name']);
    $user_id = $_SESSION['id'];

    if (empty($category_id) || empty($new_name)) {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid category ID or name."
        ]);
        exit();
    }

    $result -> updateCategory($user_id, $category_id, $new_name);

    if ($result === true) {
        echo json_encode([
            "status" => "success",
            "message" => "Category updated successfully."
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