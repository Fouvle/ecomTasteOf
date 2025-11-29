<?php
session_start();
require_once "../settings/db_cred.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Owner / Auth Details (Step 4)
    $owner_name = $_POST['owner_name'];
    $owner_email = $_POST['owner_email'];
    $owner_phone = $_POST['owner_phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Check if email exists
    $check = $conn->prepare("SELECT customer_id FROM customer WHERE customer_email = ?");
    $check->bind_param("s", $owner_email);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email address is already registered.']);
        exit;
    }

    // 2. Business Details (Steps 1-3)
    $bus_name = $_POST['business_name'];
    $bus_type = $_POST['business_type'];
    $cuisine = isset($_POST['cuisine_type']) ? implode(',', $_POST['cuisine_type']) : '';
    $desc = $_POST['business_description'];
    $price = $_POST['price_range'];
    
    $address = $_POST['business_address'];
    $city = $_POST['business_city'];
    $bus_phone = $_POST['business_phone'];
    $bus_email = $_POST['business_email'];
    $website = $_POST['website'] ?? '';
    
    $capacity = $_POST['seating_capacity'];
    $days = isset($_POST['operating_days']) ? implode(',', $_POST['operating_days']) : '';
    $open_time = $_POST['opening_time'];
    $close_time = $_POST['closing_time'];
    
    // Payment (Step 4)
    $momo_prov = $_POST['momo_provider'];
    $momo_num = $_POST['momo_number'];
    $reg_no = $_POST['business_reg_no'] ?? '';
    $tin = $_POST['tin'] ?? '';

    // File Upload (Menu)
    $menuPath = null;
    if (isset($_FILES['menu_file']) && $_FILES['menu_file']['error'] == 0) {
        $dir = "../uploads/vendors/menus/";
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        $fileName = time() . "_" . basename($_FILES['menu_file']['name']);
        if (move_uploaded_file($_FILES['menu_file']['tmp_name'], $dir . $fileName)) {
            $menuPath = "uploads/vendors/menus/" . $fileName;
        }
    }

    // START TRANSACTION
    $conn->begin_transaction();

    try {
        // A. Insert into Customer Table (as Owner)
        $cSql = "INSERT INTO customer (customer_name, customer_email, customer_pass, customer_contact, customer_city, customer_country) 
                 VALUES (?, ?, ?, ?, ?, 'Ghana')";
        $stmt1 = $conn->prepare($cSql);
        $stmt1->bind_param("sssss", $owner_name, $owner_email, $password, $owner_phone, $city);
        
        if (!$stmt1->execute()) {
            throw new Exception("Failed to create owner account.");
        }
        $customer_id = $stmt1->insert_id;

        // B. Insert into Vendors Table
        $vSql = "INSERT INTO vendors (
                    customer_id, business_name, business_type, cuisine_type, business_description, price_range,
                    business_address, business_city, business_phone, business_email, website,
                    seating_capacity, operating_days, opening_time, closing_time, menu_file,
                    momo_provider, momo_number, business_reg_no, tin, verified, admin_privilege
                 ) VALUES (
                    ?, ?, ?, ?, ?, ?, 
                    ?, ?, ?, ?, ?, 
                    ?, ?, ?, ?, ?, 
                    ?, ?, ?, ?, 0, 1
                 )";
        
        $stmt2 = $conn->prepare($vSql);
        $stmt2->bind_param(
            "issssssssssissssssss", 
            $customer_id, $bus_name, $bus_type, $cuisine, $desc, $price,
            $address, $city, $bus_phone, $bus_email, $website,
            $capacity, $days, $open_time, $close_time, $menuPath,
            $momo_prov, $momo_num, $reg_no, $tin
        );

        if (!$stmt2->execute()) {
            throw new Exception("Failed to create vendor profile: " . $conn->error);
        }

        $conn->commit();
        echo json_encode(['status' => 'success']);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>