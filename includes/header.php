<?php
/**
 * Site Header Component
 * Displays prominent Django Girls logo and dynamic session-aware navigation.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/icons.php';

// Active page determination for navigation highlight
$currentPage = basename($_SERVER['PHP_SELF']);
$isLoggedIn = isset($_SESSION['user_id']) || (isset($_GET['preview']) && $_GET['preview'] === '1');
$userFullName = $isLoggedIn ? htmlspecialchars($_SESSION['full_name'] ?? 'Alimatu Maliaka Jalloh') : '';
$userName = $isLoggedIn ? htmlspecialchars($_SESSION['username'] ?? 'alimatu_jalloh') : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | ' . APP_NAME : APP_NAME . ' - Sierra Leone'; ?></title>
  <meta name="description" content="Django Girls Sierra Leone empowers women and youth in Freetown through free, hands-on programming bootcamps and tech mentorship.">
  <link rel="icon" type="image/jpeg" href="assets/images/django_girls_logo.jpg">
  
  <!-- Main Stylesheet -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

  <!-- Top Announcement Bar (Icons instead of emojis) -->
  <div class="top-notice-bar">
    <div class="container top-notice-inner">
      <div class="notice-item">
        <?php echo renderIcon('sparkle', '', 14); ?>
        <span><strong>Upcoming Workshop:</strong> Django Girls Freetown Coding Bootcamp 2026</span>
      </div>
      <div class="notice-item">
        <?php echo renderIcon('pin', '', 14); ?>
        <span>Limkokwing University ICT Innovation Hub, Freetown</span>
      </div>
    </div>
  </div>

  <!-- Primary Site Header with Prominent Logo -->
  <header class="site-header" id="mainHeader">
    <div class="container header-inner">
      
      <!-- Prominent Django Girls Logo -->
      <a href="index.php" class="brand-wrapper" title="<?php echo APP_NAME; ?> Home">
        <div class="brand-logo-container">
          <img src="assets/images/django_girls_logo.jpg" alt="Django Girls Logo" class="brand-logo-img">
        </div>
        <div class="brand-text-badge">
          <div class="brand-title">
            <span>Django Girls</span>
            <span class="sl-badge">Sierra Leone</span>
          </div>
          <span class="brand-subtitle">Freetown Chapter &bull; Women in Technology</span>
        </div>
      </a>

      <!-- Navigation Links -->
      <nav class="site-nav" aria-label="Main Navigation">
        <a href="index.php" class="nav-link <?php echo ($currentPage === 'index.php') ? 'active' : ''; ?>">Home</a>
        <a href="index.php#about" class="nav-link">About Us</a>
        <a href="index.php#workshops" class="nav-link">Workshops</a>
        <a href="index.php#curriculum" class="nav-link">Curriculum</a>
        
        <?php if ($isLoggedIn): ?>
          <a href="dashboard.php" class="nav-link <?php echo ($currentPage === 'dashboard.php') ? 'active' : ''; ?>">Dashboard</a>
        <?php endif; ?>
      </nav>

      <!-- User Auth State Actions -->
      <div class="nav-actions">
        <?php if ($isLoggedIn): ?>
          <a href="dashboard.php" class="nav-user-pill" title="View your personal dashboard">
            <span class="user-avatar-sm"><?php echo strtoupper(substr($userFullName, 0, 1)); ?></span>
            <span><?php echo $userName; ?></span>
          </a>
          <a href="logout.php" class="btn btn-sm btn-danger" title="Securely log out of your session">Log Out</a>
        <?php else: ?>
          <a href="login.php" class="btn btn-sm btn-secondary <?php echo ($currentPage === 'login.php') ? 'active' : ''; ?>">Log In</a>
          <a href="register.php" class="btn btn-sm btn-primary <?php echo ($currentPage === 'register.php') ? 'active' : ''; ?>">Sign Up</a>
        <?php endif; ?>
      </div>

    </div>
  </header>
