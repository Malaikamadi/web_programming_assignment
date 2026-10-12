<?php
/**
 * Site Footer Component
 * Strictly complies with the Assignment 1 Copyright Specifications.
 */
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/icons.php';
?>

  <!-- Site Footer -->
  <footer class="site-footer">
    <div class="container footer-top">
      <div class="footer-grid">
        
        <!-- Organization Brand Summary -->
        <div class="footer-brand">
          <div class="footer-logo-box">
            <img src="assets/images/django_girls_logo.jpg" alt="Django Girls" class="footer-logo-img">
          </div>
          <p class="footer-desc">
            Django Girls Sierra Leone is a community-driven non-profit initiative dedicated to introducing girls and young women in Sierra Leone to programming through accessible, friendly, and practical workshops.
          </p>
          <div style="font-size: 0.85rem; color: #94a3b8; display: flex; align-items: center; gap: 6px;">
            <span style="color: var(--color-coral-pink);"><?php echo renderIcon('pin', '', 14); ?></span>
            <span>Freetown, Sierra Leone &bull; Powered by Python & Django</span>
          </div>
        </div>

        <!-- Quick Links -->
        <div>
          <h4 class="footer-heading">Quick Links</h4>
          <ul class="footer-links">
            <li><a href="index.php">Home Page</a></li>
            <li><a href="index.php#about">About Django Girls</a></li>
            <li><a href="index.php#workshops">Upcoming Workshops</a></li>
            <li><a href="register.php">Apply as Participant</a></li>
            <li><a href="login.php">Member Portal Login</a></li>
          </ul>
        </div>

        <!-- Tech Tracks -->
        <div>
          <h4 class="footer-heading">Learning Tracks</h4>
          <ul class="footer-links">
            <li><a href="register.php">HTML5 & CSS3 Web Basics</a></li>
            <li><a href="register.php">Python Programming Fundamentals</a></li>
            <li><a href="register.php">Django Web Framework</a></li>
            <li><a href="register.php">Git & GitHub Version Control</a></li>
            <li><a href="register.php">Cloud Deployment & Hosting</a></li>
          </ul>
        </div>

      </div>
    </div>

    <!-- Strictly Required Assignment Copyright Bar -->
    <div class="footer-bottom">
      <div class="container footer-bottom-inner">
        <p class="assignment-copyright-text">
          <?php echo htmlspecialchars(COPYRIGHT_STRING); ?>
        </p>
        <p class="assignment-subtext">
          Web Programming Assignment 1 &bull; Limkokwing University of Creative Technology Sierra Leone
        </p>
      </div>
    </div>
  </footer>

  <!-- Main JavaScript File -->
  <script src="assets/js/main.js"></script>
</body>
</html>
