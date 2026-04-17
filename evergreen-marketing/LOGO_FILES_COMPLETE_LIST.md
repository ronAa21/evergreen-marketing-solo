# 📋 Complete List of Files with Logo References

## Total Files: 60+ PHP files across the system

---

## 📁 Directory Structure

```
SIA/
├── evergreen-marketing/
│   ├── images/
│   │   ├── Logo.png ⭐ (Main logo - UPDATE THIS)
│   │   ├── loginlogo.png ⭐ (Login logo - UPDATE THIS)
│   │   └── icon.png (Favicon)
│   │
│   ├── cards/
│   │   ├── credit.php ✓
│   │   ├── creditno.php ✓
│   │   ├── debit.php ✓
│   │   ├── debitno.php ✓
│   │   ├── prepaid.php ✓
│   │   ├── prepaidno.php ✓
│   │   ├── points.php ✓
│   │   └── rewards.php ✓
│   │
│   ├── Content-view/
│   │   ├── index.php ✓
│   │   └── ads-view.php ✓
│   │
│   ├── includes/
│   │   └── content_helper.php ✓
│   │
│   ├── index.php ✓
│   ├── login.php ✓
│   ├── signup.php ✓
│   ├── verify.php ✓
│   ├── forgotpassword.php ✓
│   ├── profile.php ✓
│   ├── viewingpage.php ✓
│   ├── viewing.php ✓
│   ├── about.php ✓
│   ├── aboutno.php ✓
│   ├── learnmore.php ✓
│   ├── learnmoreno.php ✓
│   ├── cardrewards.php ✓
│   ├── cardrewardsno.php ✓
│   ├── faq.php ✓
│   ├── policy.php ✓
│   ├── policyno.php ✓
│   ├── terms.php ✓
│   ├── termsno.php ✓
│   ├── refer.php ✓
│   ├── admin_dashboard.php ✓
│   ├── admin_login.php ✓
│   ├── admin_statistics.php ✓
│   ├── admin_card_applications.php ✓
│   └── admin_content_management.php ✓
│
└── cards/ (Root level)
    ├── credit.php ✓
    ├── creditno.php ✓
    ├── debit.php ✓
    ├── debitno.php ✓
    ├── prepaid.php ✓
    ├── prepaidno.php ✓
    ├── points.php ✓
    └── rewards.php ✓
```

---

## 🎯 Files by Category

### 🏠 Homepage & Landing Pages (8 files)
1. `evergreen-marketing/index.php`
2. `evergreen-marketing/viewingpage.php`
3. `evergreen-marketing/viewing.php`
4. `evergreen-marketing/about.php`
5. `evergreen-marketing/aboutno.php`
6. `evergreen-marketing/learnmore.php`
7. `evergreen-marketing/learnmoreno.php`
8. `evergreen-marketing/faq.php`

### 🔐 Authentication Pages (5 files)
1. `evergreen-marketing/login.php`
2. `evergreen-marketing/signup.php`
3. `evergreen-marketing/verify.php`
4. `evergreen-marketing/forgotpassword.php`
5. `evergreen-marketing/admin_login.php`

### 💳 Card Pages - evergreen-marketing/cards/ (8 files)
1. `evergreen-marketing/cards/credit.php`
2. `evergreen-marketing/cards/creditno.php`
3. `evergreen-marketing/cards/debit.php`
4. `evergreen-marketing/cards/debitno.php`
5. `evergreen-marketing/cards/prepaid.php`
6. `evergreen-marketing/cards/prepaidno.php`
7. `evergreen-marketing/cards/points.php`
8. `evergreen-marketing/cards/rewards.php`

### 💳 Card Pages - Root cards/ (8 files)
1. `cards/credit.php`
2. `cards/creditno.php`
3. `cards/debit.php`
4. `cards/debitno.php`
5. `cards/prepaid.php`
6. `cards/prepaidno.php`
7. `cards/points.php`
8. `cards/rewards.php`

### 🎁 Rewards & Referral Pages (3 files)
1. `evergreen-marketing/cardrewards.php`
2. `evergreen-marketing/cardrewardsno.php`
3. `evergreen-marketing/refer.php`

### 📄 Legal & Policy Pages (4 files)
1. `evergreen-marketing/policy.php`
2. `evergreen-marketing/policyno.php`
3. `evergreen-marketing/terms.php`
4. `evergreen-marketing/termsno.php`

### 👤 User Profile Pages (1 file)
1. `evergreen-marketing/profile.php`

### 👨‍💼 Admin Pages (4 files)
1. `evergreen-marketing/admin_dashboard.php`
2. `evergreen-marketing/admin_login.php`
3. `evergreen-marketing/admin_statistics.php`
4. `evergreen-marketing/admin_card_applications.php`
5. `evergreen-marketing/admin_content_management.php`

### 📝 Content Management (2 files)
1. `evergreen-marketing/Content-view/index.php`
2. `evergreen-marketing/Content-view/ads-view.php`

### 🔧 Helper Files (1 file)
1. `evergreen-marketing/includes/content_helper.php`

---

## 🔍 Logo Path Patterns Found

### Current Issues:
- ❌ `images/Logo.png.png` (double extension)
- ❌ `../images/Logo.png.png` (double extension in cards folder)
- ❌ Inconsistent paths across files

### After Fix:
- ✅ `images/Logo.png` (correct)
- ✅ `../images/Logo.png` (correct for cards folder)
- ✅ `images/loginlogo.png` (correct for login pages)

---

## 📊 Statistics

| Category | Count |
|----------|-------|
| Total PHP Files | 60+ |
| Card Pages (both folders) | 16 |
| Authentication Pages | 5 |
| Admin Pages | 5 |
| Landing Pages | 8 |
| Legal Pages | 4 |
| Other Pages | 22+ |

---

## ⚡ Quick Update Command

Run the automated fixer to update all files at once:

```
http://localhost/SIA/evergreen-marketing/fix_all_logos.php
```

This will:
- ✅ Scan all 60+ files
- ✅ Fix double extensions (.png.png → .png)
- ✅ Standardize all paths
- ✅ Update both card folders
- ✅ Show detailed report
- ✅ Create backup log

---

## 🎨 Logo Specifications

### Main Logo (Logo.png)
- **Location**: `evergreen-marketing/images/Logo.png`
- **Used in**: Navigation headers, footers, main pages
- **Recommended size**: 200x60px
- **Format**: PNG with transparent background

### Login Logo (loginlogo.png)
- **Location**: `evergreen-marketing/images/loginlogo.png`
- **Used in**: Login, signup, verification pages
- **Recommended size**: 150x150px
- **Format**: PNG with transparent background

---

## ✅ Verification Checklist

After running the fixer, verify these pages:

- [ ] Homepage (index.php)
- [ ] Login page
- [ ] Signup page
- [ ] All 8 card types (credit, debit, prepaid, points, rewards)
- [ ] Profile page
- [ ] Admin dashboard
- [ ] About page
- [ ] FAQ page
- [ ] Mobile responsive view

---

**Last Updated**: <?php echo date('F d, Y'); ?>
**Total Files Covered**: 60+
**Automation**: ✅ Available via fix_all_logos.php
