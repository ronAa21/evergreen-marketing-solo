# Dynamic Content System - Complete Setup

## ✅ All Content is Now Dynamic!

Both `viewing.php` and `viewingpage.php` now pull content from the database that can be edited via the Admin Dashboard.

## Dynamic Content Fields

| Content Field | Where It Appears | Admin Panel Name |
|--------------|------------------|------------------|
| **Company Name** | Navigation bar (both pages) | Company Name |
| **Company Logo** | Navigation & Footer (both pages) | Company Logo |
| **Hero Title** | Main hero section h1 (both pages) | Hero Title |
| **Hero Paragraph** | Hero section description (both pages) | Hero Paragraph |
| **Contact Phone** | Footer contact section (both pages) | Contact Phone |
| **Contact Email** | Footer contact section (both pages) | Contact Email |

## How to Use

### Step 1: Run Migration (One Time Only)
Visit: `http://localhost/SIA/EverGG/evergreen-marketing/run_hero_paragraph_migration.php`

This will:
- Add the `hero_paragraph` field to your database
- Update the `hero_title` to "Banking that grows with you"

### Step 2: Edit Content
1. Login to Admin Dashboard: `http://localhost/SIA/EverGG/evergreen-marketing/admin_login.php`
2. Go to "Manage Content"
3. You'll see cards for each editable field:
   - **Company Name** - Your bank's name
   - **Company Logo** - Path to logo image
   - **Hero Title** - Main heading on homepage
   - **Hero Paragraph** - Description text below title
   - **Contact Phone** - Phone number in footer
   - **Contact Email** - Email address in footer

4. Edit any field and click "Save Changes"
5. Changes appear immediately on both pages!

### Step 3: View Changes
- **Logged Out Users**: `http://localhost/SIA/EverGG/evergreen-marketing/viewing.php`
- **Logged In Users**: `http://localhost/SIA/EverGG/evergreen-marketing/viewingpage.php`

Press `Ctrl + F5` to clear cache if changes don't appear immediately.

## Technical Details

### Files Modified
1. **includes/content_helper.php** - Contains all dynamic content functions
2. **viewing.php** - Updated to use dynamic content
3. **viewingpage.php** - Already using dynamic content
4. **admin_content_management.php** - Admin interface (no changes needed)

### How It Works
```php
// In content_helper.php
function get_hero_title() {
    return get_site_content('hero_title', 'Default value');
}

// In viewing.php and viewingpage.php
<h1><?php echo htmlspecialchars(get_hero_title()); ?></h1>
```

The system:
1. Loads all content from database once per page load
2. Caches it in memory for performance
3. Returns the value when requested
4. Falls back to default if database value doesn't exist

### Database Structure
```sql
site_content table:
- content_id (int, auto_increment)
- content_key (varchar, unique) - e.g., 'hero_title'
- content_value (text) - The actual content
- content_type (enum) - 'text', 'image', or 'html'
- updated_at (timestamp) - Last update time
- updated_by (int) - Admin who made the change
```

## Adding More Dynamic Content

Want to make more content editable? Follow this pattern:

### 1. Add to Database
```sql
INSERT INTO site_content (content_key, content_value, content_type) 
VALUES ('new_field', 'Default value', 'text');
```

### 2. Add Function to content_helper.php
```php
function get_new_field() {
    return get_site_content('new_field', 'Default value');
}
```

### 3. Use in Your Pages
```php
<?php echo htmlspecialchars(get_new_field()); ?>
```

### 4. It Automatically Appears in Admin Panel!
The admin panel automatically shows all fields in the `site_content` table.

## Troubleshooting

**Problem**: Changes don't appear on website
- **Solution**: Clear browser cache with `Ctrl + F5`

**Problem**: Field doesn't show in Admin Dashboard
- **Solution**: Check that it exists in the `site_content` database table

**Problem**: Error "Call to undefined function"
- **Solution**: Make sure `include_once(__DIR__ . '/includes/content_helper.php');` is at the top of your PHP file

**Problem**: Logo not displaying
- **Solution**: Check that the image path in the database is correct (e.g., `images/Logo.png.png`)

## Security Notes

- All output uses `htmlspecialchars()` to prevent XSS attacks
- Only authenticated admins can edit content
- Database queries use prepared statements
- Content is cached per request for performance

## Next Steps

You can now:
1. ✅ Edit company name and see it update everywhere
2. ✅ Change hero title and paragraph from admin panel
3. ✅ Update contact information in one place
4. ✅ Swap logo by changing the image path

All changes are instant and require no code modifications!
