# Evergreen Banking System - Flowchart Guide
## Professional Process Flow Diagrams (Modules 1-5)

**Version:** 2.0 - Professional Format  
**Date:** March 19, 2026

---

## 📖 Table of Contents
1. [Module I - User Registration & Authentication](#module-1)
2. [Module II - Marketing & Promotions](#module-2)
3. [Module III - Application Management](#module-3)
4. [Module IV - Referral System](#module-4)
5. [Module V - Admin Management](#module-5)

---

## 🎨 Flowchart Legend

```
┏━━━━━━━━━━━━━┓
┃    START    ┃  ← Start/End Point
┗━━━━━━━━━━━━━┛

┌─────────────┐
│   Process   │  ← Action/Process
└─────────────┘

◆ Decision?   ◆  ← Decision Point
   Yes / No

[Input/Output]   ← Data Input/Output

      ↓          ← Flow Direction
```

---

<a name="module-1"></a>
## MODULE I: User Registration & Authentication

### 1.1 User Registration Flow

```
┏━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃   User Registration      ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━┛
            ↓
┌─────────────────────────┐
│ User visits homepage    │
│ Clicks "Sign Up"        │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Display Registration    │
│ Form (signup.php)       │
└─────────────────────────┘
            ↓
[User enters information:]
• First Name, Last Name
• Email, Password
• Phone Number
• Province → City → Barangay
• Zip Code
            ↓
      ◆ All fields
       filled?
         ↓ No
    Show error
         ↓ Yes
      ◆ Email
       valid?
         ↓ No
    Invalid format
         ↓ Yes
      ◆ Email
      exists?
         ↓ Yes
    Already registered
         ↓ No
┌─────────────────────────┐
│ Hash password           │
│ Generate 6-digit OTP    │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Send OTP via email      │
│ (PHPMailer)             │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Store temp data in      │
│ session                 │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Redirect to verify.php  │
└─────────────────────────┘
            ↓
┏━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃         END             ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━┛
```


### 1.2 Email Verification Flow

```
┏━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃   Email Verification    ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━┛
            ↓
┌─────────────────────────┐
│ Display OTP input form  │
│ (verify.php)            │
└─────────────────────────┘
            ↓
[User enters 6-digit OTP]
            ↓
      ◆ OTP
      valid?
         ↓ No
    Show error
         ↓ Yes
      ◆ OTP
     expired?
         ↓ Yes
    Request new OTP
         ↓ No
┌─────────────────────────┐
│ Generate Bank ID        │
│ (EVGR + 8 digits)       │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Generate Member ID      │
│ (MEM + 6 digits)        │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Generate Referral Code  │
│ (8 characters)          │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Insert user into        │
│ bank_customers table    │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Clear session data      │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Show success message    │
│ Redirect to login.php   │
└─────────────────────────┘
            ↓
┏━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃         END             ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━┛
```

### 1.3 User Login Flow

```
┏━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃      User Login         ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━┛
            ↓
┌─────────────────────────┐
│ Display login form      │
│ (login.php)             │
└─────────────────────────┘
            ↓
[User enters credentials:]
• Email
• Password
            ↓
      ◆ Fields
      empty?
         ↓ Yes
    Show error
         ↓ No
┌─────────────────────────┐
│ Query database for      │
│ email                   │
└─────────────────────────┘
            ↓
      ◆ Email
      found?
         ↓ No
    Invalid credentials
         ↓ Yes
┌─────────────────────────┐
│ Verify password         │
│ (password_verify)       │
└─────────────────────────┘
            ↓
      ◆ Password
      correct?
         ↓ No
    Invalid credentials
         ↓ Yes
┌─────────────────────────┐
│ Create user session     │
│ • customer_id           │
│ • email, name           │
│ • bank_id               │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Update last_login       │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Redirect to             │
│ viewingpage.php         │
└─────────────────────────┘
            ↓
┏━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃         END             ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━┛
```

### 1.4 Password Reset Flow

```
┏━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃    Password Reset       ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━┛
            ↓
┌─────────────────────────┐
│ User clicks             │
│ "Forgot Password"       │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Display email input     │
│ (forgotpassword.php)    │
└─────────────────────────┘
            ↓
[User enters email]
            ↓
      ◆ Email
      exists?
         ↓ No
    Email not found
         ↓ Yes
┌─────────────────────────┐
│ Generate 6-digit OTP    │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Send OTP via email      │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Store OTP in session    │
└─────────────────────────┘
            ↓
[User enters OTP]
            ↓
      ◆ OTP
      valid?
         ↓ No
    Invalid OTP
         ↓ Yes
[User enters new password]
            ↓
      ◆ Passwords
       match?
         ↓ No
    Passwords don't match
         ↓ Yes
┌─────────────────────────┐
│ Hash new password       │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Update password in DB   │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Clear session           │
│ Redirect to login.php   │
└─────────────────────────┘
            ↓
┏━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃         END             ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━┛
```

---

<a name="module-2"></a>
## MODULE II: Marketing & Promotions

### 2.1 Homepage Content Display

```
┏━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃   Homepage Display      ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━┛
            ↓
┌─────────────────────────┐
│ User visits index.php   │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Load content_helper.php │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Query site_content      │
│ table                   │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Load dynamic content:   │
│ • Company name & logo   │
│ • Hero section          │
│ • About description     │
│ • Contact info          │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Display hero section    │
│ with CTA buttons        │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Display featured        │
│ card products           │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Display about section   │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Display contact info    │
│ in footer               │
└─────────────────────────┘
            ↓
      ◆ User
    logged in?
         ↓ Yes
    Show Dashboard button
         ↓ No
    Show Login/Sign Up
            ↓
┏━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃         END             ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━┛
```

### 2.2 Card Product Browsing

```
┏━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃   Card Product Browse   ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━┛
            ↓
┌─────────────────────────┐
│ User clicks card type:  │
│ • Credit Card           │
│ • Debit Card            │
│ • Prepaid Card          │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Display card details:   │
│ • Features & Benefits   │
│ • Requirements          │
│ • Fees & Charges        │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Show "Apply Now" button │
└─────────────────────────┘
            ↓
      ◆ User
    logged in?
         ↓ No
    Redirect to login.php
         ↓ Yes
┌─────────────────────────┐
│ Redirect to             │
│ evergreen_form.php      │
└─────────────────────────┘
            ↓
┏━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃         END             ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━┛
```

### 2.3 Admin Content Management

```
┏━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃   Content Management    ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━┛
            ↓
┌─────────────────────────┐
│ Admin navigates to      │
│ Content Management      │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Load current content    │
│ from site_content table │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Display edit form:      │
│ • Company name          │
│ • Hero title & desc     │
│ • About description     │
│ • Phone, Email, Address │
│ • Logo & Banner upload  │
└─────────────────────────┘
            ↓
[Admin edits content]
            ↓
      ◆ Inputs
      valid?
         ↓ No
    Show validation errors
         ↓ Yes
      ◆ Image
     uploaded?
         ↓ Yes
    Validate & upload image
         ↓ No/Done
┌─────────────────────────┐
│ Sanitize inputs         │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Update site_content     │
│ table                   │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Log activity            │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Show success message    │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Changes reflect         │
│ immediately on all pages│
└─────────────────────────┘
            ↓
┏━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃         END             ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━┛
```

---

<a name="module-3"></a>
## MODULE III: Application Management

### 3.1 Card Application Submission

```
┏━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃   Card Application      ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━┛
            ↓
┌─────────────────────────┐
│ User clicks             │
│ "Apply for Card"        │
└─────────────────────────┘
            ↓
      ◆ User
    logged in?
         ↓ No
    Redirect to login.php
         ↓ Yes
┌─────────────────────────┐
│ Display application     │
│ form (evergreen_form)   │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Pre-fill user info      │
│ from database           │
└─────────────────────────┘
            ↓
[User completes form:]
• Select card type(s)
• Employment details
• Income information
• Upload ID (front/back)
            ↓
      ◆ All fields
       filled?
         ↓ No
    Show validation errors
         ↓ Yes
      ◆ Card(s)
     selected?
         ↓ No
    Select at least one card
         ↓ Yes
      ◆ ID files
     uploaded?
         ↓ No
    Upload ID required
         ↓ Yes
┌─────────────────────────┐
│ Validate file types     │
│ & sizes                 │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Generate application ID │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Upload ID files to      │
│ uploads/ directory      │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Insert into             │
│ card_applications table │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Insert into             │
│ application_documents   │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Set status = "pending"  │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Show success message    │
│ with application ID     │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Redirect to profile.php │
└─────────────────────────┘
            ↓
┏━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃         END             ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━┛
```


### 3.2 Application Status Tracking

```
┏━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃   Application Tracking  ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━┛
            ↓
┌─────────────────────────┐
│ User logs in and        │
│ navigates to profile    │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Query card_applications │
│ for user's customer_id  │
└─────────────────────────┘
            ↓
      ◆ Has
   applications?
         ↓ No
    Show "No applications"
         ↓ Yes
┌─────────────────────────┐
│ Display applications:   │
│ • Card type             │
│ • Application ID        │
│ • Date submitted        │
│ • Status badge          │
│   - Pending (Yellow)    │
│   - Approved (Green)    │
│   - Declined (Red)      │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Show application        │
│ details                 │
└─────────────────────────┘
            ↓
┏━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃         END             ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━┛
```

---

<a name="module-4"></a>
## MODULE IV: Referral System

### 4.1 Referral Code Generation

```
┏━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃  Referral Code Gen      ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━┛
            ↓
┌─────────────────────────┐
│ User completes          │
│ registration            │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ System generates        │
│ unique 8-char code      │
│ (alphanumeric)          │
└─────────────────────────┘
            ↓
      ◆ Code
     exists?
         ↓ Yes
    Generate new code
         ↓ No
┌─────────────────────────┐
│ Store in referral_code  │
│ column                  │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Initialize              │
│ total_points = 0        │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Code available in       │
│ user profile            │
└─────────────────────────┘
            ↓
┏━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃         END             ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━┛
```


### 4.2 Referral Link Sharing

```
┏━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃   Referral Sharing      ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━┛
            ↓
┌─────────────────────────┐
│ User navigates to       │
│ refer.php               │
└─────────────────────────┘
            ↓
      ◆ User
    logged in?
         ↓ No
    Redirect to login.php
         ↓ Yes
┌─────────────────────────┐
│ Retrieve user's         │
│ referral_code from DB   │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Display referral code   │
│ prominently             │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Generate referral link: │
│ signup.php?ref=[code]   │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Display share options:  │
│ • Copy link button      │
│ • Email share           │
│ • Social media          │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Display current points  │
│ balance                 │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Display referral        │
│ history & stats         │
└─────────────────────────┘
            ↓
      ◆ User clicks
     "Copy Link"?
         ↓ Yes
┌─────────────────────────┐
│ Copy link to clipboard  │
│ Show "Copied!" message  │
└─────────────────────────┘
            ↓
┏━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃         END             ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━┛
```

### 4.3 Referral Tracking & Points

```
┏━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃   Referral Tracking     ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━┛
            ↓
┌─────────────────────────┐
│ New user clicks         │
│ referral link           │
│ (?ref=CODE)             │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Store referral code     │
│ in session              │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ User completes          │
│ registration            │
└─────────────────────────┘
            ↓
      ◆ Referral
      code in
      session?
         ↓ No
    Skip referral process
         ↓ Yes
┌─────────────────────────┐
│ Validate referral code  │
│ exists in database      │
└─────────────────────────┘
            ↓
      ◆ Code
      valid?
         ↓ No
    Skip referral process
         ↓ Yes
┌─────────────────────────┐
│ Get referrer's          │
│ customer_id             │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Insert into referrals:  │
│ • referrer_id           │
│ • referred_id           │
│ • referral_code         │
│ • status = pending      │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Wait for referred user  │
│ to complete action      │
│ (e.g., apply for card)  │
└─────────────────────────┘
            ↓
      ◆ Action
     completed?
         ↓ No
    Keep status = pending
         ↓ Yes
┌─────────────────────────┐
│ Calculate points        │
│ (Base: 100 points)      │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Update referrals:       │
│ • status = completed    │
│ • points_earned         │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Add points to referrer's│
│ total_points            │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Send notification to    │
│ referrer                │
└─────────────────────────┘
            ↓
┏━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃         END             ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━┛
```


---

<a name="module-5"></a>
## MODULE V: Admin Management

### 5.1 Admin Authentication

```
┏━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃    Admin Login          ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━┛
            ↓
┌─────────────────────────┐
│ Admin navigates to      │
│ admin_login.php         │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Display admin login     │
│ form                    │
└─────────────────────────┘
            ↓
[Admin enters credentials:]
• Username
• Password
            ↓
      ◆ Fields
      empty?
         ↓ Yes
    Show "Fill all fields"
         ↓ No
┌─────────────────────────┐
│ Query admin_users       │
│ table for username      │
└─────────────────────────┘
            ↓
      ◆ Username
       found?
         ↓ No
    Invalid credentials
         ↓ Yes
┌─────────────────────────┐
│ Verify password         │
│ (password_verify)       │
└─────────────────────────┘
            ↓
      ◆ Password
      correct?
         ↓ No
    Invalid credentials
         ↓ Yes
      ◆ Account
       active?
         ↓ No
    Account disabled
         ↓ Yes
┌─────────────────────────┐
│ Create admin session:   │
│ • admin_id              │
│ • admin_name            │
│ • admin_role            │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Update last_login       │
│ timestamp               │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Redirect to             │
│ admin_dashboard.php     │
└─────────────────────────┘
            ↓
┏━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃         END             ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━┛
```

### 5.2 Application Review & Approval

```
┏━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃  Application Review     ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━┛
            ↓
┌─────────────────────────┐
│ Admin navigates to      │
│ Card Applications       │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Query card_applications │
│ JOIN bank_customers     │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Display applications    │
│ with filters:           │
│ • All                   │
│ • Pending               │
│ • Approved              │
│ • Declined              │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Admin selects filter    │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Display filtered list   │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Admin clicks            │
│ "View ID" button        │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Open modal with:        │
│ • ID front image        │
│ • ID back image         │
│ • User details          │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Admin reviews documents │
└─────────────────────────┘
            ↓
      ◆ Decision?
         ↓
    Approve / Decline
         ↓
┌─────────────────────────┐
│ Admin clicks action     │
│ button                  │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Show confirmation       │
│ dialog                  │
└─────────────────────────┘
            ↓
      ◆ Confirm?
         ↓ No
    Cancel action
         ↓ Yes
┌─────────────────────────┐
│ Update application:     │
│ • status (appr/decl)    │
│ • reviewed_date         │
│ • reviewed_by (admin_id)│
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Log activity            │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Show success message    │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Refresh applications    │
│ list automatically      │
└─────────────────────────┘
            ↓
┏━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃         END             ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━┛
```


### 5.3 Dashboard Statistics

```
┏━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃  Dashboard Statistics   ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━┛
            ↓
┌─────────────────────────┐
│ Admin logs in to        │
│ dashboard               │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Load admin_statistics   │
│ .php                    │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Query statistics:       │
│ • Total users           │
│ • New users (month)     │
│ • Total applications    │
│ • Pending applications  │
│ • Approved applications │
│ • Declined applications │
│ • Total referrals       │
│ • Total points          │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Query card type         │
│ breakdown:              │
│ • Credit cards          │
│ • Debit cards           │
│ • Prepaid cards         │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Query recent            │
│ applications (last 5)   │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Display statistics      │
│ cards with icons        │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Display card type       │
│ breakdown chart         │
└─────────────────────────┘
            ↓
┌─────────────────────────┐
│ Display recent activity │
│ feed                    │
└─────────────────────────┘
            ↓
┏━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃         END             ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━┛
```

---

## 📋 How to Use This Guide

### Step 1: Choose Your Tool
**Free Options:**
- Draw.io (diagrams.net) - Recommended
- Lucidchart Free
- Google Drawings

**Paid Options:**
- Microsoft Visio
- Lucidchart Pro
- Creately

### Step 2: Create Visual Diagrams
1. Open your diagramming tool
2. Use standard flowchart shapes
3. Follow the flowcharts in this guide
4. Apply consistent colors:
   - **Start/End:** Green (#4CAF50)
   - **Process:** Blue (#2196F3)
   - **Decision:** Orange (#FF9800)
   - **Input/Output:** Purple (#9C27B0)
   - **Error:** Red (#F44336)

### Step 3: Best Practices
✅ Keep text concise and readable  
✅ Use consistent spacing  
✅ Label all decision branches clearly  
✅ Align elements properly  
✅ Use connectors for crossing lines  
✅ Add a title to each flowchart  
✅ Include a legend if needed  

### Step 4: Export & Document
1. Export as PNG (high resolution) or PDF
2. Name files clearly: `Module1_Registration.png`
3. Add to your documentation
4. Store source files for future edits

---

## 🎯 Professional Tips

### Color Coding
```
🟢 Green   - Start/End, Success paths
🔵 Blue    - Standard processes
🟠 Orange  - Decisions/Conditions
🟣 Purple  - Input/Output operations
🔴 Red     - Errors/Failures
```

### Layout Guidelines
- **Top to Bottom:** Main flow direction
- **Left to Right:** Alternative for wide processes
- **Consistent Spacing:** 20-30px between elements
- **Alignment:** Use grid/snap features
- **White Space:** Don't overcrowd the diagram

### Text Guidelines
- **Font:** Arial, Helvetica, or Calibri
- **Size:** 10-12pt for body, 14-16pt for titles
- **Case:** Sentence case (not ALL CAPS)
- **Length:** Max 3-4 words per shape

---

## 📊 Flowchart vs Screenshots

| Aspect | Flowchart | Screenshot |
|--------|-----------|------------|
| **Type** | Diagram | Image |
| **Shows** | Process Logic | User Interface |
| **Purpose** | How it works | What it looks like |
| **Tools** | Draw.io, Visio | Snipping Tool |
| **Content** | Shapes & arrows | Actual system |

**You need BOTH for complete documentation!**

---

## ✅ Quality Checklist

Before finalizing your flowcharts, verify:

- [ ] All flowcharts have clear start and end points
- [ ] All decision diamonds have labeled branches (Yes/No)
- [ ] All processes are described with action verbs
- [ ] Flow direction is consistent (top-to-bottom)
- [ ] No orphaned elements (all connected)
- [ ] Colors are consistent across all diagrams
- [ ] Text is readable at normal zoom
- [ ] File names are descriptive
- [ ] Source files are saved for editing
- [ ] Exported in high quality (PNG/PDF)

---

## 📝 Summary

This guide provides professional, simplified flowcharts for all 5 modules:

1. **Module I:** User Registration & Authentication (4 flows)
2. **Module II:** Marketing & Promotions (3 flows)
3. **Module III:** Application Management (2 flows)
4. **Module IV:** Referral System (3 flows)
5. **Module V:** Admin Management (3 flows)

**Total:** 15 professional flowcharts ready to be converted into visual diagrams.

---

**Document Version:** 2.0 - Professional Format  
**Last Updated:** March 19, 2026  
**Created by:** Kiro AI Assistant

