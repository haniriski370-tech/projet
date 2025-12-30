document.addEventListener('DOMContentLoaded', () => {
  const signupForm = document.getElementById('signupForm');
  const loginForm = document.getElementById('loginForm');
  const messageEl = document.querySelector('.auth-message') || null;

  function showMessage(el, txt, ok = true) {
    if (!el) return;
    el.style.display = 'block';
    el.className = 'auth-message ' + (ok ? 'success' : 'error');
    el.innerText = txt;
  }

  function getUsers() {
    try { return JSON.parse(localStorage.getItem('users') || '[]'); }
    catch { return []; }
  }

  function saveUsers(users) { localStorage.setItem('users', JSON.stringify(users)); }

  if (signupForm) {
    signupForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const name = document.getElementById('name').value.trim();
      const email = document.getElementById('email').value.trim().toLowerCase();
      const password = document.getElementById('password').value;
      const confirm = document.getElementById('confirmPassword').value;

      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showMessage(messageEl, 'Please enter a valid email address.', false);
        return;
      }
      if (password.length < 6) {
        showMessage(messageEl, 'Password must be at least 6 characters.', false);
        return;
      }
      if (password !== confirm) {
        showMessage(messageEl, 'Passwords do not match.', false);
        return;
      }

      const users = getUsers();
      if (users.find(u => u.email === email)) {
        showMessage(messageEl, 'An account with that email already exists.', false);
        return;
      }

      users.push({ name, email, password });
      saveUsers(users);
      localStorage.setItem('currentUser', JSON.stringify({ name, email }));
      showMessage(messageEl, 'Account created — redirecting...', true);
      // redirect to intended page after signup/login if present
      const dest = localStorage.getItem('postLoginRedirect') || 'project.html';
      localStorage.removeItem('postLoginRedirect');
      setTimeout(() => window.location.href = dest, 1200);
    });
  }

  if (loginForm) {
    loginForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const email = document.getElementById('email').value.trim().toLowerCase();
      const password = document.getElementById('password').value;
      const users = getUsers();
      const user = users.find(u => u.email === email && u.password === password);
      if (!user) {
        showMessage(messageEl, 'Invalid email or password.', false);
        return;
      }
      localStorage.setItem('currentUser', JSON.stringify({ name: user.name, email: user.email }));
      showMessage(messageEl, 'Login successful — redirecting...', true);
      const dest = localStorage.getItem('postLoginRedirect') || 'project.html';
      localStorage.removeItem('postLoginRedirect');
      setTimeout(() => window.location.href = dest, 900);
    });
  }
});
