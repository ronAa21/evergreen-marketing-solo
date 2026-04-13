-- ========================================
-- ADD REFERRALS TABLE MIGRATION
-- ========================================
-- This migration adds the referrals table to track customer referrals
-- Run this file separately to add the table without recreating the database

USE BankingDB;

-- Drop the table if it exists (to allow re-running this migration)
DROP TABLE IF EXISTS referrals;

-- Create the referrals table
CREATE TABLE referrals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    referrer_id INT NOT NULL COMMENT 'Customer who referred (from bank_customers)',
    referred_id INT NOT NULL COMMENT 'Customer who was referred (from bank_customers)',
    points_earned DECIMAL(10,2) DEFAULT 0.00 COMMENT 'Points earned by referrer',
    status ENUM('pending','completed','cancelled') DEFAULT 'completed',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_referral (referrer_id, referred_id),
    INDEX idx_referrer_id (referrer_id),
    INDEX idx_referred_id (referred_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Note: Foreign keys are commented out to avoid issues if bank_customers structure is different
-- Uncomment these lines if your bank_customers table has customer_id as primary key:
-- ALTER TABLE referrals 
--     ADD CONSTRAINT fk_referrer FOREIGN KEY (referrer_id) REFERENCES bank_customers(customer_id) ON DELETE CASCADE,
--     ADD CONSTRAINT fk_referred FOREIGN KEY (referred_id) REFERENCES bank_customers(customer_id) ON DELETE CASCADE;

SELECT 'Referrals table created successfully!' as status;
