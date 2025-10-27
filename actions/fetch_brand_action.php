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

//fetch all brands and categories
$brands = fetch_all_brands_ctr();
$categories = fetch_all_categories_ctr();

$brands_grouped = [];
foreach ($brands as $brand) {
    $cat_name = $brand['cat_name'];
    if (!isset($brands_grouped[$cat_name])) {
        $brands_grouped[$cat_name] = [];
    }
    $brands_grouped[$cat_name][] = [
        'brand_id' => $brand['brand_id'],
        'brand_name' => $brand['brand_name']
    ];
}

echo json_encode(['status' => 'success', 'brands' => $brands_grouped, 'categories' => $categories]);

//return the data
if (!empty($brands_grouped)) {
    echo json_encode(['status' => 'success', 'brands' => $brands_grouped]);
} else {
    echo json_encode(['status' => 'success', 'brands' => []]);
}
exit();
?>