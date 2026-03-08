# Dynamic Content Integration Guide

## ✅ IMPLEMENTATION COMPLETE

All pages have been successfully updated! The dynamic content system is now fully operational.

**Test it:** Visit `test_dynamic_content.php` to verify all content is loading from the database.

---

This guide shows how to make your pages automatically update when admin changes content.

## How It Works

1. Admin updates content in the dashboard
2. Content is saved to the `site_content` database table
3. User pages load content from the database using helper functions
4. Changes appear immediately without editing PHP files

## Setup

### Step 1: Include the Content Helper

Add this at the top of any page that needs dynamic content:

```php
<?php
// Include the content helper
include_once(__DIR__ . '/includes/content_helper.php');
?>
```

### Step 2: Replace Hardcoded Content

Replace hardcoded text with helper functions:

#### Before (Hardcoded):
```html
<div class="logo">
    <img src="images/Logo.png.png" alt="Logo">
    <span>EVERGREEN</span>
</div>
```

#### After (Dynamic):
```php
<div class="logo">
    <img src="<?php echo htmlspecialchars(get_company_logo()); ?>" alt="Logo">
    <span><?php echo htmlspecialchars(get_company_name()); ?></span>
</div>
```

## Available Helper Functions

```php
// Company Information
get_company_name()          // Returns: "Evergreen Bank"
get_company_logo()          // Returns: "images/Logo.png.png"

// Hero Section
get_hero_title()            // Returns: "Secure. Invest. Achieve."
get_hero_description()      // Returns: "Your trusted financial partner..."

// About Section
get_about_description()     // Returns: Full about text

// Contact Information
get_contact_phone()         // Returns: "1-800-EVERGREEN"
get_contact_email()         // Returns: "evrgrn.64@gmail.com"

// Images
get_banner_image()          // Returns: "images/hero-main.png"

// Generic function for any content
get_site_content('content_key', 'default_value')
```

## Example: Update Navigation

### Before:
```html
<nav>
    <div class="logo">
        <img src="images/Logo.png.png">
        <span>EVERGREEN</span>
    </div>
</nav>
```

### After:
```php
<?php include_once('includes/content_helper.php'); ?>
<nav>
    <div class="logo">
        <img src="<?php echo get_company_logo(); ?>">
        <span><?php echo strtoupper(get_company_name()); ?></span>
    </div>
</nav>
```

## Example: Update Hero Section

### Before:
```html
<section class="hero">
    <h1>Secure. Invest. Achieve.</h1>
    <p>Your trusted financial partner for a prosperous future.</p>
</section>
```

### After:
```php
<?php include_once('includes/content_helper.php'); ?>
<section class="hero">
    <h1><?php echo get_hero_title(); ?></h1>
    <p><?php echo get_hero_description(); ?></p>
</section>
```

## Example: Update Footer

### Before:
```html
<footer>
    <p>© 2023 Evergreen Bank. All rights reserved.</p>
    <p>Contact: 1-800-EVERGREEN | evrgrn.64@gmail.com</p>
</footer>
```

### After:
```php
<?php include_once('includes/content_helper.php'); ?>
<footer>
    <p>© 2023 <?php echo get_company_name(); ?>. All rights reserved.</p>
    <p>Contact: <?php echo get_contact_phone(); ?> | <?php echo get_contact_email(); ?></p>
</footer>
```

## Quick Update Script

I've created a script to automatically update your main pages. Run:

```
http://localhost/SIA/evergreen-marketing/update_pages_to_dynamic.php
```

This will:
1. Backup your current pages
2. Update them to use dynamic content
3. Show you what was changed

## Files to Update

Priority files that should use dynamic content:

1. ✅ `viewingpage.php` - Main landing page
2. ✅ `index.php` - Home page
3. ✅ `about.php` - About page
4. ✅ `profile.php` - User profile
5. ✅ `refer.php` - Referral page
6. ✅ All card pages in `/cards/` folder

## Testing

After updating pages:

1. Login to admin dashboard
2. Change company name to "Test Bank"
3. Refresh user page
4. Should see "Test Bank" instead of "Evergreen Bank"
5. Change back to "Evergreen Bank"

## Adding New Dynamic Content

### Step 1: Add to Database
```sql
INSERT INTO site_content (content_key, content_value, content_type) 
VALUES ('new_content', 'Your content here', 'text');
```

### Step 2: Add Helper Function (Optional)
Edit `includes/content_helper.php`:
```php
function get_new_content() {
    return get_site_content('new_content', 'Default value');
}
```

### Step 3: Use in Pages
```php
<?php echo get_new_content(); ?>
```

### Step 4: Add to Admin Dashboard
The content will automatically appear in the admin content management page!

## Performance

The helper functions cache all content in memory, so:
- ✅ Only 1 database query per page load
- ✅ Fast performance
- ✅ No impact on user experience

## Troubleshooting

### Content not updating?
1. Clear browser cache (Ctrl+F5)
2. Check database connection in `db_connect.php`
3. Verify content exists in `site_content` table

### Function not found?
Make sure you included the helper:
```php
include_once(__DIR__ . '/includes/content_helper.php');
```

### Wrong path?
Adjust the path based on file location:
```php
// From root: includes/content_helper.php
// From subfolder: ../includes/content_helper.php
```

## Benefits

✅ Admin can update content without touching code
✅ Changes appear immediately
✅ No need to redeploy or edit files
✅ Consistent branding across all pages
✅ Easy to maintain
✅ Professional workflow

## Next Steps

1. Run the setup script to create the database tables
2. Update your pages to use helper functions
3. Test by changing content in admin dashboard
4. Train admins on how to use the content management system
