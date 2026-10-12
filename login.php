<?php
/**
 * Login Page - User Authentication Form
 * Incorporates secure password verification and session protection (Weeks 1-4).
 */

$pageTitle = "Login | Member Authentication";
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/icons.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to dashboard if user is already authenticated
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$errorMessage = '';
$flashSuccess = $_SESSION['flash_success'] ?? '';
unset($_SESSION['flash_success']);

$flashInfo = $_SESSION['flash_info'] ?? '';
unset($_SESSION['flash_info']);

$login_identifier = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_identifier = trim($_POST['login_identifier'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($login_identifier) || empty($password)) {
        $errorMessage = "Please enter both your username/email and password.";
    } else {
        try {
            $db = getDBConnection();

            // Secure PDO Prepared Statement (SQL Injection Prevention)
            $stmt = $db->prepare("
                SELECT `id`, `full_name`, `username`, `email`, `phone`, `track`, `experience_level`, `password_hash`, `role`, `created_at`
                FROM `users`
                WHERE `username` = ? OR `email` = ?
                LIMIT 1
            ");
            $stmt->execute([$login_identifier, $login_identifier]);
            $user = $stmt->fetch();

            // Verify password using password_verify()
            if ($user && password_verify($password, $user['password_hash'])) {
                // Prevent Session Fixation attack
                session_regenerate_id(true);

                // Initialize secure session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['phone'] = $user['phone'];
                $_SESSION['track'] = $user['track'];
                $_SESSION['experience_level'] = $user['experience_level'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['member_since'] = $user['created_at'];
                $_SESSION['logged_in_time'] = time();

                // Update last login timestamp in DB
                $updateStmt = $db->prepare("UPDATE `users` SET `last_login` = NOW() WHERE `id` = ?");
                $updateStmt->execute([$user['id']]);

                // Redirect to personalized dashboard
                header("Location: dashboard.php");
                exit;

            } else {
                // Generic error message to prevent account enumeration
                $errorMessage = "Invalid username/email or password. Please check your credentials and try again.";
            }

        } catch (PDOException $e) {
            $errorMessage = "Database connection error. Please try again later.";
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="auth-page-wrapper">
  <div class="container container-auth">
    <div class="auth-card">
      
      <div class="auth-header">
        <div class="logo-badge">
          <img src="assets/images/django_girls_logo.jpg" alt="Django Girls" style="height: 30px;">
        </div>
        <h1>Welcome Back</h1>
        <p>Log in to access your personal dashboard and enrolled workshop cohort.</p>
      </div>

      <!-- Flash Success (from Registration or Logout) -->
      <?php if (!empty($flashSuccess)): ?>
        <div class="alert alert-success" role="alert">
          <div><?php echo renderIcon('check-circle', '', 16); ?> <?php echo htmlspecialchars($flashSuccess); ?></div>
        </div>
      <?php endif; ?>

      <!-- Flash Info Notice -->
      <?php if (!empty($flashInfo)): ?>
        <div class="alert alert-info" role="alert">
          <div><?php echo renderIcon('info', '', 16); ?> <?php echo htmlspecialchars($flashInfo); ?></div>
        </div>
      <?php endif; ?>

      <!-- Authentication Error -->
      <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-error" role="alert">
          <div><?php echo renderIcon('x-circle', '', 16); ?> <?php echo htmlspecialchars($errorMessage); ?></div>
        </div>
      <?php endif; ?>

      <!-- Login Form -->
      <form action="login.php" method="POST" novalidate id="loginForm">
        
        <div class="form-group">
          <label for="login_identifier" class="form-label">Username or Email Address <span class="required">*</span></label>
          <input 
            type="text" 
            id="login_identifier" 
            name="login_identifier" 
            class="form-input" 
            placeholder="Enter username or email" 
            value="<?php echo htmlspecialchars($login_identifier); ?>" 
            required 
            autocomplete="username"
            autofocus
          >
        </div>

        <div class="form-group">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
            <label for="password" class="form-label" style="margin-bottom: 0;">Password <span class="required">*</span></label>
            <a href="#" onclick="alert('For testing, use Demo account: username `alimatu_jalloh`, password `Password@123` or create a new account via Sign-Up!'); return false;" style="font-size: 0.8rem; color: #db2777;">Forgot password?</a>
          </div>
          <div class="password-input-group">
            <input 
              type="password" 
              id="password" 
              name="password" 
              class="form-input" 
              placeholder="Enter your account password" 
              required 
              autocomplete="current-password"
            >
            <button type="button" class="password-toggle-btn" data-target="password" aria-label="Toggle password visibility">Show</button>
          </div>
          <span class="form-helper">Verified securely with PHP <code>password_verify()</code>.</span>
        </div>

        <div class="form-group" style="display: flex; justify-content: space-between; align-items: center;">
          <label class="form-check">
            <input type="checkbox" name="remember" id="remember">
            <span>Remember this device</span>
          </label>
        </div>

        <button type="submit" class="btn btn-primary btn-block btn-lg" id="submitLoginBtn">
          <span>Sign In to Dashboard</span>
          <?php echo renderIcon('arrow-right', '', 16); ?>
        </button>

      </form>

      <!-- Quick Demo Credentials Box -->
      <div style="margin-top: 22px; padding: 12px 14px; background: #fff5f7; border: 1px dashed #fbcfe8; border-radius: var(--radius-md); font-size: 0.8rem; color: #475569;">
        <strong style="color: #9d174d;"><?php echo renderIcon('info', '', 13); ?> Evaluation Credentials:</strong><br>
        Username: <code style="color: #e11d48; font-weight: 700;">alimatu_jalloh</code> &bull; Password: <code style="color: #e11d48; font-weight: 700;">Password@123</code><br>
        <span style="color: #64748b;">Or register any new participant via the Sign-Up form.</span>
      </div>

      <div class="auth-footer">
        Don't have an account yet? <a href="register.php" style="font-weight: 700;">Register here for free</a>
      </div>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
