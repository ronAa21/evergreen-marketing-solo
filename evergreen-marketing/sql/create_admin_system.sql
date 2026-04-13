-- Admin System Tables
-- Run this SQL to create the admin system

-- Create admin users table
CREATE TABLE IF NOT EXISTS `admin_users` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin (username: admin, password: admin123)
-- Using INSERT IGNORE to skip if admin already exists
INSERT IGNORE INTO `admin_users` (`username`, `email`, `password_hash`, `full_name`) VALUES
('admin', 'admin@evergreen.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator');

-- Create content management table
CREATE TABLE IF NOT EXISTS `site_content` (
  `content_id` int(11) NOT NULL AUTO_INCREMENT,
  `content_key` varchar(100) NOT NULL,
  `content_value` text NOT NULL,
  `content_type` enum('text','image','html') DEFAULT 'text',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`content_id`),
  UNIQUE KEY `content_key` (`content_key`),
  KEY `updated_by` (`updated_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default content (using INSERT IGNORE to skip duplicates)
INSERT IGNORE INTO `site_content` (`content_key`, `content_value`, `content_type`) VALUES
('company_name', 'Evergreen Bank', 'text'),
('company_logo', 'images/Logo.png.png', 'image'),
('hero_title', 'Secure. Invest. Achieve.', 'text'),
('hero_description', 'Your trusted financial partner for a prosperous future.', 'text'),
('about_description', 'Evergreen Bank has been serving customers for over 20 years with dedication and excellence.', 'html'),
('banner_image', 'images/hero-main.png', 'image'),
('contact_phone', '1-800-EVERGREEN', 'text'),
('contact_email', 'evrgrn.64@gmail.com', 'text');

-- Create card applications table (if not exists)
CREATE TABLE IF NOT EXISTS `card_applications` (
  `application_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `card_type` enum('credit','debit','prepaid') NOT NULL,
  `application_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','declined') DEFAULT 'pending',
  `reviewed_by` int(11) DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`application_id`),
  KEY `customer_id` (`customer_id`),
  KEY `status` (`status`),
  KEY `reviewed_by` (`reviewed_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add some sample card applications for testing
INSERT IGNORE INTO `card_applications` (`customer_id`, `card_type`, `status`) 
SELECT customer_id, 'credit', 'pending' 
FROM bank_customers 
WHERE customer_id IN (1, 2, 3)
LIMIT 3;
