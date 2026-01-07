<?php
/**
 * BugTracker - Update Status Handler
 * Quick status update from dashboard
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
$status = $_POST['status'] ?? '';

if (empty($ticket_id) || $status === '') {
    header('Location: ../pages/dashboard.php');
    exit();
}

// Update resolved_at if status is closed
$resolved_at = null;
if ($status == 2) {
    $resolved_at = date('Y-m-d H:i:s');
}

try {
    $stmt = $pdo->prepare("UPDATE tickets SET status = ?, resolved_at = ? WHERE id_ticket = ?");
    
    if ($stmt->execute([$status, $resolved_at, $ticket_id])) {
        header('Location: ../pages/dashboard.php?success=status_updated');
        exit();
    } else {
        header('Location: ../pages/dashboard.php?error=update_failed');
        exit();
    }
} catch (PDOException $e) {
    header('Location: ../pages/dashboard.php?error=update_failed');
    exit();
}
?>