<?php
/**
 * BugTracker - Create Ticket Handler
 */

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php');
    exit();
}

require_once '../config/database.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/ticket-form.php');
    exit();
}

// Get form data
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$category_id = $_POST['category_id'] ?? '';
$priority = $_POST['priority'] ?? '';
$status = $_POST['status'] ?? 0;
$assigned_to = $_POST['assigned_to'] ?? null;
$created_by = $_SESSION['user_id'];

// Validate required fields
if (empty($title) || empty($description) || empty($category_id) || $priority === '') {
    header('Location: ../pages/ticket-form.php?error=empty');
    exit();
}

// Handle assigned_to (convert empty string to NULL)
if (empty($assigned_to)) {
    $assigned_to = null;
}

// Set resolved_at if status is closed
$resolved_at = ($status == 2) ? date('Y-m-d H:i:s') : null;

try {
    $stmt = $pdo->prepare("
        INSERT INTO tickets (title, description, category_id, priority, status, created_by, assigned_to, resolved_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    if ($stmt->execute([$title, $description, $category_id, $priority, $status, $created_by, $assigned_to, $resolved_at])) {
        header('Location: ../pages/dashboard.php?success=created');
        exit();
    } else {
        header('Location: ../pages/ticket-form.php?error=failed');
        exit();
    }
} catch (PDOException $e) {
    header('Location: ../pages/ticket-form.php?error=failed');
    exit();
}
?>