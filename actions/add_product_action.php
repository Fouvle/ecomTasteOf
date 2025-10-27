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
    $catId = $_POST['product_cat'] ?? null;
    $brandId = $_POST['product_brand'] ?? null;
    $title = trim($_POST['product_title'] ?? '');
    $price = $_POST['product_price'] ?? null;
    $desc = trim($_POST['product_desc'] ?? '');
    $keywords = trim($_POST['product_keywords'] ?? '');

    if (empty($catId) || empty($brandId) || empty($title) || empty($price) || empty($desc)) {
        echo json_encode(["status" => "error", "message" => "Missing required product data."]);
        exit();
    }

    // Attempt to add product data (image is handled separately)
    $new_product_id = add_product_ctr($catId, $brandId, $title, $price, $desc, $keywords);

    if ($new_product_id) {
        echo json_encode([
            "status" => "success",
            "message" => "Product data saved successfully.",
            "product_id" => $new_product_id // Crucial for subsequent image upload
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to add product data to the database."]);
    }
    exit();
}
?>