<?php
/**
 * core.php
 * Unified core functions for session management, user authentication, and access control.
 */

if (!defined('PROJECT_ROOT')) {
    define('PROJECT_ROOT', dirname(__DIR__));
}

// ✅ Start session only once
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Enable output buffering (prevents header errors)
ob_start();

/* ================================================================
   SESSION + AUTHENTICATION HELPERS
   ================================================================ */

/**
 * Check if user is logged in
 * Returns true if session has a valid customer ID and login flag
 */
function isLoggedIn() {
    return isset($_SESSION['customer_id']) &&
           isset($_SESSION['logged_in']) &&
           $_SESSION['logged_in'] === true;
}

/**
 * Get the logged-in user's ID
 * @return int|null
 */
function getUserId() {
    return $_SESSION['customer_id'] ?? null;
}

/**
 * Get the logged-in user's name
 * @return string|null
 */
function getUserName() {
    return $_SESSION['customer_name'] ?? null;
}

/**
 * Get the logged-in user's role
 * @return int|null
 */
function getUserRole() {
    return $_SESSION['user_role'] ?? null;
}

/* ================================================================
   ROLE CHECKS
   ================================================================ */

/**
 * Check if current user is admin
 * In your database, 1 = Admin, 2 = Regular Customer
 */
function isAdmin() {
    return (getUserRole() == 1);
}

/**
 * Check if current user is a normal customer
 */
function isCustomer() {
    return (getUserRole() == 2);
}

/* ================================================================
   REDIRECTION + ACCESS CONTROL
   ================================================================ */

/**
 * Redirect if user is not logged in
 * Typically used on protected pages
 */
function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header("Location: ../login/login.php");
        exit();
    }
}

/**
 * Redirect if user is already logged in
 * Used on login or register pages
 */
function redirectIfLoggedIn() {
    if (isLoggedIn()) {
        if (isAdmin()) {
            header("Location: ../admin/admin_dashboard.php");
        } else {
            header("Location: ../views/customer_view.php");
        }
        exit();
    }
}

/**
 * Restrict access to admin-only pages
 * Redirects non-admin users to the homepage
 */
function requireAdmin() {
    if (!isAdmin()) {
        header("Location: ../index.php");
        exit();
    }
}

/**
 * Restrict access to customer-only pages
 * Redirects admins away from customer areas
 */
function requireCustomer() {
    if (!isCustomer()) {
        header("Location: ../index.php");
        exit();
    }
}
?>
