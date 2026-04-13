-- Fix referrals table structure
-- This will correct the column names from customer_id to referrer_id and referred_id

-- First, check if the table exists and drop it to recreate with correct structure
DROP TABLE IF EXISTS `referrals`;

-- Create the referrals table with correct column names
CREATE TABLE `referrals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `referrer_id` int(11) NOT NULL COMMENT 'Customer who referred (from bank_customers)',
  `referred_id` int(11) NOT NULL COMMENT 'Customer who was referred (from bank_customers)',
  `points_earned` decimal(10,2) DEFAULT 0.00 COMMENT 'Points earned by referrer',
  `status` enum('pending','completed','cancelled') DEFAULT 'completed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_referrer` (`referrer_id`),
  KEY `idx_referred` (`referred_id`),
  CONSTRAINT `fk_referrer` FOREIGN KEY (`referrer_id`) REFERENCES `bank_customers` (`customer_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_referred` FOREIGN KEY (`referred_id`) REFERENCES `bank_customers` (`customer_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
