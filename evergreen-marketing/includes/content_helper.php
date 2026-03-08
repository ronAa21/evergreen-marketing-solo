<?php
/**
 * Content Helper Functions
 * Dynamically loads content from database so admin changes reflect immediately
 */

// Cache content in session to reduce database queries
function get_site_content($content_key, $default = '') {
    // Check if content is already cached in this request
    static $content_cache = null;
    
    if ($content_cache === null) {
        $content_cache = [];
        
        // Load all content from database
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

// Get company name
function get_company_name() {
    return get_site_content('company_name', 'Evergreen Bank');
}

// Get company logo
function get_company_logo() {
    return get_site_content('company_logo', 'images/Logo.png.png');
}

// Get hero title
function get_hero_title() {
    return get_site_content('hero_title', 'Secure. Invest. Achieve.');
}

// Get hero description
function get_hero_description() {
    return get_site_content('hero_description', 'Your trusted financial partner for a prosperous future.');
}

// Get about description
function get_about_description() {
    return get_site_content('about_description', 'Evergreen Bank has been serving customers for over 20 years with dedication and excellence.');
}

// Get banner image
function get_banner_image() {
    return get_site_content('banner_image', 'images/hero-main.png');
}

// Get contact phone
function get_contact_phone() {
    return get_site_content('contact_phone', '1-800-EVERGREEN');
}

// Get contact email
function get_contact_email() {
    return get_site_content('contact_email', 'evrgrn.64@gmail.com');
}
?>
