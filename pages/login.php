<?php
/**
 * BugTracker - Login Page
 */

session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

// Check for error messages
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BugTracker</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <div class="left-side">
                <div class="logo">
                    <div class="bug-icon">🐛</div>
                    <span class="logo-text">BugTracker</span>
                </div>
                
                <h2>Track bugs.<br>Ship faster.</h2>
                <p>Simple and powerful bug tracking for modern development teams.</p>
            </div>
            
            <div class="right-side">
                <div class="form-header">
                    <h1>Welcome back!</h1>
                    <p>Sign in to your account to continue</p>
                </div>
                
                <?php if ($error == 'invalid'): ?>
                    <div class="error">Invalid email or password</div>
                <?php endif; ?>
                
                <form action="../actions/authenticate.php" method="POST">
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input 
                            type="email" 
                            name="email"
                            class="form-input" 
                            placeholder="your.email@example.com"
                            required
                        >
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input 
                            type="password"
                            name="password" 
                            class="form-input" 
                            placeholder="Enter your password"
                            required
                        >
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Sign In</button>
                </form>
                
                <div class="auth-link">
                    Don't have an account? <a href="signup.php">Create one now</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>