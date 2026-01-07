<?php
/**
 * BugTracker - Signup Page
 */

session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

// Check for error or success messages
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - BugTracker</title>
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
                
                <h2>Start tracking bugs today</h2>
                <p>Join thousands of teams already using BugTracker to ship better software.</p>
            </div>
            
            <div class="right-side">
                <div class="form-header">
                    <h1>Create Account</h1>
                    <p>Get started with your free account</p>
                </div>
                
                <?php if ($error == 'empty'): ?>
                    <div class="error">All fields are required</div>
                <?php elseif ($error == 'password_mismatch'): ?>
                    <div class="error">Passwords do not match</div>
                <?php elseif ($error == 'email_exists'): ?>
                    <div class="error">This email is already registered</div>
                <?php elseif ($error == 'failed'): ?>
                    <div class="error">Registration failed. Please try again.</div>
                <?php endif; ?>
                
                <form action="../actions/register.php" method="POST">
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input 
                            type="text"
                            name="name" 
                            class="form-input" 
                            placeholder="John Doe"
                            required
                        >
                    </div>
                    
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
                            placeholder="Create a strong password"
                            required
                        >
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input 
                            type="password"
                            name="confirm_password" 
                            class="form-input" 
                            placeholder="Confirm your password"
                            required
                        >
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Create Account</button>
                </form>
                
                <div class="auth-link">
                    Already have an account? <a href="login.php">Sign in</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>