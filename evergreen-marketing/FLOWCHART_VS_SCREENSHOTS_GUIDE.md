# Flowchart vs Screenshots - Documentation Guide

## Understanding the Difference

### IV. System Flowchart (Per Module) = DIAGRAMS ✏️
**What it is:** Visual diagrams showing the LOGIC and PROCESS FLOW of your system

**Purpose:** Show HOW the system works internally (the logic behind the scenes)

**Tools to create:**
- Microsoft Visio
- Lucidchart
- Draw.io (free)
- Microsoft PowerPoint
- Canva

**Standard Flowchart Symbols:**
```
┌─────────┐
│  START  │  ← Oval/Rounded Rectangle (Start/End)
└─────────┘

┌─────────────────┐
│  Process/Action │  ← Rectangle (Process)
└─────────────────┘

    ╱────────╲
   ╱ Decision ╲  ← Diamond (Decision/Condition)
   ╲          ╱
    ╲────────╱

    ────────→  ← Arrow (Flow Direction)

┌──────────────┐
│ Input/Output │  ← Parallelogram (Input/Output)
└──────────────┘
```

---

## Example Flowcharts for Your System

### Module I: User Registration & Authentication

#### 1. Registration Flowchart
```
        START
          ↓
    ┌─────────────────┐
    │ User clicks      │
    │ "Sign Up"        │
    └─────────────────┘
          ↓
    ┌─────────────────┐
    │ Display          │
    │ Registration Form│
    └─────────────────┘
          ↓
    ┌─────────────────┐
    │ User fills form  │
    │ (Name, Email,    │
    │ Address, etc.)   │
    └─────────────────┘
          ↓
    ┌─────────────────┐
    │ User submits     │
    └─────────────────┘
          ↓
       ╱────────╲
      ╱ All fields ╲     NO
     ╱   valid?     ╲────────→ Show error message
     ╲              ╱              ↓
      ╲────────╱                   │
          │ YES                     │
          ↓                         │
       ╱────────╲                   │
      ╱  Email    ╲     YES         │
     ╱  already    ╲────────→ Show "Email exists"
     ╲  exists?    ╱              ↓
      ╲────────╱                   │
          │ NO                      │
          ↓                         │
    ┌─────────────────┐            │
    │ Generate OTP     │            │
    │ (6 digits)       │            │
    └─────────────────┘            │
          ↓                         │
    ┌─────────────────┐            │
    │ Send OTP via     │            │
    │ Email (PHPMailer)│            │
    └─────────────────┘            │
          ↓                         │
    ┌─────────────────┐            │
    │ Store temp data  │            │
    │ in session       │            │
    └─────────────────┘            │
          ↓                         │
    ┌─────────────────┐            │
    │ Redirect to      │            │
    │ Verification Page│            │
    └─────────────────┘            │
          ↓                         │
        END ←←←←←←←←←←←←←←←←←←←←←←←┘
```

#### 2. Login Flowchart
```
        START
          ↓
    ┌─────────────────┐
    │ Display Login    │
    │ Page             │
    └─────────────────┘
          ↓
    ┌─────────────────┐
    │ User enters      │
    │ Email & Password │
    └─────────────────┘
          ↓
    ┌─────────────────┐
    │ User clicks Login│
    └─────────────────┘
          ↓
       ╱────────╲
      ╱ Fields   ╲     NO
     ╱   empty?   ╲────────→ Show "Fill all fields"
     ╲            ╱              ↓
      ╲────────╱                 │
          │ YES                   │
          ↓                       │
    ┌─────────────────┐          │
    │ Query database   │          │
    │ for email        │          │
    └─────────────────┘          │
          ↓                       │
       ╱────────╲                 │
      ╱  Email    ╲     NO        │
     ╱   found?    ╲──────→ Show "Invalid credentials"
     ╲             ╱           ↓
      ╲────────╱               │
          │ YES                 │
          ↓                     │
    ┌─────────────────┐        │
    │ Verify password  │        │
    │ (password_verify)│        │
    └─────────────────┘        │
          ↓                     │
       ╱────────╲               │
      ╱ Password ╲     NO       │
     ╱  correct?  ╲──────→ Show "Invalid credentials"
     ╲            ╱           ↓
      ╲────────╱               │
          │ YES                 │
          ↓                     │
    ┌─────────────────┐        │
    │ Create session   │        │
    │ (user_id, email) │        │
    └─────────────────┘        │
          ↓                     │
    ┌─────────────────┐        │
    │ Redirect to      │        │
    │ Dashboard        │        │
    └─────────────────┘        │
          ↓                     │
        END ←←←←←←←←←←←←←←←←←←←┘
```

---

### Module II: Card Application Process

```
        START
          ↓
    ┌─────────────────┐
    │ User clicks      │
    │ "Apply for Card" │
    └─────────────────┘
          ↓
       ╱────────╲
      ╱  User     ╲     NO
     ╱  logged in? ╲────────→ Redirect to Login
     ╲             ╱              ↓
      ╲────────╱                  │
          │ YES                    │
          ↓                        │
    ┌─────────────────┐           │
    │ Display          │           │
    │ Application Form │           │
    └─────────────────┘           │
          ↓                        │
    ┌─────────────────┐           │
    │ User fills form  │           │
    │ - Personal info  │           │
    │ - Select cards   │           │
    │ - Upload ID      │           │
    └─────────────────┘           │
          ↓                        │
    ┌─────────────────┐           │
    │ User submits     │           │
    └─────────────────┘           │
          ↓                        │
       ╱────────╲                  │
      ╱ All fields ╲     NO        │
     ╱   valid?     ╲────────→ Show validation errors
     ╲              ╱              ↓
      ╲────────╱                   │
          │ YES                     │
          ↓                         │
       ╱────────╲                   │
      ╱  ID files  ╲     NO         │
     ╱  uploaded?   ╲────────→ Show "Upload ID required"
     ╲              ╱              ↓
      ╲────────╱                   │
          │ YES                     │
          ↓                         │
    ┌─────────────────┐            │
    │ Upload ID files  │            │
    │ to server        │            │
    └─────────────────┘            │
          ↓                         │
    ┌─────────────────┐            │
    │ Insert into      │            │
    │ card_applications│            │
    │ table            │            │
    └─────────────────┘            │
          ↓                         │
    ┌─────────────────┐            │
    │ Insert into      │            │
    │ application_     │            │
    │ documents table  │            │
    └─────────────────┘            │
          ↓                         │
    ┌─────────────────┐            │
    │ Show success     │            │
    │ message          │            │
    └─────────────────┘            │
          ↓                         │
    ┌─────────────────┐            │
    │ Redirect to      │            │
    │ Profile page     │            │
    └─────────────────┘            │
          ↓                         │
        END ←←←←←←←←←←←←←←←←←←←←←←┘
```

---

### Module III: Admin Application Review

```
        START
          ↓
    ┌─────────────────┐
    │ Admin logs in    │
    └─────────────────┘
          ↓
    ┌─────────────────┐
    │ Navigate to      │
    │ Applications page│
    └─────────────────┘
          ↓
    ┌─────────────────┐
    │ Display list of  │
    │ applications     │
    └─────────────────┘
          ↓
    ┌─────────────────┐
    │ Admin clicks     │
    │ "View ID"        │
    └─────────────────┘
          ↓
    ┌─────────────────┐
    │ Display ID       │
    │ documents modal  │
    └─────────────────┘
          ↓
    ┌─────────────────┐
    │ Admin reviews    │
    │ documents        │
    └─────────────────┘
          ↓
       ╱────────╲
      ╱ Approve  ╲
     ╱  or Decline?╲
     ╲            ╱
      ╲────────╱
       ↓      ↓
   APPROVE  DECLINE
       ↓      ↓
    ┌──────┐ ┌──────┐
    │Update│ │Update│
    │status│ │status│
    │to    │ │to    │
    │"appr"│ │"decl"│
    └──────┘ └──────┘
       ↓      ↓
    ┌─────────────────┐
    │ Show success     │
    │ message          │
    └─────────────────┘
          ↓
    ┌─────────────────┐
    │ Refresh list     │
    └─────────────────┘
          ↓
        END
```

---

## VII. Sample Screenshots = ACTUAL IMAGES 📸

**What it is:** Actual screenshots/photos of your running system

**Purpose:** Show WHAT the system looks like (the user interface)

**How to capture:**
1. Run your system (localhost)
2. Use screenshot tools:
   - Windows: `Win + Shift + S` or Snipping Tool
   - Mac: `Cmd + Shift + 4`
   - Browser: F12 → Device toolbar for mobile view
3. Save as PNG or JPG
4. Annotate with arrows/labels if needed

**What to capture:**
- Homepage
- Registration form
- Login page
- User dashboard
- Profile page
- Application form
- Admin dashboard
- Admin reviewing applications
- etc.

---

## Summary Table

| Aspect | Flowchart (IV) | Screenshots (VII) |
|--------|---------------|-------------------|
| **Type** | Diagram | Image |
| **Shows** | Process/Logic | User Interface |
| **Purpose** | How it works | What it looks like |
| **Tools** | Draw.io, Visio | Snipping Tool, Browser |
| **Content** | Shapes, arrows, decisions | Actual running system |
| **Example** | Login logic flow | Login page image |

---

## What You Need for Your Documentation

### For Section IV (Flowcharts):
✅ Create 5 flowchart diagrams showing the LOGIC:
1. User Registration Process
2. Login Authentication Process
3. Card Application Process
4. Referral System Process
5. Admin Review Process

### For Section VII (Screenshots):
✅ Capture 15-20 screenshots showing the UI:
1. Homepage
2. Registration page
3. Login page
4. User dashboard
5. Profile page
6. Card application form
7. Referral page
8. Admin login
9. Admin dashboard
10. Admin content management
11. Admin application review
12. etc.

---

## Quick Answer to Your Question:

**Flowcharts (Section IV)** = **DIAGRAMS** showing process flow with shapes and arrows

**Screenshots (Section VII)** = **ACTUAL IMAGES** of your running system

You need BOTH for complete documentation! 📊📸
