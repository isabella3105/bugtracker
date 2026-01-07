-- BugTracker Database
-- Creation script for MySQL

-- Create database
CREATE DATABASE IF NOT EXISTS bugtracker CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bugtracker;

-- Drop tables if they exist
DROP TABLE IF EXISTS tickets;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

-- Table: users
CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: categories
CREATE TABLE categories (
    id_category INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: tickets
CREATE TABLE tickets (
    id_ticket INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    resolved_at TIMESTAMP NULL DEFAULT NULL,
    priority TINYINT NOT NULL DEFAULT 1,
    status TINYINT NOT NULL DEFAULT 0,
    created_by INT NOT NULL,
    assigned_to INT NULL DEFAULT NULL,
    category_id INT NOT NULL,
    
    FOREIGN KEY (created_by) REFERENCES users(id_user) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES users(id_user) ON DELETE SET NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id_category) ON DELETE RESTRICT,
    
    INDEX idx_status (status),
    INDEX idx_priority (priority),
    INDEX idx_created_by (created_by),
    INDEX idx_assigned_to (assigned_to),
    INDEX idx_category (category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default categories
INSERT INTO categories (title) VALUES 
    ('Front-end'),
    ('Back-end'),
    ('Infrastructure');

-- Insert default admin user
-- Password: 123456 (hashed with password_hash in PHP)
INSERT INTO users (name, email, password) VALUES 
    ('Admin User', 'admin@bugtracker.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Insert sample tickets (10 tickets with different statuses)
INSERT INTO tickets (title, description, priority, status, created_by, assigned_to, category_id, resolved_at) VALUES 
    ('Login button not working on mobile', 'The login button is not responsive on mobile devices. When users try to click it on iPhone, nothing happens.', 2, 0, 1, NULL, 1, NULL),
    
    ('Database connection timeout', 'API calls are timing out after 30 seconds. This happens on the production server but not locally.', 2, 1, 1, 1, 2, NULL),
    
    ('Server crashes on high load', 'Production server crashes when traffic exceeds 1000 concurrent users. Need to investigate memory usage.', 2, 1, 1, 1, 3, NULL),
    
    ('Navbar overlaps content', 'On tablet view (768px-1024px), the navigation bar covers the main content area.', 1, 0, 1, NULL, 1, NULL),
    
    ('Email validation not working', 'Users can register with invalid email formats like "test@test". Need proper regex validation.', 1, 2, 1, 1, 2, NOW()),
    
    ('Styling issue on Safari', 'CSS grid layout breaks on Safari browser versions 14 and below. Works fine on Chrome and Firefox.', 0, 0, 1, NULL, 1, NULL),
    
    ('API returns 500 error', 'POST request to /api/users endpoint returns internal server error. Stack trace shows database constraint violation.', 2, 2, 1, 1, 2, NOW()),
    
    ('Slow page load time', 'Homepage takes more than 5 seconds to load. Need to optimize images and reduce HTTP requests.', 1, 1, 1, 1, 1, NULL),
    
    ('SSL certificate expired', 'HTTPS certificate expired yesterday. Website showing security warning to users.', 2, 0, 1, NULL, 3, NULL),
    
    ('Form validation missing', 'Contact form allows empty submissions. All fields should be required with proper error messages.', 0, 2, 1, 1, 1, NOW());