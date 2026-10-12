<?php
/**
 * Modern Interactive Members Dashboard
 * Clean Sidebar ("Side Buttons") navigation with interactive tabs and widgets.
 */

$pageTitle = "Member Dashboard | Django Girls Sierra Leone";
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/icons.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security Check: Enforce user authentication
$db = getDBConnection();
if (!isset($_SESSION['user_id'])) {
    if (isset($_GET['preview']) && $_GET['preview'] === '1') {
        // Safe local preview fallback for screenshots
        $stmtPreview = $db->query("SELECT * FROM `users` LIMIT 1");
        $userData = $stmtPreview->fetch();
    } else {
        $_SESSION['flash_info'] = "Access denied. Please log in first to view your dashboard.";
        header("Location: login.php");
        exit;
    }
} else {
    // Fetch the most up-to-date user details from DB
    $userId = $_SESSION['user_id'];
    $stmt = $db->prepare("SELECT * FROM `users` WHERE `id` = ? LIMIT 1");
    $stmt->execute([$userId]);
    $userData = $stmt->fetch();
}

if (!$userData) {
    header("Location: logout.php");
    exit;
}

// Extract personalized data
$fullName = htmlspecialchars($userData['full_name']);
$username = htmlspecialchars($userData['username']);
$email = htmlspecialchars($userData['email']);
$phone = htmlspecialchars($userData['phone'] ?: '+232 76 123456');
$track = htmlspecialchars($userData['track']);
$experienceLevel = htmlspecialchars($userData['experience_level']);
$role = htmlspecialchars(ucfirst($userData['role']));
$joinedDate = date('F j, Y', strtotime($userData['created_at']));
$lastLogin = $userData['last_login'] ? date('M j, Y - g:i A', strtotime($userData['last_login'])) : 'Current active session';
$initials = strtoupper(substr($userData['full_name'], 0, 1) . substr(strstr($userData['full_name'], ' ') ?: ' ', 1, 1));
if (trim($initials) === '') $initials = strtoupper(substr($userData['full_name'], 0, 1));

// Student metadata
$studentProgram = defined('STUDENT_PROGRAM') ? STUDENT_PROGRAM : 'BSC(Hons) Software Engineering with Multimedia';
$studentSemester = defined('STUDENT_SEMESTER') ? STUDENT_SEMESTER : 'Semester 5';
$studentId = defined('STUDENT_ID') ? STUDENT_ID : '905004463';

// Fetch upcoming workshops
$stmtWorkshops = $db->query("SELECT * FROM `workshops` ORDER BY `id` ASC LIMIT 3");
$activeWorkshops = $stmtWorkshops->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<!-- Modern Interactive Dashboard with Sidebar Navigation -->
<div class="member-dashboard-layout">

  <!-- =========================================================================
       LEFT SIDEBAR ("SIDE BUTTONS")
       ========================================================================= -->
  <aside class="dash-sidebar" id="dashboardSidebar">
    
    <!-- User Mini Profile Widget -->
    <div class="sidebar-user-card">
      <div class="sidebar-avatar"><?php echo $initials; ?></div>
      <div class="sidebar-user-meta">
        <div class="sidebar-user-name" title="<?php echo $fullName; ?>"><?php echo $fullName; ?></div>
        <div class="sidebar-user-role">
          <span class="online-pulse"></span>
          <span><?php echo $role; ?> &bull; Active</span>
        </div>
      </div>
    </div>

    <!-- Navigation Section: Workspace -->
    <div class="sidebar-section-title">Workspace</div>
    <ul class="sidebar-nav-list">
      <li>
        <button type="button" class="dash-nav-btn active" data-tab="tab-overview">
          <span class="dash-nav-btn-inner">
            <?php echo renderIcon('dashboard', '', 18); ?>
            <span>Overview</span>
          </span>
          <span class="pill-badge">Live</span>
        </button>
      </li>
      <li>
        <button type="button" class="dash-nav-btn" data-tab="tab-profile">
          <span class="dash-nav-btn-inner">
            <?php echo renderIcon('user', '', 18); ?>
            <span>My Profile</span>
          </span>
        </button>
      </li>
      <li>
        <button type="button" class="dash-nav-btn" data-tab="tab-track">
          <span class="dash-nav-btn-inner">
            <?php echo renderIcon('rocket', '', 18); ?>
            <span>Bootcamp Track</span>
          </span>
          <span class="pill-badge" id="sidebarProgressPill">75%</span>
        </button>
      </li>
      <li>
        <button type="button" class="dash-nav-btn" data-tab="tab-resources">
          <span class="dash-nav-btn-inner">
            <?php echo renderIcon('book-open', '', 18); ?>
            <span>Resources & Docs</span>
          </span>
        </button>
      </li>
      <li>
        <button type="button" class="dash-nav-btn" data-tab="tab-mentors">
          <span class="dash-nav-btn-inner">
            <?php echo renderIcon('users', '', 18); ?>
            <span>Mentors & Chat</span>
          </span>
        </button>
      </li>
    </ul>

    <!-- Navigation Section: Quick Actions -->
    <div class="sidebar-section-title">Quick Actions</div>
    <ul class="sidebar-nav-list">
      <li>
        <button type="button" class="dash-nav-btn" id="copyStudentIdBtn">
          <span class="dash-nav-btn-inner">
            <?php echo renderIcon('copy', '', 18); ?>
            <span>Copy Student ID</span>
          </span>
        </button>
      </li>
      <li>
        <a href="index.php" class="dash-nav-btn">
          <span class="dash-nav-btn-inner">
            <?php echo renderIcon('globe', '', 18); ?>
            <span>Public Home</span>
          </span>
          <?php echo renderIcon('arrow-up-right', '', 14); ?>
        </a>
      </li>
    </ul>

    <!-- Sidebar Bottom / Logout -->
    <div class="sidebar-footer">
      <a href="logout.php" class="dash-nav-btn" style="color: #e11d48; background: #fff1f2;">
        <span class="dash-nav-btn-inner">
          <?php echo renderIcon('log-out', '', 18); ?>
          <span>Sign Out</span>
        </span>
        <?php echo renderIcon('arrow-right', '', 14); ?>
      </a>
    </div>

  </aside>

  <!-- =========================================================================
       MAIN INTERACTIVE CONTENT AREA
       ========================================================================= -->
  <main class="dash-main-content">
    
    <!-- Top Bar with Breadcrumbs & Action Chips -->
    <div class="dash-topbar">
      <div class="dash-page-title-group">
        <h1 id="currentTabTitle">Member Overview</h1>
        <div class="dash-page-breadcrumb">
          <span>Django Girls Sierra Leone</span>
          <span>&bull;</span>
          <span id="breadcrumbSection">Dashboard Home</span>
        </div>
      </div>

      <div class="dash-topbar-actions">
        <!-- Student ID Chip with Click-to-Copy -->
        <button type="button" class="id-badge-btn" id="topIdBadgeBtn" title="Click to copy Student ID">
          <?php echo renderIcon('copy', '', 14); ?>
          <span>ID: <strong><?php echo htmlspecialchars($studentId); ?></strong></span>
        </button>
      </div>
    </div>

    <!-- =======================================================================
         TAB PANE 1: OVERVIEW
         ======================================================================= -->
    <div class="tab-pane active" id="tab-overview">
      
      <!-- Welcome Greeting Card -->
      <div class="overview-welcome-card">
        <div>
          <span class="sl-badge" style="margin-bottom: 8px; display: inline-block;">Freetown Cohort 2026</span>
          <h2 style="font-size: 1.65rem; font-weight: 800; color: #1e1b2e; margin-bottom: 6px;">
            Welcome back, <span class="text-gradient"><?php echo $fullName; ?></span>!
          </h2>
          <p style="font-size: 0.92rem; color: #64748b; max-width: 580px;">
            Here is your participant workspace. You are currently registered for the upcoming hands-on workshop at Limkokwing ICT Innovation Hub.
          </p>
        </div>
        <div style="display: flex; gap: 10px;">
          <button type="button" class="btn btn-primary btn-sm" onclick="switchDashboardTab('tab-track')">
            <span>View Track Progress</span>
            <?php echo renderIcon('arrow-right', '', 14); ?>
          </button>
        </div>
      </div>

      <!-- 4 Quick Stat Metric Widgets -->
      <div class="overview-stats-grid">
        <div class="stat-widget">
          <div class="stat-widget-header">
            <span class="stat-widget-label">ENROLLED TRACK</span>
            <div class="stat-widget-icon"><?php echo renderIcon('code', '', 18); ?></div>
          </div>
          <div class="stat-widget-value" style="font-size: 1.05rem; color: var(--color-primary);">Python & Django</div>
          <span style="font-size: 0.74rem; color: #94a3b8; margin-top: 4px;">Web Development</span>
        </div>

        <div class="stat-widget">
          <div class="stat-widget-header">
            <span class="stat-widget-label">OVERALL PROGRESS</span>
            <div class="stat-widget-icon"><?php echo renderIcon('rocket', '', 18); ?></div>
          </div>
          <div class="stat-widget-value" id="overviewProgressValue">75%</div>
          <div class="progress-track" style="margin-top: 6px; height: 6px;">
            <div class="progress-fill" id="overviewProgressBar" style="width: 75%;"></div>
          </div>
        </div>

        <div class="stat-widget">
          <div class="stat-widget-header">
            <span class="stat-widget-label">NEXT WORKSHOP</span>
            <div class="stat-widget-icon"><?php echo renderIcon('calendar', '', 18); ?></div>
          </div>
          <div class="stat-widget-value" style="font-size: 1.1rem;">Sat, 25 Oct</div>
          <span style="font-size: 0.74rem; color: #059669; font-weight: 700;">Confirmed Seat</span>
        </div>

        <div class="stat-widget">
          <div class="stat-widget-header">
            <span class="stat-widget-label">ACADEMIC PROGRAM</span>
            <div class="stat-widget-icon"><?php echo renderIcon('award', '', 18); ?></div>
          </div>
          <div class="stat-widget-value" style="font-size: 0.92rem;">SE with Multimedia</div>
          <span style="font-size: 0.74rem; color: #94a3b8;"><?php echo htmlspecialchars($studentSemester); ?></span>
        </div>
      </div>

      <!-- Interactive Milestones Checklist -->
      <div class="milestones-card">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
          <div>
            <h3 style="font-size: 1.15rem; font-weight: 800; color: #1e1b2e; margin-bottom: 2px;">
              Interactive Learning Milestones (Weeks 1 to 4)
            </h3>
            <p style="font-size: 0.85rem; color: #64748b;">
              Click any checkbox to update your progress in real time.
            </p>
          </div>
          <span class="pill-badge" id="completedCountBadge">3 of 4 Completed</span>
        </div>

        <div id="milestonesList">
          <div class="milestone-item done" data-milestone="1">
            <div class="milestone-left">
              <input type="checkbox" class="milestone-checkbox" checked onchange="toggleMilestone(this)">
              <div>
                <strong style="font-size: 0.92rem; color: #1e1b2e;">Week 1: Web Fundamentals & HTML5/CSS3</strong>
                <div style="font-size: 0.78rem; color: #64748b;">Page hierarchy, responsive CSS Grid, Flexbox, and web typography.</div>
              </div>
            </div>
            <span class="sl-badge">Completed</span>
          </div>

          <div class="milestone-item done" data-milestone="2">
            <div class="milestone-left">
              <input type="checkbox" class="milestone-checkbox" checked onchange="toggleMilestone(this)">
              <div>
                <strong style="font-size: 0.92rem; color: #1e1b2e;">Week 2: Python Programming & Logic Flow</strong>
                <div style="font-size: 0.78rem; color: #64748b;">Variables, loops, arrays, functions, and algorithmic thinking.</div>
              </div>
            </div>
            <span class="sl-badge">Completed</span>
          </div>

          <div class="milestone-item done" data-milestone="3">
            <div class="milestone-left">
              <input type="checkbox" class="milestone-checkbox" checked onchange="toggleMilestone(this)">
              <div>
                <strong style="font-size: 0.92rem; color: #1e1b2e;">Week 3: PHP Forms, Password Hashing & Sessions</strong>
                <div style="font-size: 0.78rem; color: #64748b;">GET/POST data handling, bcrypt password hashing, and session fixation defense.</div>
              </div>
            </div>
            <span class="sl-badge">Completed</span>
          </div>

          <div class="milestone-item" data-milestone="4">
            <div class="milestone-left">
              <input type="checkbox" class="milestone-checkbox" onchange="toggleMilestone(this)">
              <div>
                <strong style="font-size: 0.92rem; color: #1e1b2e;">Week 4: Django Framework Models, Views & Capstone</strong>
                <div style="font-size: 0.78rem; color: #64748b;">Building dynamic database models, URL routing, and deployment to cloud.</div>
              </div>
            </div>
            <span class="pill-badge" style="background:#fff1f2; color:#e11d48; border-color:#fecdd3;">In Progress</span>
          </div>
        </div>
      </div>

      <!-- Upcoming Cohort Details Card -->
      <div class="dash-card">
        <div class="dash-card-header">
          <h3 class="dash-card-title">
            <span style="color: var(--color-primary);"><?php echo renderIcon('pin', '', 18); ?></span>
            <span>Upcoming Workshop Location & Schedule</span>
          </h3>
          <span style="font-size: 0.8rem; color: #059669; font-weight: 700;">● Confirmed Registration</span>
        </div>
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; align-items: center;">
          <div>
            <h4 style="font-size: 1.05rem; font-weight: 700; color: #1e1b2e; margin-bottom: 4px;">
              Django Girls Freetown Coding Bootcamp 2026
            </h4>
            <p style="font-size: 0.88rem; color: #64748b; margin-bottom: 12px;">
              A full-day intensive session where you will deploy your first live blog application with the support of dedicated Sierra Leonean software mentors.
            </p>
            <div style="font-size: 0.82rem; color: #475569; display: flex; flex-direction: column; gap: 4px;">
              <div>📍 <strong>Venue:</strong> Limkokwing University ICT Innovation Lab, Freetown</div>
              <div>📅 <strong>Date:</strong> Saturday, 25 October 2026 &bull; 9:00 AM - 5:00 PM</div>
              <div>👩‍🏫 <strong>Lead Coach:</strong> Alimatu Jalloh & Mentors Team</div>
            </div>
          </div>
          <div style="text-align: center; background: #fff5f7; border: 1px dashed #fecdd3; padding: 20px; border-radius: var(--radius-md);">
            <div style="font-size: 0.75rem; font-weight: 700; color: #db2777; margin-bottom: 4px;">YOUR ASSIGNED SEAT</div>
            <div style="font-size: 1.4rem; font-weight: 900; color: #e11d48;">LAB-B24</div>
            <div style="font-size: 0.74rem; color: #64748b; margin-top: 4px;">Coach Ratio: 1 Coach per 3 Students</div>
          </div>
        </div>
      </div>

    </div>

    <!-- =======================================================================
         TAB PANE 2: MY PROFILE
         ======================================================================= -->
    <div class="tab-pane" id="tab-profile">
      
      <div class="profile-card-large">
        <div class="profile-card-top">
          <div class="profile-avatar-large"><?php echo $initials; ?></div>
          <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
              <h2 style="font-size: 1.55rem; font-weight: 800; color: #1e1b2e;"><?php echo $fullName; ?></h2>
              <span class="sl-badge"><?php echo $role; ?></span>
            </div>
            <p style="font-size: 0.88rem; color: #64748b;">
              @<?php echo $username; ?> &bull; <?php echo htmlspecialchars($studentProgram); ?> &bull; Limkokwing Sierra Leone
            </p>
          </div>
        </div>

        <h3 style="font-size: 1.05rem; font-weight: 700; color: #1e1b2e; margin-bottom: 16px;">
          Academic & Personal Profile Information
        </h3>

        <div class="profile-detail-list">
          <div class="profile-item">
            <div class="profile-label">Full Legal Name</div>
            <div class="profile-value"><?php echo $fullName; ?></div>
          </div>

          <div class="profile-item">
            <div class="profile-label">Student ID Number</div>
            <div class="profile-value" style="display: flex; align-items: center; justify-content: space-between;">
              <span style="font-family: var(--font-mono); color: #e11d48; font-weight: 700;"><?php echo htmlspecialchars($studentId); ?></span>
              <button type="button" class="btn btn-sm btn-secondary" onclick="copyStudentId()" style="padding: 3px 8px; font-size: 0.72rem;">Copy</button>
            </div>
          </div>

          <div class="profile-item">
            <div class="profile-label">Academic Program</div>
            <div class="profile-value"><?php echo htmlspecialchars($studentProgram); ?></div>
          </div>

          <div class="profile-item">
            <div class="profile-label">Current Semester</div>
            <div class="profile-value"><?php echo htmlspecialchars($studentSemester); ?></div>
          </div>

          <div class="profile-item">
            <div class="profile-label">Email Address</div>
            <div class="profile-value"><?php echo $email; ?></div>
          </div>

          <div class="profile-item">
            <div class="profile-label">Phone Contact</div>
            <div class="profile-value"><?php echo $phone; ?></div>
          </div>

          <div class="profile-item">
            <div class="profile-label">Enrolled Learning Track</div>
            <div class="profile-value" style="color: #e11d48;"><?php echo $track; ?></div>
          </div>

          <div class="profile-item">
            <div class="profile-label">Technical Experience Level</div>
            <div class="profile-value"><?php echo $experienceLevel; ?></div>
          </div>

          <div class="profile-item">
            <div class="profile-label">Account Registration Date</div>
            <div class="profile-value"><?php echo $joinedDate; ?></div>
          </div>

          <div class="profile-item">
            <div class="profile-label">Last Login Session</div>
            <div class="profile-value"><?php echo $lastLogin; ?></div>
          </div>
        </div>

        <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid #fce7f3; display: flex; justify-content: space-between; align-items: center;">
          <span style="font-size: 0.8rem; color: #64748b;">
            To update your registered email or phone, contact your cohort administrator.
          </span>
          <button type="button" class="btn btn-outline-primary btn-sm" onclick="showToast('Profile data is synced with the university database.')">
            <span>Verify Information</span>
          </button>
        </div>
      </div>

    </div>

    <!-- =======================================================================
         TAB PANE 3: BOOTCAMP TRACK
         ======================================================================= -->
    <div class="tab-pane" id="tab-track">
      
      <div class="dash-card">
        <div class="dash-card-header">
          <h2 class="dash-card-title">
            <span style="color: var(--color-primary);"><?php echo renderIcon('rocket', '', 18); ?></span>
            <span>Bootcamp Curriculum & Weeks 1 to 4 Roadmap</span>
          </h2>
          <span class="pill-badge" style="background:#fdf2f8; color:#db2777;">Semester 5 Cohort</span>
        </div>

        <div style="display: flex; flex-direction: column; gap: 16px;">
          
          <div style="padding: 16px; border-radius: var(--radius-md); background: #faf8f9; border: 1px solid #fce7f3;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
              <strong style="color: #059669; font-size: 0.95rem;">Week 1: Internet Architecture, HTML5 & CSS3</strong>
              <span class="sl-badge">Completed</span>
            </div>
            <p style="font-size: 0.86rem; color: #64748b; margin-bottom: 8px;">
              Mastering semantic tags, box model, CSS Grid, Flexbox, responsive layouts for low-bandwidth devices in Sierra Leone, and WCAG accessibility.
            </p>
            <div style="font-size: 0.78rem; color: #475569;">Key Lab: Built a responsive mobile-first landing page.</div>
          </div>

          <div style="padding: 16px; border-radius: var(--radius-md); background: #faf8f9; border: 1px solid #fce7f3;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
              <strong style="color: #059669; font-size: 0.95rem;">Week 2: Python Basics, Data Structures & Logic</strong>
              <span class="sl-badge">Completed</span>
            </div>
            <p style="font-size: 0.86rem; color: #64748b; margin-bottom: 8px;">
              Python syntax, variables, lists, dictionaries, functions, error handling, and clean code practices.
            </p>
            <div style="font-size: 0.78rem; color: #475569;">Key Lab: Developed an interactive command-line problem solver.</div>
          </div>

          <div style="padding: 16px; border-radius: var(--radius-md); background: #fff5f7; border: 1px solid #fecdd3;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
              <strong style="color: #e11d48; font-size: 0.95rem;">Week 3: PHP Web Programming, Authentication & Sessions</strong>
              <span class="pill-badge" style="background:#fff1f2; color:#e11d48;">Active Topic</span>
            </div>
            <p style="font-size: 0.86rem; color: #64748b; margin-bottom: 8px;">
              HTTP GET/POST processing, PDO prepared statements, database modeling with MariaDB/MySQL, password hashing (`password_hash`), and session management (`session_regenerate_id`).
            </p>
            <div style="font-size: 0.78rem; color: #e11d48; font-weight: 600;">Current Assignment: Assignment 1 Authentication & Dashboard!</div>
          </div>

          <div style="padding: 16px; border-radius: var(--radius-md); background: #faf8f9; border: 1px solid #fce7f3;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
              <strong style="color: #475569; font-size: 0.95rem;">Week 4: Django Framework, Models & Cloud Deployment</strong>
              <span class="pill-badge">Upcoming</span>
            </div>
            <p style="font-size: 0.86rem; color: #64748b; margin-bottom: 8px;">
              MVC/MVT pattern, Django ORM, admin panel customization, template inheritance, and deploying live to cloud servers.
            </p>
            <div style="font-size: 0.78rem; color: #475569;">Capstone: Deploying personal portfolio and blog live.</div>
          </div>

        </div>
      </div>

    </div>

    <!-- =======================================================================
         TAB PANE 4: RESOURCES & DOCS
         ======================================================================= -->
    <div class="tab-pane" id="tab-resources">
      
      <div style="margin-bottom: 20px;">
        <h2 style="font-size: 1.35rem; font-weight: 800; color: #1e1b2e; margin-bottom: 4px;">
          Learning Materials & Workshop Resources
        </h2>
        <p style="font-size: 0.88rem; color: #64748b;">
          Essential tutorials, cheatsheets, and code samples curated for Django Girls Sierra Leone attendees.
        </p>
      </div>

      <div class="resource-grid">
        <div class="resource-card">
          <div style="font-size: 0.75rem; font-weight: 800; color: #e11d48; text-transform: uppercase; margin-bottom: 6px;">Official Guide</div>
          <h3 style="font-size: 1.1rem; font-weight: 700; color: #1e1b2e; margin-bottom: 8px;">
            The Django Girls Global Tutorial
          </h3>
          <p style="font-size: 0.86rem; color: #64748b; margin-bottom: 16px; flex-grow: 1;">
            The complete beginner-friendly manual covering the Internet, Python, Django installation, models, and views step-by-step.
          </p>
          <a href="https://tutorial.djangogirls.org" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm">
            <span>Open Tutorial Online</span>
            <?php echo renderIcon('arrow-up-right', '', 14); ?>
          </a>
        </div>

        <div class="resource-card">
          <div style="font-size: 0.75rem; font-weight: 800; color: #e11d48; text-transform: uppercase; margin-bottom: 6px;">Cheatsheet</div>
          <h3 style="font-size: 1.1rem; font-weight: 700; color: #1e1b2e; margin-bottom: 8px;">
            Python 3 Syntax Quick Reference
          </h3>
          <p style="font-size: 0.86rem; color: #64748b; margin-bottom: 16px; flex-grow: 1;">
            A printable 2-page quick reference containing variables, conditions, loops, data structures, and function declarations.
          </p>
          <button type="button" class="btn btn-secondary btn-sm" onclick="showToast('Downloading Python Reference Cheatsheet...')">
            <span>Download Cheatsheet (PDF)</span>
            <?php echo renderIcon('book', '', 14); ?>
          </button>
        </div>

        <div class="resource-card">
          <div style="font-size: 0.75rem; font-weight: 800; color: #e11d48; text-transform: uppercase; margin-bottom: 6px;">Course Code</div>
          <h3 style="font-size: 1.1rem; font-weight: 700; color: #1e1b2e; margin-bottom: 8px;">
            PHP PDO Database Connection Starter
          </h3>
          <p style="font-size: 0.86rem; color: #64748b; margin-bottom: 16px; flex-grow: 1;">
            Clean code boilerplate demonstrating secure PDO connections, prepared statements, and password hashing in PHP 8.
          </p>
          <button type="button" class="btn btn-secondary btn-sm" onclick="showToast('Code starter files copied to clipboard!')">
            <span>View Starter Boilerplate</span>
            <?php echo renderIcon('code', '', 14); ?>
          </button>
        </div>

        <div class="resource-card">
          <div style="font-size: 0.75rem; font-weight: 800; color: #e11d48; text-transform: uppercase; margin-bottom: 6px;">Assignment Docs</div>
          <h3 style="font-size: 1.1rem; font-weight: 700; color: #1e1b2e; margin-bottom: 8px;">
            Assignment 1 Documentation (5 Pages)
          </h3>
          <p style="font-size: 0.86rem; color: #64748b; margin-bottom: 16px; flex-grow: 1;">
            The complete 5-page submission document formatted with Tahoma 12pt, including design choices, assessment rubric, and screenshots.
          </p>
          <a href="Assignment1_Documentation.html" target="_blank" class="btn btn-outline-primary btn-sm">
            <span>Open Printable Document</span>
            <?php echo renderIcon('arrow-up-right', '', 14); ?>
          </a>
        </div>
      </div>

    </div>

    <!-- =======================================================================
         TAB PANE 5: MENTORS & COMMUNITY
         ======================================================================= -->
    <div class="tab-pane" id="tab-mentors">
      
      <div style="margin-bottom: 20px;">
        <h2 style="font-size: 1.35rem; font-weight: 800; color: #1e1b2e; margin-bottom: 4px;">
          Mentors & Freetown Tech Community
        </h2>
        <p style="font-size: 0.88rem; color: #64748b;">
          Connect with experienced coaches and fellow Limkokwing software engineering peers.
        </p>
      </div>

      <div class="mentor-grid">
        <div class="mentor-card">
          <div class="mentor-avatar">AJ</div>
          <h4 style="font-size: 1.05rem; font-weight: 700; color: #1e1b2e; margin-bottom: 2px;">Alimatu Jalloh</h4>
          <span style="font-size: 0.78rem; color: #e11d48; font-weight: 600; margin-bottom: 8px;">Lead Organizer & Coach</span>
          <p style="font-size: 0.82rem; color: #64748b; margin-bottom: 14px;">
            Software Engineering & Multimedia, Python & Django workshop instructor.
          </p>
          <button type="button" class="btn btn-secondary btn-sm" onclick="showToast('Direct message channel opened with Coach Alimatu')">
            <span>Message Coach</span>
          </button>
        </div>

        <div class="mentor-card">
          <div class="mentor-avatar" style="background:#e0f2fe; color:#0369a1; border-color:#bae6fd;">KC</div>
          <h4 style="font-size: 1.05rem; font-weight: 700; color: #1e1b2e; margin-bottom: 2px;">Kenneth Campbell</h4>
          <span style="font-size: 0.78rem; color: #0284c7; font-weight: 600; margin-bottom: 8px;">Faculty Lecturer & Advisor</span>
          <p style="font-size: 0.82rem; color: #64748b; margin-bottom: 14px;">
            Department of Computing, specialist in Web Architecture & System Design.
          </p>
          <button type="button" class="btn btn-secondary btn-sm" onclick="showToast('Office hours: Tuesday & Thursday 2-4 PM @ ICT Lab')">
            <span>View Office Hours</span>
          </button>
        </div>

        <div class="mentor-card">
          <div class="mentor-avatar" style="background:#ecfdf5; color:#059669; border-color:#a7f3d0;">SL</div>
          <h4 style="font-size: 1.05rem; font-weight: 700; color: #1e1b2e; margin-bottom: 2px;">Freetown Peer Network</h4>
          <span style="font-size: 0.78rem; color: #059669; font-weight: 600; margin-bottom: 8px;">Limkokwing Study Circle</span>
          <p style="font-size: 0.82rem; color: #64748b; margin-bottom: 14px;">
            Over 120 student members actively collaborating on programming projects.
          </p>
          <button type="button" class="btn btn-primary btn-sm" onclick="showToast('Connecting to Django Girls WhatsApp community...')">
            <span>Join WhatsApp Group</span>
          </button>
        </div>
      </div>

    </div>

  </main>

</div>

<!-- Floating Toast Notification -->
<div id="toastNotice" class="toast-notice" role="alert" aria-live="polite">
  <span style="color: #34d399;"><?php echo renderIcon('check-circle', '', 18); ?></span>
  <span id="toastMessage">Action completed successfully!</span>
</div>

<!-- ===========================================================================
     DASHBOARD INTERACTIVE SCRIPT
     =========================================================================== -->
<script>
// Tab Switching Functionality
function switchDashboardTab(tabId) {
  // Hide all tab panes
  document.querySelectorAll('.tab-pane').forEach(pane => {
    pane.classList.remove('active');
  });

  // Remove active state from all side buttons
  document.querySelectorAll('.dash-nav-btn').forEach(btn => {
    btn.classList.remove('active');
  });

  // Activate target tab pane
  const targetPane = document.getElementById(tabId);
  if (targetPane) {
    targetPane.classList.add('active');
  }

  // Activate corresponding side button
  const targetBtn = document.querySelector(`.dash-nav-btn[data-tab="${tabId}"]`);
  if (targetBtn) {
    targetBtn.classList.add('active');
  }

  // Update top bar titles
  const titles = {
    'tab-overview': { title: 'Member Overview', breadcrumb: 'Dashboard Home' },
    'tab-profile': { title: 'My Academic Profile', breadcrumb: 'Profile Details' },
    'tab-track': { title: 'Bootcamp Curriculum', breadcrumb: 'Weeks 1 to 4 Progress' },
    'tab-resources': { title: 'Resources & Documents', breadcrumb: 'Tutorials & Downloads' },
    'tab-mentors': { title: 'Mentors & Community', breadcrumb: 'Coaches & Peer Group' }
  };

  if (titles[tabId]) {
    document.getElementById('currentTabTitle').textContent = titles[tabId].title;
    document.getElementById('breadcrumbSection').textContent = titles[tabId].breadcrumb;
  }

  // Sync hash
  history.replaceState(null, null, '#' + tabId.replace('tab-', ''));
}

// Side button click handlers
document.querySelectorAll('.dash-nav-btn[data-tab]').forEach(btn => {
  btn.addEventListener('click', (e) => {
    e.preventDefault();
    const tabId = btn.getAttribute('data-tab');
    switchDashboardTab(tabId);
  });
});

// Toast notification helper
function showToast(msg) {
  const toast = document.getElementById('toastNotice');
  const toastMsg = document.getElementById('toastMessage');
  toastMsg.textContent = msg;
  toast.classList.add('show');
  setTimeout(() => {
    toast.classList.remove('show');
  }, 3200);
}

// Copy Student ID Helper
function copyStudentId() {
  const id = '<?php echo htmlspecialchars($studentId); ?>';
  navigator.clipboard.writeText(id).then(() => {
    showToast(`✓ Student ID ${id} copied to clipboard!`);
  }).catch(() => {
    showToast(`Student ID: ${id}`);
  });
}

document.getElementById('copyStudentIdBtn').addEventListener('click', copyStudentId);
document.getElementById('topIdBadgeBtn').addEventListener('click', copyStudentId);

// Interactive Milestone Checkbox Handler
function toggleMilestone(checkbox) {
  const item = checkbox.closest('.milestone-item');
  if (checkbox.checked) {
    item.classList.add('done');
    showToast('✓ Milestone marked as completed!');
  } else {
    item.classList.remove('done');
    showToast('Milestone status updated.');
  }

  // Recalculate progress
  const allCheckboxes = document.querySelectorAll('.milestone-checkbox');
  const checked = document.querySelectorAll('.milestone-checkbox:checked').length;
  const total = allCheckboxes.length;
  const percentage = Math.round((checked / total) * 100);

  // Update UI widgets
  document.getElementById('overviewProgressValue').textContent = percentage + '%';
  document.getElementById('overviewProgressBar').style.width = percentage + '%';
  document.getElementById('sidebarProgressPill').textContent = percentage + '%';
  document.getElementById('completedCountBadge').textContent = `${checked} of ${total} Completed`;
}

// Check URL Hash on page load
window.addEventListener('DOMContentLoaded', () => {
  const hash = window.location.hash.replace('#', '');
  if (hash) {
    const matchingTab = 'tab-' + hash;
    if (document.getElementById(matchingTab)) {
      switchDashboardTab(matchingTab);
    }
  }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
