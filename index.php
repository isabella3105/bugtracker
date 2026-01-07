<?php
/**
 * BugTracker - Home Page
 * Redirects to login or dashboard based on session
 */

session_start();

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    // User is logged in, redirect to dashboard
    header('Location: pages/dashboard.php');
    exit();
} else {
    // User is not logged in, redirect to login
    header('Location: pages/login.php');
    exit();
}
?>