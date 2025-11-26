<?php
session_start();
require_once "../settings/connection.php";

if (isset($_POST['vendor_login_btn'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // 1. Find the Vendor via the Customer Table (since auth details are stored there)
    // We join the tables to ensure this customer actually has a vendor profile
    $sql = "SELECT c.customer_id, c.customer_name, c.customer_email, c.customer_pass, v.vendor_id 
            FROM customer c
            JOIN vendors v ON c.customer_id = v.customer_id
            WHERE c.customer_email = ?";
            
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // 2. Verify Password
        if (password_verify($password, $row['customer_pass'])) {
            // Set Vendor Session Variables
            $_SESSION['customer_id'] = $row['customer_id'];
            $_SESSION['customer_name'] = $row['customer_name'];
            $_SESSION['vendor_id'] = $row['vendor_id']; // Specific to vendors
            $_SESSION['role'] = 'vendor'; 

            // Redirect to Vendor Dashboard
            header("Location: ../admin/vendor_dashboard.php");
            exit();
        } else {
            header("Location: ../login/vendor_login.php?error=Incorrect password");
            exit();
        }
    } else {
        // No vendor found with this email (or regular customer trying to use vendor portal)
        header("Location: ../login/vendor_login.php?error=Vendor account not found");
        exit();
    }
} else {
    header("Location: ../login/vendor_login.php");
    exit();
}
?>