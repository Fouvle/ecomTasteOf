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
    $cat_id = intval($_POST['cat_id'] ?? 0);
    $brand_name = trim($_POST['brand_name'] ?? ''); 

    // Basic validation
    if (empty($cat_id) || empty($brand_name)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid brand name or category.']);
        exit();
    }

    // Update the brand using the controller
    $result = update_brand_ctr($cat_id, $brand_name);
    if ($result === true) {
        echo json_encode(['status' => 'success', 'message' => 'Brand updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $result]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
exit();;
?>