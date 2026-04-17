-- ========================================
-- DROP ALL TABLES - InfinityFree
-- ========================================
-- Run this FIRST to clean the database before importing
-- ========================================

SET FOREIGN_KEY_CHECKS = 0;

-- Drop all tables in reverse order of dependencies
DROP TABLE IF EXISTS audit_logs;
DROP TABLE IF EXISTS integration_logs;
DROP TABLE IF EXISTS compliance_reports;
DROP TABLE IF EXISTS expense_claims;
DROP TABLE IF EXISTS expense_categories;
DROP TABLE IF EXISTS loan_payments;
DROP TABLE IF EXISTS loans;
DROP TABLE IF EXISTS loan_applications;
DROP TABLE IF EXISTS loan_types;
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS salary_components;
DROP TABLE IF EXISTS payroll_payslips;
DROP TABLE IF EXISTS payslips;
DROP TABLE IF EXISTS payroll_runs;
DROP TABLE IF EXISTS payroll_periods;
DROP TABLE IF EXISTS journal_lines;
DROP TABLE IF EXISTS journal_entries;
DROP TABLE IF EXISTS journal_types;
DROP TABLE IF EXISTS account_balances;
DROP TABLE IF EXISTS accounts;
DROP TABLE IF EXISTS account_types;
DROP TABLE IF EXISTS fiscal_periods;
DROP TABLE IF EXISTS bank_transactions;
DROP TABLE IF EXISTS transaction_types;
DROP TABLE IF EXISTS customer_linked_accounts;
DROP TABLE IF EXISTS phones;
DROP TABLE IF EXISTS emails;
DROP TABLE IF EXISTS addresses;
DROP TABLE IF EXISTS customer_profiles;
DROP TABLE IF EXISTS customer_accounts;
DROP TABLE IF EXISTS source_of_funds;
DROP TABLE IF EXISTS employment_statuses;
DROP TABLE IF EXISTS bank_accounts;
DROP TABLE IF EXISTS bank_account_types;
DROP TABLE IF EXISTS bank_employees;
DROP TABLE IF EXISTS user_missions;
DROP TABLE IF EXISTS referrals;
DROP TABLE IF EXISTS points_history;
DROP TABLE IF EXISTS bank_customers;
DROP TABLE IF EXISTS barangays;
DROP TABLE IF EXISTS cities;
DROP TABLE IF EXISTS provinces;
DROP TABLE IF EXISTS genders;
DROP TABLE IF EXISTS bank_users;
DROP TABLE IF EXISTS missions;
DROP TABLE IF EXISTS account_applications;
DROP TABLE IF EXISTS system_logs;
DROP TABLE IF EXISTS reports;
DROP TABLE IF EXISTS interview;
DROP TABLE IF EXISTS applicant;
DROP TABLE IF EXISTS recruitment;
DROP TABLE IF EXISTS onboarding;
DROP TABLE IF EXISTS leave_request;
DROP TABLE IF EXISTS leave_type;
DROP TABLE IF EXISTS contract;
DROP TABLE IF EXISTS attendance;
DROP TABLE IF EXISTS employee_attendance;
DROP TABLE IF EXISTS employee_refs;
DROP TABLE IF EXISTS employee;
DROP TABLE IF EXISTS `position`;
DROP TABLE IF EXISTS department;
DROP TABLE IF EXISTS user_account;
DROP TABLE IF EXISTS login_attempts;
DROP TABLE IF EXISTS user_roles;
DROP TABLE IF EXISTS roles;
DROP TABLE IF EXISTS users;

-- Drop views
DROP VIEW IF EXISTS v_journal_summary;
DROP VIEW IF EXISTS v_account_balances;

SET FOREIGN_KEY_CHECKS = 1;

-- ========================================
-- Tables dropped successfully!
-- Now you can import unified_schema_infinityfree.sql
-- ========================================
