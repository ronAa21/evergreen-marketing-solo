<?php
/**
 * Content Helper Functions
 * Dynamically loads ALL content from database so admin changes reflect immediately
 */

// Cache content in session to reduce database queries
function get_site_content($content_key, $default = '') {
    static $content_cache = null;
    
    if ($content_cache === null) {
        $content_cache = [];
        include(__DIR__ . '/../db_connect.php');
        
        $sql = "SELECT content_key, content_value FROM site_content";
        $result = $conn->query($sql);
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $content_cache[$row['content_key']] = $row['content_value'];
            }
        }
        $conn->close();
    }
    
    return isset($content_cache[$content_key]) ? $content_cache[$content_key] : $default;
}

// ========== COMPANY INFO ==========
function get_company_name() {
    return get_site_content('company_name', 'Evergreen Bank');
}

function get_company_logo() {
    return get_site_content('company_logo', 'images/Logo.png.png');
}

function get_contact_phone() {
    return get_site_content('contact_phone', '1-800-EVERGREEN');
}

function get_contact_email() {
    return get_site_content('contact_email', 'evrgrn.64@gmail.com');
}

// ========== HERO SECTION ==========
function get_hero_title() {
    return get_site_content('hero_title', 'Banking that grows with you');
}

function get_hero_paragraph() {
    return get_site_content('hero_paragraph', 'Secure financial solutions for every stage of your life journey. Invest, save, and achieve your goals with Evergreen.');
}

function get_hero_card_title() {
    return get_site_content('hero_card_title', 'Banking at your fingertips');
}

function get_hero_card_description() {
    return get_site_content('hero_card_description', 'Experience our award-winning digital banking platform designed for your convenience.');
}

function get_hero_card_image() {
    return get_site_content('hero_card_image', 'images/hero-image.png');
}

// ========== FINANCIAL SOLUTIONS SECTION ==========
function get_solutions_title() {
    return get_site_content('solutions_title', 'Financial Solutions for Every Need');
}

function get_solutions_intro() {
    return get_site_content('solutions_intro', 'Discover our comprehensive range of banking products designed to support your financial journey.');
}

// Solution Card 1
function get_solution_1_icon() {
    return get_site_content('solution_1_icon', '💳');
}

function get_solution_1_title() {
    return get_site_content('solution_1_title', 'Everyday Banking');
}

function get_solution_1_description() {
    return get_site_content('solution_1_description', 'Fee-free checking accounts with premium benefits and rewards on everyday spending.');
}

// Solution Card 2
function get_solution_2_icon() {
    return get_site_content('solution_2_icon', '🏦');
}

function get_solution_2_title() {
    return get_site_content('solution_2_title', 'Savings & Deposits');
}

function get_solution_2_description() {
    return get_site_content('solution_2_description', 'High-yield savings accounts and CDs to help your money grow faster.');
}

// Solution Card 3
function get_solution_3_icon() {
    return get_site_content('solution_3_icon', '📈');
}

function get_solution_3_title() {
    return get_site_content('solution_3_title', 'Investments');
}

function get_solution_3_description() {
    return get_site_content('solution_3_description', 'Personalized investment strategies aligned with your financial goals.');
}

// Solution Card 4
function get_solution_4_icon() {
    return get_site_content('solution_4_icon', '🏠');
}

function get_solution_4_title() {
    return get_site_content('solution_4_title', 'Home Loans');
}

function get_solution_4_description() {
    return get_site_content('solution_4_description', 'Competitive mortgage rates and flexible repayment options for your dream home.');
}

// ========== REWARDS SECTION ==========
function get_rewards_title() {
    return get_site_content('rewards_title', 'Get a Card to get some Awesome Rewards!');
}

function get_rewards_description() {
    return get_site_content('rewards_description', 'Open an account with us today and enjoy exclusive rewards, special offers, and member-only perks designed to make your banking more rewarding.');
}

function get_rewards_button_text() {
    return get_site_content('rewards_button_text', 'Learn More');
}

function get_rewards_image() {
    return get_site_content('rewards_image', 'images/card.png');
}

// ========== LOAN SERVICES SECTION ==========
function get_loans_title() {
    return get_site_content('loans_title', 'LOAN SERVICES WE OFFER');
}

// Loan 1 - Personal
function get_loan_1_title() {
    return get_site_content('loan_1_title', 'Personal Loan');
}

function get_loan_1_description() {
    return get_site_content('loan_1_description', 'Stop worrying and bring your plans to life.');
}

function get_loan_1_image() {
    return get_site_content('loan_1_image', 'images/personalloan.png');
}

// Loan 2 - Auto
function get_loan_2_title() {
    return get_site_content('loan_2_title', 'Auto Loan');
}

function get_loan_2_description() {
    return get_site_content('loan_2_description', 'Drive your new car with low rates and fast approval.');
}

function get_loan_2_image() {
    return get_site_content('loan_2_image', 'images/autoloan.png');
}

// Loan 3 - Home
function get_loan_3_title() {
    return get_site_content('loan_3_title', 'Home Loan');
}

function get_loan_3_description() {
    return get_site_content('loan_3_description', 'Take the next step to your new home property to fund your various needs.');
}

function get_loan_3_image() {
    return get_site_content('loan_3_image', 'images/homeloan.png');
}

// Loan 4 - Multipurpose
function get_loan_4_title() {
    return get_site_content('loan_4_title', 'Multipurpose Loan');
}

function get_loan_4_description() {
    return get_site_content('loan_4_description', 'Carry on with your plans. Use your property to fund your various needs.');
}

function get_loan_4_image() {
    return get_site_content('loan_4_image', 'images/multipurposeloan.png');
}

// ========== CAREER SECTION ==========
function get_career_title() {
    return get_site_content('career_title', 'Build a Meaningful Career in the World of Banking!');
}

function get_career_intro() {
    return get_site_content('career_intro', 'At Evergreen Bank, we believe that our employees are the heart of our success. We\'re looking for dedicated, skilled, and passionate individuals who are ready to grow with us.');
}

function get_career_how_to_apply_title() {
    return get_site_content('career_how_to_apply_title', 'How to apply?');
}

function get_career_how_to_apply_text() {
    return get_site_content('career_how_to_apply_text', 'Interested applicants are encouraged to personally visit our branch to submit their application.');
}

function get_career_location_title() {
    return get_site_content('career_location_title', 'Where to Apply:');
}

function get_career_location_address() {
    return get_site_content('career_location_address', 'Evergreen Bank Main Branch<br>123 Evergreen Avenue, City Center');
}

function get_career_requirements_title() {
    return get_site_content('career_requirements_title', 'Requirements:');
}

function get_career_note() {
    return get_site_content('career_note', 'Walk-in applicants are welcome. Our HR team will be glad to assist you with the next steps in your application process.');
}

function get_career_image() {
    return get_site_content('career_image', 'images/recruit.png');
}

// ========== FOOTER SECTION ==========
function get_footer_tagline() {
    return get_site_content('footer_tagline', 'Secure. Invest. Achieve. Your trusted financial partner for a prosperous future.');
}

function get_footer_address() {
    return get_site_content('footer_address', '123 Financial District, Suite 500<br>New York, NY 10004');
}

function get_footer_copyright() {
    return get_site_content('footer_copyright', '© 2023 Evergreen Bank. All rights reserved.<br>Member FDIC. Equal Housing Lender. Evergreen Bank, N.A.');
}

// ========== SOCIAL MEDIA ==========
function get_social_facebook_url() {
    return get_site_content('social_facebook_url', 'https://www.facebook.com/profile.php?id=61582812214198');
}

function get_social_instagram_url() {
    return get_site_content('social_instagram_url', 'https://www.instagram.com/evergreenbanking/');
}

// ========== NAVIGATION ==========
function get_nav_home_text() {
    return get_site_content('nav_home_text', 'Home');
}

function get_nav_cards_text() {
    return get_site_content('nav_cards_text', 'Cards');
}

function get_nav_whatsnew_text() {
    return get_site_content('nav_whatsnew_text', 'What\'s new');
}

function get_nav_about_text() {
    return get_site_content('nav_about_text', 'About Us');
}

// ========== BUTTONS ==========
function get_btn_learn_more() {
    return get_site_content('btn_learn_more', 'Learn More');
}

function get_btn_open_account() {
    return get_site_content('btn_open_account', 'Open an Account');
}

function get_btn_get_started() {
    return get_site_content('btn_get_started', 'Get Started');
}

function get_btn_login() {
    return get_site_content('btn_login', 'Login');
}
?>
