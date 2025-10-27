<?php
session_start();
header('Content-Type: application/json');
require_once '../settings/core.php';
require_once '../controllers/brand_controller.php';

//Ensure user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access. Please log in as admin.']);
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand_name = trim($_POST['brand_name'] ?? '');
    $cat_id = intval($_POST['cat_id'] ?? 0);

    // Basic validation
    if (empty($brand_name) || $cat_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid brand name or category.']);
        exit();
    }

    // Add the brand using the controller
    $result = add_brand_ctr($brand_name, $cat_id);
    if ($result === true) {
        echo json_encode(['status' => 'success', 'message' => 'Brand added successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $result]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
exit();
?>