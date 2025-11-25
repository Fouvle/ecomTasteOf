<?php
// actions/search_products.php
session_start();
require_once "../settings/connection.php";

header('Content-Type: application/json');

// 1. Collect Inputs
$query = isset($_POST['query']) ? trim($_POST['query']) : '';
$location = isset($_POST['location']) ? trim($_POST['location']) : '';
$maxPrice = isset($_POST['max_price']) ? (int)$_POST['max_price'] : 500;
$categories = isset($_POST['categories']) ? $_POST['categories'] : []; // Array of IDs

// 2. Base SQL
// Joining products -> vendors -> customer (to get city/location) -> categories
$sql = "SELECT 
            p.product_id, 
            p.product_title, 
            p.product_price, 
            p.product_image, 
            p.product_desc,
            v.business_name, 
            cust.customer_city,
            cat.cat_name
        FROM products p
        JOIN vendors v ON p.vendor_id = v.vendor_id
        JOIN customer cust ON v.customer_id = cust.customer_id
        JOIN categories cat ON p.product_cat = cat.cat_id
        WHERE p.product_price <= ?";

// Parameters array for prepared statement
$params = [$maxPrice];
$types = "d"; // double/int for price

// 3. Apply Filters

// Search Query (Matches Product Title, Keywords, or Business Name)
if (!empty($query)) {
    $sql .= " AND (p.product_title LIKE ? OR p.product_keywords LIKE ? OR v.business_name LIKE ?)";
    $searchTerm = "%$query%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= "sss";
}

// Location Filter (Exact match on city)
if (!empty($location)) {
    $sql .= " AND cust.customer_city = ?";
    $params[] = $location;
    $types .= "s";
}

// Category Filter (IN clause)
if (!empty($categories)) {
    // Create placeholders (?, ?, ?) based on array count
    $placeholders = implode(',', array_fill(0, count($categories), '?'));
    $sql .= " AND p.product_cat IN ($placeholders)";
    $types .= str_repeat('i', count($categories));
    foreach ($categories as $catId) {
        $params[] = $catId;
    }
}

// Order results
$sql .= " ORDER BY p.product_id DESC";

// 4. Execute
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    
    echo json_encode(['status' => 'success', 'data' => $data]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Query failed']);
}
?>