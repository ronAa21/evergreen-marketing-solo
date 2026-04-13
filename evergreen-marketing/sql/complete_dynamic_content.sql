-- Complete Dynamic Content Migration
-- This adds ALL frontend content to the database for admin editing

-- Insert all content fields (using INSERT IGNORE to skip duplicates)

-- Hero Section
INSERT IGNORE INTO `site_content` (`content_key`, `content_value`, `content_type`) VALUES
('hero_title', 'Banking that grows with you', 'text'),
('hero_paragraph', 'Secure financial solutions for every stage of your life journey. Invest, save, and achieve your goals with Evergreen.', 'text'),
('hero_card_title', 'Banking at your fingertips', 'text'),
('hero_card_description', 'Experience our award-winning digital banking platform designed for your convenience.', 'text'),
('hero_card_image', 'images/hero-image.png', 'image'),

-- Financial Solutions Section
('solutions_title', 'Financial Solutions for Every Need', 'text'),
('solutions_intro', 'Discover our comprehensive range of banking products designed to support your financial journey.', 'text'),

('solution_1_icon', '💳', 'text'),
('solution_1_title', 'Everyday Banking', 'text'),
('solution_1_description', 'Fee-free checking accounts with premium benefits and rewards on everyday spending.', 'text'),

('solution_2_icon', '🏦', 'text'),
('solution_2_title', 'Savings & Deposits', 'text'),
('solution_2_description', 'High-yield savings accounts and CDs to help your money grow faster.', 'text'),

('solution_3_icon', '📈', 'text'),
('solution_3_title', 'Investments', 'text'),
('solution_3_description', 'Personalized investment strategies aligned with your financial goals.', 'text'),

('solution_4_icon', '🏠', 'text'),
('solution_4_title', 'Home Loans', 'text'),
('solution_4_description', 'Competitive mortgage rates and flexible repayment options for your dream home.', 'text'),

-- Rewards Section
('rewards_title', 'Get a Card to get some Awesome Rewards!', 'text'),
('rewards_description', 'Open an account with us today and enjoy exclusive rewards, special offers, and member-only perks designed to make your banking more rewarding.', 'text'),
('rewards_button_text', 'Learn More', 'text'),
('rewards_image', 'images/card.png', 'image'),

-- Loan Services Section
('loans_title', 'LOAN SERVICES WE OFFER', 'text'),

('loan_1_title', 'Personal Loan', 'text'),
('loan_1_description', 'Stop worrying and bring your plans to life.', 'text'),
('loan_1_image', 'images/personalloan.png', 'image'),

('loan_2_title', 'Auto Loan', 'text'),
('loan_2_description', 'Drive your new car with low rates and fast approval.', 'text'),
('loan_2_image', 'images/autoloan.png', 'image'),

('loan_3_title', 'Home Loan', 'text'),
('loan_3_description', 'Take the next step to your new home property to fund your various needs.', 'text'),
('loan_3_image', 'images/homeloan.png', 'image'),

('loan_4_title', 'Multipurpose Loan', 'text'),
('loan_4_description', 'Carry on with your plans. Use your property to fund your various needs.', 'text'),
('loan_4_image', 'images/multipurposeloan.png', 'image'),

-- Career Section
('career_title', 'Build a Meaningful Career in the World of Banking!', 'text'),
('career_intro', 'At Evergreen Bank, we believe that our employees are the heart of our success. We\'re looking for dedicated, skilled, and passionate individuals who are ready to grow with us. Whether you\'re an experienced banker or a fresh graduate eager to learn, we provide a supportive environment where your talents can thrive and your career can flourish.', 'html'),
('career_how_to_apply_title', 'How to apply?', 'text'),
('career_how_to_apply_text', 'Interested applicants are encouraged to personally visit our branch to submit their application. Please bring the following requirements and apply directly at Evergreen Bank\'s Human Resources Department.', 'text'),
('career_location_title', 'Where to Apply:', 'text'),
('career_location_address', 'Evergreen Bank Main Branch<br>123 Evergreen Avenue, City Center', 'html'),
('career_requirements_title', 'Requirements:', 'text'),
('career_note', 'Walk-in applicants are welcome. Our HR team will be glad to assist you with the next steps in your application process.', 'text'),
('career_image', 'images/recruit.png', 'image'),

-- Footer Section
('footer_tagline', 'Secure. Invest. Achieve. Your trusted financial partner for a prosperous future.', 'text'),
('footer_address', '123 Financial District, Suite 500<br>New York, NY 10004', 'html'),
('footer_copyright', '© 2023 Evergreen Bank. All rights reserved.<br>Member FDIC. Equal Housing Lender. Evergreen Bank, N.A.', 'html'),

-- Social Media Links
('social_facebook_url', 'https://www.facebook.com/profile.php?id=61582812214198', 'text'),
('social_instagram_url', 'https://www.instagram.com/evergreenbanking/', 'text'),

-- Navigation Links Text
('nav_home_text', 'Home', 'text'),
('nav_cards_text', 'Cards', 'text'),
('nav_whatsnew_text', 'What\'s new', 'text'),
('nav_about_text', 'About Us', 'text'),

-- Button Text
('btn_learn_more', 'Learn More', 'text'),
('btn_open_account', 'Open an Account', 'text'),
('btn_get_started', 'Get Started', 'text'),
('btn_login', 'Login', 'text');
