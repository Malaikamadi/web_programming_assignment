<?php
/**
 * Database Connection & Auto-Provisioning Handler
 * Uses PHP Data Objects (PDO) for secure, prepared SQL execution.
 */

require_once __DIR__ . '/constants.php';

function getDBConnection(): PDO {
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    $host = DB_HOST;
    $user = DB_USER;
    $pass = DB_PASS;
    $dbname = DB_NAME;
    $charset = DB_CHARSET;

    try {
        // Step 1: Connect to MySQL server (without selecting DB first)
        $dsnWithoutDb = "mysql:host={$host};charset={$charset}";
        $pdoInit = new PDO($dsnWithoutDb, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        // Step 2: Ensure database exists
        $pdoInit->exec("CREATE DATABASE IF NOT EXISTS `{$dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        // Step 3: Connect to the specific database
        $dsnWithDb = "mysql:host={$host};dbname={$dbname};charset={$charset}";
        $pdo = new PDO($dsnWithDb, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        // Step 4: Auto-create tables if they don't exist
        $pdo->exec("
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
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `workshops` (
              `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              `title` VARCHAR(150) NOT NULL,
              `description` TEXT NOT NULL,
              `scheduled_date` VARCHAR(100) NOT NULL,
              `location` VARCHAR(120) NOT NULL,
              `instructor` VARCHAR(100) NOT NULL,
              `status` VARCHAR(50) NOT NULL DEFAULT 'Upcoming'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // Check if demo workshops exist; if not, seed them
        $stmt = $pdo->query("SELECT COUNT(*) AS total FROM `workshops`");
        $count = (int)$stmt->fetchColumn();
        if ($count === 0) {
            $pdo->exec("
                INSERT INTO `workshops` (`title`, `description`, `scheduled_date`, `location`, `instructor`, `status`)
                VALUES 
                  ('Django Girls Freetown Coding Bootcamp', 'A full-day intensive workshop for women learning Python, Django, HTML, and CSS to launch their first website.', 'Saturday, 25 October 2026', 'Limkokwing ICT Innovation Lab, Freetown', 'Alimatu Jalloh & Mentors', 'Upcoming'),
                  ('Python Fundamentals & Problem Solving', 'Master syntax, logic flow, loops, data structures, and functions in Python.', 'Saturday, 8 November 2026', 'Hybrid Hub / Virtual Room', 'Kenneth Campbell & Coaches', 'Upcoming'),
                  ('Database Modeling & Web Architecture', 'Hands-on practical session covering MySQL, PDO, and relational database best practices.', 'Saturday, 22 November 2026', 'Freetown City Tech Center', 'Technical Lead', 'Upcoming')
            ");
        }

        // Check if demo user exists; if not, insert with known password (Password@123)
        $stmtUser = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `username` = ?");
        $stmtUser->execute(['alimatu_jalloh']);
        if ((int)$stmtUser->fetchColumn() === 0) {
            $demoHash = password_hash('Password@123', PASSWORD_DEFAULT);
            $stmtInsert = $pdo->prepare("
                INSERT INTO `users` (`full_name`, `username`, `email`, `phone`, `track`, `experience_level`, `password_hash`, `role`)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmtInsert->execute([
                'Alimatu Maliaka Jalloh',
                'alimatu_jalloh',
                'alimatu@djangogirls.org.sl',
                '+232 76 123456',
                'Python & Django Web Development',
                'Intermediate',
                $demoHash,
                'member'
            ]);
        }

        return $pdo;

    } catch (PDOException $e) {
        die("<div style='font-family: Tahoma, sans-serif; padding: 20px; background: #fee2e2; border: 1px solid #ef4444; color: #991b1b; border-radius: 8px; margin: 20px;'>
            <h3>Database Connection Error</h3>
            <p>" . htmlspecialchars($e->getMessage()) . "</p>
            <p><strong>Note:</strong> Please make sure MySQL is running in your XAMPP Control Panel.</p>
        </div>");
    }
}
