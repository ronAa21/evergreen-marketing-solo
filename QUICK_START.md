# Quick Start - Complete Dynamic Content System

## 🚀 3-Step Setup

### Step 1: Run Migration (2 minutes)
Visit: `http://localhost/SIA/EverGG/evergreen-marketing/run_complete_migration.php`

Click through and wait for "Migration Completed Successfully!"

### Step 2: Files Already Updated
✅ `content_helper.php` - All 65+ functions ready
✅ `admin_content_management.php` - Already shows all fields
✅ Migration scripts created

### Step 3: Update Frontend Files
You need to replace hard-coded text in:
- `viewingpage.php` (logged-in users)
- `viewing.php` (logged-out users)

## 📝 What to Replace

### Find & Replace Examples

**Hero Title:**
```php
// OLD:
<h1>Banking that grows <br>with <span class="highlight">you</span></br></h1>

// NEW:
<h1><?php echo htmlspecialchars(get_hero_title()); ?></h1>
```

**Solutions Title:**
```php
// OLD:
<h2>Financial Solutions for Every Need</h2>

// NEW:
<h2><?php echo htmlspecialchars(get_solutions_title()); ?></h2>
```

**Loan Title:**
```php
// OLD:
<h3>Personal Loan</h3>

// NEW:
<h3><?php echo htmlspecialchars(get_loan_1_title()); ?></h3>
```

## 🎯 All Available Functions

### Company (4)
- `get_company_name()`
- `get_company_logo()`
- `get_contact_phone()`
- `get_contact_email()`

### Hero Section (5)
- `get_hero_title()`
- `get_hero_paragraph()`
- `get_hero_card_title()`
- `get_hero_card_description()`
- `get_hero_card_image()`

### Solutions (13)
- `get_solutions_title()`
- `get_solutions_intro()`
- `get_solution_1_icon()` through `get_solution_4_icon()`
- `get_solution_1_title()` through `get_solution_4_title()`
- `get_solution_1_description()` through `get_solution_4_description()`

### Rewards (4)
- `get_rewards_title()`
- `get_rewards_description()`
- `get_rewards_button_text()`
- `get_rewards_image()`

### Loans (13)
- `get_loans_title()`
- `get_loan_1_title()` through `get_loan_4_title()`
- `get_loan_1_description()` through `get_loan_4_description()`
- `get_loan_1_image()` through `get_loan_4_image()`

### Career (9)
- `get_career_title()`
- `get_career_intro()`
- `get_career_how_to_apply_title()`
- `get_career_how_to_apply_text()`
- `get_career_location_title()`
- `get_career_location_address()`
- `get_career_requirements_title()`
- `get_career_note()`
- `get_career_image()`

### Footer (3)
- `get_footer_tagline()`
- `get_footer_address()`
- `get_footer_copyright()`

### Navigation (4)
- `get_nav_home_text()`
- `get_nav_cards_text()`
- `get_nav_whatsnew_text()`
- `get_nav_about_text()`

### Buttons (4)
- `get_btn_learn_more()`
- `get_btn_open_account()`
- `get_btn_get_started()`
- `get_btn_login()`

### Social Media (2)
- `get_social_facebook_url()`
- `get_social_instagram_url()`

## 🔒 Security Rules

**For regular text:**
```php
<?php echo htmlspecialchars(get_function_name()); ?>
```

**For HTML content (addresses, formatted text):**
```php
<?php echo get_function_name(); ?>
```

HTML fields:
- `get_career_intro()`
- `get_career_location_address()`
- `get_footer_address()`
- `get_footer_copyright()`

## ✅ Testing Checklist

After updating files:

1. ☐ Run migration script
2. ☐ Login to admin panel
3. ☐ Edit a field (e.g., hero title)
4. ☐ Save changes
5. ☐ Visit website
6. ☐ Press `Ctrl + F5` to clear cache
7. ☐ Verify change appears
8. ☐ Test on both viewing.php and viewingpage.php

## 🎨 Admin Panel Usage

1. Login: `admin_login.php`
2. Go to "Manage Content"
3. Find the field you want to edit
4. Change the text
5. Click "💾 Save Changes"
6. Done! Changes are live instantly

## 📊 Content Organization

Fields are prefixed by section:
- `hero_*` = Hero section
- `solution_*` = Financial solutions
- `rewards_*` = Rewards section
- `loan_*` = Loan services
- `career_*` = Career section
- `footer_*` = Footer
- `nav_*` = Navigation
- `btn_*` = Buttons
- `social_*` = Social media

## 💡 Pro Tips

1. **Update one section at a time** - Easier to test
2. **Keep backups** - Copy files before editing
3. **Test after each section** - Catch errors early
4. **Use Ctrl+F5** - Always clear cache when testing
5. **Check both pages** - viewing.php AND viewingpage.php

## 🆘 Need Help?

Check these files:
- `COMPLETE_DYNAMIC_CONTENT_GUIDE.md` - Full documentation
- `content_helper.php` - All function definitions
- `run_complete_migration.php` - Migration script

---

**Ready?** Run the migration and start updating your files! 🚀
