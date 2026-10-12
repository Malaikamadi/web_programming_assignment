<?php
/**
 * Sign-Up Page - User Registration Form
 * Incorporates secure input validation and password hashing (Week 1-4 concepts).
 */

$pageTitle = "Sign Up | Register for Django Girls SL";
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/icons.php';

// Redirect to dashboard if already logged in
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$errors = [];
$successMessage = '';

// Form values sticky state
$full_name = '';
$username = '';
$email = '';
$phone = '';
$track = 'Python & Django Web Development';
$experience_level = 'Beginner';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $track = trim($_POST['track'] ?? 'Python & Django Web Development');
    $experience_level = trim($_POST['experience_level'] ?? 'Beginner');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $terms = isset($_POST['terms']);

    // 1. Validation: Required fields
    if (empty($full_name)) {
        $errors[] = "Full Name is required.";
    }

    if (empty($username)) {
        $errors[] = "Username is required.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]{3,30}$/', $username)) {
        $errors[] = "Username must be 3-30 characters and contain only letters, numbers, and underscores.";
    }

    if (empty($email)) {
        $errors[] = "Email address is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please provide a valid email address.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long for security.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match. Please re-enter.";
    }

    if (!$terms) {
        $errors[] = "You must accept the Django Girls Community Code of Conduct.";
    }

    // 2. Database validation: Unique username & email
    if (empty($errors)) {
        try {
            $db = getDBConnection();

            // Check if username already exists
            $stmt = $db->prepare("SELECT `id` FROM `users` WHERE `username` = ? LIMIT 1");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $errors[] = "The username '{$username}' is already taken. Please choose another.";
            }

            // Check if email already exists
            $stmt = $db->prepare("SELECT `id` FROM `users` WHERE `email` = ? LIMIT 1");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $errors[] = "An account with this email address already exists. Please log in instead.";
            }

            // 3. Security: Password Hashing using password_hash()
            if (empty($errors)) {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                $insertStmt = $db->prepare("
                    INSERT INTO `users` (`full_name`, `username`, `email`, `phone`, `track`, `experience_level`, `password_hash`, `role`)
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'member')
                ");

                $insertStmt->execute([
                    $full_name,
                    $username,
                    $email,
                    $phone,
                    $track,
                    $experience_level,
                    $password_hash
                ]);

                // Set flash success message and redirect to login
                $_SESSION['flash_success'] = "Account created successfully! You can now log in using your credentials.";
                header("Location: login.php");
                exit;
            }

        } catch (PDOException $e) {
            $errors[] = "A system error occurred while processing your registration. Please try again later.";
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
          <img src="assets/images/django_girls_logo.jpg" alt="Django Girls" style="height: 32px;">
        </div>
        <h1>Join Django Girls</h1>
        <p>Create your free participant account to access workshops and study tracks in Sierra Leone.</p>
      </div>

      <!-- Feedback / Error Messages -->
      <?php if (!empty($errors)): ?>
        <div class="alert alert-error" role="alert">
          <div>
            <strong>Please resolve the following issues:</strong>
            <ul>
              <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      <?php endif; ?>

      <!-- Registration Form -->
      <form action="register.php" method="POST" novalidate id="registerForm">
        
        <div class="form-group">
          <label for="full_name" class="form-label">Full Name <span class="required">*</span></label>
          <input 
            type="text" 
            id="full_name" 
            name="full_name" 
            class="form-input" 
            placeholder="e.g. Alimatu Jalloh" 
            value="<?php echo htmlspecialchars($full_name); ?>" 
            required 
            autocomplete="name"
          >
        </div>

        <div class="form-row-2">
          <div class="form-group">
            <label for="username" class="form-label">Username <span class="required">*</span></label>
            <input 
              type="text" 
              id="username" 
              name="username" 
              class="form-input" 
              placeholder="e.g. alimatu_tech" 
              value="<?php echo htmlspecialchars($username); ?>" 
              required 
              autocomplete="username"
            >
            <span class="form-helper">3-30 letters, numbers, or _</span>
          </div>

          <div class="form-group">
            <label for="phone" class="form-label">Phone Number</label>
            <input 
              type="tel" 
              id="phone" 
              name="phone" 
              class="form-input" 
              placeholder="+232 76 000000" 
              value="<?php echo htmlspecialchars($phone); ?>" 
              autocomplete="tel"
            >
          </div>
        </div>

        <div class="form-group">
          <label for="email" class="form-label">Email Address <span class="required">*</span></label>
          <input 
            type="email" 
            id="email" 
            name="email" 
            class="form-input" 
            placeholder="you@example.com" 
            value="<?php echo htmlspecialchars($email); ?>" 
            required 
            autocomplete="email"
          >
        </div>

        <div class="form-row-2">
          <div class="form-group">
            <label for="track" class="form-label">Preferred Track</label>
            <select id="track" name="track" class="form-select">
              <option value="Python & Django Web Development" <?php echo ($track === 'Python & Django Web Development') ? 'selected' : ''; ?>>Python & Django Web</option>
              <option value="Web Fundamentals (HTML/CSS)" <?php echo ($track === 'Web Fundamentals (HTML/CSS)') ? 'selected' : ''; ?>>HTML & CSS Foundations</option>
              <option value="Database Design & PHP" <?php echo ($track === 'Database Design & PHP') ? 'selected' : ''; ?>>Database Design & PHP</option>
              <option value="Data Science with Python" <?php echo ($track === 'Data Science with Python') ? 'selected' : ''; ?>>Data Science Track</option>
            </select>
          </div>

          <div class="form-group">
            <label for="experience_level" class="form-label">Experience Level</label>
            <select id="experience_level" name="experience_level" class="form-select">
              <option value="Complete Beginner" <?php echo ($experience_level === 'Complete Beginner') ? 'selected' : ''; ?>>Complete Beginner</option>
              <option value="Beginner" <?php echo ($experience_level === 'Beginner') ? 'selected' : ''; ?>>Beginner (Some HTML)</option>
              <option value="Intermediate" <?php echo ($experience_level === 'Intermediate') ? 'selected' : ''; ?>>Intermediate</option>
            </select>
          </div>
        </div>

        <!-- Password Inputs with Security Considerations -->
        <div class="form-group">
          <label for="password" class="form-label">Password <span class="required">*</span></label>
          <div class="password-input-group">
            <input 
              type="password" 
              id="password" 
              name="password" 
              class="form-input" 
              placeholder="Minimum 8 characters" 
              required 
              autocomplete="new-password"
            >
            <button type="button" class="password-toggle-btn" data-target="password" aria-label="Toggle password visibility">Show</button>
          </div>
          <span class="form-helper">Stored securely using bcrypt password hashing.</span>
        </div>

        <div class="form-group">
          <label for="confirm_password" class="form-label">Confirm Password <span class="required">*</span></label>
          <div class="password-input-group">
            <input 
              type="password" 
              id="confirm_password" 
              name="confirm_password" 
              class="form-input" 
              placeholder="Re-enter your password" 
              required 
              autocomplete="new-password"
            >
            <button type="button" class="password-toggle-btn" data-target="confirm_password" aria-label="Toggle password confirmation visibility">Show</button>
          </div>
          <span id="password-match-feedback" class="form-helper"></span>
        </div>

        <div class="form-group">
          <label class="form-check">
            <input type="checkbox" name="terms" id="terms" required>
            <span>I agree to the friendly & inclusive Django Girls Code of Conduct.</span>
          </label>
        </div>

        <button type="submit" class="btn btn-primary btn-block btn-lg" id="submitRegisterBtn">
          <span>Create Free Account</span>
          <?php echo renderIcon('arrow-right', '', 16); ?>
        </button>

      </form>

      <div class="auth-footer">
        Already have an account? <a href="login.php" style="font-weight: 700;">Log in here</a>
      </div>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
