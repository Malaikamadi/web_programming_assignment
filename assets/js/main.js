/**
 * Django Girls Sierra Leone - Interactive Scripts
 * Client-side enhancements & form interactions
 */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Password Visibility Toggle
  const toggleButtons = document.querySelectorAll('.password-toggle-btn');
  toggleButtons.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const targetId = btn.getAttribute('data-target');
      const input = document.getElementById(targetId);
      if (!input) return;

      if (input.type === 'password') {
        input.type = 'text';
        btn.textContent = 'Hide';
        btn.setAttribute('aria-label', 'Hide password');
      } else {
        input.type = 'password';
        btn.textContent = 'Show';
        btn.setAttribute('aria-label', 'Show password');
      }
    });
  });

  // 2. Real-time Password Confirmation Matching (Register Page)
  const passwordInput = document.getElementById('password');
  const confirmPasswordInput = document.getElementById('confirm_password');
  const matchIndicator = document.getElementById('password-match-feedback');

  if (passwordInput && confirmPasswordInput && matchIndicator) {
    function checkPasswordsMatch() {
      const pass = passwordInput.value;
      const confirm = confirmPasswordInput.value;

      if (!confirm) {
        matchIndicator.innerHTML = '';
        matchIndicator.className = 'form-helper';
        return;
      }

      if (pass === confirm) {
        matchIndicator.innerHTML = '<span style="color: #059669; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;"><svg style="width:14px;height:14px;stroke-width:2;stroke:currentColor;fill:none;" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg> Passwords match</span>';
      } else {
        matchIndicator.innerHTML = '<span style="color: #e11d48; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;"><svg style="width:14px;height:14px;stroke-width:2;stroke:currentColor;fill:none;" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> Passwords do not match</span>';
      }
    }

    passwordInput.addEventListener('input', checkPasswordsMatch);
    confirmPasswordInput.addEventListener('input', checkPasswordsMatch);
  }

  // 3. Auto-dismiss alerts or provide close button
  const alerts = document.querySelectorAll('.alert');
  alerts.forEach(alert => {
    const closeBtn = document.createElement('button');
    closeBtn.innerHTML = '&times;';
    closeBtn.className = 'alert-close-btn';
    closeBtn.style.marginLeft = 'auto';
    closeBtn.style.background = 'none';
    closeBtn.style.border = 'none';
    closeBtn.style.fontSize = '1.2rem';
    closeBtn.style.cursor = 'pointer';
    closeBtn.style.color = 'inherit';
    closeBtn.style.opacity = '0.7';
    closeBtn.addEventListener('click', () => {
      alert.style.transition = 'opacity 0.3s ease';
      alert.style.opacity = '0';
      setTimeout(() => alert.remove(), 300);
    });
    alert.appendChild(closeBtn);
  });
});
