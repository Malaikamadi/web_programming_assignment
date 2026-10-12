<?php
/**
 * Application Constants and Configuration
 * Web Programming Assignment 1 - Limkokwing University of Creative Technology Sierra Leone
 */

// Application Details
define('APP_NAME', 'Django Girls Sierra Leone');
define('APP_TAGLINE', 'Inspiring and Empowering Women in Tech Across Sierra Leone');
define('APP_ORG', 'Django Girls Freetown Chapter');
define('APP_URL', 'http://localhost/djangogirls');

// Database Configuration (Default XAMPP credentials)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'djangogirls_sl');
define('DB_CHARSET', 'utf8mb4');

// Student Details for Assignment 1 Compliance
// Format required: © [Your First Name] [Your Last Name], [Program], [Semester], [ID], [Year]
define('STUDENT_FIRST_NAME', 'Alimatu Maliaka');
define('STUDENT_LAST_NAME', 'Jalloh');
define('STUDENT_PROGRAM', 'BSC(Hons) Software Engineering with Multimedia');
define('STUDENT_SEMESTER', 'Semester 5');
define('STUDENT_ID', '905004463');
define('STUDENT_YEAR', '2026');

// Copyright String
define('COPYRIGHT_STRING', '© ' . STUDENT_FIRST_NAME . ' ' . STUDENT_LAST_NAME . ', ' . STUDENT_PROGRAM . ', ' . STUDENT_SEMESTER . ', ' . STUDENT_ID . ', ' . STUDENT_YEAR);

// Security Settings
define('SESSION_LIFETIME', 3600); // 1 hour session
