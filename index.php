<?php
/**
 * Home Page - Django Girls Sierra Leone Web Platform
 * Course: Web Programming (Assignment 1)
 */

$pageTitle = "Home | Empowering Women Through Code";
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/icons.php';
require_once __DIR__ . '/includes/header.php';

// Fetch workshops from database
$workshops = [];
try {
    $db = getDBConnection();
    $stmt = $db->query("SELECT * FROM `workshops` ORDER BY `id` ASC");
    $workshops = $stmt->fetchAll();
} catch (Exception $e) {
    $workshops = [];
}
?>

<!-- ==========================================================================
     HERO SECTION
     ========================================================================== -->
<section class="hero-section" id="home">
  <div class="container hero-grid">
    
    <!-- Hero Text Content -->
    <div class="hero-content">
      <div class="hero-badge">
        <?php echo renderIcon('sparkle', '', 14); ?>
        <span>Sierra Leone Women in Tech Initiative</span>
      </div>
      
      <h1 class="hero-title">
        Igniting the Future of Tech for Women in <span class="text-gradient">Sierra Leone</span>
      </h1>
      
      <p class="hero-description">
        Django Girls Freetown is a non-profit volunteer initiative organizing free, hands-on programming bootcamps. We empower young Sierra Leonean women with practical coding skills, dedicated mentorship, and the confidence to innovate in our digital economy.
      </p>

      <div class="hero-cta-group">
        <?php if ($isLoggedIn): ?>
          <a href="dashboard.php" class="btn btn-primary btn-lg">
            <span>Go to Your Dashboard</span>
            <?php echo renderIcon('arrow-right', '', 16); ?>
          </a>
        <?php else: ?>
          <a href="register.php" class="btn btn-primary btn-lg">
            <span>Join the Workshop (Sign Up)</span>
            <?php echo renderIcon('arrow-right', '', 16); ?>
          </a>
          <a href="login.php" class="btn btn-secondary btn-lg">Member Login</a>
        <?php endif; ?>
      </div>

      <div class="hero-trust-row">
        <div class="trust-item">
          <span class="trust-icon-box"><?php echo renderIcon('check', '', 11); ?></span>
          <span>100% Free Workshops</span>
        </div>
        <div class="trust-item">
          <span class="trust-icon-box"><?php echo renderIcon('check', '', 11); ?></span>
          <span>1:3 Coach Ratio</span>
        </div>
        <div class="trust-item">
          <span class="trust-icon-box"><?php echo renderIcon('check', '', 11); ?></span>
          <span>Beginners Welcome</span>
        </div>
      </div>
    </div>

    <!-- Hero Interactive Code Visual Showcase -->
    <div class="hero-card-showcase">
      <div class="hero-visual-card">
        <div class="visual-top-bar">
          <div class="code-dots">
            <span></span><span></span><span></span>
          </div>
          <span class="tech-tag">python3 manage.py runserver</span>
        </div>

        <div class="hero-code-block">
          <span class="code-comment"># Welcome to Django Girls Sierra Leone</span><br>
          <span class="code-keyword">from</span> djangogirls.freetown <span class="code-keyword">import</span> EmpowerWomen<br><br>
          <span class="code-keyword">class</span> <span class="code-func">SierraLeoneanDeveloper</span>:<br>
          &nbsp;&nbsp;<span class="code-keyword">def</span> <span class="code-func">__init__</span>(self, name):<br>
          &nbsp;&nbsp;&nbsp;&nbsp;self.name = name<br>
          &nbsp;&nbsp;&nbsp;&nbsp;self.skills = [<span class="code-string">"HTML5"</span>, <span class="code-string">"CSS3"</span>, <span class="code-string">"Python"</span>, <span class="code-string">"Django"</span>]<br>
          &nbsp;&nbsp;&nbsp;&nbsp;self.ready_to_build = <span class="code-keyword">True</span><br><br>
          <span class="code-comment"># Build your first web application in 1 day!</span><br>
          future_coder = <span class="code-func">SierraLeoneanDeveloper</span>(<span class="code-string">"You"</span>)<br>
          <span class="code-func">print</span>(<span class="code-string">f"Building the future: {future_coder.skills}"</span>)
        </div>

        <div class="visual-footer-stats">
          <div class="visual-stat-box">
            <div class="visual-stat-number">500+</div>
            <div class="visual-stat-label">Girls Trained in SL</div>
          </div>
          <div class="visual-stat-box">
            <div class="visual-stat-number">100%</div>
            <div class="visual-stat-label">Free & Inclusive</div>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- ==========================================================================
     STATS BAR SECTION
     ========================================================================== -->
<section class="stats-section">
  <div class="container">
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-value">500+</div>
        <div class="stat-label">Women & Girls Trained</div>
      </div>
      <div class="stat-card">
        <div class="stat-value">100%</div>
        <div class="stat-label">Free Workshop Access</div>
      </div>
      <div class="stat-card">
        <div class="stat-value">1 : 3</div>
        <div class="stat-label">Coach to Learner Ratio</div>
      </div>
      <div class="stat-card">
        <div class="stat-value">25+</div>
        <div class="stat-label">Tech Mentors in Freetown</div>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================================================
     ABOUT DJANGO GIRLS SIERRA LEONE
     ========================================================================== -->
<section class="section section-bg-white" id="about">
  <div class="container">
    <div class="section-header">
      <span class="section-eyebrow">About The Organisation</span>
      <h2 class="section-title">Bridging Sierra Leone’s Digital Gender Gap</h2>
      <p class="section-subtitle">
        We believe women should not just be passive consumers of technology in Sierra Leone, but active creators, problem solvers, and software architects.
      </p>
    </div>

    <div class="card-grid-3">
      
      <div class="feature-card">
        <div class="feature-icon-wrapper">
          <?php echo renderIcon('code', '', 22); ?>
        </div>
        <h3 class="feature-title">Hands-On Web Development</h3>
        <p class="feature-desc">
          Participants build a real, functioning blog website from scratch using HTML, CSS, Python, and Django in a single day, taking code from idea to deployment.
        </p>
        <div class="feature-badge-list">
          <span class="pill-badge">Zero Experience Needed</span>
          <span class="pill-badge">Practical Labs</span>
        </div>
      </div>

      <div class="feature-card">
        <div class="feature-icon-wrapper">
          <?php echo renderIcon('users', '', 22); ?>
        </div>
        <h3 class="feature-title">Dedicated 1-on-1 Mentorship</h3>
        <p class="feature-desc">
          Every group of three attendees is paired with an experienced Sierra Leonean software engineer who guides them gently through every bug and syntax error.
        </p>
        <div class="feature-badge-list">
          <span class="pill-badge">Friendly Guidance</span>
          <span class="pill-badge">Safe Environment</span>
        </div>
      </div>

      <div class="feature-card">
        <div class="feature-icon-wrapper">
          <?php echo renderIcon('globe', '', 22); ?>
        </div>
        <h3 class="feature-title">Thriving Tech Community</h3>
        <p class="feature-desc">
          Graduates join an active network of alumni, continuing their journey through study groups, internships, open source contributions, and industry opportunities.
        </p>
        <div class="feature-badge-list">
          <span class="pill-badge">Lifelong Network</span>
          <span class="pill-badge">Career Pathways</span>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ==========================================================================
     UPCOMING WORKSHOPS (DYNAMIC FROM DATABASE)
     ========================================================================== -->
<section class="section section-bg-light" id="workshops">
  <div class="container">
    <div class="section-header">
      <span class="section-eyebrow">Interactive Cohorts</span>
      <h2 class="section-title">Upcoming Workshops in Sierra Leone</h2>
      <p class="section-subtitle">
        Explore our scheduled training sessions and register to secure your seat.
      </p>
    </div>

    <div class="card-grid-3">
      <?php if (!empty($workshops)): ?>
        <?php foreach ($workshops as $workshop): ?>
          <div class="workshop-card">
            <span class="workshop-status-pill">
              <span style="width: 7px; height: 7px; border-radius: 50%; background: #059669;"></span>
              <?php echo htmlspecialchars($workshop['status']); ?>
            </span>
            <h3 class="workshop-title"><?php echo htmlspecialchars($workshop['title']); ?></h3>
            <p style="font-size: 0.9rem; color: #64748b; margin-bottom: 16px; flex-grow: 1;">
              <?php echo htmlspecialchars($workshop['description']); ?>
            </p>
            <div class="workshop-meta">
              <div class="meta-row">
                <span style="color: var(--color-primary);"><?php echo renderIcon('calendar', '', 15); ?></span>
                <strong><?php echo htmlspecialchars($workshop['scheduled_date']); ?></strong>
              </div>
              <div class="meta-row">
                <span style="color: var(--color-primary);"><?php echo renderIcon('pin', '', 15); ?></span>
                <span><?php echo htmlspecialchars($workshop['location']); ?></span>
              </div>
              <div class="meta-row">
                <span style="color: var(--color-primary);"><?php echo renderIcon('user-check', '', 15); ?></span>
                <span>Lead: <?php echo htmlspecialchars($workshop['instructor']); ?></span>
              </div>
            </div>
            <div>
              <a href="register.php" class="btn btn-outline-primary btn-block btn-sm">
                <span>Apply For This Cohort</span>
                <?php echo renderIcon('arrow-right', '', 13); ?>
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="text-align: center; grid-column: 1 / -1;">No workshops scheduled at this moment. Please check back soon!</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- ==========================================================================
     CURRICULUM BREAKDOWN
     ========================================================================== -->
<section class="section section-bg-white" id="curriculum">
  <div class="container">
    <div class="section-header">
      <span class="section-eyebrow">Structured Pathway</span>
      <h2 class="section-title">What You Will Learn (Weeks 1 to 4)</h2>
      <p class="section-subtitle">
        Aligned with Web Programming fundamentals, moving from core markup to full database-driven server-side applications.
      </p>
    </div>

    <div class="card-grid-3">
      <div class="feature-card">
        <div style="font-size: 0.76rem; font-weight: 800; color: var(--color-primary); text-transform: uppercase; margin-bottom: 6px;">Module 01</div>
        <h3 class="feature-title">Web Anatomy & HTML5/CSS3</h3>
        <p class="feature-desc">
          How the Internet works, client-server architecture, semantic HTML5 structure, responsive layout with CSS Grid and Flexbox, and user accessibility.
        </p>
      </div>

      <div class="feature-card">
        <div style="font-size: 0.76rem; font-weight: 800; color: var(--color-primary); text-transform: uppercase; margin-bottom: 6px;">Module 02</div>
        <h3 class="feature-title">PHP & Python Fundamentals</h3>
        <p class="feature-desc">
          Variables, control flow, functions, loops, arrays, and form processing via HTTP GET and POST methods with strict input sanitization.
        </p>
      </div>

      <div class="feature-card">
        <div style="font-size: 0.76rem; font-weight: 800; color: var(--color-primary); text-transform: uppercase; margin-bottom: 6px;">Module 03</div>
        <h3 class="feature-title">Authentication & Security</h3>
        <p class="feature-desc">
          Secure user registration, password hashing using modern bcrypt algorithms (`password_hash`), session management, and CSRF/SQLi prevention.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================================================
     CALL TO ACTION BANNER
     ========================================================================== -->
<section class="section section-bg-light" style="padding: 40px 0;">
  <div class="container" style="background: linear-gradient(135deg, #241a35 0%, #16111f 100%); border-radius: var(--radius-xl); padding: 42px; color: #fff; text-align: center; border: 1px solid rgba(251, 113, 133, 0.2);">
    <h2 style="font-size: 2rem; font-weight: 800; margin-bottom: 10px;">Ready to Start Your Journey in Tech?</h2>
    <p style="font-size: 1rem; color: #cbd5e1; max-width: 580px; margin: 0 auto 24px;">
      Join hundreds of women across Freetown discovering their passion for software programming. All workshops are completely free.
    </p>
    <div style="display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;">
      <a href="register.php" class="btn btn-primary btn-lg">
        <span>Create Free Account</span>
        <?php echo renderIcon('arrow-right', '', 16); ?>
      </a>
      <a href="login.php" class="btn btn-secondary btn-lg">Sign In to Dashboard</a>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
