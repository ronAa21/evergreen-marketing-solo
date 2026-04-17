# 🔄 Dynamic Content System Setup Guide

## Overview

This system allows you to **edit ALL website content from the admin panel** and have changes reflect immediately on user-facing pages.

---

## ✨ Features

- ✅ **Edit from Admin Panel** - Change logo, text, images from one place
- ✅ **Instant Updates** - Changes reflect immediately (no code editing needed)
- ✅ **Centralized Management** - All content in database
- ✅ **Easy to Use** - Simple admin interface
- ✅ **No Cache Issues** - Content pulled fresh from database

---

## 🚀 Quick Setup (3 Steps)

### Step 1: Ensure Database Table Exists

Make sure you have the `site_content` table in your database:

```sql
CREATE TABLE IF NOT EXISTS `site_content` (
  `content_id` int(11) NOT NULL AUTO_INCREMENT,
  `content_key` varchar(100) NOT NULL,
  `content_value` text NOT NULL,
  `content_type` enum('text','html','image','url') DEFAULT 'text',
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`content_id`),
  UNIQUE KEY `content_key` (`content_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### Step 2: Run the Converter

Visit this URL to automatically convert all pages:

```
http://localhost/SIA/evergreen-marketing/convert_to_dynamic_content.php
```

This will:
- Add `content_helper.php` to all pages
- Enable dynamic content loading
- Make pages pull from database

### Step 3: Manage Content

Go to admin panel:

```
http://localhost/SIA/evergreen-marketing/admin_dashboard.php?page=content
```

Now you can edit:
- Company logo
- Company name
- Contact information
- Hero section text
- All page content

---

## 📋 How It Works

### Before (Static):
```php
<img src="images/Logo.png">
<h1>Banking that grows with you</h1>
<p>Evergreen Bank</p>
```

### After (Dynamic):
```php
<?php require_once 'includes/content_helper.php'; ?>
<img src="<?php echo get_company_logo(); ?>">
<h1><?php echo get_hero_title(); ?></h1>
<p><?php echo get_company_name(); ?></p>
```

---

## 🎯 Available Content Functions

### Company Information
```php
get_company_name()        // "Evergreen Bank"
get_company_logo()        // "images/Logo.png"
get_contact_phone()       // "1-800-EVERGREEN"
get_contact_email()       // "evrgrn.64@gmail.com"
```

### Hero Section
```php
get_hero_title()          // "Banking that grows with you"
get_hero_paragraph()      // Hero description
get_hero_card_title()     // Card title
get_hero_card_image()     // Hero image path
```

### Navigation
```php
get_nav_home_text()       // "Home"
get_nav_cards_text()      // "Cards"
get_nav_about_text()      // "About Us"
```

### Buttons
```php
get_btn_learn_more()      // "Learn More"
get_btn_open_account()    // "Open an Account"
get_btn_login()           // "Login"
```

### Social Media
```php
get_social_facebook_url()
get_social_instagram_url()
```

---

## 📁 Files That Will Be Updated

### Main Pages (17 files)
- `index.php` - Homepage
- `viewingpage.php` - Viewing page (logged in)
- `viewing.php` - Viewing page (no login)
- `about.php` - About page (logged in)
- `aboutno.php` - About page (no login)
- `learnmore.php` - Learn more (logged in)
- `learnmoreno.php` - Learn more (no login)
- `faq.php` - FAQ (logged in)
- `faqno.php` - FAQ (no login)
- `cardrewards.php` - Card rewards (logged in)
- `cardrewardsno.php` - Card rewards (no login)
- `refer.php` - Referral page
- `profile.php` - User profile
- `policy.php` - Privacy policy (logged in)
- `policyno.php` - Privacy policy (no login)
- `terms.php` - Terms (logged in)
- `termsno.php` - Terms (no login)

### Card Pages (8 files)
- `cards/credit.php` - Credit card page
- `cards/creditno.php` - Credit card (no login)
- `cards/debit.php` - Debit card page
- `cards/debitno.php` - Debit card (no login)
- `cards/prepaid.php` - Prepaid card page
- `cards/prepaidno.php` - Prepaid card (no login)
- `cards/points.php` - Points card page
- `cards/rewards.php` - Rewards card page

---

## 🔧 Manual Integration (If Needed)

If you want to manually add dynamic content to a page:

### Step 1: Add Include
At the top of your PHP file (after `<?php`):

```php
<?php
session_start(); // if you have this
require_once 'includes/content_helper.php';
?>
```

### Step 2: Replace Static Content

Replace hardcoded text with function calls:

```php
<!-- Before -->
<img src="images/Logo.png">

<!-- After -->
<img src="<?php echo get_company_logo(); ?>">
```

---

## 💡 Adding New Content Fields

### Step 1: Add to Database

```sql
INSERT INTO site_content (content_key, content_value, content_type) 
VALUES ('new_field_name', 'Default value', 'text');
```

### Step 2: Add Function to content_helper.php

```php
function get_new_field_name() {
    return get_site_content('new_field_name', 'Default value');
}
```

### Step 3: Use in Pages

```php
<?php echo get_new_field_name(); ?>
```

---

## 🎨 Updating Content from Admin

1. **Login to Admin Panel**
   ```
   http://localhost/SIA/evergreen-marketing/admin_login.php
   ```

2. **Go to Content Management**
   - Click "Content Management" in sidebar
   - Or visit: `?page=content`

3. **Edit Content**
   - Find the field you want to edit
   - Update the value
   - Click "Save All Changes"

4. **See Changes**
   - Visit any user-facing page
   - Changes appear immediately
   - No cache clearing needed (content is fresh from DB)

---

## 🔍 Troubleshooting

### Content Not Updating?

1. **Check Database Connection**
   ```php
   // In db_connect.php
   $conn = new mysqli($servername, $username, $password, $dbname);
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }
   ```

2. **Verify content_helper.php is Included**
   ```php
   // At top of page
   require_once 'includes/content_helper.php';
   ```

3. **Check Database Table**
   ```sql
   SELECT * FROM site_content WHERE content_key = 'company_logo';
   ```

4. **Clear PHP OpCache** (if enabled)
   ```php
   opcache_reset();
   ```

### Logo Not Showing?

1. Check the path in database:
   ```sql
   SELECT content_value FROM site_content WHERE content_key = 'company_logo';
   ```

2. Ensure path is relative: `images/Logo.png` not `/images/Logo.png`

3. Check file exists at that path

4. Clear browser cache (Ctrl+F5)

---

## 📊 Content Management Workflow

```
┌─────────────────────┐
│   Admin Panel       │
│  (Edit Content)     │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│   Database          │
│  (site_content)     │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│  content_helper.php │
│  (Load Content)     │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│   User Pages        │
│  (Display Content)  │
└─────────────────────┘
```

---

## ✅ Benefits

1. **No Code Editing** - Update content without touching PHP files
2. **Instant Changes** - See updates immediately
3. **Centralized** - All content in one place
4. **Version Control** - Track who changed what and when
5. **Easy Rollback** - Restore previous content from database
6. **Multi-language Ready** - Easy to add language support later

---

## 🎯 Best Practices

1. **Use Descriptive Keys**
   - Good: `hero_title`, `contact_email`
   - Bad: `text1`, `field2`

2. **Set Default Values**
   ```php
   get_site_content('key', 'Default if not found');
   ```

3. **Cache Content**
   - content_helper.php already caches in static variable
   - Reduces database queries

4. **Validate Input**
   - Admin panel should validate content before saving
   - Sanitize HTML content

5. **Backup Database**
   - Before major content changes
   - Regular automated backups

---

## 📞 Support

If you need help:
1. Check this guide first
2. Review `content_helper.php` for available functions
3. Check database for content keys
4. Test with simple content first

---

**Last Updated**: <?php echo date('F d, Y'); ?>
**System Status**: ✅ Ready to Use
**Files Covered**: 25+ pages (17 main pages + 8 card pages)
