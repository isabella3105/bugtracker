<?php
/**
 * BugTracker - Update Ticket Handler
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
    header('Location: ../pages/dashboard.php');
    exit();
}

// Get form data
$ticket_id = $_POST['ticket_id'] ?? '';
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$category_id = $_POST['category_id'] ?? '';
$priority = $_POST['priority'] ?? '';
$status = $_POST['status'] ?? 0;
$assigned_to = $_POST['assigned_to'] ?? null;

// Validate required fields
if (empty($ticket_id) || empty($title) || empty($description) || empty($category_id) || $priority === '') {
    header('Location: ../pages/ticket-form.php?id=' . $ticket_id . '&error=empty');
    exit();
}

// Handle assigned_to
if (empty($assigned_to)) {
    $assigned_to = null;
}

// Update resolved_at if status is closed
$resolved_at = null;
if ($status == 2) {
    $resolved_at = date('Y-m-d H:i:s');
}

try {
    // Get current ticket to check if status changed
    $check_stmt = $pdo->prepare("SELECT status FROM tickets WHERE id_ticket = ?");
    $check_stmt->execute([$ticket_id]);
    $current = $check_stmt->fetch();
    
    // Only update resolved_at if status changed to closed
    if ($current && $status == 2 && $current['status'] != 2) {
        $resolved_at = date('Y-m-d H:i:s');
    } elseif ($status != 2) {
        $resolved_at = null;
    }
    
    $stmt = $pdo->prepare("
        UPDATE tickets 
        SET title = ?, description = ?, category_id = ?, priority = ?, status = ?, assigned_to = ?, resolved_at = ?
        WHERE id_ticket = ?
    ");
    
    if ($stmt->execute([$title, $description, $category_id, $priority, $status, $assigned_to, $resolved_at, $ticket_id])) {
        header('Location: ../pages/dashboard.php?success=updated');
        exit();
    } else {
        header('Location: ../pages/ticket-form.php?id=' . $ticket_id . '&error=failed');
        exit();
    }
} catch (PDOException $e) {
    header('Location: ../pages/ticket-form.php?id=' . $ticket_id . '&error=failed');
    exit();
}
?>