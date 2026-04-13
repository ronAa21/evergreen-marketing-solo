# InfinityFree Deployment Guide - Evergreen Bank Marketing System

## 🚀 Complete Deployment Guide for InfinityFree

### Prerequisites
- GitHub account (you have this ✅)
- Email address for InfinityFree registration
- Your project files ready

---

## Step 1: Create InfinityFree Account

1. Go to: https://www.infinityfree.net/
2. Click **"Sign Up Now"**
3. Fill in:
   - Email address
   - Password
   - Accept terms
4. Verify your email
5. Login to your account

---

## Step 2: Create Your Website

1. Click **"Create Account"** in the control panel
2. Fill in:
   - **Domain**: Choose a free subdomain (e.g., `evergreenbank.rf.gd`)
   - Or use your own domain if you have one
3. Click **"Create Account"**
4. Wait 2-5 minutes for account activation

---

## Step 3: Access cPanel

1. From InfinityFree dashboard, click **"Control Panel"**
2. You'll be redirected to cPanel (VistaPanel)
3. Note your credentials:
   - **FTP Hostname**: (will be shown)
   - **FTP Username**: (will be shown)
   - **FTP Password**: (set during creation)

---

## Step 4: Create MySQL Database

1. In cPanel, find **"MySQL Databases"**
2. Click **"Create Database"**
3. Database name: `bankingdb` (or any name)
4. Click **"Create Database"**
5. **IMPORTANT**: Note these credentials:
   ```
   Database Name: epiz_XXXXXXX_bankingdb
   Database Host: sqlXXX.infinityfreeapp.com
   Database User: epiz_XXXXXXX
   Database Password: [your password]
   ```

---

## Step 5: Import Database

### Option A: Using phpMyAdmin (Recommended)

1. In cPanel, click **"phpMyAdmin"**
2. Select your database from left sidebar
3. Click **"Import"** tab
4. Click **"Choose File"**
5. Select: `sql/bankingdb_fixed.sql` (or `sql/unified_schema.sql`)
6. Click **"Go"** at bottom
7. Wait for import to complete

### Option B: Using SQL Query

If file is too large:
1. Open phpMyAdmin
2. Click **"SQL"** tab
3. Copy contents from `sql/bankingdb_fixed.sql`
4. Paste and click **"Go"**
5. Repeat for large files in chunks

---

## Step 6: Update Database Configuration

Update `db_connect.php` with your InfinityFree credentials:

```php
<?php
// InfinityFree Database Configuration
$host = 'sqlXXX.infinityfreeapp.com'; // Your DB host
$username = 'epiz_XXXXXXX';            // Your DB username
$password = 'your_password_here';      // Your DB password
$database = 'epiz_XXXXXXX_bankingdb';  // Your DB name
$port = '3306';

$conn = new mysqli($host, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
```

---

## Step 7: Upload Files

### Option A: File Manager (Easy)

1. In cPanel, click **"File Manager"**
2. Navigate to **"htdocs"** folder
3. Delete default files (index.html, etc.)
4. Click **"Upload"**
5. Upload ALL your project files
6. **Important folder structure:**
   ```
   htdocs/
   ├── index.php
   ├── login.php
   ├── signup.php
   ├── admin_dashboard.php
   ├── db_connect.php
   ├── includes/
   ├── assets/
   ├── images/
   ├── cards/
   ├── uploads/
   └── ... (all other files)
   ```

### Option B: FTP (Faster for many files)

1. Download **FileZilla** (https://filezilla-project.org/)
2. Connect using:
   - **Host**: ftpupload.net (or your FTP host)
   - **Username**: epiz_XXXXXXX
   - **Password**: [your password]
   - **Port**: 21
3. Navigate to **"htdocs"** folder on right panel
4. Drag all your project files from left to right

### Option C: Git Deployment (Advanced)

InfinityFree doesn't support direct Git deployment, but you can:
1. Use GitHub Actions to auto-deploy via FTP
2. I can create this workflow if needed

---

## Step 8: Set Folder Permissions

1. In File Manager, right-click on **"uploads"** folder
2. Click **"Change Permissions"**
3. Set to **755** or **777** (for write access)
4. Check **"Recurse into subdirectories"**
5. Click **"Change Permissions"**

Repeat for:
- `uploads/id_documents/` → 755
- `uploads/id_images/` → 755
- `uploads/ads/` → 755
- `cache/` → 755

---

## Step 9: Configure PHP Settings (Optional)

Create `.htaccess` file in htdocs:

```apache
# PHP Settings
php_value upload_max_filesize 10M
php_value post_max_size 10M
php_value max_execution_time 300
php_value max_input_time 300

# Security
Options -Indexes

# Error Handling
php_flag display_errors Off
php_flag log_errors On

# Timezone
php_value date.timezone "Asia/Manila"

# Redirect to HTTPS (if available)
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

---

## Step 10: Test Your Website

1. Visit your domain: `http://yourdomain.rf.gd`
2. Test pages:
   - ✅ Homepage: `index.php`
   - ✅ Login: `login.php`
   - ✅ Signup: `signup.php`
   - ✅ Admin: `admin_login.php`
3. Check database connection
4. Test file uploads
5. Test admin dashboard

---

## Step 11: Setup Email (PHPMailer)

InfinityFree blocks port 25 (SMTP). Use external SMTP:

### Option A: Gmail SMTP (Recommended)

Update your email configuration:
```php
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->Username = 'your-email@gmail.com';
$mail->Password = 'your-app-password'; // Use App Password, not regular password
```

### Option B: SendGrid (Free 100 emails/day)
1. Sign up: https://sendgrid.com/
2. Get API key
3. Use SendGrid SMTP

---

## Common Issues & Solutions

### Issue 1: Database Connection Failed
**Solution**: 
- Double-check credentials in `db_connect.php`
- Ensure database host is correct (sqlXXX.infinityfreeapp.com)
- Check if database was created successfully

### Issue 2: 403 Forbidden Error
**Solution**:
- Check folder permissions (755)
- Ensure index.php exists in htdocs
- Check .htaccess for errors

### Issue 3: File Upload Not Working
**Solution**:
- Set uploads folder to 755 or 777
- Check PHP upload limits in .htaccess
- Verify folder exists: `uploads/id_documents/`

### Issue 4: Images Not Loading
**Solution**:
- Check image paths (use relative paths)
- Ensure images folder uploaded correctly
- Check file permissions

### Issue 5: Email Not Sending
**Solution**:
- Use external SMTP (Gmail, SendGrid)
- Don't use mail() function (blocked)
- Use PHPMailer with SMTP

### Issue 6: Session Issues
**Solution**:
- Check if session_start() is called
- Verify session folder permissions
- Clear browser cookies

---

## InfinityFree Limitations

⚠️ **Be Aware:**
- **Daily Hits**: 50,000 hits/day
- **Storage**: 5GB
- **Bandwidth**: Unlimited
- **MySQL**: 400 databases, 1024MB each
- **Email**: Must use external SMTP
- **Cron Jobs**: Not available (use external services)
- **Ads**: May show ads (can be removed with premium)

---

## Performance Tips

1. **Enable Caching**: Use browser caching in .htaccess
2. **Optimize Images**: Compress images before upload
3. **Minify CSS/JS**: Reduce file sizes
4. **Use CDN**: For external libraries (Bootstrap, jQuery)
5. **Database Optimization**: Add indexes, optimize queries

---

## Security Checklist

- ✅ Change default admin password
- ✅ Use prepared statements (already done)
- ✅ Validate all user inputs
- ✅ Set proper folder permissions
- ✅ Hide error messages in production
- ✅ Use HTTPS (if available)
- ✅ Regular database backups

---

## Backup Strategy

### Manual Backup:
1. **Files**: Download via FTP regularly
2. **Database**: Export from phpMyAdmin weekly

### Automated Backup:
- Use cPanel backup feature (if available)
- Download backups to local machine

---

## Next Steps After Deployment

1. ✅ Test all functionality
2. ✅ Setup admin account
3. ✅ Add initial content
4. ✅ Test email functionality
5. ✅ Monitor error logs
6. ✅ Setup Google Analytics (optional)
7. ✅ Add custom domain (optional)

---

## Support Resources

- **InfinityFree Forum**: https://forum.infinityfree.net/
- **Knowledge Base**: https://infinityfree.net/support/
- **Status Page**: https://status.infinityfree.net/

---

## Upgrade Options

If you need more features:
- **iFastNet Premium**: $4.99/month
  - No ads
  - More resources
  - Priority support
  - Cron jobs

---

## Quick Reference

### Your Credentials Template:
```
Website URL: http://____________.rf.gd
FTP Host: ftpupload.net
FTP Username: epiz_____________
FTP Password: ________________
Database Host: sql___.infinityfreeapp.com
Database Name: epiz_____________
Database User: epiz_____________
Database Password: ________________
cPanel URL: https://cpanel.infinityfree.net/
```

---

## Troubleshooting Commands

Check PHP version:
```php
<?php phpinfo(); ?>
```

Test database connection:
```php
<?php
include('db_connect.php');
echo "Connected successfully!";
?>
```

---

## Need Help?

If you encounter issues:
1. Check InfinityFree forum
2. Review error logs in cPanel
3. Test locally first
4. Contact InfinityFree support

---

**Good luck with your deployment! 🚀**
