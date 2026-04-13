# 📋 InfinityFree Deployment Checklist

## Pre-Deployment

- [ ] Backup local database
- [ ] Test all features locally
- [ ] Export database to SQL file
- [ ] Prepare all files for upload
- [ ] Create InfinityFree account

## Database Setup

- [ ] Create MySQL database on InfinityFree
- [ ] Note database credentials:
  - [ ] Database Host: `sql___.infinityfreeapp.com`
  - [ ] Database Name: `epiz_______`
  - [ ] Database User: `epiz_______`
  - [ ] Database Password: `________`
- [ ] Import database via phpMyAdmin
- [ ] Verify tables imported correctly
- [ ] Test database connection

## File Configuration

- [ ] Update `db_connect.php` with production credentials
- [ ] Update `.htaccess` with your domain
- [ ] Check file permissions requirements
- [ ] Verify PHPMailer SMTP settings
- [ ] Update any hardcoded localhost URLs

## File Upload

- [ ] Delete default files in htdocs
- [ ] Upload all PHP files
- [ ] Upload all folders:
  - [ ] `includes/`
  - [ ] `images/`
  - [ ] `cards/`
  - [ ] `uploads/`
  - [ ] `Content-view/`
  - [ ] `Admin-side/`
  - [ ] `PHPMailer-7.0.0/`
  - [ ] `api/`
  - [ ] `cache/`
  - [ ] `sql/` (optional, for backup)
- [ ] Verify folder structure matches local

## Permissions Setup

- [ ] Set `uploads/` to 755
- [ ] Set `uploads/id_documents/` to 755
- [ ] Set `uploads/id_images/` to 755
- [ ] Set `uploads/ads/` to 755
- [ ] Set `cache/` to 755
- [ ] Verify write permissions work

## Testing Phase

### Basic Tests
- [ ] Visit homepage: `http://yourdomain.rf.gd`
- [ ] Test login page: `/login.php`
- [ ] Test signup page: `/signup.php`
- [ ] Test admin login: `/admin_login.php`

### User Features
- [ ] User registration works
- [ ] Email verification works
- [ ] User login works
- [ ] Profile page loads
- [ ] Card application works
- [ ] File upload works
- [ ] Referral system works
- [ ] Points system works

### Admin Features
- [ ] Admin login works
- [ ] Dashboard statistics display
- [ ] Content management works
- [ ] Card applications view works
- [ ] Ads management works
- [ ] User management works

### Database Tests
- [ ] Data saves correctly
- [ ] Data retrieves correctly
- [ ] Relationships work
- [ ] No SQL errors in logs

### Email Tests
- [ ] Signup verification email sends
- [ ] Password reset email sends
- [ ] Application confirmation email sends
- [ ] SMTP connection works

## Security Checks

- [ ] Change default admin password
- [ ] Remove test accounts
- [ ] Disable error display (production)
- [ ] Enable error logging
- [ ] Check SQL injection protection
- [ ] Verify XSS protection
- [ ] Test CSRF protection
- [ ] Check file upload validation
- [ ] Verify session security
- [ ] Test password hashing

## Performance Optimization

- [ ] Enable browser caching
- [ ] Compress images
- [ ] Minify CSS (optional)
- [ ] Minify JavaScript (optional)
- [ ] Test page load speed
- [ ] Check mobile responsiveness

## Final Checks

- [ ] All images load correctly
- [ ] All CSS styles apply
- [ ] All JavaScript works
- [ ] No console errors
- [ ] No PHP errors in logs
- [ ] Forms submit correctly
- [ ] Redirects work properly
- [ ] Sessions work correctly
- [ ] Cookies work correctly

## Post-Deployment

- [ ] Monitor error logs daily
- [ ] Test from different devices
- [ ] Test from different browsers
- [ ] Get user feedback
- [ ] Setup regular backups
- [ ] Document any issues
- [ ] Create maintenance schedule

## Backup Schedule

- [ ] Daily: Database export
- [ ] Weekly: Full file backup
- [ ] Monthly: Complete system backup
- [ ] Store backups locally and cloud

## Monitoring

- [ ] Check error logs: `/php_errors.log`
- [ ] Monitor disk space usage
- [ ] Track bandwidth usage
- [ ] Monitor database size
- [ ] Check uptime status

## Common Issues to Watch

- [ ] Session timeout issues
- [ ] File upload failures
- [ ] Email delivery problems
- [ ] Database connection drops
- [ ] Image loading issues
- [ ] Permission errors

## Support Contacts

- **InfinityFree Forum**: https://forum.infinityfree.net/
- **Support Tickets**: Via cPanel
- **Status Page**: https://status.infinityfree.net/

## Notes

```
Deployment Date: _______________
Domain: ________________________
Database Name: _________________
Admin Email: ___________________
Issues Found: __________________
_______________________________
_______________________________
```

---

## Quick Commands

### Export Database (Local)
```bash
mysqldump -u root -p bankingdb > bankingdb_backup.sql
```

### Test Database Connection
```php
<?php
include('db_connect.php');
echo "Connected successfully!";
?>
```

### Check PHP Version
```php
<?php phpinfo(); ?>
```

---

**✅ Deployment Complete!**

Remember to:
- Keep credentials secure
- Regular backups
- Monitor logs
- Update regularly
