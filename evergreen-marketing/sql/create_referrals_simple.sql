-- Simple script to create referrals table
-- Run this in phpMyAdmin or MySQL command line

-- Make sure you're using the correct database
USE BankingDB;

-- Create referrals table (drop first if exists)
DROP TABLE IF EXISTS referrals;

CREATE TABLE referrals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    referrer_id INT NOT NULL COMMENT 'Customer who made the referral',
    referred_id INT NOT NULL COMMENT 'Customer who was referred',
    points_earned DECIMAL(10,2) DEFAULT 0.00,
    status ENUM('pending','completed','cancelled') DEFAULT 'completed',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_referral (referrer_id, referred_id),
    INDEX idx_referrer_id (referrer_id),
    INDEX idx_referred_id (referred_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Verify the table was created
SELECT 'Referrals table created successfully!' as message;
DESCRIBE referrals;
