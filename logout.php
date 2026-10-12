<?php
/**
 * Logout Functionality
 * Securely terminates user session, removes cookies, and redirects to login.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Unset all session variables in memory
$_SESSION = [];

// 2. Clear session cookie from the client's browser if present
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 3. Destroy session data on the server
session_destroy();

// 4. Start a fresh new session to pass feedback flash message
session_start();
$_SESSION['flash_success'] = "You have been securely logged out. Have a wonderful day!";

// 5. Redirect back to login page
header("Location: login.php");
exit;
