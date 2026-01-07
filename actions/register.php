<?php
/**
 * BugTracker - Registration Handler
 * Process user signup
 */

session_start();
require_once '../config/database.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/signup.php');
    exit();
}

// Get form data
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validate data
if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
    header('Location: ../pages/signup.php?error=empty');
    exit();
}

// Check if passwords match
if ($password !== $confirm_password) {
    header('Location: ../pages/signup.php?error=password_mismatch');
    exit();
}

// Check if email already exists
try {
    $stmt = $pdo->prepare("SELECT id_user FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        header('Location: ../pages/signup.php?error=email_exists');
        exit();
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    
    if ($stmt->execute([$name, $email, $hashed_password])) {
        // Registration successful, redirect to login
        header('Location: ../pages/login.php?success=registered');
        exit();
    } else {
        header('Location: ../pages/signup.php?error=failed');
        exit();
    }
    
} catch (PDOException $e) {
    header('Location: ../pages/signup.php?error=failed');
    exit();
}
?>