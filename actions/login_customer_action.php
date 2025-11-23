<?php
// actions/login_action.php
session_start();
require_once "../settings/connection.php";

if (isset($_POST['login_btn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 1. Check Customer Table
    $sql = "SELECT * FROM customer WHERE customer_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify Password (assuming hashed)
        if (password_verify($password, $row['customer_pass'])) {
            $_SESSION['customer_id'] = $row['customer_id'];
            $_SESSION['customer_name'] = $row['customer_name'];
            $_SESSION['role'] = 'customer'; 

            // 2. Check if also a Vendor (As per sketch logic)
            $vSql = "SELECT * FROM vendors WHERE customer_id = ?";
            $vStmt = $conn->prepare($vSql);
            $vStmt->bind_param("i", $row['customer_id']);
            $vStmt->execute();
            $vRes = $vStmt->get_result();

            if ($vRes->num_rows > 0) {
                $vRow = $vRes->fetch_assoc();
                $_SESSION['role'] = 'vendor';
                $_SESSION['vendor_id'] = $vRow['vendor_id'];
                header("Location: ../admin/vendor_dashboard.php");
            } else {
                header("Location: ../index.php");
            }
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }
}
?>