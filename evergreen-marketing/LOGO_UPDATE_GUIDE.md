# 🎨 Logo Update Guide

## Quick Update Method

### Option 1: Replace Image Files (Easiest)
Simply replace the logo image files in the `images/` folder:

1. **Main Logo** - Replace `images/Logo.png` with your new logo
2. **Login Logo** - Replace `images/loginlogo.png` with your new logo
3. Keep the same filenames to avoid code changes

### Option 2: Update Configuration File
Edit `config/logo_config.php` to change logo paths:

```php
define('LOGO_MAIN', 'images/YourNewLogo.png');
define('LOGO_LOGIN', 'images/YourNewLoginLogo.png');
```

---

## Files That Use Logos

### 📁 Main Navigation/Header Files
- `evergreen-marketing/index.php`
- `evergreen-marketing/viewingpage.php`
- `evergreen-marketing/viewing.php`
- `evergreen-marketing/about.php`
- `evergreen-marketing/aboutno.php`
- `evergreen-marketing/learnmore.php`
- `evergreen-marketing/learnmoreno.php`
- `evergreen-marketing/cardrewards.php`
- `evergreen-marketing/cardrewardsno.php`
- `evergreen-marketing/faq.php`
- `evergreen-marketing/policy.php`
- `evergreen-marketing/policyno.php`
- `evergreen-marketing/terms.php`
- `evergreen-marketing/termsno.php`

### 🔐 Authentication Pages
- `evergreen-marketing/login.php`
- `evergreen-marketing/signup.php`
- `evergreen-marketing/verify.php`
- `evergreen-marketing/forgotpassword.php`
- `evergreen-marketing/admin_login.php`

### 💳 Card Pages
**evergreen-marketing/cards/**
- `evergreen-marketing/cards/credit.php`
- `evergreen-marketing/cards/creditno.php`
- `evergreen-marketing/cards/debit.php`
- `evergreen-marketing/cards/debitno.php`
- `evergreen-marketing/cards/prepaid.php`
- `evergreen-marketing/cards/prepaidno.php`
- `evergreen-marketing/cards/points.php`
- `evergreen-marketing/cards/rewards.php`

**Root cards/ folder**
- `cards/credit.php`
- `cards/creditno.php`
- `cards/debit.php`
- `cards/debitno.php`
- `cards/prepaid.php`
- `cards/prepaidno.php`
- `cards/points.php`
- `cards/rewards.php`

### 👤 User Profile Pages
- `evergreen-marketing/profile.php`
- `evergreen-marketing/refer.php`

### 🎯 Admin Pages
- `evergreen-marketing/admin_dashboard.php`
- `evergreen-marketing/admin_login.php`
- `evergreen-marketing/admin_statistics.php`
- `evergreen-marketing/admin_card_applications.php`
- `evergreen-marketing/admin_content_management.php`

### 📝 Content Management
- `evergreen-marketing/Content-view/index.php`
- `evergreen-marketing/Content-view/ads-view.php`

---

## Logo Specifications

### Recommended Sizes
- **Main Logo**: 200x60px (PNG with transparent background)
- **Login Logo**: 150x150px (PNG with transparent background)
- **Favicon**: 32x32px or 64x64px (ICO or PNG)

### File Formats
- Primary: PNG (supports transparency)
- Alternative: SVG (scalable, best for logos)
- Avoid: JPG (no transparency support)

---

## Automated Update Script

Run this command to update all logo references at once:

```bash
# Search for all logo references
grep -r "images/Logo.png" evergreen-marketing/

# Replace old logo with new logo (example)
find evergreen-marketing/ -type f -name "*.php" -exec sed -i 's/images\/Logo\.png\.png/images\/Logo.png/g' {} +
```

---

## Current Logo Issues Found

### ❌ Duplicate Extension Issue
Some files reference: `images/Logo.png.png` (incorrect)
Should be: `images/Logo.png`

### Files with Incorrect Path:
1. `signup.php` - Line 1189
2. `viewing.php` - Line 1590
3. `cards/points.php` - Line 1469
4. Multiple card pages

---

## Quick Fix Script

Create a file `fix_logo_paths.php` and run it once:

```php
<?php
$files = glob('evergreen-marketing/**/*.php');

foreach ($files as $file) {
    $content = file_get_contents($file);
    
    // Fix double extension
    $content = str_replace('Logo.png.png', 'Logo.png', $content);
    
    // Standardize paths
    $content = str_replace('images/loginlogo.png', 'images/Logo.png', $content);
    
    file_put_contents($file, $content);
}

echo "Logo paths fixed!";
?>
```

---

## Best Practices

1. ✅ **Use consistent naming**: Stick to one logo filename
2. ✅ **Optimize images**: Compress logos for faster loading
3. ✅ **Use alt text**: Always include descriptive alt text
4. ✅ **Test responsive**: Ensure logo looks good on mobile
5. ✅ **Version control**: Keep backup of old logos

---

## Testing Checklist

After updating logos, test these pages:

- [ ] Homepage (index.php)
- [ ] Login page
- [ ] Signup page
- [ ] All card pages
- [ ] Profile page
- [ ] Admin dashboard
- [ ] Mobile view
- [ ] Print view

---

## Support

If you encounter issues:
1. Clear browser cache (Ctrl+F5)
2. Check file permissions (755 for folders, 644 for files)
3. Verify image paths are correct
4. Check console for 404 errors

---

**Last Updated**: <?php echo date('F d, Y'); ?>
