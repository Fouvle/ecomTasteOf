<?php
session_start();
require_once "../settings/db_cred.php";

if (isset($_POST['register_btn'])) {
    // 1. Sanitize Inputs
    $name = htmlspecialchars(trim($_POST['customer_name']));
    $email = htmlspecialchars(trim($_POST['customer_email']));
    $pass = password_hash($_POST['customer_pass'], PASSWORD_DEFAULT); // Secure Hash
    $country = htmlspecialchars(trim($_POST['customer_country']));
    $city = htmlspecialchars(trim($_POST['customer_city']));
    $contact = htmlspecialchars(trim($_POST['customer_contact']));
    
    // 2. Handle Image Upload
    $imagePath = NULL;
    if (isset($_FILES['customer_image']) && $_FILES['customer_image']['error'] === 0) {
        $targetDir = "../uploads/users/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true); // Create dir if missing
        
        $fileName = time() . "_" . basename($_FILES['customer_image']['name']);
        $targetFile = $targetDir . $fileName;
        
        // Basic File Type Check
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['customer_image']['tmp_name'], $targetFile)) {
                $imagePath = "uploads/users/" . $fileName; // Store relative path for DB
            }
        }
    }

    // 3. Check for Duplicate Email
    $checkSql = "SELECT customer_id FROM customer WHERE customer_email = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        header("Location: ../login/register.php?error=Email is already registered");
        exit();
    }

    // 4. Insert Customer
    $sql = "INSERT INTO customer (customer_name, customer_email, customer_pass, customer_country, customer_city, customer_contact, customer_image) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $name, $email, $pass, $country, $city, $contact, $imagePath);
    
    if ($stmt->execute()) {
        $new_customer_id = $stmt->insert_id;

        // 5. Handle Vendor Registration (If Checked)
        if (isset($_POST['is_vendor']) && $_POST['is_vendor'] == '1') {
            $bus_name = htmlspecialchars(trim($_POST['business_name']));
            $bus_addr = htmlspecialchars(trim($_POST['business_address']));
            $bus_desc = htmlspecialchars(trim($_POST['business_description']));

            // Insert into Vendors Table (Defaulting verified=0, admin_privilege=1)
            $vSql = "INSERT INTO vendors (customer_id, business_name, business_address, business_description, verified, admin_privilege) 
                     VALUES (?, ?, ?, ?, 0, 1)";
            $vStmt = $conn->prepare($vSql);
            $vStmt->bind_param("isss", $new_customer_id, $bus_name, $bus_addr, $bus_desc);
            $vStmt->execute();
        }

        // Success: Redirect to Login
        header("Location: ../login/login.php?success=Account created successfully! Please login.");
        exit();
    } else {
        header("Location: ../login/register.php?error=Database error occurred");
        exit();
    }
} else {
    header("Location: ../login/register.php");
    exit();
}
?>