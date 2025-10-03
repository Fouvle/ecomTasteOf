<?php
// Settings/core.php
session_start();

// For header redirection
ob_start();

/**
 * Function to check if a user is logged in
 * @return bool
 */
function isLoggedIn()
{
    return isset($_SESSION['id']);
}

/**
 * Function to get the logged-in user ID
 * @return mixed|null
 */
function getUserId()
{
    return isset($_SESSION['id']) ? $_SESSION['id'] : null;
}

/**
 * Function to check if user has admin privileges
 * @return bool
 */
function isAdmin()
{
    return (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
}

/**
 * Enforce login requirement:
 * Call this at the top of protected pages
 */
function requireLogin()
{
    if (!isLoggedIn()) {
        header("Location: ../login/login.php");
        exit;
    }
}

/**
 * Enforce admin requirement:
 * Call this at the top of admin-only pages
 */
function requireAdmin()
{
    if (!isAdmin()) {
        // Redirect non-admins to regular dashboard
        header("Location: ..views/customer_view.php");
        exit;
    }
}
?>
