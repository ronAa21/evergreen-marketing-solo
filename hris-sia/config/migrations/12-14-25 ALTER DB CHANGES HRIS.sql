-- ========================================
-- HRIS-SIA ROLE MANAGEMENT MIGRATION
-- ========================================
-- This migration adds support for Manager and Supervisor roles
-- with department-based permissions for leave approvals
-- 
-- Run this script on your BankingDB database
-- ========================================

-- Add managed_department_id column to user_account table
-- This allows Managers/Supervisors to be assigned to a specific department
ALTER TABLE user_account 
ADD COLUMN IF NOT EXISTS managed_department_id INT DEFAULT NULL AFTER role;

-- Add foreign key constraint for department reference
-- Only add if constraint doesn't already exist
SET @constraint_exists = (
    SELECT COUNT(*) 
    FROM information_schema.TABLE_CONSTRAINTS 
    WHERE TABLE_SCHEMA = 'BankingDB' 
    AND TABLE_NAME = 'user_account' 
    AND CONSTRAINT_NAME = 'fk_user_account_department'
);

SET @sql = IF(@constraint_exists = 0,
    'ALTER TABLE user_account ADD CONSTRAINT fk_user_account_department FOREIGN KEY (managed_department_id) REFERENCES department(department_id) ON DELETE SET NULL',
    'SELECT "Constraint already exists" AS message'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Update role column to support new role values
-- Current: 'Admin', 'HR Manager'
-- New: 'Admin', 'HR Manager', 'Manager', 'Supervisor', 'Employee'
-- The VARCHAR(20) should accommodate all these values

-- Create sample Manager and Supervisor accounts (optional)
-- Uncomment if you want to create test accounts

-- INSERT INTO user_account (employee_id, username, password_hash, role, managed_department_id, last_login)
-- VALUES (
--     2, -- Link to an existing employee ID
--     'manager1',
--     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: 'password'
--     'Manager',
--     1, -- Department ID 1 (e.g., HR Department)
--     NULL
-- )
-- ON DUPLICATE KEY UPDATE 
--     role = VALUES(role),
--     managed_department_id = VALUES(managed_department_id);

-- ========================================
-- NOTES
-- ========================================
-- Role Hierarchy:
--   1. Admin - Full system access, can approve all leaves, manage all employees
--   2. HR Manager - Can approve all leaves, view all employees, limited edit
--   3. Manager - Can approve leaves for their department only
--   4. Supervisor - View-only access for their department
--   5. Employee - Self-service access via employee_dashboard.php
--
-- To assign a Manager to a department:
--   UPDATE user_account SET role = 'Manager', managed_department_id = <dept_id> WHERE user_id = <user_id>;
--
-- To assign a Supervisor to a department:
--   UPDATE user_account SET role = 'Supervisor', managed_department_id = <dept_id> WHERE user_id = <user_id>;
-- ========================================

SELECT 'Migration completed successfully!' as status;

-- ========================================
-- ROLE CHANGE AUDIT LOG TABLE
-- ========================================
-- Tracks all role assignments and changes for audit/compliance

CREATE TABLE IF NOT EXISTS role_change_log (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    changed_by INT NOT NULL,
    old_role VARCHAR(20) DEFAULT NULL,
    new_role VARCHAR(20) NOT NULL,
    old_department_id INT DEFAULT NULL,
    new_department_id INT DEFAULT NULL,
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_changed_at (changed_at),
    FOREIGN KEY (user_id) REFERENCES user_account(user_id) ON DELETE CASCADE,
    FOREIGN KEY (changed_by) REFERENCES user_account(user_id) ON DELETE CASCADE,
    FOREIGN KEY (old_department_id) REFERENCES department(department_id) ON DELETE SET NULL,
    FOREIGN KEY (new_department_id) REFERENCES department(department_id) ON DELETE SET NULL
);

SELECT 'Role change audit log table created!' as status;

-- ========================================
-- SAMPLE MANAGER ACCOUNT
-- ========================================
-- Username: manager
-- Password: password
-- Role: Manager (can approve leaves for IT Department)

INSERT INTO user_account (employee_id, username, password_hash, role, managed_department_id)
VALUES (
    2,                                                                    -- Link to employee ID 2
    'manager',                                                            -- Username
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',     -- Password: 'password'
    'Manager',                                                            -- Role
    1                                                                      -- Department ID 1 (HR/IT)
)
ON DUPLICATE KEY UPDATE role = 'Manager', managed_department_id = 1;


-- ========================================
-- SAMPLE SUPERVISOR ACCOUNT
-- ========================================
-- Username: supervisor
-- Password: password
-- Role: Supervisor (view-only access for Finance Department)

INSERT INTO user_account (employee_id, username, password_hash, role, managed_department_id)
VALUES (
    3,                                                                    -- Link to employee ID 3
    'supervisor',                                                         -- Username
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',     -- Password: 'password'
    'Supervisor',                                                         -- Role
    2                                                                      -- Department ID 2 (Finance)
)
ON DUPLICATE KEY UPDATE role = 'Supervisor', managed_department_id = 2;

-- ========================================
-- EVENT PARTICIPANTS TABLE
-- ========================================
-- Tracks employees invited to recruitment events with RSVP status

CREATE TABLE IF NOT EXISTS event_participants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    employee_id INT NOT NULL,
    rsvp_status ENUM('Pending', 'Accepted', 'Declined', 'Maybe') DEFAULT 'Pending',
    rsvp_date DATETIME DEFAULT NULL,
    invited_by INT DEFAULT NULL,
    notified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_event_id (event_id),
    INDEX idx_employee_id (employee_id),
    INDEX idx_rsvp_status (rsvp_status),
    FOREIGN KEY (event_id) REFERENCES recruitment(recruitment_id) ON DELETE CASCADE,
    FOREIGN KEY (employee_id) REFERENCES employee(employee_id) ON DELETE CASCADE,
    FOREIGN KEY (invited_by) REFERENCES user_account(user_id) ON DELETE SET NULL,
    UNIQUE KEY unique_participant (event_id, employee_id)
);

SELECT 'Event participants table created!' as status;