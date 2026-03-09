-- Add hero_paragraph content key to site_content table
-- Run this via the PHP migration script: run_hero_paragraph_migration.php

INSERT IGNORE INTO `site_content` (`content_key`, `content_value`, `content_type`) VALUES
('hero_paragraph', 'Secure financial solutions for every stage of your life journey. Invest, save, and achieve your goals with Evergreen.', 'text');

