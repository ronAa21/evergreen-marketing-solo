-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 13, 2026 at 10:40 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bankingdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(150) NOT NULL,
  `type_id` int(11) NOT NULL,
  `parent_account_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `account_applications`
--

CREATE TABLE `account_applications` (
  `application_id` int(11) NOT NULL,
  `application_number` varchar(50) NOT NULL,
  `application_status` enum('pending','approved','rejected') DEFAULT 'pending',
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `date_of_birth` date NOT NULL,
  `street_address` varchar(255) NOT NULL,
  `barangay` varchar(150) NOT NULL DEFAULT '',
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `zip_code` varchar(20) NOT NULL,
  `ssn` varchar(50) NOT NULL COMMENT 'TIN (Tax Identification Number)',
  `id_type` varchar(50) NOT NULL COMMENT 'Philippine Government ID Type',
  `id_number` varchar(100) NOT NULL,
  `id_document_path` varchar(255) DEFAULT NULL COMMENT 'Path to uploaded ID document',
  `employment_status` varchar(50) NOT NULL,
  `employer_name` varchar(150) DEFAULT NULL,
  `job_title` varchar(100) DEFAULT NULL,
  `annual_income` decimal(15,2) DEFAULT NULL,
  `account_type` varchar(50) NOT NULL COMMENT 'acct-checking, acct-savings, acct-both',
  `selected_cards` text DEFAULT NULL COMMENT 'Comma-separated: debit, credit, prepaid',
  `additional_services` text DEFAULT NULL COMMENT 'Comma-separated: debit, online, mobile, overdraft',
  `terms_accepted` tinyint(1) DEFAULT 0,
  `privacy_acknowledged` tinyint(1) DEFAULT 0,
  `marketing_consent` tinyint(1) DEFAULT 0,
  `submitted_at` datetime DEFAULT current_timestamp(),
  `reviewed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `account_balances`
--

CREATE TABLE `account_balances` (
  `id` bigint(20) NOT NULL,
  `account_id` int(11) NOT NULL,
  `fiscal_period_id` int(11) NOT NULL,
  `opening_balance` decimal(18,2) DEFAULT 0.00,
  `debit_movements` decimal(18,2) DEFAULT 0.00,
  `credit_movements` decimal(18,2) DEFAULT 0.00,
  `closing_balance` decimal(18,2) DEFAULT 0.00,
  `last_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `account_status_history`
--

CREATE TABLE `account_status_history` (
  `status_history_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `previous_status` enum('active','below_maintaining','flagged_for_removal','closed') DEFAULT NULL,
  `new_status` enum('active','below_maintaining','flagged_for_removal','closed') NOT NULL,
  `balance_at_change` decimal(10,2) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `changed_by` int(11) DEFAULT NULL COMMENT 'Employee ID who triggered the change, NULL for system',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `account_types`
--

CREATE TABLE `account_types` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `category` enum('asset','liability','equity','revenue','expense') NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `address_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `address_line` varchar(200) NOT NULL,
  `barangay_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `address_type` varchar(20) DEFAULT 'home',
  `is_primary` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`admin_id`, `username`, `email`, `password_hash`, `full_name`, `is_active`, `created_at`, `last_login`) VALUES
(1, 'admin', 'admin@evergreen.com', '$2y$10$LVqFB7zVJ38rhTUq5AUFzO5aRlYmUxa9lPeKya0NWjsAlctrYEReO', 'System Administrator', 1, '2026-03-08 10:06:34', '2026-04-13 05:07:29');

-- --------------------------------------------------------

--
-- Table structure for table `advertisements`
--

CREATE TABLE `advertisements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image_path` varchar(500) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `advertisements`
--

INSERT INTO `advertisements` (`id`, `title`, `description`, `image_path`, `status`, `created_at`, `updated_at`) VALUES
(43, '5% Cashback on Groceries', 'Shop smarter with our Grocery Rewards Card! Earn up to 5% cashback on all grocery purchases, 3% on gas, and 1% on everything else.', 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&q=80&w=800', 'active', '2026-03-07 11:19:22', '2026-03-07 11:19:22'),
(44, 'Home Loan at 3.5% Interest', 'Make your dream home a reality! Get pre-approved for a home loan with interest rates as low as 3.5% APR. Flexible payment terms up to 30 years.', 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&q=80&w=800', 'active', '2026-03-06 11:19:22', '2026-03-06 11:19:22'),
(45, 'Student Savings Account Bonus', 'Students save more! Open a student savings account and get ₱500 bonus. No maintaining balance required.', 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&q=80&w=800', 'active', '2026-03-05 11:19:22', '2026-03-05 11:19:22'),
(47, 'Business Loan Up to ₱5M', 'Grow your business with our flexible business loans! Get up to ₱5 million with competitive rates starting at 4.5%.', 'https://images.unsplash.com/photo-1664575602276-acd073f104c1?auto=format&fit=crop&q=80&w=800', 'active', '2026-03-03 11:19:22', '2026-03-03 11:19:22'),
(48, 'High-Yield Savings Account', 'Earn up to 4.5% annual interest on your savings! No monthly fees, no minimum balance. Watch your money grow.', 'https://images.unsplash.com/photo-1579621970795-87facc2f976d?auto=format&fit=crop&q=80&w=800', 'active', '2026-03-09 10:19:22', '2026-03-09 10:19:22'),
(49, 'Refer a Friend - Get ₱1,000', 'Share the love and earn rewards! Refer friends to Evergreen Bank and get ₱1,000 for each successful referral.', 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&q=80&w=800', 'active', '2026-03-09 09:19:22', '2026-03-09 09:19:22'),
(50, 'Auto Loan Special Promo', 'Drive your dream car today! Get auto loans with rates as low as 3.9% and up to 7 years to pay.', 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&q=80&w=800', 'active', '2026-03-09 08:19:22', '2026-03-09 08:19:22'),
(52, 'Digital Banking Made Easy', 'Bank anytime, anywhere! Download our mobile app and enjoy instant transfers, bill payments, and QR payments.', 'https://images.unsplash.com/photo-1563986768609-322da13575f3?auto=format&fit=crop&q=80&w=800', 'active', '2026-03-09 06:19:22', '2026-03-09 06:19:22'),
(53, 'Personal Loan in 24 Hours', 'Need cash fast? Get personal loans up to ₱500,000 with approval in just 24 hours! Low interest rates.', 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&q=80&w=800', 'active', '2026-03-02 11:19:22', '2026-03-02 11:19:22'),
(54, 'Platinum Card Exclusive', 'Experience luxury banking with our Platinum Card. Unlimited airport lounge access worldwide and concierge service.', 'https://images.unsplash.com/photo-1613243555988-441166d4d6fd?auto=format&fit=crop&q=80&w=800', 'active', '2026-03-01 11:19:22', '2026-03-01 11:19:22'),
(56, 'Online Shopping Cashback', 'Shop online and save! Get 10% cashback on all online purchases with our e-Commerce Card.', 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?auto=format&fit=crop&q=80&w=800', 'active', '2026-02-27 11:19:22', '2026-02-27 11:19:22'),
(57, 'Insurance Protection Plan', 'Protect what matters most. Get comprehensive life and health insurance with affordable premiums.', 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&q=80&w=800', 'active', '2026-02-26 11:19:22', '2026-02-26 11:19:22'),
(59, 'Payroll Account Benefits', 'Switch your payroll to Evergreen Bank! Get free checking account, free ATM card, and free online banking.', 'https://images.unsplash.com/photo-1544377193-33dcf4d68fb5?auto=format&fit=crop&q=80&w=800', 'active', '2026-02-24 11:19:22', '2026-02-24 11:19:22'),
(60, 'Holiday Loan Promo', 'Make this holiday season special! Get instant holiday loans up to ₱200,000 for travel or shopping.', 'https://images.unsplash.com/photo-1512909006721-3d6018887383?auto=format&fit=crop&q=80&w=800', 'active', '2026-02-23 11:19:22', '2026-02-23 11:19:22'),
(61, 'Rewards Points Multiplier', 'Triple your rewards this month! Earn 3X points on all purchases. Redeem for cash, travel, or gadgets.', 'https://images.unsplash.com/photo-1607082349566-187342175e2f?auto=format&fit=crop&q=80&w=800', 'active', '2026-02-22 11:19:22', '2026-02-22 11:19:22'),
(62, 'Loans', 'Get Loans with 0% Interest!', 'uploads/ads/ad_1773804232_69ba1ac8d8532.png', 'active', '2026-03-18 03:23:52', '2026-03-18 03:23:52');

-- --------------------------------------------------------

--
-- Table structure for table `applicant`
--

CREATE TABLE `applicant` (
  `applicant_id` int(11) NOT NULL,
  `recruitment_id` int(11) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `resume_file` varchar(255) DEFAULT NULL,
  `application_status` varchar(20) DEFAULT NULL,
  `archived_at` datetime DEFAULT NULL,
  `offer_status` enum('Pending','Accepted','Declined') DEFAULT 'Pending',
  `offer_token` varchar(100) DEFAULT NULL,
  `offer_sent_at` datetime DEFAULT NULL,
  `offer_acceptance_timestamp` datetime DEFAULT NULL,
  `offer_declined_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `application_documents`
--

CREATE TABLE `application_documents` (
  `document_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `document_type` varchar(50) NOT NULL COMMENT 'id_front, id_back, proof_of_income, proof_of_address',
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` int(11) DEFAULT NULL COMMENT 'File size in bytes',
  `mime_type` varchar(100) DEFAULT NULL COMMENT 'image/jpeg, image/png, application/pdf',
  `uploaded_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `application_documents`
--

INSERT INTO `application_documents` (`document_id`, `application_id`, `document_type`, `file_name`, `file_path`, `file_size`, `mime_type`, `uploaded_at`) VALUES
(1, 6, 'id_front', 'HD-wallpaper-msi-logo-beautiful-best-black-laptop-msi-niketh-ranuja-ranuja-niketh-red-white.jpg', 'uploads/id_images/id_front_6_1772972630.jpg', 15373, 'image/jpeg', '2026-03-08 20:23:50'),
(2, 6, 'id_back', 'Screenshot 2024-09-20 202837.png', 'uploads/id_images/id_back_6_1772972630.png', 15025, 'image/png', '2026-03-08 20:23:50'),
(3, 7, 'id_front', 'Screenshot 2024-09-15 213552.png', 'uploads/id_images/id_front_7_1772972943.png', 979875, 'image/png', '2026-03-08 20:29:03'),
(4, 7, 'id_back', 'Screenshot 2024-09-15 214202.png', 'uploads/id_images/id_back_7_1772972943.png', 318220, 'image/png', '2026-03-08 20:29:03'),
(5, 8, 'id_front', 'Screenshot 2024-09-20 202837.png', 'uploads/id_images/id_front_8_1772979651.png', 15025, 'image/png', '2026-03-08 22:20:51'),
(6, 8, 'id_back', 'Screenshot 2024-09-15 214202.png', 'uploads/id_images/id_back_8_1772979651.png', 318220, 'image/png', '2026-03-08 22:20:51'),
(7, 9, 'id_front', 'Screenshot 2024-09-15 214202.png', 'uploads/id_images/id_front_9_1773013179.png', 318220, 'image/png', '2026-03-09 07:39:39'),
(8, 9, 'id_back', 'Screenshot 2024-09-15 213552.png', 'uploads/id_images/id_back_9_1773013179.png', 979875, 'image/png', '2026-03-09 07:39:39'),
(9, 10, 'id_front', 'Gemini_Generated_Image_rrvfvzrrvfvzrrvf-removebg-preview.png', 'uploads/id_images/id_front_10_1773053965.png', 55207, 'image/png', '2026-03-09 18:59:25'),
(10, 10, 'id_back', '491351975_1840225856756021_2071783492692964241_n-removebg-preview (1)(1)(1).png', 'uploads/id_images/id_back_10_1773053965.png', 111981, 'image/png', '2026-03-09 18:59:25'),
(11, 11, 'id_front', 'NBI-Records-Check.jpg', 'uploads/id_images/id_front_11_1773804509.jpg', 100697, 'image/jpeg', '2026-03-18 11:28:29'),
(12, 11, 'id_back', 'nbi-clearance.jpg', 'uploads/id_images/id_back_11_1773804509.jpg', 72406, 'image/jpeg', '2026-03-18 11:28:29');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time_in` datetime DEFAULT NULL,
  `time_out` datetime DEFAULT NULL,
  `total_hours` decimal(5,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `action` varchar(150) NOT NULL,
  `object_type` varchar(100) NOT NULL,
  `object_id` varchar(100) NOT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `additional_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`additional_info`)),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bank_accounts`
--

CREATE TABLE `bank_accounts` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(150) NOT NULL,
  `bank_name` varchar(150) NOT NULL,
  `account_number` varchar(64) NOT NULL,
  `currency` varchar(10) DEFAULT 'PHP',
  `current_balance` decimal(18,2) DEFAULT 0.00,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bank_account_types`
--

CREATE TABLE `bank_account_types` (
  `account_type_id` int(11) NOT NULL,
  `type_name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bank_customers`
--

CREATE TABLE `bank_customers` (
  `customer_id` int(11) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city_province` varchar(100) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `verification_code` varchar(100) DEFAULT NULL,
  `bank_id` varchar(50) DEFAULT NULL,
  `referral_code` varchar(20) DEFAULT NULL,
  `total_points` decimal(10,2) DEFAULT 0.00,
  `referred_by_customer_id` int(11) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by_employee_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bank_employees`
--

CREATE TABLE `bank_employees` (
  `employee_id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `role` enum('admin','teller','manager') DEFAULT 'teller',
  `is_active` tinyint(1) DEFAULT 1,
  `employee_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bank_transactions`
--

CREATE TABLE `bank_transactions` (
  `transaction_id` int(11) NOT NULL,
  `transaction_ref` varchar(50) DEFAULT NULL,
  `account_id` int(11) NOT NULL,
  `transaction_type_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `related_account_id` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bank_users`
--

CREATE TABLE `bank_users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city_province` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `birthday` date NOT NULL,
  `password` varchar(255) NOT NULL,
  `verification_code` varchar(100) DEFAULT NULL,
  `bank_id` varchar(50) DEFAULT NULL,
  `referral_code` varchar(50) DEFAULT NULL,
  `total_points` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_verified` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `barangays`
--

CREATE TABLE `barangays` (
  `barangay_id` int(11) NOT NULL,
  `barangay_name` varchar(100) NOT NULL,
  `city_id` int(11) NOT NULL,
  `zip_code` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `card_applications`
--

CREATE TABLE `card_applications` (
  `application_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `card_type` enum('credit','debit','prepaid') NOT NULL,
  `application_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','declined') DEFAULT 'pending',
  `reviewed_by` int(11) DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `card_applications`
--

INSERT INTO `card_applications` (`application_id`, `customer_id`, `card_type`, `application_date`, `status`, `reviewed_by`, `reviewed_at`, `notes`) VALUES
(1, 1, 'credit', '2026-03-08 10:15:01', 'declined', 1, '2026-03-08 11:29:57', NULL),
(2, 2, 'credit', '2026-03-08 10:15:01', 'pending', NULL, NULL, NULL),
(3, 3, 'credit', '2026-03-08 10:15:01', 'pending', NULL, NULL, NULL),
(4, 1, 'credit', '2026-03-08 10:31:19', 'approved', 1, '2026-03-08 11:29:53', NULL),
(5, 2, 'credit', '2026-03-08 10:31:19', 'pending', NULL, NULL, NULL),
(6, 3, 'credit', '2026-03-08 10:31:19', 'pending', NULL, NULL, NULL),
(7, 11, 'debit', '2026-03-08 12:29:03', 'approved', 1, '2026-03-08 12:31:41', NULL),
(8, 12, 'prepaid', '2026-03-08 14:20:51', 'approved', 1, '2026-03-08 23:34:56', NULL),
(9, 12, 'debit', '2026-03-08 23:39:39', 'declined', 1, '2026-03-08 23:39:51', NULL),
(10, 1, 'credit', '2026-03-09 09:20:30', 'pending', NULL, NULL, NULL),
(11, 2, 'credit', '2026-03-09 09:20:30', 'pending', NULL, NULL, NULL),
(12, 3, 'credit', '2026-03-09 09:20:30', 'pending', NULL, NULL, NULL),
(13, 12, 'debit', '2026-03-09 10:59:25', 'pending', NULL, NULL, NULL),
(14, 12, 'prepaid', '2026-03-09 10:59:25', 'pending', NULL, NULL, NULL),
(15, 14, 'debit', '2026-03-18 03:28:29', 'pending', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `city_id` int(11) NOT NULL,
  `city_name` varchar(100) NOT NULL,
  `province_id` int(11) NOT NULL,
  `city_type` enum('city','municipality') DEFAULT 'city',
  `zip_code` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_content`
--

CREATE TABLE `cms_content` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `author_id` int(11) NOT NULL,
  `status` enum('Draft','Published') DEFAULT 'Draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cms_content`
--

INSERT INTO `cms_content` (`id`, `title`, `slug`, `body`, `author_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'New title for test 4', 'test-4-', 'Dance with me', 1, 'Draft', '2026-03-07 03:47:54', '2026-03-07 05:08:42'),
(2, 'test 5 ', 'test-5-', 'test test', 1, '', '2026-03-07 03:52:00', '2026-03-07 03:52:00'),
(3, 'test 6 ', 'test-6-', 'test test', 1, '', '2026-03-07 04:14:06', '2026-03-07 04:14:06'),
(10, 'I think I wanna marry you', 'i-think-i-wanna-marry-you', 'Bruno Mars', 1, 'Published', '2026-03-07 06:41:57', '2026-03-07 06:41:57'),
(11, 'Test Post - 2026-03-07 09:08:07', 'test-post', 'This is a test post created by the system test script.', 1, 'Published', '2026-03-07 08:08:07', '2026-03-07 08:08:07'),
(12, 'meowers', 'johsua-nambio', 'kaba', 1, 'Published', '2026-03-07 08:14:46', '2026-03-07 08:45:49');

-- --------------------------------------------------------

--
-- Table structure for table `compliance_reports`
--

CREATE TABLE `compliance_reports` (
  `id` int(11) NOT NULL,
  `report_type` enum('gaap','sox','bir','ifrs') NOT NULL,
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `generated_date` datetime DEFAULT current_timestamp(),
  `generated_by` int(11) NOT NULL,
  `status` enum('generating','completed','failed') DEFAULT 'generating',
  `file_path` varchar(500) DEFAULT NULL,
  `report_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`report_data`)),
  `compliance_score` decimal(5,2) DEFAULT NULL,
  `issues_found` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contract`
--

CREATE TABLE `contract` (
  `contract_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `contract_type` varchar(50) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `benefits` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_accounts`
--

CREATE TABLE `customer_accounts` (
  `account_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `account_number` varchar(30) NOT NULL,
  `account_type_id` int(11) NOT NULL,
  `interest_rate` decimal(5,2) DEFAULT NULL,
  `last_interest_date` date DEFAULT NULL,
  `is_locked` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by_employee_id` int(11) DEFAULT NULL,
  `maintaining_balance_required` decimal(10,2) DEFAULT 500.00,
  `monthly_service_fee` decimal(10,2) DEFAULT 100.00,
  `below_maintaining_since` date DEFAULT NULL,
  `account_status` enum('active','below_maintaining','flagged_for_removal','closed') DEFAULT NULL,
  `last_service_fee_date` date DEFAULT NULL,
  `closure_warning_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_linked_accounts`
--

CREATE TABLE `customer_linked_accounts` (
  `link_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `linked_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_profiles`
--

CREATE TABLE `customer_profiles` (
  `profile_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `gender_id` int(11) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `marital_status` enum('single','married','divorced','widowed','other') DEFAULT 'single',
  `national_id` varchar(50) DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `income_range` varchar(50) DEFAULT NULL,
  `preferred_language` varchar(50) DEFAULT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  `loyalty_member` tinyint(1) DEFAULT 0,
  `profile_created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE `emails` (
  `email_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `employee_id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `house_number` varchar(50) DEFAULT NULL,
  `street` varchar(100) DEFAULT NULL,
  `barangay` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `secondary_email` varchar(100) DEFAULT NULL,
  `secondary_contact_number` varchar(20) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `position_id` int(11) DEFAULT NULL,
  `contract_id` int(11) DEFAULT NULL,
  `employment_status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_attendance`
--

CREATE TABLE `employee_attendance` (
  `id` int(11) NOT NULL,
  `employee_external_no` varchar(100) NOT NULL,
  `attendance_date` date NOT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `status` enum('present','absent','late','leave','half_day') DEFAULT 'present',
  `hours_worked` decimal(4,2) DEFAULT 0.00,
  `overtime_hours` decimal(4,2) DEFAULT 0.00,
  `late_minutes` int(11) DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_refs`
--

CREATE TABLE `employee_refs` (
  `id` int(11) NOT NULL,
  `external_employee_no` varchar(100) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `base_monthly_salary` decimal(12,2) DEFAULT 0.00,
  `employment_type` enum('regular','contract','part-time') DEFAULT 'regular',
  `external_source` varchar(100) DEFAULT 'HRIS',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employment_statuses`
--

CREATE TABLE `employment_statuses` (
  `employment_status_id` int(11) NOT NULL,
  `status_name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_participants`
--

CREATE TABLE `event_participants` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `rsvp_status` enum('Pending','Accepted','Declined','Maybe') DEFAULT 'Pending',
  `rsvp_date` datetime DEFAULT NULL,
  `invited_by` int(11) DEFAULT NULL,
  `notified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_categories`
--

CREATE TABLE `expense_categories` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `account_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_claims`
--

CREATE TABLE `expense_claims` (
  `id` bigint(20) NOT NULL,
  `claim_no` varchar(50) NOT NULL,
  `employee_external_no` varchar(100) NOT NULL,
  `expense_date` date NOT NULL,
  `category_id` int(11) NOT NULL,
  `amount` decimal(18,2) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('draft','submitted','approved','rejected','paid') DEFAULT 'draft',
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `payment_id` bigint(20) DEFAULT NULL,
  `journal_entry_id` bigint(20) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fiscal_periods`
--

CREATE TABLE `fiscal_periods` (
  `id` int(11) NOT NULL,
  `period_name` varchar(50) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('open','closed','locked') DEFAULT 'open',
  `closed_by` int(11) DEFAULT NULL,
  `closed_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `genders`
--

CREATE TABLE `genders` (
  `gender_id` int(11) NOT NULL,
  `gender_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `integration_logs`
--

CREATE TABLE `integration_logs` (
  `id` bigint(20) NOT NULL,
  `source_system` varchar(100) NOT NULL,
  `endpoint` varchar(200) NOT NULL,
  `request_type` varchar(20) NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
  `response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`response`)),
  `status` enum('success','error','pending') NOT NULL,
  `error_message` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `interview`
--

CREATE TABLE `interview` (
  `interview_id` int(11) NOT NULL,
  `applicant_id` int(11) DEFAULT NULL,
  `interviewer_id` int(11) DEFAULT NULL,
  `interview_date` date DEFAULT NULL,
  `interview_result` varchar(20) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `journal_entries`
--

CREATE TABLE `journal_entries` (
  `id` bigint(20) NOT NULL,
  `journal_no` varchar(50) NOT NULL,
  `journal_type_id` int(11) NOT NULL,
  `entry_date` date NOT NULL,
  `description` text DEFAULT NULL,
  `fiscal_period_id` int(11) NOT NULL,
  `reference_no` varchar(100) DEFAULT NULL,
  `total_debit` decimal(18,2) DEFAULT 0.00,
  `total_credit` decimal(18,2) DEFAULT 0.00,
  `status` enum('draft','posted','reversed','voided') DEFAULT 'draft',
  `posted_by` int(11) DEFAULT NULL,
  `posted_at` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `journal_lines`
--

CREATE TABLE `journal_lines` (
  `id` bigint(20) NOT NULL,
  `journal_entry_id` bigint(20) NOT NULL,
  `account_id` int(11) NOT NULL,
  `debit` decimal(18,2) DEFAULT 0.00,
  `credit` decimal(18,2) DEFAULT 0.00,
  `memo` varchar(255) DEFAULT NULL,
  `cost_center_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `journal_types`
--

CREATE TABLE `journal_types` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `auto_reversing` tinyint(1) DEFAULT 0,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_request`
--

CREATE TABLE `leave_request` (
  `leave_request_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `leave_type_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `total_days` int(11) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `approver_id` int(11) DEFAULT NULL,
  `date_requested` date DEFAULT NULL,
  `date_approved` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_type`
--

CREATE TABLE `leave_type` (
  `leave_type_id` int(11) NOT NULL,
  `leave_name` varchar(100) DEFAULT NULL,
  `purpose` varchar(255) DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `paid_unpaid` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `id` bigint(20) NOT NULL,
  `loan_no` varchar(50) NOT NULL,
  `loan_type_id` int(11) NOT NULL,
  `borrower_external_no` varchar(100) NOT NULL,
  `principal_amount` decimal(18,2) NOT NULL,
  `interest_rate` decimal(6,4) NOT NULL,
  `start_date` date NOT NULL,
  `term_months` int(11) NOT NULL,
  `monthly_payment` decimal(18,2) NOT NULL,
  `current_balance` decimal(18,2) DEFAULT 0.00,
  `next_payment_due` date DEFAULT NULL,
  `status` enum('pending','active','paid','defaulted','cancelled') DEFAULT 'pending',
  `application_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan_applications`
--

CREATE TABLE `loan_applications` (
  `id` int(11) NOT NULL,
  `loan_type_id` int(11) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `job` varchar(255) DEFAULT NULL,
  `monthly_salary` decimal(10,2) DEFAULT NULL,
  `user_email` varchar(255) NOT NULL,
  `loan_type` varchar(50) DEFAULT NULL,
  `loan_terms` varchar(50) DEFAULT NULL,
  `loan_amount` decimal(12,2) DEFAULT NULL,
  `purpose` text DEFAULT NULL,
  `monthly_payment` decimal(10,2) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `remarks` text DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approved_by` varchar(100) DEFAULT NULL,
  `approved_by_user_id` int(11) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `next_payment_due` date DEFAULT NULL,
  `rejected_by` varchar(255) DEFAULT NULL,
  `rejected_by_user_id` int(11) DEFAULT NULL,
  `rejected_at` datetime DEFAULT NULL,
  `rejection_remarks` text DEFAULT NULL,
  `proof_of_income` varchar(255) DEFAULT NULL,
  `coe_document` varchar(255) DEFAULT NULL,
  `pdf_path` varchar(255) DEFAULT NULL,
  `pdf_approved` varchar(255) DEFAULT NULL,
  `pdf_active` varchar(255) DEFAULT NULL,
  `pdf_rejected` varchar(255) DEFAULT NULL,
  `loan_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan_application_types`
--

CREATE TABLE `loan_application_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_application_types`
--

INSERT INTO `loan_application_types` (`id`, `name`) VALUES
(1, 'Personal Loan'),
(2, 'Car Loan'),
(3, 'Home Loan'),
(4, 'Multi-Purpose Loan');

-- --------------------------------------------------------

--
-- Table structure for table `loan_payments`
--

CREATE TABLE `loan_payments` (
  `id` bigint(20) NOT NULL,
  `loan_id` bigint(20) NOT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(18,2) NOT NULL,
  `principal_amount` decimal(18,2) NOT NULL,
  `interest_amount` decimal(18,2) NOT NULL,
  `payment_reference` varchar(100) DEFAULT NULL,
  `journal_entry_id` bigint(20) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan_types`
--

CREATE TABLE `loan_types` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `max_amount` decimal(18,2) DEFAULT NULL,
  `max_term_months` int(11) DEFAULT NULL,
  `interest_rate` decimal(6,4) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan_valid_id`
--

CREATE TABLE `loan_valid_id` (
  `id` int(11) NOT NULL,
  `valid_id_type` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loan_valid_id`
--

INSERT INTO `loan_valid_id` (`id`, `valid_id_type`) VALUES
(1, 'Driver\'s License'),
(2, 'Postal Id'),
(3, 'GSIS'),
(4, 'NBI Clearance'),
(5, 'Passport'),
(6, 'National Id'),
(7, 'UMId'),
(8, 'Voter\'s ID'),
(9, 'PRC ID'),
(10, 'Postal ID'),
(11, 'PhilHealth ID'),
(12, 'Senior Citizen ID');

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `attempt_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `attempt_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `success` tinyint(1) DEFAULT 0,
  `failure_reason` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `missions`
--

CREATE TABLE `missions` (
  `id` int(11) NOT NULL,
  `mission_text` varchar(255) NOT NULL,
  `points_value` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `onboarding`
--

CREATE TABLE `onboarding` (
  `onboarding_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `completion_status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) NOT NULL,
  `payment_no` varchar(50) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_type` enum('cash','check','bank_transfer') NOT NULL,
  `from_bank_account_id` int(11) DEFAULT NULL,
  `payee_name` varchar(150) NOT NULL,
  `amount` decimal(18,2) NOT NULL,
  `reference_no` varchar(150) DEFAULT NULL,
  `memo` text DEFAULT NULL,
  `status` enum('pending','completed','failed','voided') DEFAULT 'pending',
  `journal_entry_id` bigint(20) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_payslips`
--

CREATE TABLE `payroll_payslips` (
  `payslip_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `pay_period_start` date DEFAULT NULL,
  `pay_period_end` date DEFAULT NULL,
  `gross_salary` decimal(10,2) DEFAULT NULL,
  `deduction` decimal(10,2) DEFAULT NULL,
  `net_pay` decimal(10,2) DEFAULT NULL,
  `release_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_periods`
--

CREATE TABLE `payroll_periods` (
  `id` int(11) NOT NULL,
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `frequency` enum('monthly','semimonthly','weekly') DEFAULT 'semimonthly',
  `status` enum('open','processing','posted','paid') DEFAULT 'open',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_runs`
--

CREATE TABLE `payroll_runs` (
  `id` int(11) NOT NULL,
  `payroll_period_id` int(11) NOT NULL,
  `run_by_user_id` int(11) NOT NULL,
  `run_at` datetime DEFAULT current_timestamp(),
  `total_gross` decimal(18,2) DEFAULT 0.00,
  `total_deductions` decimal(18,2) DEFAULT 0.00,
  `total_net` decimal(18,2) DEFAULT 0.00,
  `status` enum('draft','finalized','exported','completed') DEFAULT 'draft',
  `journal_entry_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payslips`
--

CREATE TABLE `payslips` (
  `id` bigint(20) NOT NULL,
  `payroll_run_id` int(11) NOT NULL,
  `employee_external_no` varchar(100) NOT NULL,
  `gross_pay` decimal(18,2) DEFAULT 0.00,
  `total_deductions` decimal(18,2) DEFAULT 0.00,
  `net_pay` decimal(18,2) DEFAULT 0.00,
  `payslip_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payslip_json`)),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phones`
--

CREATE TABLE `phones` (
  `phone_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `phone_number` varchar(30) NOT NULL,
  `phone_type` varchar(20) DEFAULT 'mobile',
  `is_primary` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `points_history`
--

CREATE TABLE `points_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `points` decimal(10,2) NOT NULL,
  `description` varchar(255) NOT NULL,
  `transaction_type` enum('mission','redemption','referral','bonus') DEFAULT 'mission',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `position`
--

CREATE TABLE `position` (
  `position_id` int(11) NOT NULL,
  `position_title` varchar(100) NOT NULL,
  `job_description` varchar(255) DEFAULT NULL,
  `salary_grade` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

CREATE TABLE `provinces` (
  `province_id` int(11) NOT NULL,
  `province_name` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL DEFAULT 'Philippines',
  `region` varchar(100) DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `recruitment`
--

CREATE TABLE `recruitment` (
  `recruitment_id` int(11) NOT NULL,
  `job_title` varchar(100) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `date_posted` date DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `posted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `id` int(11) NOT NULL,
  `referrer_id` int(11) NOT NULL COMMENT 'Customer who referred',
  `referred_id` int(11) NOT NULL COMMENT 'Customer who was referred',
  `points_earned` decimal(10,2) DEFAULT 0.00 COMMENT 'Points earned by referrer',
  `status` enum('pending','completed','cancelled') DEFAULT 'completed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `attendance_summary` text DEFAULT NULL,
  `recruitment_summary` text DEFAULT NULL,
  `leave_summary` text DEFAULT NULL,
  `payroll_summary` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_change_log`
--

CREATE TABLE `role_change_log` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `changed_by` int(11) NOT NULL,
  `old_role` varchar(20) DEFAULT NULL,
  `new_role` varchar(20) NOT NULL,
  `old_department_id` int(11) DEFAULT NULL,
  `new_department_id` int(11) DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salary_components`
--

CREATE TABLE `salary_components` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('earning','deduction','tax','employer_contrib') NOT NULL,
  `calculation_method` enum('fixed','percent','per_hour','formula') DEFAULT 'fixed',
  `value` decimal(15,4) DEFAULT 0.0000,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_fee_charges`
--

CREATE TABLE `service_fee_charges` (
  `fee_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `fee_amount` decimal(10,2) NOT NULL,
  `balance_before` decimal(10,2) NOT NULL,
  `balance_after` decimal(10,2) NOT NULL,
  `charge_date` date NOT NULL,
  `fee_type` enum('monthly_service_fee','below_maintaining_fee') DEFAULT 'monthly_service_fee',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `site_content`
--

CREATE TABLE `site_content` (
  `content_id` int(11) NOT NULL,
  `content_key` varchar(100) NOT NULL,
  `content_value` text NOT NULL,
  `content_type` enum('text','image','html') DEFAULT 'text',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_content`
--

INSERT INTO `site_content` (`content_id`, `content_key`, `content_value`, `content_type`, `updated_at`, `updated_by`) VALUES
(1, 'company_name', 'EVERGREEN', 'text', '2026-03-08 23:37:34', 1),
(2, 'company_logo', 'images/Logo.png.png', 'image', '2026-03-08 23:35:58', 1),
(3, 'hero_title', 'Banking that grows with you', 'text', '2026-03-09 09:22:31', 1),
(4, 'hero_description', 'Your trusted financial partner for a prosperous future.', 'text', '2026-03-08 10:06:34', NULL),
(5, 'about_description', 'Evergreen Bank has been serving customers for over 20 years with dedication and excellence.', 'html', '2026-03-08 10:26:16', 1),
(6, 'banner_image', 'images/hero-main.png', 'image', '2026-03-08 10:06:34', NULL),
(7, 'contact_phone', '09123456789', 'text', '2026-03-08 10:20:43', 1),
(8, 'contact_email', 'evrgrn.64@gmail.com', 'text', '2026-03-08 10:06:34', NULL),
(25, 'credit_card_title', 'Evergreen Credit Card', 'text', '2026-03-08 10:31:19', NULL),
(26, 'credit_card_description', 'Enjoy exclusive rewards and benefits with our premium credit card.', 'html', '2026-03-08 10:31:19', NULL),
(27, 'credit_card_features', 'Up to 5% cashback on all purchases|No annual fee for the first year|Travel insurance included|24/7 customer support', 'text', '2026-03-08 10:31:19', NULL),
(28, 'credit_card_image', 'images/card.png', 'image', '2026-03-08 10:31:19', NULL),
(29, 'debit_card_title', 'Evergreen Debit Card', 'text', '2026-03-08 10:31:19', NULL),
(30, 'debit_card_description', 'Access your money anytime, anywhere with our secure debit card.', 'html', '2026-03-08 10:32:46', 1),
(31, 'debit_card_features', 'Free ATM withdrawals nationwide|Contactless payment enabled|Real-time transaction alerts|Zero liability protection', 'text', '2026-03-08 10:31:19', NULL),
(32, 'debit_card_image', 'images/card.png', 'image', '2026-03-08 10:31:19', NULL),
(33, 'prepaid_card_title', 'Evergreen Prepaid Card', 'text', '2026-03-08 10:31:19', NULL),
(34, 'prepaid_card_description', 'Control your spending with our flexible prepaid card solution.', 'html', '2026-03-08 10:31:19', NULL),
(35, 'prepaid_card_features', 'No credit check required|Reload anytime online or in-store|Budget-friendly spending control|Accepted worldwide', 'text', '2026-03-08 10:31:19', NULL),
(36, 'prepaid_card_image', 'images/card.png', 'image', '2026-03-08 10:31:19', NULL),
(45, 'company_address', '673 Quirino Highway, San Bartolome, Novaliches, Quezon City, 1116 Philippines', 'text', '2026-03-08 15:27:05', 1),
(46, 'company_phone', '09123456789', 'text', '2026-03-08 15:27:05', 1),
(47, 'company_email', 'evrgrn.64@gmail.com', 'text', '2026-03-08 15:27:05', 1),
(48, 'hero_paragraph', 'Secure financial solutions for every stage of your life journey. Invest, save, and achieve your goals with Evergreen.', 'text', '2026-03-09 09:12:09', 1),
(59, 'hero_card_title', 'Banking at your fingertips', 'text', '2026-03-09 09:28:06', NULL),
(60, 'hero_card_description', 'Experience our award-winning digital banking platform designed for your convenience.', 'text', '2026-03-09 09:28:06', NULL),
(61, 'hero_card_image', 'images/hero-image.png', 'image', '2026-03-09 09:28:06', NULL),
(62, 'solutions_title', 'Financial Solutions for Every Need', 'text', '2026-03-09 09:28:06', NULL),
(63, 'solutions_intro', 'Discover our comprehensive range of banking products designed to support your financial journey.', 'text', '2026-03-09 09:28:06', NULL),
(64, 'solution_1_icon', '💳', 'text', '2026-03-09 09:28:06', NULL),
(65, 'solution_1_title', 'Everyday Banking', 'text', '2026-03-09 09:28:06', NULL),
(66, 'solution_1_description', 'Fee-free checking accounts with premium benefits and rewards on everyday spending.', 'text', '2026-03-09 09:28:06', NULL),
(67, 'solution_2_icon', '🏦', 'text', '2026-03-09 09:28:06', NULL),
(68, 'solution_2_title', 'Savings & Deposits', 'text', '2026-03-09 09:28:06', NULL),
(69, 'solution_2_description', 'High-yield savings accounts and CDs to help your money grow faster.', 'text', '2026-03-09 09:28:06', NULL),
(70, 'solution_3_icon', '📈', 'text', '2026-03-09 09:28:06', NULL),
(71, 'solution_3_title', 'Investments', 'text', '2026-03-09 09:28:06', NULL),
(72, 'solution_3_description', 'Personalized investment strategies aligned with your financial goals.', 'text', '2026-03-09 09:28:06', NULL),
(73, 'solution_4_icon', '🏠', 'text', '2026-03-09 09:28:06', NULL),
(74, 'solution_4_title', 'Home Loans', 'text', '2026-03-09 09:28:06', NULL),
(75, 'solution_4_description', 'Competitive mortgage rates and flexible repayment options for your dream home.', 'text', '2026-03-09 09:28:06', NULL),
(76, 'rewards_title', 'Get a Card to get some Awesome Rewards!', 'text', '2026-03-09 09:28:06', NULL),
(77, 'rewards_description', 'Open an account with us today and enjoy exclusive rewards, special offers, and member-only perks designed to make your banking more rewarding.', 'text', '2026-03-09 09:28:06', NULL),
(78, 'rewards_button_text', 'Learn More', 'text', '2026-03-09 09:28:06', NULL),
(79, 'rewards_image', 'images/card.png', 'image', '2026-03-09 09:28:06', NULL),
(80, 'loans_title', 'LOAN SERVICES WE OFFER', 'text', '2026-03-09 09:28:06', NULL),
(81, 'loan_1_title', 'Personal Loan', 'text', '2026-03-09 09:28:06', NULL),
(82, 'loan_1_description', 'Stop worrying and bring your plans to life.', 'text', '2026-03-09 09:28:06', NULL),
(83, 'loan_1_image', 'images/personalloan.png', 'image', '2026-03-09 09:28:06', NULL),
(84, 'loan_2_title', 'Auto Loan', 'text', '2026-03-09 09:28:06', NULL),
(85, 'loan_2_description', 'Drive your new car with low rates and fast approval.', 'text', '2026-03-09 09:28:06', NULL),
(86, 'loan_2_image', 'images/autoloan.png', 'image', '2026-03-09 09:28:06', NULL),
(87, 'loan_3_title', 'Home Loan', 'text', '2026-03-09 09:28:06', NULL),
(88, 'loan_3_description', 'Take the next step to your new home property to fund your various needs.', 'text', '2026-03-09 09:28:06', NULL),
(89, 'loan_3_image', 'images/homeloan.png', 'image', '2026-03-09 09:28:06', NULL),
(90, 'loan_4_title', 'Multipurpose Loan', 'text', '2026-03-09 09:28:06', NULL),
(91, 'loan_4_description', 'Carry on with your plans. Use your property to fund your various needs.', 'text', '2026-03-09 09:28:06', NULL),
(92, 'loan_4_image', 'images/multipurposeloan.png', 'image', '2026-03-09 09:28:06', NULL),
(93, 'career_title', 'Build a Meaningful Career in the World of Banking!', 'text', '2026-03-09 09:28:06', NULL),
(94, 'career_intro', 'At Evergreen Bank, we believe that our employees are the heart of our success. We\'re looking for dedicated, skilled, and passionate individuals who are ready to grow with us. Whether you\'re an experienced banker or a fresh graduate eager to learn, we provide a supportive environment where your talents can thrive and your career can flourish.', 'html', '2026-03-09 09:42:29', 1),
(95, 'career_how_to_apply_title', 'How to apply?', 'text', '2026-03-09 09:28:06', NULL),
(96, 'career_how_to_apply_text', 'Interested applicants are encouraged to personally visit our branch to submit their application. Please bring the following requirements and apply directly at Evergreen Bank\'s Human Resources Department.', 'text', '2026-03-09 09:28:06', NULL),
(97, 'career_location_title', 'Where to Apply:', 'text', '2026-03-09 09:28:06', NULL),
(98, 'career_location_address', 'Evergreen Bank Main Branch<br>123 Evergreen Avenue, City Center', 'html', '2026-03-09 09:28:06', NULL),
(99, 'career_requirements_title', 'Requirements:', 'text', '2026-03-09 09:28:06', NULL),
(100, 'career_note', 'Walk-in applicants are welcome. Our HR team will be glad to assist you with the next steps in your application process.', 'text', '2026-03-09 09:28:06', NULL),
(101, 'career_image', 'images/recruit.png', 'image', '2026-03-09 09:28:06', NULL),
(102, 'footer_tagline', 'Secure. Invest. Achieve. Your trusted financial partner for a prosperous future.', 'text', '2026-03-09 09:28:06', NULL),
(103, 'footer_address', '673 Quirino Highway, San Bartolome, Novaliches, Quezon City, 1116 Philippines', 'html', '2026-03-09 09:29:45', 1),
(104, 'footer_copyright', '© 2023 Evergreen Bank. All rights reserved.<br>Member FDIC. Equal Housing Lender. Evergreen Bank, N.A.', 'html', '2026-03-09 09:28:06', NULL),
(105, 'social_facebook_url', 'https://www.facebook.com/profile.php?id=61582812214198', 'text', '2026-03-09 09:28:06', NULL),
(106, 'social_instagram_url', 'https://www.instagram.com/evergreenbanking/', 'text', '2026-03-09 09:28:06', NULL),
(107, 'nav_home_text', 'Home', 'text', '2026-03-09 09:28:06', NULL),
(108, 'nav_cards_text', 'Cards', 'text', '2026-03-09 09:28:06', NULL),
(109, 'nav_whatsnew_text', 'What\'s new', 'text', '2026-03-09 09:28:06', NULL),
(110, 'nav_about_text', 'About Us', 'text', '2026-03-09 09:28:06', NULL),
(111, 'btn_learn_more', 'Learn More', 'text', '2026-03-09 09:28:06', NULL),
(112, 'btn_open_account', 'Open an Account', 'text', '2026-03-09 09:28:06', NULL),
(113, 'btn_get_started', 'Get Started', 'text', '2026-03-09 09:28:06', NULL),
(114, 'btn_login', 'Login', 'text', '2026-03-09 09:28:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `source_of_funds`
--

CREATE TABLE `source_of_funds` (
  `source_id` int(11) NOT NULL,
  `source_name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `requires_proof` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_logs`
--

CREATE TABLE `system_logs` (
  `log_id` int(11) NOT NULL,
  `log_level` enum('DEBUG','INFO','WARNING','ERROR','CRITICAL') NOT NULL,
  `log_type` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `details` text DEFAULT NULL,
  `request_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`request_data`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_types`
--

CREATE TABLE `transaction_types` (
  `transaction_type_id` int(11) NOT NULL,
  `type_name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_account`
--

CREATE TABLE `user_account` (
  `user_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_account`
--

INSERT INTO `user_account` (`user_id`, `employee_id`, `username`, `password_hash`, `role`, `last_login`) VALUES
(1, 1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', NULL),
(2, NULL, 'hrmanager', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'HR Manager', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_missions`
--

CREATE TABLE `user_missions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `mission_id` int(11) NOT NULL,
  `points_earned` decimal(10,2) NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_account_balances`
-- (See below for the actual view)
--
CREATE TABLE `v_account_balances` (
`code` varchar(50)
,`name` varchar(150)
,`account_type` enum('asset','liability','equity','revenue','expense')
,`fiscal_period_id` int(11)
,`period_name` varchar(50)
,`opening_balance` decimal(18,2)
,`debit_movements` decimal(18,2)
,`credit_movements` decimal(18,2)
,`closing_balance` decimal(18,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_journal_summary`
-- (See below for the actual view)
--
CREATE TABLE `v_journal_summary` (
`journal_no` varchar(50)
,`entry_date` date
,`journal_type` varchar(50)
,`description` text
,`total_debit` decimal(18,2)
,`total_credit` decimal(18,2)
,`status` enum('draft','posted','reversed','voided')
,`created_by` varchar(50)
,`created_at` datetime
);

-- --------------------------------------------------------

--
-- Structure for view `v_account_balances`
--
DROP TABLE IF EXISTS `v_account_balances`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_account_balances`  AS SELECT `a`.`code` AS `code`, `a`.`name` AS `name`, `at`.`category` AS `account_type`, `ab`.`fiscal_period_id` AS `fiscal_period_id`, `fp`.`period_name` AS `period_name`, `ab`.`opening_balance` AS `opening_balance`, `ab`.`debit_movements` AS `debit_movements`, `ab`.`credit_movements` AS `credit_movements`, `ab`.`closing_balance` AS `closing_balance` FROM (((`accounts` `a` join `account_types` `at` on(`a`.`type_id` = `at`.`id`)) join `account_balances` `ab` on(`a`.`id` = `ab`.`account_id`)) join `fiscal_periods` `fp` on(`ab`.`fiscal_period_id` = `fp`.`id`)) WHERE `a`.`is_active` = 1 ;

-- --------------------------------------------------------

--
-- Structure for view `v_journal_summary`
--
DROP TABLE IF EXISTS `v_journal_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_journal_summary`  AS SELECT `je`.`journal_no` AS `journal_no`, `je`.`entry_date` AS `entry_date`, `jt`.`name` AS `journal_type`, `je`.`description` AS `description`, `je`.`total_debit` AS `total_debit`, `je`.`total_credit` AS `total_credit`, `je`.`status` AS `status`, `u`.`username` AS `created_by`, `je`.`created_at` AS `created_at` FROM ((`journal_entries` `je` join `journal_types` `jt` on(`je`.`journal_type_id` = `jt`.`id`)) join `users` `u` on(`je`.`created_by` = `u`.`id`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `parent_account_id` (`parent_account_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_code` (`code`),
  ADD KEY `idx_type_id` (`type_id`);

--
-- Indexes for table `account_applications`
--
ALTER TABLE `account_applications`
  ADD PRIMARY KEY (`application_id`),
  ADD UNIQUE KEY `application_number` (`application_number`),
  ADD KEY `idx_application_number` (`application_number`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_status` (`application_status`),
  ADD KEY `idx_submitted_at` (`submitted_at`);

--
-- Indexes for table `account_balances`
--
ALTER TABLE `account_balances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account_id` (`account_id`,`fiscal_period_id`),
  ADD KEY `fiscal_period_id` (`fiscal_period_id`);

--
-- Indexes for table `account_status_history`
--
ALTER TABLE `account_status_history`
  ADD PRIMARY KEY (`status_history_id`),
  ADD KEY `changed_by` (`changed_by`),
  ADD KEY `idx_account_status` (`account_id`,`created_at`);

--
-- Indexes for table `account_types`
--
ALTER TABLE `account_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `idx_customer_id` (`customer_id`),
  ADD KEY `idx_barangay_id` (`barangay_id`),
  ADD KEY `idx_city_id` (`city_id`),
  ADD KEY `idx_province_id` (`province_id`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `advertisements`
--
ALTER TABLE `advertisements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `applicant`
--
ALTER TABLE `applicant`
  ADD PRIMARY KEY (`applicant_id`),
  ADD UNIQUE KEY `offer_token` (`offer_token`),
  ADD KEY `idx_recruitment_id` (`recruitment_id`),
  ADD KEY `idx_offer_token` (`offer_token`);

--
-- Indexes for table `application_documents`
--
ALTER TABLE `application_documents`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `idx_application_id` (`application_id`),
  ADD KEY `idx_document_type` (`document_type`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `idx_date` (`date`),
  ADD KEY `idx_employee_date` (`employee_id`,`date`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_object_type` (`object_type`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD UNIQUE KEY `bank_name` (`bank_name`,`account_number`),
  ADD KEY `idx_code` (`code`);

--
-- Indexes for table `bank_account_types`
--
ALTER TABLE `bank_account_types`
  ADD PRIMARY KEY (`account_type_id`);

--
-- Indexes for table `bank_customers`
--
ALTER TABLE `bank_customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `idx_email` (`email`),
  ADD UNIQUE KEY `idx_referral_code` (`referral_code`),
  ADD KEY `idx_created_by_employee_id` (`created_by_employee_id`),
  ADD KEY `idx_referred_by` (`referred_by_customer_id`),
  ADD KEY `idx_bank_id` (`bank_id`);

--
-- Indexes for table `bank_employees`
--
ALTER TABLE `bank_employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `idx_username` (`username`),
  ADD UNIQUE KEY `idx_email` (`email`);

--
-- Indexes for table `bank_transactions`
--
ALTER TABLE `bank_transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `idx_account_id` (`account_id`),
  ADD KEY `idx_related_account_id` (`related_account_id`),
  ADD KEY `idx_transaction_type_id` (`transaction_type_id`),
  ADD KEY `idx_employee_id` (`employee_id`);

--
-- Indexes for table `bank_users`
--
ALTER TABLE `bank_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_bank_id` (`bank_id`);

--
-- Indexes for table `barangays`
--
ALTER TABLE `barangays`
  ADD PRIMARY KEY (`barangay_id`),
  ADD KEY `idx_barangay_name` (`barangay_name`),
  ADD KEY `idx_city_id` (`city_id`);

--
-- Indexes for table `card_applications`
--
ALTER TABLE `card_applications`
  ADD PRIMARY KEY (`application_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `status` (`status`),
  ADD KEY `reviewed_by` (`reviewed_by`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`city_id`),
  ADD KEY `idx_city_name` (`city_name`),
  ADD KEY `idx_province_id` (`province_id`);

--
-- Indexes for table `compliance_reports`
--
ALTER TABLE `compliance_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `generated_by` (`generated_by`),
  ADD KEY `idx_report_type` (`report_type`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `contract`
--
ALTER TABLE `contract`
  ADD PRIMARY KEY (`contract_id`),
  ADD KEY `idx_employee_id` (`employee_id`);

--
-- Indexes for table `customer_accounts`
--
ALTER TABLE `customer_accounts`
  ADD PRIMARY KEY (`account_id`),
  ADD UNIQUE KEY `account_number` (`account_number`),
  ADD KEY `idx_customer_id` (`customer_id`),
  ADD KEY `idx_account_type_id` (`account_type_id`),
  ADD KEY `idx_created_by_employee_id` (`created_by_employee_id`);

--
-- Indexes for table `customer_linked_accounts`
--
ALTER TABLE `customer_linked_accounts`
  ADD PRIMARY KEY (`link_id`),
  ADD UNIQUE KEY `customer_id` (`customer_id`,`account_id`),
  ADD KEY `idx_account_id` (`account_id`);

--
-- Indexes for table `customer_profiles`
--
ALTER TABLE `customer_profiles`
  ADD PRIMARY KEY (`profile_id`),
  ADD KEY `idx_customer_id` (`customer_id`),
  ADD KEY `idx_gender_id` (`gender_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `emails`
--
ALTER TABLE `emails`
  ADD PRIMARY KEY (`email_id`),
  ADD UNIQUE KEY `customer_id` (`customer_id`,`email`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`employee_id`),
  ADD KEY `idx_department_id` (`department_id`),
  ADD KEY `idx_position_id` (`position_id`),
  ADD KEY `idx_employment_status` (`employment_status`);

--
-- Indexes for table `employee_attendance`
--
ALTER TABLE `employee_attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_employee_date` (`employee_external_no`,`attendance_date`);

--
-- Indexes for table `employee_refs`
--
ALTER TABLE `employee_refs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `external_employee_no` (`external_employee_no`,`external_source`),
  ADD KEY `idx_external_no` (`external_employee_no`);

--
-- Indexes for table `employment_statuses`
--
ALTER TABLE `employment_statuses`
  ADD PRIMARY KEY (`employment_status_id`),
  ADD UNIQUE KEY `status_name` (`status_name`);

--
-- Indexes for table `event_participants`
--
ALTER TABLE `event_participants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_participant` (`event_id`,`employee_id`),
  ADD KEY `idx_event_id` (`event_id`),
  ADD KEY `idx_employee_id` (`employee_id`),
  ADD KEY `idx_rsvp_status` (`rsvp_status`),
  ADD KEY `invited_by` (`invited_by`);

--
-- Indexes for table `expense_categories`
--
ALTER TABLE `expense_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `idx_code` (`code`);

--
-- Indexes for table `expense_claims`
--
ALTER TABLE `expense_claims`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `claim_no` (`claim_no`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `approved_by` (`approved_by`),
  ADD KEY `payment_id` (`payment_id`),
  ADD KEY `journal_entry_id` (`journal_entry_id`),
  ADD KEY `idx_claim_no` (`claim_no`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `fiscal_periods`
--
ALTER TABLE `fiscal_periods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `start_date` (`start_date`,`end_date`),
  ADD KEY `closed_by` (`closed_by`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `genders`
--
ALTER TABLE `genders`
  ADD PRIMARY KEY (`gender_id`),
  ADD UNIQUE KEY `gender_name` (`gender_name`);

--
-- Indexes for table `integration_logs`
--
ALTER TABLE `integration_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `interview`
--
ALTER TABLE `interview`
  ADD PRIMARY KEY (`interview_id`),
  ADD KEY `idx_applicant_id` (`applicant_id`),
  ADD KEY `idx_interviewer_id` (`interviewer_id`);

--
-- Indexes for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `journal_no` (`journal_no`),
  ADD KEY `journal_type_id` (`journal_type_id`),
  ADD KEY `fiscal_period_id` (`fiscal_period_id`),
  ADD KEY `posted_by` (`posted_by`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_journal_no` (`journal_no`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_entry_date` (`entry_date`);

--
-- Indexes for table `journal_lines`
--
ALTER TABLE `journal_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `idx_journal_entry_id` (`journal_entry_id`);

--
-- Indexes for table `journal_types`
--
ALTER TABLE `journal_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `idx_code` (`code`);

--
-- Indexes for table `leave_request`
--
ALTER TABLE `leave_request`
  ADD PRIMARY KEY (`leave_request_id`),
  ADD KEY `idx_employee_id` (`employee_id`),
  ADD KEY `idx_leave_type_id` (`leave_type_id`),
  ADD KEY `idx_leave_status_date` (`employee_id`,`status`,`start_date`,`end_date`);

--
-- Indexes for table `leave_type`
--
ALTER TABLE `leave_type`
  ADD PRIMARY KEY (`leave_type_id`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `loan_no` (`loan_no`),
  ADD KEY `loan_type_id` (`loan_type_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_loan_no` (`loan_no`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_application_id` (`application_id`);

--
-- Indexes for table `loan_applications`
--
ALTER TABLE `loan_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_email` (`user_email`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_loan_type_id` (`loan_type_id`),
  ADD KEY `idx_approved_by_user_id` (`approved_by_user_id`),
  ADD KEY `idx_rejected_by_user_id` (`rejected_by_user_id`),
  ADD KEY `idx_loan_id` (`loan_id`);

--
-- Indexes for table `loan_application_types`
--
ALTER TABLE `loan_application_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_payments`
--
ALTER TABLE `loan_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `journal_entry_id` (`journal_entry_id`),
  ADD KEY `idx_loan_id` (`loan_id`),
  ADD KEY `idx_payment_date` (`payment_date`);

--
-- Indexes for table `loan_types`
--
ALTER TABLE `loan_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `idx_code` (`code`);

--
-- Indexes for table `loan_valid_id`
--
ALTER TABLE `loan_valid_id`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`attempt_id`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_ip_address` (`ip_address`),
  ADD KEY `idx_attempt_time` (`attempt_time`);

--
-- Indexes for table `missions`
--
ALTER TABLE `missions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `onboarding`
--
ALTER TABLE `onboarding`
  ADD PRIMARY KEY (`onboarding_id`),
  ADD KEY `idx_employee_id` (`employee_id`),
  ADD KEY `idx_department_id` (`department_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_no` (`payment_no`),
  ADD KEY `from_bank_account_id` (`from_bank_account_id`),
  ADD KEY `journal_entry_id` (`journal_entry_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_payment_no` (`payment_no`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `payroll_payslips`
--
ALTER TABLE `payroll_payslips`
  ADD PRIMARY KEY (`payslip_id`),
  ADD KEY `idx_employee_id` (`employee_id`);

--
-- Indexes for table `payroll_periods`
--
ALTER TABLE `payroll_periods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `period_start` (`period_start`,`period_end`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `payroll_runs`
--
ALTER TABLE `payroll_runs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payroll_period_id` (`payroll_period_id`),
  ADD KEY `run_by_user_id` (`run_by_user_id`),
  ADD KEY `journal_entry_id` (`journal_entry_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `payslips`
--
ALTER TABLE `payslips`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payroll_run_id` (`payroll_run_id`),
  ADD KEY `idx_employee_external_no` (`employee_external_no`);

--
-- Indexes for table `phones`
--
ALTER TABLE `phones`
  ADD PRIMARY KEY (`phone_id`),
  ADD UNIQUE KEY `customer_id` (`customer_id`,`phone_number`);

--
-- Indexes for table `points_history`
--
ALTER TABLE `points_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`position_id`);

--
-- Indexes for table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`province_id`),
  ADD KEY `idx_province_name` (`province_name`);

--
-- Indexes for table `recruitment`
--
ALTER TABLE `recruitment`
  ADD PRIMARY KEY (`recruitment_id`),
  ADD KEY `idx_department_id` (`department_id`);

--
-- Indexes for table `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_referral` (`referrer_id`,`referred_id`),
  ADD KEY `idx_referrer_id` (`referrer_id`),
  ADD KEY `idx_referred_id` (`referred_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `role_change_log`
--
ALTER TABLE `role_change_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_changed_at` (`changed_at`),
  ADD KEY `changed_by` (`changed_by`),
  ADD KEY `old_department_id` (`old_department_id`),
  ADD KEY `new_department_id` (`new_department_id`);

--
-- Indexes for table `salary_components`
--
ALTER TABLE `salary_components`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `idx_type` (`type`);

--
-- Indexes for table `service_fee_charges`
--
ALTER TABLE `service_fee_charges`
  ADD PRIMARY KEY (`fee_id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `idx_account_date` (`account_id`,`charge_date`);

--
-- Indexes for table `site_content`
--
ALTER TABLE `site_content`
  ADD PRIMARY KEY (`content_id`),
  ADD UNIQUE KEY `content_key` (`content_key`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `source_of_funds`
--
ALTER TABLE `source_of_funds`
  ADD PRIMARY KEY (`source_id`),
  ADD UNIQUE KEY `source_name` (`source_name`);

--
-- Indexes for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_log_level` (`log_level`),
  ADD KEY `idx_log_type` (`log_type`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_employee_id` (`employee_id`);

--
-- Indexes for table `transaction_types`
--
ALTER TABLE `transaction_types`
  ADD PRIMARY KEY (`transaction_type_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `user_account`
--
ALTER TABLE `user_account`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_employee_id` (`employee_id`);

--
-- Indexes for table `user_missions`
--
ALTER TABLE `user_missions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_mission` (`user_id`,`mission_id`),
  ADD KEY `idx_mission_id` (`mission_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `account_applications`
--
ALTER TABLE `account_applications`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `account_balances`
--
ALTER TABLE `account_balances`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `account_status_history`
--
ALTER TABLE `account_status_history`
  MODIFY `status_history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `account_types`
--
ALTER TABLE `account_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `advertisements`
--
ALTER TABLE `advertisements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `applicant`
--
ALTER TABLE `applicant`
  MODIFY `applicant_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `application_documents`
--
ALTER TABLE `application_documents`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank_account_types`
--
ALTER TABLE `bank_account_types`
  MODIFY `account_type_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank_customers`
--
ALTER TABLE `bank_customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank_employees`
--
ALTER TABLE `bank_employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank_transactions`
--
ALTER TABLE `bank_transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank_users`
--
ALTER TABLE `bank_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `barangays`
--
ALTER TABLE `barangays`
  MODIFY `barangay_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `card_applications`
--
ALTER TABLE `card_applications`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `city_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `compliance_reports`
--
ALTER TABLE `compliance_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contract`
--
ALTER TABLE `contract`
  MODIFY `contract_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_accounts`
--
ALTER TABLE `customer_accounts`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_linked_accounts`
--
ALTER TABLE `customer_linked_accounts`
  MODIFY `link_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_profiles`
--
ALTER TABLE `customer_profiles`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emails`
--
ALTER TABLE `emails`
  MODIFY `email_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_attendance`
--
ALTER TABLE `employee_attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_refs`
--
ALTER TABLE `employee_refs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employment_statuses`
--
ALTER TABLE `employment_statuses`
  MODIFY `employment_status_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_participants`
--
ALTER TABLE `event_participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_categories`
--
ALTER TABLE `expense_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_claims`
--
ALTER TABLE `expense_claims`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fiscal_periods`
--
ALTER TABLE `fiscal_periods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `genders`
--
ALTER TABLE `genders`
  MODIFY `gender_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `integration_logs`
--
ALTER TABLE `integration_logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `interview`
--
ALTER TABLE `interview`
  MODIFY `interview_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journal_entries`
--
ALTER TABLE `journal_entries`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journal_lines`
--
ALTER TABLE `journal_lines`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journal_types`
--
ALTER TABLE `journal_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_request`
--
ALTER TABLE `leave_request`
  MODIFY `leave_request_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_type`
--
ALTER TABLE `leave_type`
  MODIFY `leave_type_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_applications`
--
ALTER TABLE `loan_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_payments`
--
ALTER TABLE `loan_payments`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_types`
--
ALTER TABLE `loan_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `attempt_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `missions`
--
ALTER TABLE `missions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `onboarding`
--
ALTER TABLE `onboarding`
  MODIFY `onboarding_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll_payslips`
--
ALTER TABLE `payroll_payslips`
  MODIFY `payslip_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll_periods`
--
ALTER TABLE `payroll_periods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll_runs`
--
ALTER TABLE `payroll_runs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payslips`
--
ALTER TABLE `payslips`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phones`
--
ALTER TABLE `phones`
  MODIFY `phone_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `points_history`
--
ALTER TABLE `points_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `position`
--
ALTER TABLE `position`
  MODIFY `position_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provinces`
--
ALTER TABLE `provinces`
  MODIFY `province_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recruitment`
--
ALTER TABLE `recruitment`
  MODIFY `recruitment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role_change_log`
--
ALTER TABLE `role_change_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salary_components`
--
ALTER TABLE `salary_components`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_fee_charges`
--
ALTER TABLE `service_fee_charges`
  MODIFY `fee_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `site_content`
--
ALTER TABLE `site_content`
  MODIFY `content_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `source_of_funds`
--
ALTER TABLE `source_of_funds`
  MODIFY `source_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaction_types`
--
ALTER TABLE `transaction_types`
  MODIFY `transaction_type_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_account`
--
ALTER TABLE `user_account`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_missions`
--
ALTER TABLE `user_missions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `account_types` (`id`),
  ADD CONSTRAINT `accounts_ibfk_2` FOREIGN KEY (`parent_account_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `accounts_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `account_balances`
--
ALTER TABLE `account_balances`
  ADD CONSTRAINT `account_balances_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `account_balances_ibfk_2` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`);

--
-- Constraints for table `account_status_history`
--
ALTER TABLE `account_status_history`
  ADD CONSTRAINT `account_status_history_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `customer_accounts` (`account_id`),
  ADD CONSTRAINT `account_status_history_ibfk_2` FOREIGN KEY (`changed_by`) REFERENCES `bank_employees` (`employee_id`) ON DELETE SET NULL;

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `bank_customers` (`customer_id`),
  ADD CONSTRAINT `addresses_ibfk_2` FOREIGN KEY (`barangay_id`) REFERENCES `barangays` (`barangay_id`),
  ADD CONSTRAINT `addresses_ibfk_3` FOREIGN KEY (`city_id`) REFERENCES `cities` (`city_id`),
  ADD CONSTRAINT `addresses_ibfk_4` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`province_id`);

--
-- Constraints for table `applicant`
--
ALTER TABLE `applicant`
  ADD CONSTRAINT `applicant_ibfk_1` FOREIGN KEY (`recruitment_id`) REFERENCES `recruitment` (`recruitment_id`);

--
-- Constraints for table `application_documents`
--
ALTER TABLE `application_documents`
  ADD CONSTRAINT `application_documents_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `account_applications` (`application_id`) ON DELETE CASCADE;

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`) ON DELETE CASCADE;

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `bank_customers`
--
ALTER TABLE `bank_customers`
  ADD CONSTRAINT `fk_referred_by` FOREIGN KEY (`referred_by_customer_id`) REFERENCES `bank_customers` (`customer_id`) ON DELETE SET NULL;

--
-- Constraints for table `bank_transactions`
--
ALTER TABLE `bank_transactions`
  ADD CONSTRAINT `bank_transactions_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `customer_accounts` (`account_id`),
  ADD CONSTRAINT `bank_transactions_ibfk_2` FOREIGN KEY (`related_account_id`) REFERENCES `customer_accounts` (`account_id`),
  ADD CONSTRAINT `bank_transactions_ibfk_3` FOREIGN KEY (`transaction_type_id`) REFERENCES `transaction_types` (`transaction_type_id`),
  ADD CONSTRAINT `bank_transactions_ibfk_4` FOREIGN KEY (`employee_id`) REFERENCES `bank_employees` (`employee_id`);

--
-- Constraints for table `barangays`
--
ALTER TABLE `barangays`
  ADD CONSTRAINT `barangays_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`city_id`) ON DELETE CASCADE;

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `cities_ibfk_1` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`province_id`) ON DELETE CASCADE;

--
-- Constraints for table `compliance_reports`
--
ALTER TABLE `compliance_reports`
  ADD CONSTRAINT `compliance_reports_ibfk_1` FOREIGN KEY (`generated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `contract`
--
ALTER TABLE `contract`
  ADD CONSTRAINT `contract_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_accounts`
--
ALTER TABLE `customer_accounts`
  ADD CONSTRAINT `customer_accounts_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `bank_customers` (`customer_id`),
  ADD CONSTRAINT `customer_accounts_ibfk_2` FOREIGN KEY (`account_type_id`) REFERENCES `bank_account_types` (`account_type_id`),
  ADD CONSTRAINT `customer_accounts_ibfk_3` FOREIGN KEY (`created_by_employee_id`) REFERENCES `bank_employees` (`employee_id`);

--
-- Constraints for table `customer_linked_accounts`
--
ALTER TABLE `customer_linked_accounts`
  ADD CONSTRAINT `customer_linked_accounts_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `bank_customers` (`customer_id`),
  ADD CONSTRAINT `customer_linked_accounts_ibfk_2` FOREIGN KEY (`account_id`) REFERENCES `customer_accounts` (`account_id`);

--
-- Constraints for table `customer_profiles`
--
ALTER TABLE `customer_profiles`
  ADD CONSTRAINT `customer_profiles_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `bank_customers` (`customer_id`),
  ADD CONSTRAINT `customer_profiles_ibfk_2` FOREIGN KEY (`gender_id`) REFERENCES `genders` (`gender_id`);

--
-- Constraints for table `emails`
--
ALTER TABLE `emails`
  ADD CONSTRAINT `emails_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `bank_customers` (`customer_id`);

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`),
  ADD CONSTRAINT `employee_ibfk_2` FOREIGN KEY (`position_id`) REFERENCES `position` (`position_id`);

--
-- Constraints for table `event_participants`
--
ALTER TABLE `event_participants`
  ADD CONSTRAINT `event_participants_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `recruitment` (`recruitment_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_participants_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_participants_ibfk_3` FOREIGN KEY (`invited_by`) REFERENCES `user_account` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `expense_categories`
--
ALTER TABLE `expense_categories`
  ADD CONSTRAINT `expense_categories_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`);

--
-- Constraints for table `expense_claims`
--
ALTER TABLE `expense_claims`
  ADD CONSTRAINT `expense_claims_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `expense_categories` (`id`),
  ADD CONSTRAINT `expense_claims_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `expense_claims_ibfk_3` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`),
  ADD CONSTRAINT `expense_claims_ibfk_4` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`);

--
-- Constraints for table `fiscal_periods`
--
ALTER TABLE `fiscal_periods`
  ADD CONSTRAINT `fiscal_periods_ibfk_1` FOREIGN KEY (`closed_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `interview`
--
ALTER TABLE `interview`
  ADD CONSTRAINT `interview_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicant` (`applicant_id`),
  ADD CONSTRAINT `interview_ibfk_2` FOREIGN KEY (`interviewer_id`) REFERENCES `employee` (`employee_id`);

--
-- Constraints for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD CONSTRAINT `journal_entries_ibfk_1` FOREIGN KEY (`journal_type_id`) REFERENCES `journal_types` (`id`),
  ADD CONSTRAINT `journal_entries_ibfk_2` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`),
  ADD CONSTRAINT `journal_entries_ibfk_3` FOREIGN KEY (`posted_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `journal_entries_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `journal_lines`
--
ALTER TABLE `journal_lines`
  ADD CONSTRAINT `journal_lines_ibfk_1` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`),
  ADD CONSTRAINT `journal_lines_ibfk_2` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`);

--
-- Constraints for table `leave_request`
--
ALTER TABLE `leave_request`
  ADD CONSTRAINT `leave_request_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_request_ibfk_2` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_type` (`leave_type_id`);

--
-- Constraints for table `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `fk_loans_application_id` FOREIGN KEY (`application_id`) REFERENCES `loan_applications` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `loans_ibfk_1` FOREIGN KEY (`loan_type_id`) REFERENCES `loan_types` (`id`),
  ADD CONSTRAINT `loans_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `loan_applications`
--
ALTER TABLE `loan_applications`
  ADD CONSTRAINT `loan_applications_ibfk_1` FOREIGN KEY (`loan_type_id`) REFERENCES `loan_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `loan_applications_ibfk_2` FOREIGN KEY (`approved_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `loan_applications_ibfk_3` FOREIGN KEY (`rejected_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `loan_applications_ibfk_4` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `loan_payments`
--
ALTER TABLE `loan_payments`
  ADD CONSTRAINT `loan_payments_ibfk_1` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`),
  ADD CONSTRAINT `loan_payments_ibfk_2` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`);

--
-- Constraints for table `onboarding`
--
ALTER TABLE `onboarding`
  ADD CONSTRAINT `onboarding_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `onboarding_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`from_bank_account_id`) REFERENCES `bank_accounts` (`id`),
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`),
  ADD CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `payroll_payslips`
--
ALTER TABLE `payroll_payslips`
  ADD CONSTRAINT `payroll_payslips_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`) ON DELETE CASCADE;

--
-- Constraints for table `payroll_runs`
--
ALTER TABLE `payroll_runs`
  ADD CONSTRAINT `payroll_runs_ibfk_1` FOREIGN KEY (`payroll_period_id`) REFERENCES `payroll_periods` (`id`),
  ADD CONSTRAINT `payroll_runs_ibfk_2` FOREIGN KEY (`run_by_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `payroll_runs_ibfk_3` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`);

--
-- Constraints for table `payslips`
--
ALTER TABLE `payslips`
  ADD CONSTRAINT `payslips_ibfk_1` FOREIGN KEY (`payroll_run_id`) REFERENCES `payroll_runs` (`id`);

--
-- Constraints for table `phones`
--
ALTER TABLE `phones`
  ADD CONSTRAINT `phones_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `bank_customers` (`customer_id`);

--
-- Constraints for table `points_history`
--
ALTER TABLE `points_history`
  ADD CONSTRAINT `points_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `bank_customers` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `recruitment`
--
ALTER TABLE `recruitment`
  ADD CONSTRAINT `recruitment_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`);

--
-- Constraints for table `referrals`
--
ALTER TABLE `referrals`
  ADD CONSTRAINT `referrals_ibfk_1` FOREIGN KEY (`referrer_id`) REFERENCES `bank_customers` (`customer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `referrals_ibfk_2` FOREIGN KEY (`referred_id`) REFERENCES `bank_customers` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `role_change_log`
--
ALTER TABLE `role_change_log`
  ADD CONSTRAINT `role_change_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_account` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_change_log_ibfk_2` FOREIGN KEY (`changed_by`) REFERENCES `user_account` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_change_log_ibfk_3` FOREIGN KEY (`old_department_id`) REFERENCES `department` (`department_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `role_change_log_ibfk_4` FOREIGN KEY (`new_department_id`) REFERENCES `department` (`department_id`) ON DELETE SET NULL;

--
-- Constraints for table `service_fee_charges`
--
ALTER TABLE `service_fee_charges`
  ADD CONSTRAINT `service_fee_charges_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `customer_accounts` (`account_id`),
  ADD CONSTRAINT `service_fee_charges_ibfk_2` FOREIGN KEY (`transaction_id`) REFERENCES `bank_transactions` (`transaction_id`) ON DELETE SET NULL;

--
-- Constraints for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD CONSTRAINT `system_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_account` (`user_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `system_logs_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`) ON DELETE SET NULL;

--
-- Constraints for table `user_missions`
--
ALTER TABLE `user_missions`
  ADD CONSTRAINT `user_missions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `bank_customers` (`customer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_missions_ibfk_2` FOREIGN KEY (`mission_id`) REFERENCES `missions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
