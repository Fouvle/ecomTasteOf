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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and validate fields
    $productId = $_POST['product_id'] ?? null;
    $catId = $_POST['product_cat'] ?? null;
    $brandId = $_POST['product_brand'] ?? null;
    $title = trim($_POST['product_title'] ?? '');
    $price = $_POST['product_price'] ?? null;
    $desc = trim($_POST['product_desc'] ?? '');
    $keywords = trim($_POST['product_keywords'] ?? '');

    if (empty($productId) || empty($catId) || empty($brandId) || empty($title) || empty($price) || empty($desc)) {
        echo json_encode(["status" => "error", "message" => "Missing required product data."]);
        exit();
    }

    // Attempt to update product data (image is handled separately if submitted)
    $result = update_product_ctr($productId, $catId, $brandId, $title, $price, $desc, $keywords);

    if ($result) {
        echo json_encode([
            "status" => "success",
            "message" => "Product data updated successfully.",
            "product_id" => $productId // Return ID for potential subsequent image upload
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update product data in the database."]);
    }
    exit();
}
?>