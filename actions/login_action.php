<?php
session_start();
require_once "../settings/connection.php";

if (isset($_POST['login_btn'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        header("Location: ../login/login.php?error=All fields are required");
        exit();
    }

    // 1. Fetch Customer from DB
    $sql = "SELECT * FROM customer WHERE customer_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // 2. Verify Password
        if (password_verify($password, $row['customer_pass'])) {
            
            // Set Basic Session Info
            $_SESSION['customer_id'] = $row['customer_id'];
            $_SESSION['customer_name'] = $row['customer_name'];
            $_SESSION['customer_email'] = $row['customer_email'];
            $_SESSION['role'] = 'customer'; // Default role

            // 3. Check if User is ALSO a Vendor
            $vSql = "SELECT vendor_id, admin_privilege FROM vendors WHERE customer_id = ?";
            $vStmt = $conn->prepare($vSql);
            $vStmt->bind_param("i", $row['customer_id']);
            $vStmt->execute();
            $vResult = $vStmt->get_result();

            if ($vResult->num_rows > 0) {
                $vRow = $vResult->fetch_assoc();
                $_SESSION['role'] = 'vendor'; // Update role
                $_SESSION['vendor_id'] = $vRow['vendor_id'];
                
                // Redirect to Vendor Dashboard
                header("Location: ../admin/vendor_dashboard.php");
                exit();
            }

            // Redirect Regular Customer to Homepage
            header("Location: ../index.php");
            exit();

        } else {
            header("Location: ../login/login.php?error=Incorrect password");
            exit();
        }
    } else {
        header("Location: ../login/login.php?error=User not found");
        exit();
    }
} else {
    header("Location: ../login/login.php");
    exit();
}
?>