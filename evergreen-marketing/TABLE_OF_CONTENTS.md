# Evergreen Banking Marketing System
## System Integration and Architecture II

**System Name:** Evergreen Banking Marketing System  
**Date:** March 19, 2026  
**Version:** 1.0

---

## Table of Contents

### I. Proposed System/Application Name
**Evergreen Banking Marketing System**

### II. Description of Proposed System/Application

The **Evergreen Banking System** is a web-based platform designed to support digital marketing and user engagement for banking services. The system focuses on an integrated marketing subsystem that allows banks to promote their financial products, manage promotional campaigns, and interact with customers through digital channels.

The proposed system includes features such as:
- Automated marketing campaigns
- Personalized advertisements
- Rewards and loyalty programs
- Customer notifications
- Account application management
- Card application processing
- Referral system with rewards
- Dynamic content management
- Admin dashboard for content control

It is connected to a simulated core banking database that allows the system to categorize customers and deliver relevant promotions based on their banking activities.

### III. System Architectural Model

#### III.I System Architectural Model Title
**Three-Tier Web Application Architecture with MVC Pattern**

The system follows a three-tier architecture:
1. **Presentation Layer** - User Interface (HTML, CSS, JavaScript)
2. **Application Layer** - Business Logic (PHP)
3. **Data Layer** - Database Management (MySQL)

### IV. System Flowchart (Per Module)

#### IV.I Flowchart Module I - User Registration & Authentication
- User Registration Flow
- Email Verification Process
- Login Authentication
- Password Reset Flow
- Session Management

#### IV.II Flowchart Module II - Marketing & Promotions
- Homepage Content Display
- Card Product Browsing
- Promotional Content Management
- Dynamic Content Loading
- Rewards Program Display

#### IV.III Flowchart Module III - Application Management
- Account Application Submission
- Card Application Process
- Document Upload & Verification
- Application Status Tracking
- Admin Review & Approval

#### IV.IV Flowchart Module IV - Referral System
- Referral Code Generation
- Referral Link Sharing
- Points Calculation
- Rewards Distribution
- Referral Tracking

#### IV.V Flowchart Module V - Admin Management
- Admin Authentication
- Content Management
- Application Review
- User Management
- System Configuration

### V. Data Flow Diagram

#### V.I Data Flow Diagram Model
**Context Level (Level 0) DFD:**
- External Entities: Customers, Administrators, Email System
- Main Process: Evergreen Banking Marketing System
- Data Stores: Customer Database, Content Database, Application Database

**Level 1 DFD:**
1. User Registration & Authentication Process
2. Marketing Content Management Process
3. Application Processing System
4. Referral Management System
5. Admin Control Panel

**Level 2 DFD (Detailed):**
- Registration: Data validation, email verification, account creation
- Applications: Form submission, document upload, status tracking
- Referrals: Code generation, tracking, rewards calculation
- Content: Dynamic loading, admin updates, cache management

### VI. Use Case Diagram

#### VI.I Use Case Model

**Actors:**
1. **Customer/User**
   - Register Account
   - Login/Logout
   - Browse Products
   - Apply for Cards
   - Apply for Accounts
   - View Profile
   - Update Contact Information
   - Track Application Status
   - Generate Referral Code
   - Refer Friends
   - View Rewards Points
   - Reset Password

2. **Administrator**
   - Login to Admin Dashboard
   - Manage Content
   - Review Applications
   - Approve/Decline Cards
   - View Customer Information
   - Update Company Information
   - Manage Marketing Content
   - View System Analytics

3. **System**
   - Send Verification Emails
   - Generate Referral Codes
   - Calculate Rewards Points
   - Process Applications
   - Validate User Input
   - Manage Sessions

### VII. Sample Screenshots

#### VII.I Sample Screenshot I - User Interface
1. **Homepage (Landing Page)**
   - Hero section with company branding
   - Featured products and services
   - Call-to-action buttons
   - Navigation menu
   - Footer with contact information

2. **Registration Page**
   - Multi-step registration form
   - Personal information fields
   - Address dropdown (Province/City/Barangay)
   - Email verification
   - Terms and conditions acceptance

3. **Login Page**
   - Email and password fields
   - Remember me option
   - Forgot password link
   - Sign up redirect

4. **User Profile Page**
   - Personal information display
   - Editable contact details
   - Address management with dropdowns
   - Card application status
   - Account statistics
   - Referral code display

5. **Card Products Pages**
   - Credit Card offerings
   - Debit Card options
   - Prepaid Card information
   - Rewards program details
   - Application buttons

6. **Application Form**
   - Account application
   - Card selection
   - Personal details
   - Address information
   - ID document upload
   - Terms acceptance

7. **Referral Page**
   - Referral code display
   - Share options
   - Rewards information
   - Referral history
   - Points tracking

#### VII.II Sample Screenshot II - Admin Interface
1. **Admin Login Page**
   - Secure authentication
   - Admin credentials
   - Session management

2. **Admin Dashboard**
   - Sidebar navigation
   - Content Management section
   - Card Applications section
   - Statistics overview
   - Quick actions

3. **Content Management Panel**
   - Company name editor
   - Logo upload
   - Hero section content
   - About description
   - Contact information (Phone, Email, Address)
   - Banner image management
   - Save changes functionality

4. **Card Applications Management**
   - Applications list
   - Filter by status (Pending/Approved/Declined)
   - User information display
   - ID document viewer
   - Approve/Decline actions
   - Application details modal

### VIII. System Features & Modules

#### VIII.I User-Facing Features
1. **User Registration & Authentication**
   - Email-based registration
   - OTP verification
   - Secure password hashing
   - Session management
   - Password recovery

2. **Marketing Content Display**
   - Dynamic homepage
   - Product pages (Cards)
   - About page
   - FAQ section
   - Terms & Privacy Policy
   - Learn More sections

3. **Application System**
   - Account application form
   - Card application process
   - Multi-card selection
   - Document upload (ID front/back)
   - Application tracking
   - Status notifications

4. **Referral Program**
   - Unique referral code generation
   - Referral link sharing
   - Points system
   - Rewards tracking
   - Referral history

5. **User Profile Management**
   - View personal information
   - Edit contact details
   - Update address (with cascading dropdowns)
   - View application status
   - Display bank ID and member info

#### VIII.II Admin Features
1. **Content Management System**
   - Update company information
   - Manage contact details
   - Edit marketing content
   - Upload images
   - Real-time updates

2. **Application Management**
   - Review card applications
   - View applicant details
   - Verify ID documents
   - Approve/Decline applications
   - Filter and search

3. **Dashboard Analytics**
   - Application statistics
   - User metrics
   - System overview

### IX. Technical Specifications

#### IX.I Technology Stack
- **Frontend:** HTML5, CSS3, JavaScript
- **Backend:** PHP 7.4+
- **Database:** MySQL 8.0+
- **Server:** Apache (XAMPP)
- **Email:** PHPMailer with SMTP
- **Version Control:** Git

#### IX.II Database Tables
1. **bank_customers** - User accounts
2. **site_content** - Dynamic content
3. **card_applications** - Card applications
4. **account_applications** - Account applications
5. **application_documents** - Uploaded documents
6. **addresses** - User addresses
7. **provinces** - Philippine provinces
8. **cities** - Philippine cities
9. **barangays** - Philippine barangays
10. **referrals** - Referral tracking
11. **admin_users** - Administrator accounts

#### IX.III Security Features
- Password hashing (bcrypt)
- SQL injection prevention (prepared statements)
- XSS protection (htmlspecialchars)
- CSRF protection (session tokens)
- Secure session management
- Input validation
- File upload restrictions

### X. System Requirements

#### X.I Hardware Requirements
- **Server:** Minimum 2GB RAM, 20GB Storage
- **Client:** Any device with web browser

#### X.II Software Requirements
- **Server:** PHP 7.4+, MySQL 8.0+, Apache 2.4+
- **Client:** Modern web browser (Chrome, Firefox, Safari, Edge)

#### X.III Network Requirements
- Internet connection for email services
- HTTPS recommended for production

### XI. Installation & Setup

#### XI.I Database Setup
1. Import database schema
2. Run migration scripts
3. Setup admin account
4. Configure database connection

#### XI.II System Configuration
1. Configure email settings (PHPMailer)
2. Setup company information
3. Upload logo and images
4. Configure location data (provinces/cities/barangays)

#### XI.III Content Setup
1. Run `add_company_location.php`
2. Login to admin dashboard
3. Update content management fields
4. Test dynamic content display

### XII. User Manual

#### XII.I For Customers
1. How to register
2. How to apply for cards
3. How to use referral system
4. How to track applications
5. How to update profile

#### XII.II For Administrators
1. How to login to admin panel
2. How to manage content
3. How to review applications
4. How to approve/decline cards
5. How to update company information

### XIII. Maintenance & Support

#### XIII.I Regular Maintenance
- Database backup
- Security updates
- Content updates
- Performance monitoring

#### XIII.II Troubleshooting Guide
- Common issues and solutions
- Error handling
- Debug procedures

### XIV. Future Enhancements

1. Mobile application
2. SMS notifications
3. Advanced analytics dashboard
4. Multi-language support
5. Social media integration
6. Live chat support
7. Document OCR verification
8. Automated credit scoring
9. Integration with core banking system
10. API for third-party integrations

### XV. Conclusion

The Evergreen Banking Marketing System provides a comprehensive digital platform for customer engagement and application management. The system successfully integrates marketing features with banking services, offering a seamless user experience while providing administrators with powerful management tools.

---

## Appendices

### Appendix A: File Structure
```
evergreen-marketing/
├── index.php (Landing page)
├── login.php (User login)
├── signup.php (User registration)
├── verify.php (Email verification)
├── forgotpassword.php (Password reset)
├── profile.php (User profile)
├── viewingpage.php (Logged-in homepage)
├── refer.php (Referral system)
├── evergreen_form.php (Application form)
├── about.php (About page)
├── policy.php (Privacy policy)
├── terms.php (Terms & conditions)
├── faq.php (FAQ page)
├── learnmore.php (Learn more)
├── cardrewards.php (Rewards program)
├── admin_login.php (Admin authentication)
├── admin_dashboard.php (Admin panel)
├── admin_content_management.php (Content CMS)
├── admin_card_applications.php (Application management)
├── cards/ (Card product pages)
│   ├── credit.php
│   ├── debit.php
│   ├── prepaid.php
│   ├── points.php
│   └── rewards.php
├── includes/
│   └── content_helper.php (Dynamic content functions)
├── assets/ (CSS, JS, Images)
├── uploads/ (User uploaded documents)
└── sql/ (Database schemas)
```

### Appendix B: API Endpoints
- `get_locations_db.php` - Location data API
- `referral_api.php` - Referral system API
- `points_api.php` - Points calculation API

### Appendix C: Helper Functions
- `get_company_name()`
- `get_company_logo()`
- `get_company_phone()`
- `get_company_email()`
- `get_company_address()`
- `get_hero_title()`
- `get_hero_description()`
- `get_about_description()`

### Appendix D: Documentation Files
- `README.md` - System overview
- `SCOPE_AND_DELIMITATIONS.md` - Project scope
- `INSTALLATION_GUIDE.md` - Setup instructions
- `COMPANY_CONTACT_INFO.md` - Contact management guide
- `DYNAMIC_CONTACT_UPDATE.md` - Dynamic content guide
- `PROFILE_ADDRESS_UPDATE.md` - Address system documentation

---

**End of Document**
