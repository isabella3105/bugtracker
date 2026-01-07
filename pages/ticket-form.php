<?php
/**
 * BugTracker - Ticket Form
 * Create or edit a ticket
 */

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../config/database.php';

// Check if we're editing
$ticket_id = $_GET['id'] ?? null;
$ticket = null;

if ($ticket_id) {
    // Get ticket data
    $stmt = $pdo->prepare("SELECT * FROM tickets WHERE id_ticket = ?");
    $stmt->execute([$ticket_id]);
    $ticket = $stmt->fetch();
    
    if (!$ticket) {
        header('Location: dashboard.php');
        exit();
    }
}

// Get categories
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

// Get users for assignment
$users = $pdo->query("SELECT id_user, name FROM users")->fetchAll();

// Get error message
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $ticket ? 'Edit' : 'Create'; ?> Ticket - BugTracker</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <nav class="navbar">
            <div class="logo">
                <div class="bug-icon">🐛</div>
                <span class="logo-text">BugTracker</span>
            </div>
            <a href="dashboard.php" class="logout-btn" style="text-decoration: none;">
                <span>← Back to Dashboard</span>
            </a>
        </nav>
        
        <div class="card" style="max-width: 900px; margin: 0 auto;">
            <div style="margin-bottom: 32px; padding-bottom: 24px; border-bottom: 2px solid #f0f0f0;">
                <h1 style="font-size: 28px; color: #333333; margin-bottom: 8px;">
                    <?php echo $ticket ? 'Edit Ticket' : 'Create New Ticket'; ?>
                </h1>
                <p style="color: #6c757d; font-size: 15px;">
                    <?php echo $ticket ? 'Update the ticket details below' : 'Fill in the details below to report a new bug'; ?>
                </p>
            </div>
            
            <?php if ($error === 'empty'): ?>
                <div class="error">All required fields must be filled</div>
            <?php elseif ($error === 'failed'): ?>
                <div class="error">Failed to save ticket. Please try again.</div>
            <?php endif; ?>
            
            <form action="../actions/<?php echo $ticket ? 'update-ticket.php' : 'create-ticket.php'; ?>" method="POST">
                <?php if ($ticket): ?>
                    <input type="hidden" name="ticket_id" value="<?php echo $ticket['id_ticket']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label class="form-label">Title <span style="color: #fa5252;">*</span></label>
                    <input 
                        type="text" 
                        name="title"
                        class="form-input" 
                        placeholder="Brief description of the bug"
                        value="<?php echo $ticket ? htmlspecialchars($ticket['title']) : ''; ?>"
                        required
                    >
                </div>
                
                <div class="form-group">
                    <label class="form-label">Description <span style="color: #fa5252;">*</span></label>
                    <textarea 
                        name="description"
                        class="form-textarea" 
                        placeholder="Detailed description of the bug, including steps to reproduce..."
                        required
                    ><?php echo $ticket ? htmlspecialchars($ticket['description']) : ''; ?></textarea>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label class="form-label">Category <span style="color: #fa5252;">*</span></label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select a category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id_category']; ?>"
                                    <?php echo ($ticket && $ticket['category_id'] == $category['id_category']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['title']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Priority <span style="color: #fa5252;">*</span></label>
                        <select name="priority" class="form-select" required>
                            <option value="0" <?php echo ($ticket && $ticket['priority'] == 0) ? 'selected' : ''; ?>>Low</option>
                            <option value="1" <?php echo (!$ticket || $ticket['priority'] == 1) ? 'selected' : ''; ?>>Standard</option>
                            <option value="2" <?php echo ($ticket && $ticket['priority'] == 2) ? 'selected' : ''; ?>>High</option>
                        </select>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label class="form-label">Status <span style="color: #fa5252;">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="0" <?php echo (!$ticket || $ticket['status'] == 0) ? 'selected' : ''; ?>>Open</option>
                            <option value="1" <?php echo ($ticket && $ticket['status'] == 1) ? 'selected' : ''; ?>>In Progress</option>
                            <option value="2" <?php echo ($ticket && $ticket['status'] == 2) ? 'selected' : ''; ?>>Closed</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Assign To</label>
                        <select name="assigned_to" class="form-select">
                            <option value="">Not assigned</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?php echo $user['id_user']; ?>"
                                    <?php echo ($ticket && $ticket['assigned_to'] == $user['id_user']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($user['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div style="display: flex; gap: 12px; margin-top: 32px; padding-top: 24px; border-top: 2px solid #f0f0f0;">
                    <a href="dashboard.php" class="btn" style="background: #f8f9fa; color: #6c757d; border: 2px solid #e9ecef; text-decoration: none; text-align: center;">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <?php echo $ticket ? 'Update Ticket' : 'Create Ticket'; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>