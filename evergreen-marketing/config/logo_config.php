<?php
/**
 * Centralized Logo Configuration
 * Update logo paths here to change across entire system
 */

// Define logo paths
define('LOGO_MAIN', 'images/Logo.png');           // Main logo (navigation, headers)
define('LOGO_LOGIN', 'images/loginlogo.png');     // Login/signup pages logo
define('LOGO_ICON', 'images/icon.png');           // Favicon/small icon
define('LOGO_ALT_TEXT', 'Evergreen Bank Logo');   // Alt text for accessibility

/**
 * Get the appropriate logo path based on context
 * @param string $type Type of logo needed ('main', 'login', 'icon')
 * @param bool $relative Whether to return relative path (default: true)
 * @return string Logo path
 */
function get_logo($type = 'main', $relative = true) {
    $logos = [
        'main' => LOGO_MAIN,
        'login' => LOGO_LOGIN,
        'icon' => LOGO_ICON
    ];
    
    $logo = isset($logos[$type]) ? $logos[$type] : LOGO_MAIN;
    
    // Adjust path based on current directory depth
    if ($relative) {
        $depth = substr_count($_SERVER['PHP_SELF'], '/') - 2;
        if ($depth > 0) {
            $logo = str_repeat('../', $depth) . $logo;
        }
    }
    
    return $logo;
}

/**
 * Get logo alt text
 * @return string Alt text for logo
 */
function get_logo_alt() {
    return LOGO_ALT_TEXT;
}

/**
 * Render logo HTML
 * @param string $type Type of logo ('main', 'login', 'icon')
 * @param array $attributes Additional HTML attributes
 * @return string HTML img tag
 */
function render_logo($type = 'main', $attributes = []) {
    $src = get_logo($type);
    $alt = get_logo_alt();
    
    $attrs = ['src' => $src, 'alt' => $alt];
    $attrs = array_merge($attrs, $attributes);
    
    $html = '<img';
    foreach ($attrs as $key => $value) {
        $html .= ' ' . htmlspecialchars($key) . '="' . htmlspecialchars($value) . '"';
    }
    $html .= '>';
    
    return $html;
}
?>
