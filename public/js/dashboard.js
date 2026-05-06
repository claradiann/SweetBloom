'use strict';

const BASE = '';

// ── API helper ─────────────────────────────────────────────
async function api(method, url, body) {
  const opts = {
    method,
    credentials: 'include',
    headers: { 'Content-Type': 'application/json' },
  };
  if (body) opts.body = JSON.stringify(body);
  const res  = await fetch(BASE + url, opts);
  const data = await res.json().catch(() => ({}));
  return { ok: res.ok, status: res.status, data };
}

// ── Alert ──────────────────────────────────────────────────
function showProfileAlert(msg, type = 'error') {
  const el = document.getElementById('profileAlert');
  const colors = {
    error:   { bg: '#fce4ec', color: '#c62828', border: '#f8bbd0' },
    success: { bg: '#e8f5e9', color: '#2e7d32', border: '#a5d6a7' },
  };
  const c = colors[type] || colors.error;
  el.innerHTML = `<div style="padding:14px 18px;border-radius:12px;background:${c.bg};color:${c.color};border:1px solid ${c.border};font-size:0.9rem;font-weight:500">${msg}</div>`;
  el.classList.remove('hidden');
  setTimeout(() => { el.classList.add('hidden'); el.innerHTML = ''; }, 4000);
}

// ── Sections (Home / Profile) ──────────────────────────────
function showSection(name) {
  document.getElementById('section-home').style.display    = name === 'home'    ? '' : 'none';
  document.getElementById('section-profile').style.display = name === 'profile' ? '' : 'none';

  document.querySelectorAll('.nav-links a').forEach(a => a.classList.remove('active'));
  if (name === 'home')    document.querySelectorAll('.nav-links a')[0].classList.add('active');
  if (name === 'profile') document.querySelectorAll('.nav-links a')[2].classList.add('active');
}

// ── Profile tabs ───────────────────────────────────────────
function switchTab(name, el) {
  ['info','password','orders'].forEach(t => {
    document.getElementById('tab-' + t).style.display = t === name ? '' : 'none';
  });
  document.querySelectorAll('.profile-nav a').forEach(a => a.classList.remove('active'));
  if (el) el.classList.add('active');
}

// ── Password toggles ───────────────────────────────────────
function initPasswordToggles() {
  document.querySelectorAll('#tab-password .btn-eye').forEach(btn => {
    btn.addEventListener('click', () => {
      const input = btn.closest('.input-wrapper').querySelector('input');
      const isHidden = input.type === 'password';
      input.type = isHidden ? 'text' : 'password';
      btn.textContent = isHidden ? '🙈' : '👁️';
    });
  });
}

// ── Load user ──────────────────────────────────────────────
async function loadUser() {
  try {
    const { ok, data } = await api('GET', '/api/auth/me');
    if (!ok) { window.location.href = '/login'; return; }

    const u = data.user;
    window._user = u;

    // Navbar avatar initial
    document.getElementById('navAvatar').textContent = u.name.charAt(0).toUpperCase();

    // Sidebar
    document.getElementById('sidebarName').textContent  = u.name;
    document.getElementById('sidebarEmail').textContent = u.email;
    document.getElementById('sidebarRole').textContent  = u.role === 'admin' ? '👑 Admin' : '🛍️ Customer';

    // Avatar emoji based on role
    document.getElementById('avatarEmoji').textContent = u.role === 'admin' ? '👑' : '🌸';

    // Info tab fields
    document.getElementById('infoName').value  = u.name;
    document.getElementById('infoEmail').value = u.email;

  } catch {
    window.location.href = '/login';
  }
}

// ── Save Info ──────────────────────────────────────────────
async function handleSaveInfo() {
  const name = document.getElementById('infoName').value.trim();
  if (!name || name.length < 2) {
    return showProfileAlert('Nama minimal 2 karakter.');
  }

  const { ok, data } = await api('PUT', '/api/auth/profile', { name });
  if (ok) {
    showProfileAlert('Profil berhasil diperbarui! 🌸', 'success');
    document.getElementById('sidebarName').textContent = name;
    document.getElementById('navAvatar').textContent   = name.charAt(0).toUpperCase();
    window._user.name = name;
  } else {
    showProfileAlert(data.message || 'Gagal menyimpan perubahan.');
  }
}

// ── Change Password ────────────────────────────────────────
async function handleChangePassword() {
  const currentPassword = document.getElementById('pwCurrent').value;
  const newPassword     = document.getElementById('pwNew').value;
  const confirmPassword = document.getElementById('pwConfirm').value;

  if (!currentPassword || !newPassword || !confirmPassword) {
    return showProfileAlert('Semua field password wajib diisi.');
  }
  if (newPassword.length < 8) {
    return showProfileAlert('Password baru minimal 8 karakter.');
  }
  if (!/(?=.*[A-Za-z])(?=.*\d)/.test(newPassword)) {
    return showProfileAlert('Password harus mengandung huruf dan angka.');
  }
  if (newPassword !== confirmPassword) {
    return showProfileAlert('Konfirmasi password tidak cocok.');
  }

  const btn = document.getElementById('pwSubmitBtn');
  btn.disabled = true;
  btn.textContent = 'Menyimpan...';

  const { ok, data } = await api('POST', '/api/auth/change-password', {
    currentPassword, newPassword, confirmPassword
  });

  btn.disabled = false;
  btn.textContent = '🔒 Ubah Password';

  if (ok) {
    showProfileAlert('Password berhasil diubah! 🔐', 'success');
    document.getElementById('pwCurrent').value = '';
    document.getElementById('pwNew').value     = '';
    document.getElementById('pwConfirm').value = '';
  } else {
    showProfileAlert(data.message || 'Gagal mengubah password.');
  }
}

// ── Logout ─────────────────────────────────────────────────
async function handleLogout() {
  await api('POST', '/api/auth/logout', {});
  sessionStorage.removeItem('sb_user');
  window.location.href = '/login';
}

// ── Delete Account ─────────────────────────────────────────
function confirmDeleteAccount() {
  document.getElementById('deleteModal').style.display = 'flex';
}

function closeDeleteModal() {
  document.getElementById('deleteModal').style.display = 'none';
}

async function handleDeleteAccount() {
  const btn = document.getElementById('deleteConfirmBtn');
  btn.disabled    = true;
  btn.textContent = 'Menghapus...';

  const { ok, data } = await api('DELETE', '/api/auth/account', {});

  if (ok) {
    sessionStorage.removeItem('sb_user');
    window.location.href = '/login?info=' + encodeURIComponent('Akun berhasil dihapus. Sampai jumpa! 👋');
  } else {
    closeDeleteModal();
    showProfileAlert(data.message || 'Gagal menghapus akun.');
    btn.disabled    = false;
    btn.textContent = 'Ya, Hapus Akun';
  }
}

// ── Wishlist toggle ────────────────────────────────────────
function initWishlist() {
  document.querySelectorAll('.product-wishlist').forEach(btn => {
    btn.addEventListener('click', e => {
      e.stopPropagation();
      const liked = btn.classList.toggle('liked');
      btn.textContent = liked ? '❤️' : '🤍';
    });
  });
}

// ── Init ───────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  loadUser();
  initPasswordToggles();
  initWishlist();

  // Nav links
  document.querySelectorAll('.nav-links a')[0].addEventListener('click', e => { e.preventDefault(); showSection('home'); });
  document.querySelectorAll('.nav-links a')[2].addEventListener('click', e => { e.preventDefault(); showSection('profile'); });
});