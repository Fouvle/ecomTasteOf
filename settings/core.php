<?php
// core.php - Core functions for session management

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && 
           isset($_SESSION['logged_in']) && 
           $_SESSION['logged_in'] === true;
}

/**
 * Redirect if not logged in
 */
function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header("Location: ../login/login.php");
        exit();
    }
}

/**
 * Redirect if already logged in
 */
function redirectIfLoggedIn() {
    if (isLoggedIn()) {
        if (isset($_SESSION['role']) && $_SESSION['role'] == 1) {
            header("Location: ../admin/admin_dashboard.php");
        } else {
            header("Location: ../views/customer_view.php");
        }
        exit();
    }
}

/**
 * Get current user ID
 */
function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current user role
 */
function getUserRole() {
    return $_SESSION['role'] ?? null;
}

/**
 * Check if user is admin
 */
function isAdmin() {
    return getUserRole() == 1;
}

/**
 * Check if user is customer
 */
function isCustomer() {
    return getUserRole() == 2; // Assuming 2 is customer role
}
?>