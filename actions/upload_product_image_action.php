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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['product_image']) && isset($_POST['product_id'])) {
    $productId = intval($_POST['product_id']);
    $userId = getUserId(); 

    if (!$productId || !$userId) {
        echo json_encode(["status" => "error", "message" => "Product ID or User ID is missing."]);
        exit();
    }

    $file = $_FILES['product_image'];
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    
    // Construct the required path structure: uploads/u{user_id}/p{product_id}/
    $target_dir_relative = "uploads/u{$userId}/p{$productId}/";
    $target_dir = "../" . $target_dir_relative; // Target directory from the action script's location
    $file_name = "product_image." . $file_extension;
    $target_file = $target_dir . $file_name;
    
    // Safety Check: Verify the target directory is within the authorized 'uploads' folder
    $safe_uploads_dir = realpath(__DIR__ . '/../uploads/');
    // Check if path is valid and starts with the safe uploads directory
    if (strpos(realpath($target_dir), $safe_uploads_dir) !== 0) {
        echo json_encode(["status" => "error", "message" => "Unauthorized upload path detected. Must be inside uploads/."]);
        exit();
    }

    // 1. Create directory if it doesn't exist
    if (!is_dir($target_dir)) {
        if (!mkdir($target_dir, 0777, true)) {
            echo json_encode(["status" => "error", "message" => "Failed to create upload directory."]);
            exit();
        }
    }

    // 2. Attempt to move the uploaded file
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        // 3. Construct the relative path for the database
        $db_path = $target_dir_relative . $file_name;
        
        // 4. Update the database record
        if (update_product_image_ctr($productId, $db_path)) {
            echo json_encode(["status" => "success", "message" => "Image uploaded and database updated successfully.", "path" => $db_path]);
        } else {
            echo json_encode(["status" => "error", "message" => "Image uploaded but failed to update database record."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to upload file to server."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request or missing file/product ID."]);
}
?>