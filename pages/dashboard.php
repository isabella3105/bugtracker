<?php
/**
 * BugTracker - Dashboard
 * Main page showing all tickets
 */

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../config/database.php';

// Get filter from URL
$filter = $_GET['filter'] ?? 'all';
$user_id = $_SESSION['user_id'];

// Base query
$query = "SELECT t.*, c.title as category_name, 
          u1.name as creator_name, u2.name as assigned_name
          FROM tickets t
          LEFT JOIN categories c ON t.category_id = c.id_category
          LEFT JOIN users u1 ON t.created_by = u1.id_user
          LEFT JOIN users u2 ON t.assigned_to = u2.id_user";

// Apply filter
if ($filter === 'my') {
    $query .= " WHERE t.assigned_to = :user_id";
} elseif ($filter === 'frontend') {
    $query .= " WHERE c.title = 'Front-end'";
} elseif ($filter === 'backend') {
    $query .= " WHERE c.title = 'Back-end'";
} elseif ($filter === 'infrastructure') {
    $query .= " WHERE c.title = 'Infrastructure'";
}

$query .= " ORDER BY t.created_at DESC";

$stmt = $pdo->prepare($query);
if ($filter === 'my') {
    $stmt->bindParam(':user_id', $user_id);
}
$stmt->execute();
$tickets = $stmt->fetchAll();

// Get statistics
$stats_query = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as open,
    SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as in_progress,
    SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as closed
    FROM tickets";
$stats = $pdo->query($stats_query)->fetch();

// Get success message
$success = $_GET['success'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - BugTracker</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <nav class="navbar">
            <div class="logo">
                <div class="bug-icon">🐛</div>
                <span class="logo-text">BugTracker</span>
            </div>
            <div class="user-section">
                <div class="user-name">
                    <span>👤</span>
                    <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                </div>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </nav>
        
        <?php if ($success === 'created'): ?>
            <div class="success">Ticket created successfully!</div>
        <?php elseif ($success === 'updated'): ?>
            <div class="success">Ticket updated successfully!</div>
        <?php elseif ($success === 'deleted'): ?>
            <div class="success">Ticket deleted successfully!</div>
        <?php endif; ?>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Total Tickets</span>
                    <div class="stat-icon" style="background: #e7f5ff; color: #1971c2;">📊</div>
                </div>
                <div class="stat-value"><?php echo $stats['total']; ?></div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Open</span>
                    <div class="stat-icon" style="background: #fff4e6; color: #fd7e14;">🔓</div>
                </div>
                <div class="stat-value"><?php echo $stats['open']; ?></div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">In Progress</span>
                    <div class="stat-icon" style="background: #e7f5ff; color: #1c7ed6;">⚡</div>
                </div>
                <div class="stat-value"><?php echo $stats['in_progress']; ?></div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Closed</span>
                    <div class="stat-icon" style="background: #d3f9d8; color: #2f9e44;">✅</div>
                </div>
                <div class="stat-value"><?php echo $stats['closed']; ?></div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">All Tickets</h2>
                <div class="actions">
                    <select class="filter-select" onchange="window.location.href='dashboard.php?filter=' + this.value">
                        <option value="all" <?php echo $filter === 'all' ? 'selected' : ''; ?>>All Tickets</option>
                        <option value="my" <?php echo $filter === 'my' ? 'selected' : ''; ?>>My Tickets</option>
                        <option value="frontend" <?php echo $filter === 'frontend' ? 'selected' : ''; ?>>Front-end</option>
                        <option value="backend" <?php echo $filter === 'backend' ? 'selected' : ''; ?>>Back-end</option>
                        <option value="infrastructure" <?php echo $filter === 'infrastructure' ? 'selected' : ''; ?>>Infrastructure</option>
                    </select>
                    <a href="ticket-form.php" class="btn-new">
                        <span>+</span>
                        <span>New Ticket</span>
                    </a>
                </div>
            </div>
            
            <?php if (empty($tickets)): ?>
                <p style="text-align: center; padding: 40px; color: #6c757d;">No tickets found</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Created</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Assigned</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tickets as $ticket): ?>
                            <tr>
                                <td><span class="ticket-id">#<?php echo str_pad($ticket['id_ticket'], 3, '0', STR_PAD_LEFT); ?></span></td>
                                <td><?php echo htmlspecialchars($ticket['title']); ?></td>
                                <td>
                                    <?php 
                                    $badge_class = 'badge-frontend';
                                    if ($ticket['category_name'] === 'Back-end') $badge_class = 'badge-backend';
                                    if ($ticket['category_name'] === 'Infrastructure') $badge_class = 'badge-infrastructure';
                                    ?>
                                    <span class="badge <?php echo $badge_class; ?>">
                                        <?php echo htmlspecialchars($ticket['category_name']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($ticket['created_at'])); ?></td>
                                <td>
                                    <form method="POST" action="../actions/update-status.php" style="display: inline;">
                                        <input type="hidden" name="ticket_id" value="<?php echo $ticket['id_ticket']; ?>">
                                        <select name="status" class="status-select" onchange="this.form.submit()">
                                            <option value="0" <?php echo $ticket['status'] == 0 ? 'selected' : ''; ?>>Open</option>
                                            <option value="1" <?php echo $ticket['status'] == 1 ? 'selected' : ''; ?>>In Progress</option>
                                            <option value="2" <?php echo $ticket['status'] == 2 ? 'selected' : ''; ?>>Closed</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <div class="priority">
                                        <span class="priority-dot" style="background: <?php 
                                            echo $ticket['priority'] == 2 ? '#fa5252' : ($ticket['priority'] == 1 ? '#fd7e14' : '#51cf66');
                                        ?>;"></span>
                                        <span><?php 
                                            echo $ticket['priority'] == 2 ? 'High' : ($ticket['priority'] == 1 ? 'Standard' : 'Low');
                                        ?></span>
                                    </div>
                                </td>
                                <td><?php echo $ticket['assigned_name'] ?? '-'; ?></td>
                                <td>
                                    <div class="action-btns">
                                        <a href="ticket-form.php?id=<?php echo $ticket['id_ticket']; ?>" class="btn-icon btn-edit">✏️</a>
                                        <form method="POST" action="../actions/delete-ticket.php" style="display: inline;" 
                                              onsubmit="return confirm('Are you sure you want to delete this ticket?');">
                                            <input type="hidden" name="ticket_id" value="<?php echo $ticket['id_ticket']; ?>">
                                            <button type="submit" class="btn-icon btn-delete">🗑️</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>