'use strict';

// ════════════════════════════════════════════════════════════
//  UI HELPERS
// ════════════════════════════════════════════════════════════

function showAlert(containerId, message, type = 'error') {
  const el = document.getElementById(containerId);
  if (!el) return;
  const icons = { error: '❌', success: '✅', info: 'ℹ️', warning: '⚠️' };
  el.innerHTML = `
    <div class="alert alert-${type}">
      <span class="alert-icon">${icons[type] || 'ℹ️'}</span>
      <span>${message}</span>
    </div>`;
  el.classList.remove('hidden');
}

function hideAlert(containerId) {
  const el = document.getElementById(containerId);
  if (el) { el.innerHTML = ''; el.classList.add('hidden'); }
}

function setLoading(btnId, loading) {
  const btn = document.getElementById(btnId);
  if (!btn) return;
  btn.disabled = loading;
  btn.classList.toggle('loading', loading);
}

function initPasswordToggles() {
  document.querySelectorAll('.btn-eye').forEach(btn => {
    btn.addEventListener('click', () => {
      const input = btn.closest('.input-wrapper').querySelector('input');
      const isHidden = input.type === 'password';
      input.type = isHidden ? 'text' : 'password';
      btn.textContent = isHidden ? '🙈' : '👁️';
    });
  });
}

function initPasswordStrength() {
  const input     = document.getElementById('password');
  const container = document.getElementById('passwordStrength');
  if (!input || !container) return;

  container.style.display = 'block';

  input.addEventListener('input', () => {
    const val = input.value;
    let score = 0;
    if (val.length >= 8)             score++;
    if (/[A-Z]/.test(val))           score++;
    if (/[0-9]/.test(val))           score++;
    if (/[^A-Za-z0-9]/.test(val))    score++;

    const segments = container.querySelectorAll('.strength-segment');
    const label    = container.querySelector('.strength-label');
    const labels   = ['', 'Lemah', 'Cukup', 'Kuat', 'Sangat Kuat'];
    const classes  = ['', 'weak', 'medium', 'strong', 'strong'];

    segments.forEach((seg, i) => {
      seg.className = 'strength-segment';
      if (i < score) seg.classList.add(classes[score]);
    });
    label.textContent = val.length > 0 ? labels[score] || 'Sangat Kuat' : '';
  });
}

function getParam(name) {
  return new URLSearchParams(window.location.search).get(name);
}

// ════════════════════════════════════════════════════════════
//  API CALLS
// ════════════════════════════════════════════════════════════

async function apiPost(url, body) {
  const res  = await fetch(url, {
    method:      'POST',
    headers:     { 'Content-Type': 'application/json' },
    credentials: 'include',
    body:        JSON.stringify(body),
  });
  const data = await res.json();
  return { ok: res.ok, status: res.status, data };
}

// ════════════════════════════════════════════════════════════
//  REGISTER
// ════════════════════════════════════════════════════════════
function initRegister() {
  const form = document.getElementById('registerForm');
  if (!form) return;

  initPasswordToggles();
  initPasswordStrength();

  const info = getParam('info');
  if (info) showAlert('alertBox', decodeURIComponent(info), 'info');

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    hideAlert('alertBox');

    const name            = document.getElementById('name').value.trim();
    const email           = document.getElementById('email').value.trim();
    const password        = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    if (!name || !email || !password || !confirmPassword) {
      return showAlert('alertBox', 'Semua field wajib diisi.', 'error');
    }
    if (password.length < 8) {
      return showAlert('alertBox', 'Password minimal 8 karakter.', 'error');
    }
    if (!/(?=.*[A-Za-z])(?=.*\d)/.test(password)) {
      return showAlert('alertBox', 'Password harus mengandung huruf dan angka.', 'error');
    }
    if (password !== confirmPassword) {
      return showAlert('alertBox', 'Konfirmasi password tidak cocok.', 'error');
    }

    setLoading('submitBtn', true);
    const { ok, data } = await apiPost('/api/auth/register', { name, email, password, confirmPassword });
    setLoading('submitBtn', false);

    if (ok) {
      showAlert('alertBox', data.message, 'success');
      form.reset();
      document.getElementById('passwordStrength').querySelector('.strength-label').textContent = '';
      document.querySelectorAll('.strength-segment').forEach(s => s.className = 'strength-segment');
    } else {
      showAlert('alertBox', data.message || 'Terjadi kesalahan.', 'error');
    }
  });
}

// ════════════════════════════════════════════════════════════
//  LOGIN
// ════════════════════════════════════════════════════════════
function initLogin() {
  const form = document.getElementById('loginForm');
  if (!form) return;

  initPasswordToggles();

  const success = getParam('success');
  const error   = getParam('error');
  const info    = getParam('info');

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    hideAlert('alertBox');

    const email    = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;

    if (!email || !password) {
      return showAlert('alertBox', 'Email dan password wajib diisi.', 'error');
    }

    setLoading('submitBtn', true);
    const { ok, data } = await apiPost('/api/auth/login', { email, password });
    setLoading('submitBtn', false);

    if (ok) {
      showAlert('alertBox', data.message, 'success');
      if (data.user) sessionStorage.setItem('sb_user', JSON.stringify(data.user));
      setTimeout(() => { window.location.href = '/dashboard'; }, 800);
    } else {
      showAlert('alertBox', data.message || 'Terjadi kesalahan.', 'error');
    }
  });
}

// ════════════════════════════════════════════════════════════
//  FORGOT PASSWORD
// ════════════════════════════════════════════════════════════
function initForgotPassword() {
  const form = document.getElementById('forgotForm');
  if (!form) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    hideAlert('alertBox');

    const email = document.getElementById('email').value.trim();
    if (!email) return showAlert('alertBox', 'Email wajib diisi.', 'error');

    setLoading('submitBtn', true);
    const { ok, data } = await apiPost('/api/auth/forgot-password', { email });
    setLoading('submitBtn', false);

    showAlert('alertBox', data.message, ok ? 'success' : 'error');
    if (ok) form.reset();
  });
}

// ════════════════════════════════════════════════════════════
//  RESET PASSWORD
// ════════════════════════════════════════════════════════════
function initResetPassword() {
  const form = document.getElementById('resetForm');
  if (!form) return;

  initPasswordToggles();
  initPasswordStrength();

  const token = getParam('token');
  if (!token) {
    showAlert('alertBox', 'Link reset password tidak valid. Pastikan kamu membuka link dari email.', 'error');
    document.getElementById('submitBtn').disabled = true;
    return;
  }

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    hideAlert('alertBox');

    const password        = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    if (!password || !confirmPassword) {
      return showAlert('alertBox', 'Semua field wajib diisi.', 'error');
    }
    if (password.length < 8) {
      return showAlert('alertBox', 'Password minimal 8 karakter.', 'error');
    }
    if (!/(?=.*[A-Za-z])(?=.*\d)/.test(password)) {
      return showAlert('alertBox', 'Password harus mengandung huruf dan angka.', 'error');
    }
    if (password !== confirmPassword) {
      return showAlert('alertBox', 'Konfirmasi password tidak cocok.', 'error');
    }

    setLoading('submitBtn', true);
    const { ok, data } = await apiPost('/api/auth/reset-password', { token, password, confirmPassword });
    setLoading('submitBtn', false);

    if (ok) {
      showAlert('alertBox', data.message, 'success');
      form.reset();
      setTimeout(() => { window.location.href = '/login'; }, 2000);
    } else {
      showAlert('alertBox', data.message || 'Terjadi kesalahan.', 'error');
      if (data.code === 'TOKEN_EXPIRED') {
        document.getElementById('submitBtn').disabled = true;
        const forgotLink = document.createElement('p');
        forgotLink.style.cssText = 'text-align:center;margin-top:12px;font-size:14px;';
        forgotLink.innerHTML = '<a href="/forgot-password.html" style="color:var(--pink-600);font-weight:600;">Minta Link Reset Baru →</a>';
        form.after(forgotLink);
      }
    }
  });
}

// ════════════════════════════════════════════════════════════
//  DASHBOARD
// ════════════════════════════════════════════════════════════
function initDashboard() {
  const container = document.getElementById('dashboardContent');
  if (!container) return;

  (async () => {
    try {
      const res  = await fetch('/api/auth/me', { credentials: 'include' });
      const data = await res.json();

      if (!res.ok) {
        window.location.href = '/login';
        return;
      }

      const u = data.user;
      document.getElementById('welcomeName').textContent = `Halo, ${u.name}! 🌸`;
      document.getElementById('userEmail').textContent   = u.email;
      document.getElementById('userRole').textContent    = u.role === 'admin' ? '👑 Admin' : '🛍️ Customer';
      container.classList.remove('hidden');

    } catch {
      window.location.href = '/login.html';
    }
  })();

  document.getElementById('logoutBtn')?.addEventListener('click', async () => {
    await apiPost('/api/auth/logout', {});
    sessionStorage.removeItem('sb_user');
    window.location.href = '/login.html';
  });
}

