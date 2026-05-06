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

// ── Cart (Dashboard) ───────────────────────────────────────
const cart = JSON.parse(sessionStorage.getItem('sb_cart') || '[]');

function saveCart() { sessionStorage.setItem('sb_cart', JSON.stringify(cart)); }

function updateCartBadge() {
  const total = cart.reduce((s, i) => s + i.qty, 0);
  document.querySelectorAll('.cart-badge').forEach(el => el.textContent = total);
}

function addToCart(id, name, price, emoji) {
  const ex = cart.find(i => i.id === id);
  if (ex) ex.qty++;
  else cart.push({ id, name, price, emoji, qty: 1 });
  saveCart();
  updateCartBadge();
  showCartToast(`${emoji} ${name} ditambahkan!`);
}

function showCartToast(msg) {
  let t = document.getElementById('cartToast');
  if (!t) {
    t = document.createElement('div');
    t.id = 'cartToast';
    t.style.cssText = 'position:fixed;bottom:24px;right:24px;z-index:9999;background:#fff0f5;color:#c2185b;border:1px solid #f8bbd0;padding:12px 20px;border-radius:12px;font-weight:600;box-shadow:0 4px 20px rgba(194,24,91,.15);transition:opacity .3s;font-size:.9rem;';
    document.body.appendChild(t);
  }
  t.textContent = `🛒 ${msg}`;
  t.style.opacity = '1';
  clearTimeout(t._t);
  t._t = setTimeout(() => t.style.opacity = '0', 2500);
}

function formatPrice(p) { return 'Rp ' + p.toLocaleString('id-ID'); }

function openCart() {
  const modal = document.getElementById('cartModal');
  const itemsEl = document.getElementById('cartItems');
  const totalEl = document.getElementById('cartTotal');
  const checkoutBtn = document.getElementById('checkoutBtn');

  if (cart.length === 0) {
    itemsEl.innerHTML = '<div style="text-align:center;padding:40px 0;color:#bbb;">Keranjang masih kosong 🛒</div>';
    totalEl.innerHTML = '';
    checkoutBtn.style.display = 'none';
  } else {
    itemsEl.innerHTML = cart.map(i => `
      <div style="display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid #fce4ec;">
        <span style="font-size:1.8rem;">${i.emoji}</span>
        <div style="flex:1;">
          <div style="font-weight:700;font-size:.95rem;">${i.name}</div>
          <div style="color:#e91e8c;font-weight:600;font-size:.9rem;">${formatPrice(i.price)}</div>
        </div>
        <div style="display:flex;align-items:center;gap:8px;">
          <button onclick="changeQty(${i.id},-1)" style="width:28px;height:28px;border-radius:50%;border:1.5px solid #f8bbd0;background:#fff;cursor:pointer;font-size:1rem;">−</button>
          <span style="font-weight:700;min-width:20px;text-align:center;">${i.qty}</span>
          <button onclick="changeQty(${i.id},1)" style="width:28px;height:28px;border-radius:50%;border:none;background:#e91e8c;color:#fff;cursor:pointer;font-size:1rem;">+</button>
        </div>
      </div>
    `).join('');
    const total = cart.reduce((s, i) => s + i.price * i.qty, 0);
    totalEl.innerHTML = `<span>Total: ${formatPrice(total)}</span>`;
    checkoutBtn.style.display = 'block';
  }
  modal.style.display = 'flex';
}

function changeQty(id, delta) {
  const idx = cart.findIndex(i => i.id === id);
  if (idx === -1) return;
  cart[idx].qty += delta;
  if (cart[idx].qty <= 0) cart.splice(idx, 1);
  saveCart();
  updateCartBadge();
  openCart();
}

function closeCart() { document.getElementById('cartModal').style.display = 'none'; }

function initCart() {
  document.querySelector('.nav-cart').addEventListener('click', openCart);
  document.getElementById('cartModal').addEventListener('click', e => {
    if (e.target === document.getElementById('cartModal')) closeCart();
  });

  document.querySelectorAll('.product-card').forEach(card => {
    const btn     = card.querySelector('.product-add-btn');
    const name    = card.querySelector('.product-name').textContent.trim();
    const price   = parseInt(card.querySelector('.product-price').textContent.replace(/\D/g, ''));
    const emoji   = card.querySelector('.product-card-image').childNodes[0].textContent.trim();
    const id      = name.toLowerCase().replace(/\s+/g, '-');
    btn.addEventListener('click', e => { e.stopPropagation(); addToCart(id, name, price, emoji); });
  });
}

// ── Profile Modal (Dashboard) ──────────────────────────────
function openProfileModal() {
  const u = window._user;
  if (!u) return;
  const modal = document.getElementById('profileModal');
  document.getElementById('profileModalContent').innerHTML = `
    <div style="text-align:center;margin-bottom:24px;">
      <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#f8bbd0,#e91e8c);display:flex;align-items:center;justify-content:center;font-size:2rem;margin:0 auto 12px;">
        ${u.role === 'admin' ? '👑' : '🌸'}
      </div>
      <div style="font-weight:800;font-size:1.1rem;">${u.name}</div>
      <div style="color:#aaa;font-size:.875rem;">${u.email}</div>
    </div>
    <div id="profileView">
      <div style="margin-bottom:16px;"><label style="font-size:.8rem;font-weight:700;color:#aaa;text-transform:uppercase;">Nama</label><div style="padding:10px 14px;background:#fafafa;border-radius:10px;margin-top:4px;font-weight:600;">${u.name}</div></div>
      <div style="margin-bottom:16px;"><label style="font-size:.8rem;font-weight:700;color:#aaa;text-transform:uppercase;">Email</label><div style="padding:10px 14px;background:#fafafa;border-radius:10px;margin-top:4px;font-weight:600;">${u.email}</div></div>
      <div style="margin-bottom:16px;"><label style="font-size:.8rem;font-weight:700;color:#aaa;text-transform:uppercase;">No. Telepon</label><div style="padding:10px 14px;background:#fafafa;border-radius:10px;margin-top:4px;font-weight:600;">${u.phone || '-'}</div></div>
      <div style="margin-bottom:24px;"><label style="font-size:.8rem;font-weight:700;color:#aaa;text-transform:uppercase;">Alamat</label><div style="padding:10px 14px;background:#fafafa;border-radius:10px;margin-top:4px;font-weight:600;">${u.address || '-'}</div></div>
      <div id="profileMsg" style="margin-bottom:12px;"></div>
      <button onclick="showEditProfile()" style="width:100%;padding:12px;background:linear-gradient(135deg,#e91e8c,#f06292);color:#fff;border:none;border-radius:50px;font-weight:700;cursor:pointer;margin-bottom:10px;">✏️ Edit Profil</button>
      <button onclick="showChangePassword()" style="width:100%;padding:12px;background:#fff;color:#e91e8c;border:1.5px solid #f8bbd0;border-radius:50px;font-weight:700;cursor:pointer;margin-bottom:10px;">🔒 Ubah Password</button>
      <button onclick="handleLogout()" style="width:100%;padding:12px;background:#fff;color:#888;border:1.5px solid #eee;border-radius:50px;font-weight:700;cursor:pointer;margin-bottom:10px;">🚪 Logout</button>
      <button onclick="confirmDeleteAccount()" style="width:100%;padding:12px;background:#fff;color:#e53935;border:1.5px solid #ffcdd2;border-radius:50px;font-weight:700;cursor:pointer;">🗑️ Hapus Akun</button>
    </div>
  `;
  modal.style.display = 'flex';
}

function showEditProfile() {
  const u = window._user;
  document.getElementById('profileView').innerHTML = `
    <div style="margin-bottom:14px;"><label style="font-size:.8rem;font-weight:700;color:#aaa;text-transform:uppercase;">Nama</label><input id="editName" value="${u.name}" style="width:100%;padding:10px 14px;border:1.5px solid #f8bbd0;border-radius:10px;margin-top:4px;font-size:.95rem;box-sizing:border-box;outline:none;"></div>
    <div style="margin-bottom:14px;"><label style="font-size:.8rem;font-weight:700;color:#aaa;text-transform:uppercase;">No. Telepon</label><input id="editPhone" value="${u.phone || ''}" placeholder="08xxxxxxxxxx" style="width:100%;padding:10px 14px;border:1.5px solid #f8bbd0;border-radius:10px;margin-top:4px;font-size:.95rem;box-sizing:border-box;outline:none;"></div>
    <div style="margin-bottom:20px;"><label style="font-size:.8rem;font-weight:700;color:#aaa;text-transform:uppercase;">Alamat</label><textarea id="editAddress" style="width:100%;padding:10px 14px;border:1.5px solid #f8bbd0;border-radius:10px;margin-top:4px;font-size:.95rem;box-sizing:border-box;outline:none;resize:vertical;min-height:80px;">${u.address || ''}</textarea></div>
    <div id="profileMsg" style="margin-bottom:12px;"></div>
    <button onclick="saveProfile()" style="width:100%;padding:12px;background:linear-gradient(135deg,#e91e8c,#f06292);color:#fff;border:none;border-radius:50px;font-weight:700;cursor:pointer;margin-bottom:10px;">💾 Simpan</button>
    <button onclick="openProfileModal()" style="width:100%;padding:12px;background:#fff;color:#888;border:1.5px solid #eee;border-radius:50px;font-weight:700;cursor:pointer;">← Batal</button>
  `;
}

function showChangePassword() {
  document.getElementById('profileView').innerHTML = `
    <div style="margin-bottom:14px;"><label style="font-size:.8rem;font-weight:700;color:#aaa;text-transform:uppercase;">Password Lama</label><input id="pwCurrent" type="password" placeholder="••••••••" style="width:100%;padding:10px 14px;border:1.5px solid #f8bbd0;border-radius:10px;margin-top:4px;font-size:.95rem;box-sizing:border-box;outline:none;"></div>
    <div style="margin-bottom:14px;"><label style="font-size:.8rem;font-weight:700;color:#aaa;text-transform:uppercase;">Password Baru</label><input id="pwNew" type="password" placeholder="Min. 8 karakter + angka" style="width:100%;padding:10px 14px;border:1.5px solid #f8bbd0;border-radius:10px;margin-top:4px;font-size:.95rem;box-sizing:border-box;outline:none;"></div>
    <div style="margin-bottom:20px;"><label style="font-size:.8rem;font-weight:700;color:#aaa;text-transform:uppercase;">Konfirmasi Password</label><input id="pwConfirm" type="password" placeholder="Ulangi password baru" style="width:100%;padding:10px 14px;border:1.5px solid #f8bbd0;border-radius:10px;margin-top:4px;font-size:.95rem;box-sizing:border-box;outline:none;"></div>
    <div id="profileMsg" style="margin-bottom:12px;"></div>
    <button onclick="savePassword()" style="width:100%;padding:12px;background:linear-gradient(135deg,#e91e8c,#f06292);color:#fff;border:none;border-radius:50px;font-weight:700;cursor:pointer;margin-bottom:10px;">🔒 Ubah Password</button>
    <button onclick="openProfileModal()" style="width:100%;padding:12px;background:#fff;color:#888;border:1.5px solid #eee;border-radius:50px;font-weight:700;cursor:pointer;">← Batal</button>
  `;
}

function showMsg(msg, type) {
  const el = document.getElementById('profileMsg');
  if (!el) return;
  const bg = type === 'success' ? '#e8f5e9' : '#fce4ec';
  const color = type === 'success' ? '#2e7d32' : '#c62828';
  el.innerHTML = `<div style="padding:10px 14px;border-radius:10px;background:${bg};color:${color};font-size:.875rem;font-weight:600;">${msg}</div>`;
}

async function saveProfile() {
  const name    = document.getElementById('editName').value.trim();
  const phone   = document.getElementById('editPhone').value.trim();
  const address = document.getElementById('editAddress').value.trim();
  if (!name || name.length < 2) return showMsg('Nama minimal 2 karakter.', 'error');
  const res = await api('PUT', '/api/auth/profile', { name, phone, address });
  if (res.ok) {
    window._user = { ...window._user, name, phone, address };
    document.getElementById('navAvatar').textContent = name.charAt(0).toUpperCase();
    showMsg('Profil berhasil diperbarui! 🌸', 'success');
    setTimeout(openProfileModal, 1200);
  } else {
    showMsg(res.data.message || 'Gagal menyimpan.', 'error');
  }
}

async function savePassword() {
  const currentPassword = document.getElementById('pwCurrent').value;
  const newPassword     = document.getElementById('pwNew').value;
  const confirmPassword = document.getElementById('pwConfirm').value;
  if (!currentPassword || !newPassword || !confirmPassword) return showMsg('Semua field wajib diisi.', 'error');
  if (newPassword.length < 8) return showMsg('Password baru minimal 8 karakter.', 'error');
  if (!/(?=.*[A-Za-z])(?=.*\d)/.test(newPassword)) return showMsg('Password harus mengandung huruf dan angka.', 'error');
  if (newPassword !== confirmPassword) return showMsg('Konfirmasi tidak cocok.', 'error');
  const res = await api('POST', '/api/auth/change-password', { currentPassword, newPassword, confirmPassword });
  if (res.ok) {
    showMsg('Password berhasil diubah! 🔐', 'success');
    setTimeout(openProfileModal, 1200);
  } else {
    showMsg(res.data.message || 'Gagal mengubah password.', 'error');
  }
}

function closeProfileModal() { document.getElementById('profileModal').style.display = 'none'; }

// ── Init ───────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  loadUser();
  initPasswordToggles();
  initWishlist();
  initCart();
  updateCartBadge();

  document.querySelectorAll('.nav-links a')[0].addEventListener('click', e => { e.preventDefault(); showSection('home'); });
  document.querySelectorAll('.nav-links a')[2].addEventListener('click', e => { e.preventDefault(); showSection('profile'); });

  document.getElementById('navAvatar').addEventListener('click', openProfileModal);

  document.getElementById('profileModal').addEventListener('click', e => {
    if (e.target === document.getElementById('profileModal')) closeProfileModal();
  });
});