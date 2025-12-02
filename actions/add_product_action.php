<?php
session_start();
require_once __DIR__ . '/../settings/db_cred.php';

header('Content-Type: application/json');

// Ensure vendor is logged in
if (!isset($_SESSION['vendor_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized.']);
    exit;
}

$vendor_id = (int) $_SESSION['vendor_id'];
$action = $_POST['action'] ?? 'add';

// Validate Basic Inputs
$title = trim($_POST['productTitle'] ?? '');
$price = $_POST['productPrice'] ?? '';
$cat = $_POST['productCategory'] ?? '';
$brand = $_POST['productBrand'] ?? '';
$desc = trim($_POST['productDescription'] ?? '');

if ($title === '' || $price === '' || $cat === '' || $brand === '') {
    echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields.']);
    exit;
}

// Handle Image Upload
$image_path = null;
if (!empty($_FILES['productImage']) && $_FILES['productImage']['error'] !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES['productImage'];
    $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
    
    // Validate Type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mime, $allowed)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid image format.']);
        exit;
    }

    // Upload
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $dir = __DIR__ . '/../uploads/products/';
    if (!is_dir($dir)) mkdir($dir, 0777, true);
    
    $fileName = time() . '_' . uniqid() . '.' . $ext;
    if (move_uploaded_file($file['tmp_name'], $dir . $fileName)) {
        $image_path = 'uploads/products/' . $fileName;
    }
}

// --- ADD LOGIC ---
if ($action === 'add') {
    $sql = "INSERT INTO products (vendor_id, product_cat, product_brand, product_title, product_price, product_desc, product_image) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiisdss', $vendor_id, $cat, $brand, $title, $price, $desc, $image_path);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Product added successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $stmt->error]);
    }
} 
// --- EDIT LOGIC ---
elseif ($action === 'edit') {
    $product_id = $_POST['product_id'] ?? 0;
    
    // Verify Ownership
    $check = $conn->prepare("SELECT product_image FROM products WHERE product_id = ? AND vendor_id = ?");
    $check->bind_param("ii", $product_id, $vendor_id);
    $check->execute();
    $res = $check->get_result()->fetch_assoc();
    
    if (!$res) {
        echo json_encode(['status' => 'error', 'message' => 'Product not found or unauthorized.']);
        exit;
    }
    
    // Keep old image if no new one uploaded
    if (!$image_path) {
        $image_path = $res['product_image'];
    } else {
        // Optional: Delete old image file here if you want to save space
        // if($res['product_image'] && file_exists("../".$res['product_image'])) unlink("../".$res['product_image']);
    }

    $sql = "UPDATE products SET product_cat=?, product_brand=?, product_title=?, product_price=?, product_desc=?, product_image=? 
            WHERE product_id=? AND vendor_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisdssii", $cat, $brand, $title, $price, $desc, $image_path, $product_id, $vendor_id);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Product updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Update failed.']);
    }
}
?>