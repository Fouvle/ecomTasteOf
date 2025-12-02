<?php
session_start();
require_once __DIR__ . '/../settings/db_cred.php';

header('Content-Type: application/json');

// Ensure vendor is logged in
if (!isset($_SESSION['vendor_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized.']);
    exit;
}

$vendor_id = (int) $_SESSION['vendor_id'];

// Accept POST only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed.']);
    exit;
}

$title = trim($_POST['productTitle'] ?? '');
$price = $_POST['productPrice'] ?? '';
$cat = $_POST['productCategory'] ?? '';
$brand = $_POST['productBrand'] ?? '';
$desc = trim($_POST['productDescription'] ?? '');

$errors = [];
if ($title === '') { $errors[] = 'Product name is required.'; }
if ($price === '' || !is_numeric($price) || $price < 0) { $errors[] = 'Valid product price is required.'; }
if ($cat === '' || !is_numeric($cat)) { $errors[] = 'Product category is required.'; }
if ($brand === '' || !is_numeric($brand)) { $errors[] = 'Product brand is required.'; }

// Handle image upload (optional)
$image_path = null;
if (!empty($_FILES['productImage']) && $_FILES['productImage']['error'] !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES['productImage'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Error uploading image.';
    } else {
        $allowed = ['image/jpeg','image/png','image/webp','image/gif'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mime, $allowed)) {
            $errors[] = 'Invalid image type. Allowed: JPG, PNG, WEBP, GIF.';
        } elseif ($file['size'] > 3 * 1024 * 1024) {
            $errors[] = 'Image too large (max 3MB).';
        } else {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $uploadsDir = __DIR__ . '/../uploads/products';
            if (!is_dir($uploadsDir)) { mkdir($uploadsDir, 0755, true); }
            $safeName = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
            $dest = $uploadsDir . '/' . $safeName;
            if (!move_uploaded_file($file['tmp_name'], $dest)) {
                $errors[] = 'Failed to move uploaded image.';
            } else {
                // store relative path for DB
                $image_path = 'uploads/products/' . $safeName;
            }
        }
    }
}

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => implode(' ', $errors)]);
    exit;
}

// Insert product
$sql = "INSERT INTO products (vendor_id, product_cat, product_brand, product_title, product_price, product_desc, product_image) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    error_log('Add product prepare failed: ' . $conn->error);
    echo json_encode(['status' => 'error', 'message' => 'Server error.']);
    exit;
}

$price_val = (float) $price;
$img_val = $image_path ?? null;
$stmt->bind_param('iiisdss', $vendor_id, $cat, $brand, $title, $price_val, $desc, $img_val);

if (!$stmt->execute()) {
    http_response_code(500);
    error_log('Add product execute failed: ' . $stmt->error);
    echo json_encode(['status' => 'error', 'message' => 'Failed to add product.']);
    exit;
}

$new_id = $stmt->insert_id;
$stmt->close();

$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if ($isAjax) {
    echo json_encode(['status' => 'success', 'message' => 'Product added.', 'product_id' => $new_id]);
    exit;
}

// Fallback: redirect back to vendor dashboard
header('Location: ../admin/vendor_dashboard.php?added=1');
exit;

?>
