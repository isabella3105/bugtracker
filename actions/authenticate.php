<?php
/**
 * BugTracker - Authentication Handler
 * Process user login
 */

session_start();
require_once '../config/database.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/login.php');
    exit();
}

// Get form data
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Validate data
if (empty($email) || empty($password)) {
    header('Location: ../pages/login.php?error=empty');
    exit();
}

// Check credentials
try {
    $stmt = $pdo->prepare("SELECT id_user, name, email, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        // Login successful
        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        
        header('Location: ../pages/dashboard.php');
        exit();
    } else {
        // Invalid credentials
        header('Location: ../pages/login.php?error=invalid');
        exit();
    }
    
} catch (PDOException $e) {
    header('Location: ../pages/login.php?error=invalid');
    exit();
}
?>