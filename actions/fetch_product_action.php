<?php
session_start();
header("Content-Type: application/json");
require_once "../Settings/core.php";
require_once "../Controllers/product_controller.php";

// Ensure user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    echo json_encode(["status" => "error", "message" => "Unauthorized access."]);
    exit();
}

$products = get_all_products_ctr();

if ($products) {
    echo json_encode([
        "status" => "success",
        "data" => $products
    ]);
} else {
    echo json_encode([
        "status" => "success",
        "data" => [] 
    ]);
}
exit();
?>