# Hero Paragraph Dynamic Content Setup

## What Changed

The hero section description on the main page is now dynamic and can be edited from the Admin Dashboard.

**Before:** Hard-coded text
```
Secure financial solutions for every stage of your life journey.
Invest, save, and achieve your goals with Evergreen.
```

**After:** Dynamic content loaded from database via `get_hero_paragraph()` function

## Setup Instructions

### Step 1: Run the Migration

Visit this URL in your browser (one time only):
```
http://localhost/SIA/EverGG/evergreen-marketing/run_hero_paragraph_migration.php
```

This will add the `hero_paragraph` content key to your database.

### Step 2: Edit Content from Admin Dashboard

1. Login to Admin Dashboard: `http://localhost/SIA/EverGG/evergreen-marketing/admin_login.php`
2. Go to **Manage Content** section
3. Find the **"Hero Paragraph"** card
4. Edit the text as needed
5. Click **Save Changes**

### Step 3: Verify Changes

1. Visit the main page: `http://localhost/SIA/EverGG/evergreen-marketing/viewingpage.php`
2. The hero section description should now show your updated text
3. Press `Ctrl + F5` to clear cache if needed

## Files Modified

1. **includes/content_helper.php** - Added `get_hero_paragraph()` function
2. **viewingpage.php** - Updated to use dynamic content
3. **sql/add_hero_paragraph.sql** - SQL migration file
4. **run_hero_paragraph_migration.php** - Migration runner script

## How It Works

```php
// In content_helper.php
function get_hero_paragraph() {
    return get_site_content('hero_paragraph', 'Default text...');
}

// In viewingpage.php
<p><?php echo htmlspecialchars(get_hero_paragraph()); ?></p>
```

The content is cached in memory during each page load to minimize database queries.

## Troubleshooting

**Issue:** Changes don't appear on the website
- **Solution:** Clear browser cache with `Ctrl + F5`

**Issue:** "hero_paragraph" doesn't show in Admin Dashboard
- **Solution:** Run the migration script again

**Issue:** Database error
- **Solution:** Check that `site_content` table exists in your database

## Database Structure

```sql
site_content table:
- content_id (int, auto_increment)
- content_key (varchar, unique) = 'hero_paragraph'
- content_value (text) = Your editable text
- content_type (enum) = 'text'
- updated_at (timestamp)
- updated_by (int, admin_id)
```
