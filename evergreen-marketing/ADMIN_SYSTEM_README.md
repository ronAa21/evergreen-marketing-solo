# Admin Dashboard System

Complete admin system for Evergreen Bank with content management and card application review.

## Features

### 1. Admin Login
- Secure authentication system
- Session management
- Password hashing

### 2. Content Management
- Update company name
- Change company logo
- Modify marketing descriptions
- Update contact information
- Changes reflect immediately on the main site

### 3. Card Applications Management
- View all card applications
- Filter by status (pending, approved, declined)
- Approve or decline applications
- Track review history
- Statistics dashboard

## Installation

### Step 1: Run the Setup Script
Open your browser and navigate to:
```
http://localhost/SIA/evergreen-marketing/setup_admin_system.php
```

This will:
- Create all necessary database tables
- Insert default admin user
- Initialize site content
- Set up card applications table

### Step 2: Access Admin Login
Navigate to:
```
http://localhost/SIA/evergreen-marketing/admin_login.php
```

### Default Credentials
- **Username:** admin
- **Password:** admin123

⚠️ **Important:** Change the default password after first login!

## File Structure

```
evergreen-marketing/
├── admin_login.php                    # Admin login page
├── admin_dashboard.php                # Main dashboard
├── admin_content_management.php       # Content management module
├── admin_card_applications.php        # Card applications module
├── admin_logout.php                   # Logout handler
├── setup_admin_system.php             # Setup script
└── sql/
    └── create_admin_system.sql        # Database schema
```

## Database Tables

### admin_users
Stores admin user accounts with encrypted passwords.

### site_content
Stores all editable website content (company info, descriptions, etc.).

### card_applications
Stores customer card applications with status tracking.

## Usage

### Content Management
1. Login to admin dashboard
2. Click "Content Management" in sidebar
3. Edit any content field
4. Click "Save Changes"
5. Changes appear immediately on the main site

### Card Applications
1. Login to admin dashboard
2. Click "Card Applications" in sidebar
3. View all applications with customer details
4. Click "Approve" or "Decline" for pending applications
5. Status updates are reflected in customer dashboards

## Security Features

- Password hashing with bcrypt
- Session-based authentication
- SQL injection protection with prepared statements
- XSS protection with htmlspecialchars
- Admin-only access control

## Customization

### Adding New Content Fields
Edit `sql/create_admin_system.sql` and add:
```sql
INSERT INTO `site_content` (`content_key`, `content_value`, `content_type`) VALUES
('your_key', 'Your value', 'text');
```

### Creating Additional Admin Users
Use the admin_users table:
```sql
INSERT INTO admin_users (username, email, password_hash, full_name) VALUES
('newadmin', 'admin@example.com', '$2y$10$...', 'Admin Name');
```

## Troubleshooting

### Can't Login
- Verify database tables exist
- Check default admin user exists
- Clear browser cookies/cache

### Content Not Updating
- Check database connection
- Verify admin permissions
- Check for SQL errors in browser console

### Applications Not Showing
- Ensure card_applications table exists
- Check foreign key relationships
- Verify customer data exists

## Support

For issues or questions, contact the development team.

## Version
1.0.0 - Initial Release
