<?php
/**
 * Complete Dynamic Content Migration
 * Run this ONCE to make ALL frontend content editable from admin panel
 */

include('db_connect.php');

echo "<style>
body { font-family: Arial, sans-serif; padding: 40px; background: #f5f5f5; }
.container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
h2 { color: #003631; margin-bottom: 20px; }
.success { color: #155724; background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0; }
.error { color: #721c24; background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0; }
.info { color: #004085; background: #cce5ff; padding: 10px; border-radius: 5px; margin: 10px 0; }
.btn { display: inline-block; padding: 12px 24px; background: #003631; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
.btn:hover { background: #005544; }
.btn-secondary { background: #F1B24A; color: #003631; }
.btn-secondary:hover { background: #e69610; }
ul { margin: 15px 0; padding-left: 20px; }
li { margin: 5px 0; }
</style>";

echo "<div class='container'>";
echo "<h2>🚀 Complete Dynamic Content Migration</h2>";
echo "<p>This will make ALL frontend content editable from the admin panel.</p>";

$content_items = [
    // Hero Section
    ['hero_title', 'Banking that grows with you', 'text'],
    ['hero_paragraph', 'Secure financial solutions for every stage of your life journey. Invest, save, and achieve your goals with Evergreen.', 'text'],
    ['hero_card_title', 'Banking at your fingertips', 'text'],
    ['hero_card_description', 'Experience our award-winning digital banking platform designed for your convenience.', 'text'],
    ['hero_card_image', 'images/hero-image.png', 'image'],
    
    // Financial Solutions
    ['solutions_title', 'Financial Solutions for Every Need', 'text'],
    ['solutions_intro', 'Discover our comprehensive range of banking products designed to support your financial journey.', 'text'],
    ['solution_1_icon', '💳', 'text'],
    ['solution_1_title', 'Everyday Banking', 'text'],
    ['solution_1_description', 'Fee-free checking accounts with premium benefits and rewards on everyday spending.', 'text'],
    ['solution_2_icon', '🏦', 'text'],
    ['solution_2_title', 'Savings & Deposits', 'text'],
    ['solution_2_description', 'High-yield savings accounts and CDs to help your money grow faster.', 'text'],
    ['solution_3_icon', '📈', 'text'],
    ['solution_3_title', 'Investments', 'text'],
    ['solution_3_description', 'Personalized investment strategies aligned with your financial goals.', 'text'],
    ['solution_4_icon', '🏠', 'text'],
    ['solution_4_title', 'Home Loans', 'text'],
    ['solution_4_description', 'Competitive mortgage rates and flexible repayment options for your dream home.', 'text'],
    
    // Rewards Section
    ['rewards_title', 'Get a Card to get some Awesome Rewards!', 'text'],
    ['rewards_description', 'Open an account with us today and enjoy exclusive rewards, special offers, and member-only perks designed to make your banking more rewarding.', 'text'],
    ['rewards_button_text', 'Learn More', 'text'],
    ['rewards_image', 'images/card.png', 'image'],
    
    // Loan Services
    ['loans_title', 'LOAN SERVICES WE OFFER', 'text'],
    ['loan_1_title', 'Personal Loan', 'text'],
    ['loan_1_description', 'Stop worrying and bring your plans to life.', 'text'],
    ['loan_1_image', 'images/personalloan.png', 'image'],
    ['loan_2_title', 'Auto Loan', 'text'],
    ['loan_2_description', 'Drive your new car with low rates and fast approval.', 'text'],
    ['loan_2_image', 'images/autoloan.png', 'image'],
    ['loan_3_title', 'Home Loan', 'text'],
    ['loan_3_description', 'Take the next step to your new home property to fund your various needs.', 'text'],
    ['loan_3_image', 'images/homeloan.png', 'image'],
    ['loan_4_title', 'Multipurpose Loan', 'text'],
    ['loan_4_description', 'Carry on with your plans. Use your property to fund your various needs.', 'text'],
    ['loan_4_image', 'images/multipurposeloan.png', 'image'],
    
    // Career Section
    ['career_title', 'Build a Meaningful Career in the World of Banking!', 'text'],
    ['career_intro', 'At Evergreen Bank, we believe that our employees are the heart of our success. We\'re looking for dedicated, skilled, and passionate individuals who are ready to grow with us.', 'html'],
    ['career_how_to_apply_title', 'How to apply?', 'text'],
    ['career_how_to_apply_text', 'Interested applicants are encouraged to personally visit our branch to submit their application.', 'text'],
    ['career_location_title', 'Where to Apply:', 'text'],
    ['career_location_address', 'Evergreen Bank Main Branch<br>123 Evergreen Avenue, City Center', 'html'],
    ['career_requirements_title', 'Requirements:', 'text'],
    ['career_note', 'Walk-in applicants are welcome. Our HR team will be glad to assist you with the next steps in your application process.', 'text'],
    ['career_image', 'images/recruit.png', 'image'],
    
    // Footer
    ['footer_tagline', 'Secure. Invest. Achieve. Your trusted financial partner for a prosperous future.', 'text'],
    ['footer_address', '123 Financial District, Suite 500<br>New York, NY 10004', 'html'],
    ['footer_copyright', '© 2023 Evergreen Bank. All rights reserved.<br>Member FDIC. Equal Housing Lender. Evergreen Bank, N.A.', 'html'],
    
    // Social Media
    ['social_facebook_url', 'https://www.facebook.com/profile.php?id=61582812214198', 'text'],
    ['social_instagram_url', 'https://www.instagram.com/evergreenbanking/', 'text'],
    
    // Navigation
    ['nav_home_text', 'Home', 'text'],
    ['nav_cards_text', 'Cards', 'text'],
    ['nav_whatsnew_text', 'What\'s new', 'text'],
    ['nav_about_text', 'About Us', 'text'],
    
    // Buttons
    ['btn_learn_more', 'Learn More', 'text'],
    ['btn_open_account', 'Open an Account', 'text'],
    ['btn_get_started', 'Get Started', 'text'],
    ['btn_login', 'Login', 'text'],
];

$added = 0;
$skipped = 0;
$errors = 0;

echo "<div class='info'><strong>Processing " . count($content_items) . " content items...</strong></div>";

foreach ($content_items as $item) {
    list($key, $value, $type) = $item;
    
    $sql = "INSERT IGNORE INTO `site_content` (`content_key`, `content_value`, `content_type`) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $key, $value, $type);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $added++;
            echo "<div class='success'>✓ Added: <strong>$key</strong></div>";
        } else {
            $skipped++;
        }
    } else {
        $errors++;
        echo "<div class='error'>✗ Error adding $key: " . $conn->error . "</div>";
    }
    $stmt->close();
}

echo "<hr style='margin: 30px 0;'>";
echo "<h3>Migration Summary</h3>";
echo "<ul>";
echo "<li><strong style='color: #155724;'>$added</strong> new content fields added</li>";
echo "<li><strong style='color: #856404;'>$skipped</strong> fields already existed (skipped)</li>";
if ($errors > 0) {
    echo "<li><strong style='color: #721c24;'>$errors</strong> errors occurred</li>";
}
echo "</ul>";

if ($errors == 0) {
    echo "<div class='success'>";
    echo "<h3>✅ Migration Completed Successfully!</h3>";
    echo "<p>All frontend content is now editable from the Admin Dashboard.</p>";
    echo "<p><strong>What you can now edit:</strong></p>";
    echo "<ul>";
    echo "<li>Hero section (title, paragraph, card)</li>";
    echo "<li>Financial Solutions section (all 4 cards)</li>";
    echo "<li>Rewards section (title, description, button)</li>";
    echo "<li>Loan Services section (all 4 loan types)</li>";
    echo "<li>Career section (all text content)</li>";
    echo "<li>Footer content</li>";
    echo "<li>Navigation text</li>";
    echo "<li>Button labels</li>";
    echo "</ul>";
    echo "</div>";
}

$conn->close();

echo "<div style='margin-top: 30px; text-align: center;'>";
echo "<a href='admin_dashboard.php?page=content' class='btn'>Go to Content Management</a>";
echo "<a href='viewingpage.php' class='btn-secondary btn'>View Website</a>";
echo "</div>";

echo "</div>";
?>
