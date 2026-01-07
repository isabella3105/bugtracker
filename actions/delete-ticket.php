<?php
/**
 * BugTracker - Delete Ticket Handler
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

// Get ticket ID
$ticket_id = $_POST['ticket_id'] ?? '';

if (empty($ticket_id)) {
    header('Location: ../pages/dashboard.php');
    exit();
}

try {
    $stmt = $pdo->prepare("DELETE FROM tickets WHERE id_ticket = ?");
    
    if ($stmt->execute([$ticket_id])) {
        header('Location: ../pages/dashboard.php?success=deleted');
        exit();
    } else {
        header('Location: ../pages/dashboard.php?error=delete_failed');
        exit();
    }
} catch (PDOException $e) {
    header('Location: ../pages/dashboard.php?error=delete_failed');
    exit();
}
?>