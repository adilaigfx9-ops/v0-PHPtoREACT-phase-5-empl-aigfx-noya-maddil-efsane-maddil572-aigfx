-- =====================================================
-- Adil GFX Platform - Production Database Schema
-- =====================================================
-- Version: 2.0.0 (Production Ready - Consolidated)
-- Database: MySQL 5.7+ / MariaDB 10.2+
-- Created: October 21, 2025
-- Domain: adilcreator.com
-- Database: u720615217_adil_db
-- =====================================================
-- 
-- IMPORTANT: This is a consolidated schema combining:
--   ✓ Core tables (28 tables)
--   ✓ RBAC system (user, viewer, editor, admin roles)
--   ✓ Translation system (12 languages support)
--   ✓ API integrations & funnel testing
--   ✓ Complete gamification system
--   ✓ All production features
--
-- Total Tables: 42
-- Total Views: 5
-- Total Stored Procedures: 7
-- Total Triggers: 5
--
-- =====================================================


-- =====================================================
-- Adil GFX Platform - Complete Database Schema
-- =====================================================
-- Version: 1.0.0
-- Database: MySQL 5.7+ / MariaDB 10.2+
-- Purpose: Complete schema for PHP/MySQL backend on Hostinger
-- =====================================================

-- Database creation (run separately if needed)
-- CREATE DATABASE IF NOT EXISTS u720615217_adil_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE u720615217_adil_db;

-- =====================================================
-- USER MANAGEMENT TABLES
-- =====================================================

-- Users table: Core user authentication and profile data
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    avatar VARCHAR(500) DEFAULT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    verified BOOLEAN DEFAULT FALSE,
    last_login TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- User tokens: Gamification token system
CREATE TABLE IF NOT EXISTS user_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    balance INT DEFAULT 0,
    total_earned INT DEFAULT 0,
    total_spent INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Token history: Transaction log for tokens
CREATE TABLE IF NOT EXISTS token_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('earned', 'spent') NOT NULL,
    amount INT NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_type (type),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- User streaks: Login streak tracking with milestones
CREATE TABLE IF NOT EXISTS user_streaks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    current_streak INT DEFAULT 0,
    longest_streak INT DEFAULT 0,
    last_check_in DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_streak (user_id),
    INDEX idx_last_check_in (last_check_in)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Referrals: Viral referral system
CREATE TABLE IF NOT EXISTS referrals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    referrer_id INT NOT NULL,
    referral_code VARCHAR(50) UNIQUE NOT NULL,
    total_referred INT DEFAULT 0,
    successful_conversions INT DEFAULT 0,
    earnings INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (referrer_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_referral_code (referral_code),
    INDEX idx_referrer_id (referrer_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Referral tracking: Individual referral records
CREATE TABLE IF NOT EXISTS referral_tracking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    referrer_id INT NOT NULL,
    referred_user_id INT NOT NULL,
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    reward_amount INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (referrer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (referred_user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_referrer (referrer_id),
    INDEX idx_referred (referred_user_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- CONTENT MANAGEMENT TABLES
-- =====================================================

-- Blogs: Blog post content management
CREATE TABLE IF NOT EXISTS blogs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(500) NOT NULL,
    slug VARCHAR(500) UNIQUE NOT NULL,
    excerpt TEXT,
    content LONGTEXT,
    category VARCHAR(100),
    author_name VARCHAR(255) DEFAULT 'Adil',
    author_avatar VARCHAR(500) DEFAULT '/api/placeholder/80/80',
    author_bio TEXT DEFAULT 'YouTube Growth Specialist & Design Expert',
    featured_image VARCHAR(500),
    tags JSON,
    featured BOOLEAN DEFAULT FALSE,
    published BOOLEAN DEFAULT TRUE,
    views INT DEFAULT 0,
    likes INT DEFAULT 0,
    published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FULLTEXT KEY fulltext_search (title, excerpt, content),
    INDEX idx_slug (slug),
    INDEX idx_category (category),
    INDEX idx_featured (featured),
    INDEX idx_published (published),
    INDEX idx_published_at (published_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Portfolio: Project showcase
CREATE TABLE IF NOT EXISTS portfolio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(500) NOT NULL,
    slug VARCHAR(500) UNIQUE NOT NULL,
    category VARCHAR(100),
    description TEXT,
    long_description LONGTEXT,
    client VARCHAR(255),
    completion_date DATE,
    featured_image VARCHAR(500),
    images JSON,
    before_image VARCHAR(500),
    after_image VARCHAR(500),
    tags JSON,
    results JSON,
    featured BOOLEAN DEFAULT FALSE,
    published BOOLEAN DEFAULT TRUE,
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_category (category),
    INDEX idx_featured (featured),
    INDEX idx_published (published)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Services: Service offerings with pricing
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    icon VARCHAR(100),
    tagline VARCHAR(500),
    description TEXT,
    features JSON,
    pricing_tiers JSON,
    delivery_time VARCHAR(100),
    popular BOOLEAN DEFAULT FALSE,
    active BOOLEAN DEFAULT TRUE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_popular (popular),
    INDEX idx_active (active),
    INDEX idx_sort_order (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Testimonials: Client feedback and reviews
CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    role VARCHAR(255),
    company VARCHAR(255),
    content TEXT NOT NULL,
    rating INT DEFAULT 5 CHECK (rating >= 1 AND rating <= 5),
    avatar VARCHAR(500),
    project_type VARCHAR(100),
    verified BOOLEAN DEFAULT FALSE,
    published BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_verified (verified),
    INDEX idx_published (published),
    INDEX idx_rating (rating)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- DYNAMIC CMS TABLES
-- =====================================================

-- Settings: Global site configuration
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value LONGTEXT,
    setting_type ENUM('text', 'json', 'boolean', 'number', 'file') DEFAULT 'text',
    category VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_setting_key (setting_key),
    INDEX idx_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pages: Dynamic page management
CREATE TABLE IF NOT EXISTS pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    meta_title VARCHAR(255),
    meta_description TEXT,
    sections JSON,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    sort_order INT DEFAULT 0,
    show_in_nav BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    published_at TIMESTAMP NULL DEFAULT NULL,
    unpublished_at TIMESTAMP NULL DEFAULT NULL,
    INDEX idx_slug (slug),
    INDEX idx_status (status),
    INDEX idx_sort_order (sort_order),
    INDEX idx_show_in_nav (show_in_nav)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Carousel slides: Slider content management
CREATE TABLE IF NOT EXISTS carousel_slides (
    id INT AUTO_INCREMENT PRIMARY KEY,
    carousel_name VARCHAR(100) NOT NULL,
    title VARCHAR(255),
    subtitle VARCHAR(255),
    description TEXT,
    image_url VARCHAR(500),
    cta_text VARCHAR(100),
    cta_url VARCHAR(500),
    sort_order INT DEFAULT 0,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_carousel_name (carousel_name),
    INDEX idx_sort_order (sort_order),
    INDEX idx_active (active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Media uploads: File storage metadata
CREATE TABLE IF NOT EXISTS media_uploads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    file_size INT NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    alt_text VARCHAR(255),
    caption TEXT,
    uploaded_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_mime_type (mime_type),
    INDEX idx_uploaded_by (uploaded_by),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- NOTIFICATIONS & COMMUNICATIONS
-- =====================================================

-- Notifications: User notifications system
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    type ENUM('success', 'info', 'reward', 'promo', 'milestone', 'system') DEFAULT 'info',
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    icon VARCHAR(50),
    action_url VARCHAR(500),
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_type (type),
    INDEX idx_is_read (is_read),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Contact submissions: Form submissions
CREATE TABLE IF NOT EXISTS contact_submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    service VARCHAR(255),
    budget VARCHAR(100),
    timeline VARCHAR(100),
    message TEXT NOT NULL,
    status ENUM('new', 'in_progress', 'completed', 'spam') DEFAULT 'new',
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_status (status),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Newsletter subscribers: Email list management
CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    status ENUM('active', 'unsubscribed', 'bounced') DEFAULT 'active',
    source VARCHAR(100) DEFAULT 'website',
    ip_address VARCHAR(45),
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    unsubscribed_at TIMESTAMP NULL DEFAULT NULL,
    INDEX idx_email (email),
    INDEX idx_status (status),
    INDEX idx_subscribed (subscribed_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- ORDERS & TRANSACTIONS
-- =====================================================

-- Orders: Project orders tracking
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    service_id INT,
    package_name VARCHAR(255),
    status ENUM('pending', 'in_progress', 'review', 'completed', 'cancelled') DEFAULT 'pending',
    amount DECIMAL(10, 2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'USD',
    payment_method VARCHAR(50),
    payment_status ENUM('pending', 'paid', 'refunded', 'failed') DEFAULT 'pending',
    order_details JSON,
    notes TEXT,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expected_completion DATE,
    completed_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_service_id (service_id),
    INDEX idx_status (status),
    INDEX idx_payment_status (payment_status),
    INDEX idx_order_date (order_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Order revisions: Track revision requests
CREATE TABLE IF NOT EXISTS order_revisions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    revision_number INT NOT NULL,
    description TEXT,
    status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    INDEX idx_order_id (order_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- ACHIEVEMENTS & GAMIFICATION
-- =====================================================

-- Achievements: Achievement definitions
CREATE TABLE IF NOT EXISTS achievements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    icon VARCHAR(50),
    badge_image VARCHAR(500),
    criteria JSON,
    reward_tokens INT DEFAULT 0,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_active (active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- User achievements: User progress on achievements
CREATE TABLE IF NOT EXISTS user_achievements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    achievement_id INT NOT NULL,
    progress INT DEFAULT 0,
    target INT DEFAULT 100,
    unlocked BOOLEAN DEFAULT FALSE,
    unlocked_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (achievement_id) REFERENCES achievements(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_achievement (user_id, achievement_id),
    INDEX idx_user_id (user_id),
    INDEX idx_achievement_id (achievement_id),
    INDEX idx_unlocked (unlocked)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- SYSTEM & SECURITY TABLES
-- =====================================================

-- Rate limits: API rate limiting
CREATE TABLE IF NOT EXISTS rate_limits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    endpoint VARCHAR(255) NOT NULL,
    requests INT DEFAULT 1,
    window_start TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_rate_limit (ip_address, endpoint),
    INDEX idx_ip_address (ip_address),
    INDEX idx_endpoint (endpoint),
    INDEX idx_window_start (window_start)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Activity logs: System activity tracking
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    entity VARCHAR(50),
    entity_id INT,
    details JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_action (action),
    INDEX idx_entity (entity, entity_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sessions: User session management (optional)
CREATE TABLE IF NOT EXISTS user_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    session_token VARCHAR(255) UNIQUE NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_session_token (session_token),
    INDEX idx_expires_at (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- INITIAL DATA & DEFAULTS
-- =====================================================

-- Create default admin user (password: admin123)
INSERT IGNORE INTO users (id, email, password_hash, name, role, verified)
VALUES (
    1,
    'admin@adilgfx.com',
    '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5eBx5kRbMYO.W',
    'Adil (Admin)',
    'admin',
    TRUE
);

-- Initialize admin tokens
INSERT IGNORE INTO user_tokens (user_id, balance, total_earned)
VALUES (1, 1000, 1000);

-- Initialize admin streak
INSERT IGNORE INTO user_streaks (user_id, current_streak, longest_streak, last_check_in)
VALUES (1, 1, 1, CURDATE());

-- Create admin referral code
INSERT IGNORE INTO referrals (referrer_id, referral_code)
VALUES (1, 'ADMIN001');

-- Default settings
INSERT IGNORE INTO settings (setting_key, setting_value, setting_type, category, description) VALUES
('site_name', 'Adil GFX', 'text', 'branding', 'Website name'),
('primary_color', '#FF0000', 'text', 'branding', 'Primary brand color'),
('contact_email', 'hello@adilgfx.com', 'text', 'contact', 'Contact email address'),
('enable_referrals', 'true', 'boolean', 'features', 'Enable referral system'),
('enable_streaks', 'true', 'boolean', 'features', 'Enable login streak tracking'),
('enable_tokens', 'true', 'boolean', 'features', 'Enable token system');

-- Default achievements
INSERT IGNORE INTO achievements (id, name, description, icon, reward_tokens, active) VALUES
(1, 'Welcome Aboard', 'Complete your profile', 'User', 50, TRUE),
(2, 'First Order', 'Place your first order', 'ShoppingCart', 100, TRUE),
(3, 'Week Warrior', 'Login for 7 consecutive days', 'Flame', 200, TRUE),
(4, 'Referral Master', 'Refer 5 friends', 'Users', 500, TRUE),
(5, 'Token Millionaire', 'Earn 1000 tokens', 'Coins', 1000, TRUE);

-- =====================================================
-- VIEWS FOR COMMON QUERIES (Optional but recommended)
-- =====================================================

-- User dashboard summary view
CREATE OR REPLACE VIEW user_dashboard_summary AS
SELECT
    u.id,
    u.email,
    u.name,
    u.avatar,
    u.verified,
    ut.balance AS token_balance,
    ut.total_earned AS total_tokens_earned,
    us.current_streak,
    us.longest_streak,
    r.referral_code,
    r.total_referred,
    (SELECT COUNT(*) FROM orders WHERE user_id = u.id) AS total_orders,
    (SELECT COUNT(*) FROM user_achievements WHERE user_id = u.id AND unlocked = TRUE) AS achievements_unlocked
FROM users u
LEFT JOIN user_tokens ut ON u.id = ut.user_id
LEFT JOIN user_streaks us ON u.id = us.user_id
LEFT JOIN referrals r ON u.id = r.referrer_id;

-- =====================================================
-- STORED PROCEDURES (Optional)
-- =====================================================

-- Procedure to add tokens to user
DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS add_user_tokens(
    IN p_user_id INT,
    IN p_amount INT,
    IN p_description VARCHAR(255)
)
BEGIN
    UPDATE user_tokens
    SET balance = balance + p_amount,
        total_earned = total_earned + p_amount
    WHERE user_id = p_user_id;

    INSERT INTO token_history (user_id, type, amount, description)
    VALUES (p_user_id, 'earned', p_amount, p_description);
END$$
DELIMITER ;

-- Procedure to spend tokens
DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS spend_user_tokens(
    IN p_user_id INT,
    IN p_amount INT,
    IN p_description VARCHAR(255)
)
BEGIN
    DECLARE current_balance INT;

    SELECT balance INTO current_balance
    FROM user_tokens
    WHERE user_id = p_user_id;

    IF current_balance >= p_amount THEN
        UPDATE user_tokens
        SET balance = balance - p_amount,
            total_spent = total_spent + p_amount
        WHERE user_id = p_user_id;

        INSERT INTO token_history (user_id, type, amount, description)
        VALUES (p_user_id, 'spent', p_amount, p_description);
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Insufficient token balance';
    END IF;
END$$
DELIMITER ;

-- =====================================================
-- TRIGGERS (Optional but recommended)
-- =====================================================

-- Trigger to log user registration
DELIMITER $$
CREATE TRIGGER IF NOT EXISTS after_user_insert
AFTER INSERT ON users
FOR EACH ROW
BEGIN
    INSERT INTO activity_logs (user_id, action, entity, entity_id)
    VALUES (NEW.id, 'user_registered', 'users', NEW.id);
END$$
DELIMITER ;

-- Trigger to log order status changes
DELIMITER $$
CREATE TRIGGER IF NOT EXISTS after_order_status_update
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    IF NEW.status != OLD.status THEN
        INSERT INTO activity_logs (user_id, action, entity, entity_id, details)
        VALUES (
            NEW.user_id,
            'order_status_changed',
            'orders',
            NEW.id,
            JSON_OBJECT('old_status', OLD.status, 'new_status', NEW.status)
        );
    END IF;
END$$
DELIMITER ;

-- =====================================================
-- INDEXES FOR PERFORMANCE (Additional recommendations)
-- =====================================================

-- Composite indexes for common queries
CREATE INDEX IF NOT EXISTS idx_user_email_role ON users(email, role);
CREATE INDEX IF NOT EXISTS idx_blog_published_featured ON blogs(published, featured, published_at);
CREATE INDEX IF NOT EXISTS idx_portfolio_published_category ON portfolio(published, category);
CREATE INDEX IF NOT EXISTS idx_order_user_status ON orders(user_id, status, order_date);

-- =====================================================
-- DATABASE MAINTENANCE QUERIES
-- =====================================================

-- Clean up expired sessions (run periodically via cron)
-- DELETE FROM user_sessions WHERE expires_at < NOW();

-- Clean up old rate limit entries (run periodically via cron)
-- DELETE FROM rate_limits WHERE window_start < DATE_SUB(NOW(), INTERVAL 2 HOUR);

-- Clean up old activity logs (run periodically via cron)
-- DELETE FROM activity_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 90 DAY);

-- =====================================================
-- END OF SCHEMA
-- =====================================================

-- Verify table creation
SELECT
    TABLE_NAME,
    TABLE_ROWS,
    CREATE_TIME
FROM information_schema.TABLES
WHERE TABLE_SCHEMA = DATABASE()
ORDER BY TABLE_NAME;



-- =====================================================
-- RBAC (Role-Based Access Control) - Already Integrated
-- =====================================================
-- Note: users table already has ENUM('user', 'viewer', 'editor', 'admin')
-- No additional migration needed!


CREATE TABLE IF NOT EXISTS api_integrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    provider VARCHAR(100) NOT NULL,
    category ENUM('seo', 'communication', 'payment', 'ai', 'crm', 'social', 'analytics') NOT NULL,
    enabled BOOLEAN DEFAULT FALSE,
    config JSON COMMENT 'Encrypted API keys and configuration',
    rate_limit_per_hour INT DEFAULT 100,
    requests_today INT DEFAULT 0,
    requests_this_month INT DEFAULT 0,
    quota_limit INT DEFAULT NULL COMMENT 'Monthly quota limit',
    last_request TIMESTAMP NULL,
    last_success TIMESTAMP NULL,
    last_error TEXT NULL,
    error_count INT DEFAULT 0,
    success_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_name (name),
    INDEX idx_category (category),
    INDEX idx_enabled (enabled)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- API Request/Response Logging
CREATE TABLE IF NOT EXISTS api_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    integration_name VARCHAR(100) NOT NULL,
    endpoint VARCHAR(500),
    method VARCHAR(10),
    request_data JSON,
    response_data JSON,
    status_code INT,
    response_time_ms INT,
    error TEXT NULL,
    user_id INT NULL COMMENT 'User who triggered the API call',
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_integration (integration_name),
    INDEX idx_created (created_at),
    INDEX idx_status (status_code),
    INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Funnel Simulations
CREATE TABLE IF NOT EXISTS funnel_simulations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    traffic_source ENUM('google', 'linkedin', 'cold_email', 'direct', 'facebook', 'instagram', 'twitter', 'organic') NOT NULL,
    campaign_name VARCHAR(255),
    mock_user_id INT,
    status ENUM('pending', 'running', 'completed', 'failed', 'partial') DEFAULT 'pending',
    current_step VARCHAR(100),
    current_stage ENUM('landing', 'signup', 'engagement', 'checkout', 'conversion') DEFAULT 'landing',
    started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    total_steps INT DEFAULT 0,
    successful_steps INT DEFAULT 0,
    failed_steps INT DEFAULT 0,
    conversion_value DECIMAL(10,2) DEFAULT 0,
    payment_method ENUM('stripe', 'coinbase', 'none') DEFAULT 'none',
    metadata JSON COMMENT 'Additional simulation data',
    notes TEXT,
    FOREIGN KEY (mock_user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_source (traffic_source),
    INDEX idx_stage (current_stage),
    INDEX idx_started (started_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Funnel Step Tracking
CREATE TABLE IF NOT EXISTS funnel_steps (
    id INT AUTO_INCREMENT PRIMARY KEY,
    simulation_id INT NOT NULL,
    step_name VARCHAR(100) NOT NULL,
    step_order INT NOT NULL,
    stage ENUM('landing', 'signup', 'engagement', 'checkout', 'conversion') NOT NULL,
    status ENUM('pending', 'running', 'success', 'failed', 'skipped') DEFAULT 'pending',
    api_calls JSON COMMENT 'List of API calls made in this step',
    request_payload JSON,
    response_data JSON,
    error_message TEXT NULL,
    duration_ms INT,
    retry_count INT DEFAULT 0,
    started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (simulation_id) REFERENCES funnel_simulations(id) ON DELETE CASCADE,
    INDEX idx_simulation (simulation_id),
    INDEX idx_status (status),
    INDEX idx_stage (stage),
    INDEX idx_started (started_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Funnel Metrics Aggregation (for reporting)
CREATE TABLE IF NOT EXISTS funnel_metrics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    traffic_source VARCHAR(50),
    stage ENUM('landing', 'signup', 'engagement', 'checkout', 'conversion') NOT NULL,
    total_simulations INT DEFAULT 0,
    successful_simulations INT DEFAULT 0,
    failed_simulations INT DEFAULT 0,
    avg_duration_seconds INT DEFAULT 0,
    total_conversion_value DECIMAL(12,2) DEFAULT 0,
    conversion_rate DECIMAL(5,2) COMMENT 'Percentage',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_daily_metric (date, traffic_source, stage),
    INDEX idx_date (date),
    INDEX idx_source (traffic_source),
    INDEX idx_stage (stage)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Webhook Event Log
CREATE TABLE IF NOT EXISTS webhook_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    provider ENUM('stripe', 'coinbase', 'sendgrid', 'whatsapp', 'telegram', 'other') NOT NULL,
    event_type VARCHAR(100) NOT NULL,
    event_id VARCHAR(255) UNIQUE,
    payload JSON NOT NULL,
    signature VARCHAR(500),
    signature_verified BOOLEAN DEFAULT FALSE,
    processed BOOLEAN DEFAULT FALSE,
    processing_result TEXT,
    ip_address VARCHAR(45),
    received_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    processed_at TIMESTAMP NULL,
    INDEX idx_provider (provider),
    INDEX idx_event_type (event_type),
    INDEX idx_processed (processed),
    INDEX idx_received (received_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Email Campaign Tracking
CREATE TABLE IF NOT EXISTS email_campaigns (
    id INT AUTO_INCREMENT PRIMARY KEY,
    campaign_name VARCHAR(255) NOT NULL,
    provider ENUM('sendgrid', 'mailchimp', 'manual') NOT NULL,
    subject VARCHAR(500),
    template_id VARCHAR(100),
    segment VARCHAR(100) COMMENT 'Target audience segment',
    scheduled_at TIMESTAMP NULL,
    sent_at TIMESTAMP NULL,
    total_recipients INT DEFAULT 0,
    delivered_count INT DEFAULT 0,
    opened_count INT DEFAULT 0,
    clicked_count INT DEFAULT 0,
    bounced_count INT DEFAULT 0,
    status ENUM('draft', 'scheduled', 'sending', 'sent', 'failed') DEFAULT 'draft',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_scheduled (scheduled_at),
    INDEX idx_sent (sent_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- WhatsApp Message Queue
CREATE TABLE IF NOT EXISTS whatsapp_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    phone_number VARCHAR(20) NOT NULL,
    message_type ENUM('text', 'template', 'media') NOT NULL,
    template_name VARCHAR(100),
    message_content TEXT,
    media_url VARCHAR(500),
    status ENUM('queued', 'sending', 'sent', 'delivered', 'read', 'failed') DEFAULT 'queued',
    message_id VARCHAR(255) UNIQUE COMMENT 'WhatsApp message ID',
    error_message TEXT,
    scheduled_at TIMESTAMP NULL,
    sent_at TIMESTAMP NULL,
    delivered_at TIMESTAMP NULL,
    read_at TIMESTAMP NULL,
    user_id INT NULL,
    simulation_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (simulation_id) REFERENCES funnel_simulations(id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_phone (phone_number),
    INDEX idx_user (user_id),
    INDEX idx_scheduled (scheduled_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Telegram Notifications
CREATE TABLE IF NOT EXISTS telegram_notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chat_id VARCHAR(100) NOT NULL,
    message_type ENUM('alert', 'notification', 'report', 'error') NOT NULL,
    message_text TEXT NOT NULL,
    parse_mode ENUM('HTML', 'Markdown', 'MarkdownV2') DEFAULT 'HTML',
    status ENUM('queued', 'sent', 'failed') DEFAULT 'queued',
    message_id INT COMMENT 'Telegram message ID',
    error_message TEXT,
    sent_at TIMESTAMP NULL,
    priority ENUM('low', 'normal', 'high', 'urgent') DEFAULT 'normal',
    user_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_priority (priority),
    INDEX idx_sent (sent_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Payment Transactions (for funnel testing)
CREATE TABLE IF NOT EXISTS payment_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    provider ENUM('stripe', 'coinbase', 'paypal') NOT NULL,
    transaction_id VARCHAR(255) UNIQUE NOT NULL,
    order_id INT,
    user_id INT,
    simulation_id INT NULL COMMENT 'If part of funnel test',
    amount DECIMAL(10,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'USD',
    status ENUM('pending', 'processing', 'completed', 'failed', 'refunded', 'cancelled') DEFAULT 'pending',
    payment_method VARCHAR(50),
    metadata JSON,
    test_mode BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (simulation_id) REFERENCES funnel_simulations(id) ON DELETE SET NULL,
    INDEX idx_provider (provider),
    INDEX idx_transaction (transaction_id),
    INDEX idx_order (order_id),
    INDEX idx_user (user_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEO Tracking (Google Search Console)
CREATE TABLE IF NOT EXISTS seo_metrics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    page_url VARCHAR(500),
    query VARCHAR(500),
    impressions INT DEFAULT 0,
    clicks INT DEFAULT 0,
    ctr DECIMAL(5,2) COMMENT 'Click-through rate percentage',
    position DECIMAL(5,2) COMMENT 'Average position in search results',
    country VARCHAR(2),
    device ENUM('desktop', 'mobile', 'tablet'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_date (date),
    INDEX idx_page (page_url(255)),
    INDEX idx_query (query(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- PageSpeed Insights Results
CREATE TABLE IF NOT EXISTS pagespeed_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page_url VARCHAR(500) NOT NULL,
    device ENUM('mobile', 'desktop') NOT NULL,
    performance_score INT COMMENT '0-100',
    accessibility_score INT COMMENT '0-100',
    best_practices_score INT COMMENT '0-100',
    seo_score INT COMMENT '0-100',
    fcp_ms INT COMMENT 'First Contentful Paint',
    lcp_ms INT COMMENT 'Largest Contentful Paint',
    cls DECIMAL(4,3) COMMENT 'Cumulative Layout Shift',
    ttfb_ms INT COMMENT 'Time to First Byte',
    raw_data JSON,
    tested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_page (page_url(255)),
    INDEX idx_device (device),
    INDEX idx_tested (tested_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Insert Default API Integrations
-- =====================================================

INSERT INTO api_integrations (name, provider, category, enabled, rate_limit_per_hour) VALUES
('Google Search Console', 'google', 'seo', FALSE, 50),
('PageSpeed Insights', 'google', 'seo', FALSE, 25),
('SendGrid Email', 'sendgrid', 'communication', FALSE, 100),
('WhatsApp Business', 'meta', 'communication', FALSE, 80),
('Telegram Bot', 'telegram', 'communication', FALSE, 200),
('Stripe Payments', 'stripe', 'payment', FALSE, 100),
('Coinbase Commerce', 'coinbase', 'payment', FALSE, 50),
('Hunter.io', 'hunter', 'crm', FALSE, 50),
('OpenAI ChatGPT', 'openai', 'ai', FALSE, 60),
('LinkedIn API', 'linkedin', 'social', FALSE, 30)
ON DUPLICATE KEY UPDATE updated_at = CURRENT_TIMESTAMP;

-- =====================================================
-- Views for Reporting
-- =====================================================

-- Funnel conversion rates by source
CREATE OR REPLACE VIEW funnel_conversion_rates AS
SELECT
    traffic_source,
    COUNT(*) as total_simulations,
    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
    SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed,
    ROUND(SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) as completion_rate,
    AVG(CASE WHEN status = 'completed' THEN conversion_value ELSE 0 END) as avg_conversion_value,
    AVG(TIMESTAMPDIFF(SECOND, started_at, completed_at)) as avg_duration_seconds
FROM funnel_simulations
GROUP BY traffic_source;

-- API performance summary
CREATE OR REPLACE VIEW api_performance_summary AS
SELECT
    integration_name,
    COUNT(*) as total_requests,
    SUM(CASE WHEN status_code >= 200 AND status_code < 300 THEN 1 ELSE 0 END) as successful_requests,
    SUM(CASE WHEN status_code >= 400 THEN 1 ELSE 0 END) as failed_requests,
    AVG(response_time_ms) as avg_response_time,
    MAX(response_time_ms) as max_response_time,
    DATE(created_at) as date
FROM api_logs
GROUP BY integration_name, DATE(created_at)
ORDER BY date DESC, integration_name;

-- =====================================================
-- Stored Procedures for Funnel Testing
-- =====================================================

DELIMITER $$

-- Start a new funnel simulation
CREATE PROCEDURE IF NOT EXISTS start_funnel_simulation(
    IN p_traffic_source VARCHAR(50),
    IN p_campaign_name VARCHAR(255)
)
BEGIN
    INSERT INTO funnel_simulations (traffic_source, campaign_name, status, current_stage)
    VALUES (p_traffic_source, p_campaign_name, 'pending', 'landing');

    SELECT LAST_INSERT_ID() as simulation_id;
END$$

-- Log funnel step completion
CREATE PROCEDURE IF NOT EXISTS log_funnel_step(
    IN p_simulation_id INT,
    IN p_step_name VARCHAR(100),
    IN p_step_order INT,
    IN p_stage VARCHAR(50),
    IN p_status VARCHAR(20),
    IN p_duration_ms INT,
    IN p_api_calls JSON,
    IN p_response_data JSON,
    IN p_error_message TEXT
)
BEGIN
    INSERT INTO funnel_steps (
        simulation_id, step_name, step_order, stage, status,
        duration_ms, api_calls, response_data, error_message, completed_at
    ) VALUES (
        p_simulation_id, p_step_name, p_step_order, p_stage, p_status,
        p_duration_ms, p_api_calls, p_response_data, p_error_message, NOW()
    );

    -- Update simulation progress
    UPDATE funnel_simulations
    SET total_steps = total_steps + 1,
        successful_steps = successful_steps + CASE WHEN p_status = 'success' THEN 1 ELSE 0 END,
        failed_steps = failed_steps + CASE WHEN p_status = 'failed' THEN 1 ELSE 0 END,
        current_step = p_step_name,
        current_stage = p_stage
    WHERE id = p_simulation_id;
END$$

-- Complete funnel simulation
CREATE PROCEDURE IF NOT EXISTS complete_funnel_simulation(
    IN p_simulation_id INT,
    IN p_status VARCHAR(20),
    IN p_conversion_value DECIMAL(10,2),
    IN p_payment_method VARCHAR(20)
)
BEGIN
    UPDATE funnel_simulations
    SET status = p_status,
        conversion_value = p_conversion_value,
        payment_method = p_payment_method,
        completed_at = NOW()
    WHERE id = p_simulation_id;

    -- Aggregate daily metrics
    INSERT INTO funnel_metrics (
        date, traffic_source, stage,
        total_simulations, successful_simulations, failed_simulations,
        total_conversion_value
    )
    SELECT
        CURDATE(),
        traffic_source,
        'conversion',
        1,
        CASE WHEN p_status = 'completed' THEN 1 ELSE 0 END,
        CASE WHEN p_status = 'failed' THEN 1 ELSE 0 END,
        p_conversion_value
    FROM funnel_simulations
    WHERE id = p_simulation_id
    ON DUPLICATE KEY UPDATE
        total_simulations = total_simulations + 1,
        successful_simulations = successful_simulations + VALUES(successful_simulations),
        failed_simulations = failed_simulations + VALUES(failed_simulations),
        total_conversion_value = total_conversion_value + VALUES(total_conversion_value);
END$$

DELIMITER ;

-- =====================================================
-- Triggers
-- =====================================================

-- Log API integration usage
DELIMITER $$
CREATE TRIGGER IF NOT EXISTS after_api_log_insert
AFTER INSERT ON api_logs
FOR EACH ROW
BEGIN
    UPDATE api_integrations
    SET requests_today = requests_today + 1,
        requests_this_month = requests_this_month + 1,
        last_request = NOW(),
        last_success = CASE WHEN NEW.status_code >= 200 AND NEW.status_code < 300 THEN NOW() ELSE last_success END,
        last_error = CASE WHEN NEW.status_code >= 400 THEN NEW.error ELSE last_error END,
        success_count = success_count + CASE WHEN NEW.status_code >= 200 AND NEW.status_code < 300 THEN 1 ELSE 0 END,
        error_count = error_count + CASE WHEN NEW.status_code >= 400 THEN 1 ELSE 0 END
    WHERE name = NEW.integration_name;
END$$
DELIMITER ;

-- =====================================================
-- Cleanup Jobs (run via cron)
-- =====================================================

-- Clean up old API logs (keep 90 days)
-- DELETE FROM api_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 90 DAY);

-- Clean up old funnel simulations (keep 180 days)
-- DELETE FROM funnel_simulations WHERE started_at < DATE_SUB(NOW(), INTERVAL 180 DAY);

-- Reset daily API request counters (run at midnight)
-- UPDATE api_integrations SET requests_today = 0;

-- =====================================================
-- END OF PART 2 SCHEMA
-- =====================================================


CREATE TABLE IF NOT EXISTS translations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content_type ENUM(
        'blog',
        'testimonial',
        'service',
        'portfolio',
        'page_section',
        'carousel',
        'ui_string',
        'setting',
        'notification'
    ) NOT NULL,
    content_id INT DEFAULT NULL COMMENT 'FK to original content, NULL for UI strings',
    field_name VARCHAR(100) NOT NULL COMMENT 'title, description, content, tagline, etc.',
    lang_code VARCHAR(5) NOT NULL COMMENT 'ISO 639-1 language code',
    original_text LONGTEXT NOT NULL COMMENT 'Original content for reference',
    translated_text LONGTEXT NOT NULL COMMENT 'Translated content',
    manual_override BOOLEAN DEFAULT FALSE COMMENT 'True if admin manually edited',
    translation_method ENUM('auto', 'manual', 'hybrid') DEFAULT 'auto',
    quality_score DECIMAL(3,2) DEFAULT NULL COMMENT '0.00 to 1.00 confidence score',
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_content_lookup (content_type, content_id, field_name, lang_code),
    INDEX idx_lang_code (lang_code),
    INDEX idx_manual_override (manual_override),
    INDEX idx_content_type (content_type),
    INDEX idx_translation_method (translation_method)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- SUPPORTED LANGUAGES TABLE
-- =====================================================

CREATE TABLE IF NOT EXISTS supported_languages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lang_code VARCHAR(5) UNIQUE NOT NULL COMMENT 'ISO 639-1 code',
    lang_name VARCHAR(100) NOT NULL COMMENT 'English name',
    native_name VARCHAR(100) NOT NULL COMMENT 'Native name',
    flag_icon VARCHAR(10) DEFAULT NULL COMMENT 'Emoji flag or icon code',
    rtl BOOLEAN DEFAULT FALSE COMMENT 'Right-to-left language',
    active BOOLEAN DEFAULT TRUE COMMENT 'Enabled for users',
    default_lang BOOLEAN DEFAULT FALSE COMMENT 'Primary language',
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_active (active),
    INDEX idx_default_lang (default_lang),
    INDEX idx_sort_order (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TRANSLATION CACHE TABLE
-- =====================================================

CREATE TABLE IF NOT EXISTS translation_cache (
    id INT AUTO_INCREMENT PRIMARY KEY,
    source_text_hash VARCHAR(32) NOT NULL COMMENT 'MD5 hash of source text',
    source_lang VARCHAR(5) NOT NULL,
    target_lang VARCHAR(5) NOT NULL,
    source_text TEXT NOT NULL COMMENT 'Original text for reference',
    translated_text LONGTEXT NOT NULL,
    api_provider VARCHAR(50) DEFAULT 'google' COMMENT 'google, deepl, etc.',
    cache_hits INT DEFAULT 0 COMMENT 'Number of times reused',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP DEFAULT NULL COMMENT 'Cache expiration',

    UNIQUE KEY unique_translation (source_text_hash, source_lang, target_lang),
    INDEX idx_hash_lookup (source_text_hash, target_lang),
    INDEX idx_expires (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TRANSLATION STATISTICS TABLE
-- =====================================================

CREATE TABLE IF NOT EXISTS translation_stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lang_code VARCHAR(5) NOT NULL,
    content_type VARCHAR(50) NOT NULL,
    total_items INT DEFAULT 0,
    translated_items INT DEFAULT 0,
    manual_overrides INT DEFAULT 0,
    completion_percentage DECIMAL(5,2) DEFAULT 0.00,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY unique_stat (lang_code, content_type),
    INDEX idx_lang_code (lang_code),
    INDEX idx_completion (completion_percentage)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- INSERT DEFAULT LANGUAGES
-- =====================================================

INSERT INTO supported_languages (lang_code, lang_name, native_name, flag_icon, rtl, active, default_lang, sort_order) VALUES
('en', 'English', 'English', '🇬🇧', FALSE, TRUE, TRUE, 1),
('es', 'Spanish', 'Español', '🇪🇸', FALSE, TRUE, FALSE, 2),
('fr', 'French', 'Français', '🇫🇷', FALSE, TRUE, FALSE, 3),
('ar', 'Arabic', 'العربية', '🇸🇦', TRUE, TRUE, FALSE, 4),
('de', 'German', 'Deutsch', '🇩🇪', FALSE, TRUE, FALSE, 5),
('pt', 'Portuguese', 'Português', '🇵🇹', FALSE, TRUE, FALSE, 6),
('it', 'Italian', 'Italiano', '🇮🇹', FALSE, FALSE, FALSE, 7),
('ru', 'Russian', 'Русский', '🇷🇺', FALSE, FALSE, FALSE, 8),
('zh', 'Chinese', '中文', '🇨🇳', FALSE, FALSE, FALSE, 9),
('ja', 'Japanese', '日本語', '🇯🇵', FALSE, FALSE, FALSE, 10),
('hi', 'Hindi', 'हिन्दी', '🇮🇳', FALSE, FALSE, FALSE, 11),
('tr', 'Turkish', 'Türkçe', '🇹🇷', FALSE, FALSE, FALSE, 12)
ON DUPLICATE KEY UPDATE
    active = VALUES(active),
    sort_order = VALUES(sort_order);

-- =====================================================
-- INSERT DEFAULT UI STRINGS FOR TRANSLATION
-- =====================================================

INSERT INTO translations (content_type, content_id, field_name, lang_code, original_text, translated_text, manual_override, translation_method) VALUES
-- Navigation UI strings
('ui_string', NULL, 'nav_home', 'en', 'Home', 'Home', TRUE, 'manual'),
('ui_string', NULL, 'nav_about', 'en', 'About', 'About', TRUE, 'manual'),
('ui_string', NULL, 'nav_services', 'en', 'Services', 'Services', TRUE, 'manual'),
('ui_string', NULL, 'nav_portfolio', 'en', 'Portfolio', 'Portfolio', TRUE, 'manual'),
('ui_string', NULL, 'nav_blog', 'en', 'Blog', 'Blog', TRUE, 'manual'),
('ui_string', NULL, 'nav_contact', 'en', 'Contact', 'Contact', TRUE, 'manual'),
('ui_string', NULL, 'nav_faq', 'en', 'FAQ', 'FAQ', TRUE, 'manual'),
('ui_string', NULL, 'nav_testimonials', 'en', 'Testimonials', 'Testimonials', TRUE, 'manual'),

-- CTA buttons
('ui_string', NULL, 'btn_get_started', 'en', 'Get Started', 'Get Started', TRUE, 'manual'),
('ui_string', NULL, 'btn_learn_more', 'en', 'Learn More', 'Learn More', TRUE, 'manual'),
('ui_string', NULL, 'btn_contact_us', 'en', 'Contact Us', 'Contact Us', TRUE, 'manual'),
('ui_string', NULL, 'btn_view_portfolio', 'en', 'View Portfolio', 'View Portfolio', TRUE, 'manual'),
('ui_string', NULL, 'btn_read_more', 'en', 'Read More', 'Read More', TRUE, 'manual'),

-- Common labels
('ui_string', NULL, 'label_name', 'en', 'Name', 'Name', TRUE, 'manual'),
('ui_string', NULL, 'label_email', 'en', 'Email', 'Email', TRUE, 'manual'),
('ui_string', NULL, 'label_phone', 'en', 'Phone', 'Phone', TRUE, 'manual'),
('ui_string', NULL, 'label_message', 'en', 'Message', 'Message', TRUE, 'manual'),
('ui_string', NULL, 'label_service', 'en', 'Service', 'Service', TRUE, 'manual'),
('ui_string', NULL, 'label_submit', 'en', 'Submit', 'Submit', TRUE, 'manual'),

-- Footer strings
('ui_string', NULL, 'footer_copyright', 'en', '© 2025 Adil GFX. All rights reserved.', '© 2025 Adil GFX. All rights reserved.', TRUE, 'manual'),
('ui_string', NULL, 'footer_tagline', 'en', 'Professional Design Services', 'Professional Design Services', TRUE, 'manual')
ON DUPLICATE KEY UPDATE
    translated_text = VALUES(translated_text);

-- =====================================================
-- STORED PROCEDURES
-- =====================================================

-- Get translation with fallback
DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS get_translation(
    IN p_content_type VARCHAR(50),
    IN p_content_id INT,
    IN p_field_name VARCHAR(100),
    IN p_lang_code VARCHAR(5),
    IN p_fallback_text TEXT
)
BEGIN
    DECLARE v_translated_text LONGTEXT;

    SELECT translated_text INTO v_translated_text
    FROM translations
    WHERE content_type = p_content_type
      AND (content_id = p_content_id OR (content_id IS NULL AND p_content_id IS NULL))
      AND field_name = p_field_name
      AND lang_code = p_lang_code
    LIMIT 1;

    IF v_translated_text IS NULL OR v_translated_text = '' THEN
        SET v_translated_text = p_fallback_text;
    END IF;

    SELECT v_translated_text AS translation;
END$$
DELIMITER ;

-- Update translation statistics
DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS update_translation_stats(
    IN p_lang_code VARCHAR(5)
)
BEGIN
    INSERT INTO translation_stats (lang_code, content_type, total_items, translated_items, manual_overrides, completion_percentage)
    SELECT
        p_lang_code,
        content_type,
        COUNT(DISTINCT CONCAT(content_type, '-', IFNULL(content_id, 'null'), '-', field_name)) AS total,
        COUNT(*) AS translated,
        SUM(CASE WHEN manual_override = TRUE THEN 1 ELSE 0 END) AS manual,
        (COUNT(*) / COUNT(DISTINCT CONCAT(content_type, '-', IFNULL(content_id, 'null'), '-', field_name))) * 100 AS percentage
    FROM translations
    WHERE lang_code = p_lang_code
    GROUP BY content_type
    ON DUPLICATE KEY UPDATE
        total_items = VALUES(total_items),
        translated_items = VALUES(translated_items),
        manual_overrides = VALUES(manual_overrides),
        completion_percentage = VALUES(completion_percentage);
END$$
DELIMITER ;

-- =====================================================
-- VIEWS FOR COMMON QUERIES
-- =====================================================

-- Translation completion overview
CREATE OR REPLACE VIEW translation_completion_overview AS
SELECT
    sl.lang_code,
    sl.lang_name,
    sl.native_name,
    sl.active,
    IFNULL(SUM(ts.translated_items), 0) AS total_translations,
    IFNULL(SUM(ts.manual_overrides), 0) AS manual_overrides,
    IFNULL(AVG(ts.completion_percentage), 0) AS avg_completion
FROM supported_languages sl
LEFT JOIN translation_stats ts ON sl.lang_code = ts.lang_code
WHERE sl.active = TRUE
GROUP BY sl.lang_code, sl.lang_name, sl.native_name, sl.active;

-- =====================================================
-- TRIGGERS
-- =====================================================

-- Update translation stats on insert/update
DELIMITER $$
CREATE TRIGGER IF NOT EXISTS after_translation_insert
AFTER INSERT ON translations
FOR EACH ROW
BEGIN
    CALL update_translation_stats(NEW.lang_code);
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER IF NOT EXISTS after_translation_update
AFTER UPDATE ON translations
FOR EACH ROW
BEGIN
    CALL update_translation_stats(NEW.lang_code);
    IF NEW.lang_code != OLD.lang_code THEN
        CALL update_translation_stats(OLD.lang_code);
    END IF;
END$$
DELIMITER ;

-- =====================================================
-- INDEXES FOR PERFORMANCE
-- =====================================================

CREATE INDEX IF NOT EXISTS idx_translation_lookup
ON translations(content_type, field_name, lang_code);

CREATE INDEX IF NOT EXISTS idx_cache_lookup
ON translation_cache(source_text_hash, source_lang, target_lang);

-- =====================================================
-- END OF SCHEMA
-- =====================================================
