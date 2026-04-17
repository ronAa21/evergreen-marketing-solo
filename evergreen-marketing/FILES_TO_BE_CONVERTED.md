# 📋 Complete List of Files to be Converted to Dynamic Content

## Total: 25 Files

---

## 📁 Main Pages (17 files)

### Homepage & Landing
1. ✅ `index.php` - Main homepage
2. ✅ `viewingpage.php` - Viewing page (logged in users)
3. ✅ `viewing.php` - Viewing page (public/no login)

### About & Information
4. ✅ `about.php` - About page (logged in)
5. ✅ `aboutno.php` - About page (public)
6. ✅ `learnmore.php` - Learn more (logged in)
7. ✅ `learnmoreno.php` - Learn more (public)

### FAQ & Support
8. ✅ `faq.php` - FAQ page (logged in)
9. ✅ `faqno.php` - FAQ page (public)

### Rewards & Referrals
10. ✅ `cardrewards.php` - Card rewards (logged in)
11. ✅ `cardrewardsno.php` - Card rewards (public)
12. ✅ `refer.php` - Referral program page

### User Profile
13. ✅ `profile.php` - User profile page

### Legal Pages
14. ✅ `policy.php` - Privacy policy (logged in)
15. ✅ `policyno.php` - Privacy policy (public)
16. ✅ `terms.php` - Terms & conditions (logged in)
17. ✅ `termsno.php` - Terms & conditions (public)

---

## 💳 Card Pages (8 files)

### Credit Cards
1. ✅ `cards/credit.php` - Credit card details page
2. ✅ `cards/creditno.php` - Credit card (public view)

### Debit Cards
3. ✅ `cards/debit.php` - Debit card details page
4. ✅ `cards/debitno.php` - Debit card (public view)

### Prepaid Cards
5. ✅ `cards/prepaid.php` - Prepaid card details page
6. ✅ `cards/prepaidno.php` - Prepaid card (public view)

### Special Cards
7. ✅ `cards/points.php` - Points card page
8. ✅ `cards/rewards.php` - Rewards card page

---

## 🔄 What Will Be Changed

### In Each File:

#### 1. Add Dynamic Content Include
```php
// At the top of each file (after <?php)
require_once 'includes/content_helper.php';  // For main pages
// OR
require_once '../includes/content_helper.php';  // For cards/ folder
```

#### 2. Logo Will Become Dynamic
**Before:**
```html
<img src="images/Logo.png">
<img src="../images/Logo.png">  <!-- in cards folder -->
```

**After:**
```php
<img src="<?php echo get_company_logo(); ?>">
<img src="<?php echo '../' . get_company_logo(); ?>">  <!-- in cards folder -->
```

#### 3. All Text Content Will Be Dynamic
**Before:**
```html
<h1>Banking that grows with you</h1>
<p>Evergreen Bank</p>
<a href="mailto:evrgrn.64@gmail.com">Contact Us</a>
```

**After:**
```php
<h1><?php echo get_hero_title(); ?></h1>
<p><?php echo get_company_name(); ?></p>
<a href="mailto:<?php echo get_contact_email(); ?>">Contact Us</a>
```

---

## 📊 File Statistics

| Category | Count | Path |
|----------|-------|------|
| Homepage & Landing | 3 | Root directory |
| About & Info | 4 | Root directory |
| FAQ & Support | 2 | Root directory |
| Rewards & Referrals | 3 | Root directory |
| User Profile | 1 | Root directory |
| Legal Pages | 4 | Root directory |
| **Main Pages Total** | **17** | |
| Credit Cards | 2 | cards/ folder |
| Debit Cards | 2 | cards/ folder |
| Prepaid Cards | 2 | cards/ folder |
| Special Cards | 2 | cards/ folder |
| **Card Pages Total** | **8** | |
| **GRAND TOTAL** | **25** | |

---

## 🎯 Content That Will Be Editable from Admin

After conversion, you can edit from admin panel:

### Company Information
- ✅ Company logo
- ✅ Company name
- ✅ Contact phone
- ✅ Contact email
- ✅ Company address

### Hero Section
- ✅ Hero title
- ✅ Hero paragraph
- ✅ Hero card title
- ✅ Hero card description
- ✅ Hero images

### Navigation
- ✅ Menu item text
- ✅ Button labels
- ✅ Link text

### Content Sections
- ✅ Section titles
- ✅ Section descriptions
- ✅ Feature descriptions
- ✅ Service descriptions

### Footer
- ✅ Footer tagline
- ✅ Footer address
- ✅ Copyright text
- ✅ Social media links

### Images
- ✅ Logo
- ✅ Hero images
- ✅ Feature images
- ✅ Service images
- ✅ Card images

---

## ⚡ How to Run Conversion

### Step 1: Backup (Recommended)
```bash
# Create backup of current files
cp -r evergreen-marketing evergreen-marketing-backup
```

### Step 2: Run Converter
Visit in browser:
```
http://localhost/SIA/evergreen-marketing/convert_to_dynamic_content.php
```

### Step 3: Verify
The converter will show:
- ✅ Files successfully converted
- ⏭️ Files already converted (skipped)
- ❌ Files with errors (if any)

### Step 4: Test
Visit these pages to verify:
1. Homepage: `index.php`
2. Card page: `cards/debit.php`
3. About page: `about.php`

### Step 5: Manage Content
Go to admin panel:
```
http://localhost/SIA/evergreen-marketing/admin_dashboard.php?page=content
```

---

## 🔍 Verification Checklist

After conversion, check:

- [ ] All 25 files show "Converted" or "Already Converted"
- [ ] No files show errors
- [ ] Homepage loads without errors
- [ ] Card pages load without errors
- [ ] Logo appears on all pages
- [ ] Text content displays correctly
- [ ] Admin content management works
- [ ] Changes in admin reflect on pages

---

## 🚨 Troubleshooting

### If a file fails to convert:

1. **Check file permissions**
   ```bash
   chmod 644 evergreen-marketing/*.php
   chmod 644 evergreen-marketing/cards/*.php
   ```

2. **Check if file has `<?php` tag**
   - File must start with `<?php`

3. **Manually add include**
   ```php
   <?php
   require_once 'includes/content_helper.php';
   // rest of code...
   ?>
   ```

### If content doesn't update:

1. **Clear browser cache** (Ctrl+F5)
2. **Check database connection** in `db_connect.php`
3. **Verify content_helper.php** exists
4. **Check database** has `site_content` table

---

## 📞 Support

If you encounter issues:
1. Check converter output for specific errors
2. Review `DYNAMIC_CONTENT_SETUP.md` for detailed guide
3. Verify database connection
4. Check file permissions

---

**Ready to Convert?**
Run: `http://localhost/SIA/evergreen-marketing/convert_to_dynamic_content.php`

**Total Files**: 25
**Estimated Time**: < 1 minute
**Backup Recommended**: Yes
