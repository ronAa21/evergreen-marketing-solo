# 🎨 Simple Logo Replacement Guide

## The Easiest Way to Update Your Logo

### Step 1: Prepare Your New Logo
- Save your new logo as `Logo.png`
- Recommended size: 200x60 pixels
- Format: PNG with transparent background

### Step 2: Replace the File
1. Navigate to: `evergreen-marketing/images/`
2. **Backup** the old `Logo.png` (rename it to `Logo_old.png`)
3. **Upload** your new `Logo.png` file
4. Done! ✅

### Step 3: Clear Cache
- Press `Ctrl + F5` on your browser to see the new logo

---

## If You See Duplicate Extensions (.png.png)

Run the automated fixer:

1. Open your browser
2. Go to: `http://localhost/evergreen-marketing/fix_all_logos.php`
3. Click "Run Fix"
4. Done! All logo paths will be corrected automatically

---

## Logo Files Location

```
evergreen-marketing/
└── images/
    ├── Logo.png          ← Main logo (replace this)
    ├── loginlogo.png     ← Login page logo
    └── icon.png          ← Favicon/small icon
```

---

## Quick Checklist

- [ ] New logo is PNG format
- [ ] Logo has transparent background
- [ ] Logo is properly sized (not too large)
- [ ] Old logo is backed up
- [ ] New logo uploaded to `images/` folder
- [ ] Browser cache cleared
- [ ] Tested on homepage
- [ ] Tested on login page
- [ ] Tested on mobile view

---

## Common Issues

### Logo not showing?
1. Check file name is exactly `Logo.png` (case-sensitive)
2. Clear browser cache (Ctrl+F5)
3. Check file permissions (should be 644)

### Logo looks blurry?
1. Use higher resolution image
2. Save as PNG, not JPG
3. Use 2x size for retina displays

### Logo too big/small?
1. Resize image before uploading
2. Or adjust CSS in the page

---

## Need Help?

Contact your developer or check the detailed guide:
📄 `LOGO_UPDATE_GUIDE.md`
