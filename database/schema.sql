-- ============================================================
-- Django Girls Sierra Leone - Database Schema
-- Web Programming Assignment 1
-- Database: djangogirls_sl
-- ============================================================

CREATE DATABASE IF NOT EXISTS `djangogirls_sl`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `djangogirls_sl`;

-- ------------------------------------------------------------
-- Table: users
-- Stores registered participants, organizers, and mentors
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `full_name` VARCHAR(120) NOT NULL,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(120) NOT NULL UNIQUE,
  `phone` VARCHAR(30) NULL,
  `track` VARCHAR(100) NOT NULL DEFAULT 'Python & Web Development',
  `experience_level` VARCHAR(50) NOT NULL DEFAULT 'Beginner',
  `password_hash` VARCHAR(255) NOT NULL,
  `role` VARCHAR(20) NOT NULL DEFAULT 'member',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_login` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table: workshops
-- Django Girls workshops & training tracks in Sierra Leone
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `workshops` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT NOT NULL,
  `scheduled_date` VARCHAR(100) NOT NULL,
  `location` VARCHAR(120) NOT NULL,
  `instructor` VARCHAR(100) NOT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'Upcoming'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Sample Seed Data for Workshops
-- ------------------------------------------------------------
INSERT INTO `workshops` (`title`, `description`, `scheduled_date`, `location`, `instructor`, `status`)
VALUES 
  ('Django Girls Freetown Hands-On Workshop', 'A full-day intensive bootcamp for women learning Python, Django, HTML, and CSS to build their first live web application.', 'Saturday, 25 October 2026', 'Limkokwing ICT Innovation Lab, Freetown', 'Alimatu Jalloh & DG Mentors', 'Upcoming'),
  ('Python Fundamentals & Problem Solving', 'Master variables, loops, data structures, and algorithmic logic before diving into server-side frameworks.', 'Saturday, 8 November 2026', 'Virtual / Hybrid Freetown Hub', 'Kenneth Campbell & Coaches', 'Upcoming'),
  ('Database Design & MySQL with PHP / Django', 'Learn relational database modeling, SQL queries, and secure PDO connections for production applications.', 'Saturday, 22 November 2026', 'Freetown City Tech Center', 'DG Technical Team', 'Upcoming')
ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);

-- ------------------------------------------------------------
-- Seed Demo Account
-- Username: student_demo | Password: Password@123
-- ------------------------------------------------------------
INSERT INTO `users` (`full_name`, `username`, `email`, `phone`, `track`, `experience_level`, `password_hash`, `role`)
VALUES 
  ('Alimatu Maliaka Jalloh', 'alimatu_jalloh', 'alimatu@djangogirls.org.sl', '+232 76 123456', 'Python & Django Web Development', 'Intermediate', '$2y$10$wK1eHmsD6GZzZ3PfvB597eZkVm1jL8U1N2Hw0TqNnLd9vJ7L0e8W2', 'member')
ON DUPLICATE KEY UPDATE `full_name` = VALUES(`full_name`);
