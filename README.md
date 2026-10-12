# Django Girls Sierra Leone - Web Platform & Authentication System
**Course:** Web Programming (DTECH 206 / ICT204) — Assignment 1 (Weeks 1 to 4)  
**Student:** Alimatu Maliaka Jalloh &bull; ID: 905004463  
**Program:** BSC(Hons) Software Engineering with Multimedia &bull; Semester 5  

---

## 1. Project Overview
This project is an initial web platform developed for **Django Girls Sierra Leone** (Freetown Chapter), a non-profit volunteer initiative that empowers women and girls across Sierra Leone with free computer programming bootcamps and tech mentorship.

The application incorporates all fundamental concepts covered during Weeks 1 to 4 of class:
- Semantic HTML5 page layout & responsive Vanilla CSS design system.
- Prominently displayed Django Girls logo at the top header across all pages.
- Complete user registration system (`register.php`) with client- and server-side validation.
- Secure user authentication (`login.php`) powered by PHP `password_verify()`.
- Password hashing using `password_hash($password, PASSWORD_DEFAULT)` with bcrypt encryption.
- Personalized user dashboard (`dashboard.php`) displaying member profile, enrolled tracks, and cohort progress.
- Session termination and logout functionality (`logout.php`) clearing memory, server sessions, and browser cookies.
- Standardized assignment copyright footer strictly formatted as required.

---

## 2. File & Directory Structure

```text
Web_Programming_assignment/
├── assets/
│   ├── css/
│   │   └── style.css                     # Modern, responsive Vanilla CSS design system
│   ├── js/
│   │   └── main.js                      # Password toggles, match indicators & interactions
│   └── images/
│       ├── django_girls_logo.jpg         # Prominent uploaded Django Girls logo
│       ├── logo.svg                      # High-resolution vector logo
│       ├── homepage_screenshot.png       # Printed screenshot of Home Page
│       ├── login_screenshot.png          # Screenshot of login portal
│       └── register_screenshot.png       # Screenshot of sign-up form
├── config/
│   ├── constants.php                     # App titles, database config & copyright constants
│   └── db.php                            # PDO connection with auto-provisioning
├── database/
│   └── schema.sql                        # MySQL database schema & sample seed data
├── includes/
│   ├── header.php                        # Site header with prominent logo & dynamic navigation
│   └── footer.php                        # Footer with required copyright information
├── index.php                             # Home Page (Logo, Hero, Stats, Workshops, Curriculum)
├── register.php                          # Sign-Up Page (Registration form & validation)
├── login.php                             # Login Page (Authentication & session regeneration)
├── dashboard.php                         # Protected Personalized User Dashboard
├── logout.php                            # Secure Session Destruction & Logout
├── Assignment1_Documentation.html        # 5-Page Printable HTML Documentation (Tahoma 12pt)
├── DTECH_Web_Programming_Assignment1_Alimatu_Jalloh.docx  # 5-Page Word Submission Document (Tahoma 12pt)
└── README.md                             # Documentation & Setup Guide
```

---

## 3. How to Run Locally

### Option A: Using XAMPP (Recommended)
1. Open the **XAMPP Control Panel** and make sure **Apache** and **MySQL** are running (both showing green).
2. The project has already been linked to your XAMPP htdocs directory via directory junction:
   ```
   C:\xampp\htdocs\djangogirls -> C:\Users\madi\Desktop\Web_Programming_assignment
   ```
3. Open any web browser and visit:
   ```
   http://localhost/djangogirls/index.php
   ```
4. The database (`djangogirls_sl`) and all tables will automatically initialize on the first request if they don't already exist!

### Option B: Using PHP Built-In Server
Run the following command from the project root:
```powershell
& "C:\xampp\php\php.exe" -S localhost:8000
```
Then visit: `http://localhost:8000/index.php`

---

## 4. Evaluation Credentials

You can test user registration with any new account, or immediately log in with the pre-seeded evaluation account:
- **Username:** `alimatu_jalloh` (or email: `alimatu@djangogirls.org.sl`)
- **Password:** `Password@123`

---

## 5. Security Highlights (Weeks 1 to 4)
- **Password Hashing:** Passwords are never stored in plaintext. They are encrypted using `password_hash($pass, PASSWORD_DEFAULT)` which applies the secure **bcrypt** algorithm.
- **SQL Injection Prevention:** 100% of database queries use **PDO prepared statements** with bound parameters.
- **Session Protection:** Calls `session_regenerate_id(true)` upon successful login to prevent session fixation attacks.
- **XSS Defense:** All output values are escaped using `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`.

---

## 6. Submission Documents (4 to 5 Pages, Tahoma 12pt)
In accordance with the assignment guidelines:
1. **Microsoft Word Format:** `DTECH_Web_Programming_Assignment1_Alimatu_Jalloh.docx`
   - Formatted in **Tahoma font, size 12**.
   - Exactly 5 pages:
     - Page 1: Academic Cover Page
     - Page 2: Section 1 (Executive Summary) & Section 2 (System Architecture)
     - Page 3: Section 3 (Design Choices, Themes & Plugins) & Section 4 (PHP Security)
     - Page 4: Section 5 (Home Page Interface & Embedded High-Res Screenshot)
     - Page 5: Section 6 (Assessment Form Rubric & Signed Student Declaration)
2. **Printable HTML Format:** `Assignment1_Documentation.html`
   - Ready to open in any web browser and print to PDF or paper with one click.
