@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="/css/style.css">
<style>
  .products-page { max-width: 1200px; margin: 0 auto; padding: 40px 24px 80px; }
  .products-page-header { text-align: center; margin-bottom: 40px; }
  .products-page-header h1 { font-size: 2rem; font-weight: 800; color: var(--gray-900, #111); }
  .products-page-header h1 span { color: var(--rose, #e91e8c); }
  .products-page-header p { color: var(--gray-500, #888); margin-top: 8px; }
  .products-toolbar { display: flex; gap: 12px; flex-wrap: wrap; align-items: center; margin-bottom: 32px; }
  .products-search { flex: 1; min-width: 200px; padding: 12px 18px; border: 1.5px solid #f8bbd0; border-radius: 50px; font-size: 0.95rem; outline: none; }
  .products-search:focus { border-color: var(--rose, #e91e8c); }
  .filter-btn { padding: 10px 20px; border-radius: 50px; border: 1.5px solid #f8bbd0; background: #fff; cursor: pointer; font-size: 0.875rem; font-weight: 600; color: #888; transition: all .2s; }
  .filter-btn.active, .filter-btn:hover { background: var(--rose, #e91e8c); color: #fff; border-color: var(--rose, #e91e8c); }
  .products-count { color: #aaa; font-size: 0.875rem; margin-bottom: 16px; }
  .products-grid-full { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 24px; }
  .empty-state { text-align: center; padding: 80px 0; color: #bbb; font-size: 1.1rem; }
  .back-btn { display: inline-flex; align-items: center; gap: 8px; color: var(--rose, #e91e8c); font-weight: 600; text-decoration: none; margin-bottom: 32px; font-size: 0.95rem; }
  .back-btn:hover { opacity: .75; }
  .navbar { position: sticky; top: 0; z-index: 100; }
</style>

<!-- Navbar -->
<nav class="navbar">
  <a href="/dashboard" class="nav-logo">Sweet<span>Bloom</span> ✦</a>
  <ul class="nav-links">
    <li><a href="/dashboard">Home</a></li>
    <li><a href="#" class="active">Menu</a></li>
  </ul>
  <div class="nav-actions">
    <button class="nav-cart" id="navCart">🛒 <span class="cart-badge">0</span></button>
    <button class="nav-avatar" id="navAvatar">?</button>
  </div>
</nav>

<div class="products-page">
  <a href="/dashboard" class="back-btn">← Kembali ke Home</a>

  <div class="products-page-header">
    <h1>✨ Semua <span>Produk</span> Kami</h1>
    <p>Dibuat segar setiap hari dengan bahan-bahan pilihan 🌸</p>
  </div>

  <div class="products-toolbar">
    <input type="text" class="products-search" id="searchInput" placeholder="🔍 Cari kue favoritmu...">
    <button class="filter-btn active" data-cat="all">Semua</button>
    <button class="filter-btn" data-cat="cake">Cake</button>
    <button class="filter-btn" data-cat="cupcake">Cupcake</button>
    <button class="filter-btn" data-cat="mousse">Mousse</button>
  </div>

  <div class="products-count" id="productsCount"></div>
  <div class="products-grid-full" id="productsGrid"></div>
</div>

<!-- Cart Modal -->
<div id="cartModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.4);align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:24px;padding:32px;width:90%;max-width:420px;max-height:80vh;overflow-y:auto;position:relative;">
    <button onclick="closeCart()" style="position:absolute;top:16px;right:20px;background:none;border:none;font-size:1.5rem;cursor:pointer;color:#aaa;">✕</button>
    <h2 style="font-size:1.3rem;font-weight:800;margin-bottom:20px;">🛒 Keranjang Belanja</h2>
    <div id="cartItems"></div>
    <div id="cartTotal" style="margin-top:20px;padding-top:16px;border-top:1px solid #f8bbd0;font-weight:700;font-size:1.1rem;color:#e91e8c;"></div>
    <button onclick="checkout()" id="checkoutBtn" style="width:100%;margin-top:16px;padding:14px;background:linear-gradient(135deg,#e91e8c,#f06292);color:#fff;border:none;border-radius:50px;font-weight:700;font-size:1rem;cursor:pointer;display:none;">Checkout 🎉</button>
  </div>
</div>

<!-- Profile Modal -->
<div id="profileModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.4);align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:24px;padding:32px;width:90%;max-width:440px;max-height:90vh;overflow-y:auto;position:relative;">
    <button onclick="closeProfileModal()" style="position:absolute;top:16px;right:20px;background:none;border:none;font-size:1.5rem;cursor:pointer;color:#aaa;">✕</button>
    <div id="profileModalContent"></div>
  </div>
</div>

<script>
const cart = JSON.parse(sessionStorage.getItem('sb_cart') || '[]');
let allProducts = [];
let currentCategory = 'all';

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
  showToast(`${emoji} ${name} ditambahkan!`);
}

function showToast(msg) {
  let t = document.getElementById('toast');
  if (!t) {
    t = document.createElement('div');
    t.id = 'toast';
    t.style.cssText = 'position:fixed;bottom:24px;right:24px;z-index:99999;background:#fff0f5;color:#c2185b;border:1px solid #f8bbd0;padding:12px 20px;border-radius:12px;font-weight:600;box-shadow:0 4px 20px rgba(194,24,91,.15);transition:opacity .3s;font-size:.9rem;';
    document.body.appendChild(t);
  }
  t.textContent = `🛒 ${msg}`;
  t.style.opacity = '1';
  clearTimeout(t._t);
  t._t = setTimeout(() => t.style.opacity = '0', 2500);
}

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
          <button onclick="changeQty(${i.id}, -1)" style="width:28px;height:28px;border-radius:50%;border:1.5px solid #f8bbd0;background:#fff;cursor:pointer;font-size:1rem;line-height:1;">−</button>
          <span style="font-weight:700;min-width:20px;text-align:center;">${i.qty}</span>
          <button onclick="changeQty(${i.id}, 1)" style="width:28px;height:28px;border-radius:50%;border:none;background:#e91e8c;color:#fff;cursor:pointer;font-size:1rem;line-height:1;">+</button>
        </div>
      </div>
    `).join('');
    const total = cart.reduce((s, i) => s + i.price * i.qty, 0);
    totalEl.innerHTML = `Total: ${formatPrice(total)}`;
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

function checkout() { alert('Fitur checkout akan segera hadir! 🎉'); }

function formatPrice(p) { return 'Rp ' + p.toLocaleString('id-ID'); }

function renderProducts(products) {
  const grid = document.getElementById('productsGrid');
  const count = document.getElementById('productsCount');
  count.textContent = `Menampilkan ${products.length} produk`;

  if (products.length === 0) {
    grid.innerHTML = '<div class="empty-state">😢 Produk tidak ditemukan</div>';
    return;
  }

  grid.innerHTML = products.map(p => `
    <div class="product-card">
      <div class="product-card-image">
        ${p.emoji}
        ${p.badge ? `<span class="product-badge">${p.badge}</span>` : ''}
        <button class="product-wishlist" onclick="toggleWishlist(this)">🤍</button>
      </div>
      <div class="product-info">
        <div class="product-name">${p.name}</div>
        <div class="product-desc">${p.description}</div>
        <div class="product-meta">
          <span class="product-price">${formatPrice(p.price)}</span>
          <div style="display:flex;align-items:center;gap:8px">
            <span class="product-rating">⭐ ${p.rating}</span>
            <button class="product-add-btn" onclick="addToCart(${p.id}, '${p.name}', ${p.price}, '${p.emoji}')">+</button>
          </div>
        </div>
      </div>
    </div>
  `).join('');
}

function toggleWishlist(btn) {
  const liked = btn.classList.toggle('liked');
  btn.textContent = liked ? '❤️' : '🤍';
}

function filterAndSearch() {
  const q = document.getElementById('searchInput').value.toLowerCase();
  let result = allProducts;
  if (currentCategory !== 'all') result = result.filter(p => p.category === currentCategory);
  if (q) result = result.filter(p => p.name.toLowerCase().includes(q) || p.description.toLowerCase().includes(q));
  renderProducts(result);
}

async function loadProducts() {
  try {
    const res = await fetch('/api/products');
    const data = await res.json();
    allProducts = data.products || [];
    renderProducts(allProducts);
  } catch {
    document.getElementById('productsGrid').innerHTML = '<div class="empty-state">Gagal memuat produk 😢</div>';
  }
}

async function loadUser() {
  try {
    const res = await fetch('/api/auth/me', { credentials: 'include' });
    const data = await res.json();
    if (!res.ok) { window.location.href = '/login'; return; }
    const u = data.user;
    document.getElementById('navAvatar').textContent = u.name.charAt(0).toUpperCase();
    window._user = u;
  } catch {
    window.location.href = '/login';
  }
}

// Profile Modal
function openProfileModal() {
  const u = window._user;
  if (!u) return;
  document.getElementById('profileModalContent').innerHTML = `
    <div style="text-align:center;margin-bottom:24px;">
      <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#f8bbd0,#e91e8c);display:flex;align-items:center;justify-content:center;font-size:2rem;margin:0 auto 12px;">
        ${u.role === 'admin' ? '👑' : '🌸'}
      </div>
      <div style="font-weight:800;font-size:1.1rem;">${u.name}</div>
      <div style="color:#aaa;font-size:.875rem;">${u.email}</div>
      <span style="display:inline-block;margin-top:6px;padding:4px 12px;background:#fce4ec;color:#e91e8c;border-radius:50px;font-size:.8rem;font-weight:700;">${u.role === 'admin' ? '👑 Admin' : '🛍️ Customer'}</span>
    </div>
    <div id="profileView">
      <div style="margin-bottom:16px;">
        <label style="font-size:.8rem;font-weight:700;color:#aaa;text-transform:uppercase;">Nama</label>
        <div style="padding:10px 14px;background:#fafafa;border-radius:10px;margin-top:4px;font-weight:600;">${u.name}</div>
      </div>
      <div style="margin-bottom:16px;">
        <label style="font-size:.8rem;font-weight:700;color:#aaa;text-transform:uppercase;">Email</label>
        <div style="padding:10px 14px;background:#fafafa;border-radius:10px;margin-top:4px;font-weight:600;">${u.email}</div>
      </div>
      <div style="margin-bottom:16px;">
        <label style="font-size:.8rem;font-weight:700;color:#aaa;text-transform:uppercase;">No. Telepon</label>
        <div style="padding:10px 14px;background:#fafafa;border-radius:10px;margin-top:4px;font-weight:600;">${u.phone || '-'}</div>
      </div>
      <div style="margin-bottom:24px;">
        <label style="font-size:.8rem;font-weight:700;color:#aaa;text-transform:uppercase;">Alamat</label>
        <div style="padding:10px 14px;background:#fafafa;border-radius:10px;margin-top:4px;font-weight:600;">${u.address || '-'}</div>
      </div>
      <div id="profileMsg" style="margin-bottom:12px;"></div>
      <button onclick="showEditProfile()" style="width:100%;padding:12px;background:linear-gradient(135deg,#e91e8c,#f06292);color:#fff;border:none;border-radius:50px;font-weight:700;cursor:pointer;margin-bottom:10px;">✏️ Edit Profil</button>
      <button onclick="showChangePassword()" style="width:100%;padding:12px;background:#fff;color:#e91e8c;border:1.5px solid #f8bbd0;border-radius:50px;font-weight:700;cursor:pointer;margin-bottom:10px;">🔒 Ubah Password</button>
      <button onclick="handleLogout()" style="width:100%;padding:12px;background:#fff;color:#888;border:1.5px solid #eee;border-radius:50px;font-weight:700;cursor:pointer;margin-bottom:10px;">🚪 Logout</button>
      <button onclick="confirmDelete()" style="width:100%;padding:12px;background:#fff;color:#e53935;border:1.5px solid #ffcdd2;border-radius:50px;font-weight:700;cursor:pointer;">🗑️ Hapus Akun</button>
    </div>
  `;
  document.getElementById('profileModal').style.display = 'flex';
}

function showEditProfile() {
  const u = window._user;
  document.getElementById('profileView').innerHTML = `
    <div style="margin-bottom:14px;">
      <label style="font-size:.8rem;font-weight:700;color:#aaa;text-transform:uppercase;">Nama</label>
      <input id="editName" value="${u.name}" style="width:100%;padding:10px 14px;border:1.5px solid #f8bbd0;border-radius:10px;margin-top:4px;font-size:.95rem;box-sizing:border-box;outline:none;">
    </div>
    <div style="margin-bottom:14px;">
      <label style="font-size:.8rem;font-weight:700;color:#aaa;text-transform:uppercase;">No. Telepon</label>
      <input id="editPhone" value="${u.phone || ''}" placeholder="08xxxxxxxxxx" style="width:100%;padding:10px 14px;border:1.5px solid #f8bbd0;border-radius:10px;margin-top:4px;font-size:.95rem;box-sizing:border-box;outline:none;">
    </div>
    <div style="margin-bottom:20px;">
      <label style="font-size:.8rem;font-weight:700;color:#aaa;text-transform:uppercase;">Alamat</label>
      <textarea id="editAddress" placeholder="Alamat lengkap..." style="width:100%;padding:10px 14px;border:1.5px solid #f8bbd0;border-radius:10px;margin-top:4px;font-size:.95rem;box-sizing:border-box;outline:none;resize:vertical;min-height:80px;">${u.address || ''}</textarea>
    </div>
    <div id="profileMsg" style="margin-bottom:12px;"></div>
    <button onclick="saveProfile()" style="width:100%;padding:12px;background:linear-gradient(135deg,#e91e8c,#f06292);color:#fff;border:none;border-radius:50px;font-weight:700;cursor:pointer;margin-bottom:10px;">💾 Simpan</button>
    <button onclick="openProfileModal()" style="width:100%;padding:12px;background:#fff;color:#888;border:1.5px solid #eee;border-radius:50px;font-weight:700;cursor:pointer;">← Batal</button>
  `;
}

function showChangePassword() {
  document.getElementById('profileView').innerHTML = `
    <div style="margin-bottom:14px;">
      <label style="font-size:.8rem;font-weight:700;color:#aaa;text-transform:uppercase;">Password Lama</label>
      <input id="pwCurrent" type="password" placeholder="••••••••" style="width:100%;padding:10px 14px;border:1.5px solid #f8bbd0;border-radius:10px;margin-top:4px;font-size:.95rem;box-sizing:border-box;outline:none;">
    </div>
    <div style="margin-bottom:14px;">
      <label style="font-size:.8rem;font-weight:700;color:#aaa;text-transform:uppercase;">Password Baru</label>
      <input id="pwNew" type="password" placeholder="Min. 8 karakter + angka" style="width:100%;padding:10px 14px;border:1.5px solid #f8bbd0;border-radius:10px;margin-top:4px;font-size:.95rem;box-sizing:border-box;outline:none;">
    </div>
    <div style="margin-bottom:20px;">
      <label style="font-size:.8rem;font-weight:700;color:#aaa;text-transform:uppercase;">Konfirmasi Password</label>
      <input id="pwConfirm" type="password" placeholder="Ulangi password baru" style="width:100%;padding:10px 14px;border:1.5px solid #f8bbd0;border-radius:10px;margin-top:4px;font-size:.95rem;box-sizing:border-box;outline:none;">
    </div>
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

  const res = await fetch('/api/auth/profile', {
    method: 'PUT',
    credentials: 'include',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ name, phone, address }),
  });
  const data = await res.json();

  if (res.ok) {
    window._user = { ...window._user, name, phone, address };
    document.getElementById('navAvatar').textContent = name.charAt(0).toUpperCase();
    showMsg('Profil berhasil diperbarui! 🌸', 'success');
    setTimeout(openProfileModal, 1200);
  } else {
    showMsg(data.message || 'Gagal menyimpan.', 'error');
  }
}

async function savePassword() {
  const currentPassword = document.getElementById('pwCurrent').value;
  const newPassword     = document.getElementById('pwNew').value;
  const confirmPassword = document.getElementById('pwConfirm').value;

  if (!currentPassword || !newPassword || !confirmPassword) return showMsg('Semua field wajib diisi.', 'error');
  if (newPassword.length < 8) return showMsg('Password baru minimal 8 karakter.', 'error');
  if (!/(?=.*[A-Za-z])(?=.*\d)/.test(newPassword)) return showMsg('Password harus mengandung huruf dan angka.', 'error');
  if (newPassword !== confirmPassword) return showMsg('Konfirmasi password tidak cocok.', 'error');

  const res = await fetch('/api/auth/change-password', {
    method: 'POST',
    credentials: 'include',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ currentPassword, newPassword, confirmPassword }),
  });
  const data = await res.json();

  if (res.ok) {
    showMsg('Password berhasil diubah! 🔐', 'success');
    setTimeout(openProfileModal, 1200);
  } else {
    showMsg(data.message || 'Gagal mengubah password.', 'error');
  }
}

function confirmDelete() {
  if (confirm('Yakin ingin menghapus akun? Tindakan ini tidak bisa dibatalkan!')) {
    handleDeleteAccount();
  }
}

async function handleDeleteAccount() {
  const res = await fetch('/api/auth/account', { method: 'DELETE', credentials: 'include' });
  if (res.ok) {
    sessionStorage.clear();
    window.location.href = '/login?info=' + encodeURIComponent('Akun berhasil dihapus. Sampai jumpa! 👋');
  } else {
    showMsg('Gagal menghapus akun.', 'error');
  }
}

async function handleLogout() {
  await fetch('/api/auth/logout', { method: 'POST', credentials: 'include' });
  sessionStorage.clear();
  window.location.href = '/login';
}

function closeProfileModal() { document.getElementById('profileModal').style.display = 'none'; }

// Event listeners
document.addEventListener('DOMContentLoaded', () => {
  loadUser().then(() => {
    document.getElementById('navAvatar').addEventListener('click', openProfileModal);
  });
  loadProducts();
  updateCartBadge();

  document.getElementById('navCart').addEventListener('click', openCart);

  document.getElementById('searchInput').addEventListener('input', filterAndSearch);

  document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      currentCategory = btn.dataset.cat;
      filterAndSearch();
    });
  });

  // Tutup modal kalau klik backdrop
  document.getElementById('cartModal').addEventListener('click', e => { if (e.target === document.getElementById('cartModal')) closeCart(); });
  document.getElementById('profileModal').addEventListener('click', e => { if (e.target === document.getElementById('profileModal')) closeProfileModal(); });
});
</script>

@endsection@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="/css/style.css">
<style>
  .products-page { max-width: 1200px; margin: 0 auto; padding: 40px 24px 80px; }
  .products-page-header { text-align: center; margin-bottom: 40px; }
  .products-page-header h1 { font-size: 2rem; font-weight: 800; color: var(--gray-900, #111); }
  .products-page-header h1 span { color: var(--rose, #e91e8c); }
  .products-page-header p { color: var(--gray-500, #888); margin-top: 8px; }
  .products-toolbar { display: flex; gap: 12px; flex-wrap: wrap; align-items: center; margin-bottom: 32px; }
  .products-search { flex: 1; min-width: 200px; padding: 12px 18px; border: 1.5px solid #f8bbd0; border-radius: 50px; font-size: 0.95rem; outline: none; }
  .products-search:focus { border-color: var(--rose, #e91e8c); }
  .filter-btn { padding: 10px 20px; border-radius: 50px; border: 1.5px solid #f8bbd0; background: #fff; cursor: pointer; font-size: 0.875rem; font-weight: 600; color: #888; transition: all .2s; }
  .filter-btn.active, .filter-btn:hover { background: var(--rose, #e91e8c); color: #fff; border-color: var(--rose, #e91e8c); }
  .products-count { color: #aaa; font-size: 0.875rem; margin-bottom: 16px; }
  .products-grid-full { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 24px; }
  .empty-state { text-align: center; padding: 80px 0; color: #bbb; font-size: 1.1rem; }
  .back-btn { display: inline-flex; align-items: center; gap: 8px; color: var(--rose, #e91e8c); font-weight: 600; text-decoration: none; margin-bottom: 32px; font-size: 0.95rem; }
  .back-btn:hover { opacity: .75; }
  .navbar { position: sticky; top: 0; z-index: 100; }
</style>

<!-- Navbar -->
<nav class="navbar">
  <a href="/dashboard" class="nav-logo">Sweet<span>Bloom</span> ✦</a>
  <ul class="nav-links">
    <li><a href="/dashboard">Home</a></li>
    <li><a href="#" class="active">Menu</a></li>
  </ul>
  <div class="nav-actions">
    <button class="nav-cart" id="navCart">🛒 <span class="cart-badge">0</span></button>
    <button class="nav-avatar" id="navAvatar">?</button>
  </div>
</nav>

<div class="products-page">
  <a href="/dashboard" class="back-btn">← Kembali ke Home</a>

  <div class="products-page-header">
    <h1>✨ Semua <span>Produk</span> Kami</h1>
    <p>Dibuat segar setiap hari dengan bahan-bahan pilihan 🌸</p>
  </div>

  <div class="products-toolbar">
    <input type="text" class="products-search" id="searchInput" placeholder="🔍 Cari kue favoritmu...">
    <button class="filter-btn active" data-cat="all">Semua</button>
    <button class="filter-btn" data-cat="cake">Cake</button>
    <button class="filter-btn" data-cat="cupcake">Cupcake</button>
    <button class="filter-btn" data-cat="mousse">Mousse</button>
  </div>

  <div class="products-count" id="productsCount"></div>
  <div class="products-grid-full" id="productsGrid"></div>
</div>

<!-- Cart Modal -->
<div id="cartModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.4);align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:24px;padding:32px;width:90%;max-width:420px;max-height:80vh;overflow-y:auto;position:relative;">
    <button onclick="closeCart()" style="position:absolute;top:16px;right:20px;background:none;border:none;font-size:1.5rem;cursor:pointer;color:#aaa;">✕</button>
    <h2 style="font-size:1.3rem;font-weight:800;margin-bottom:20px;">🛒 Keranjang Belanja</h2>
    <div id="cartItems"></div>
    <div id="cartTotal" style="margin-top:20px;padding-top:16px;border-top:1px solid #f8bbd0;font-weight:700;font-size:1.1rem;color:#e91e8c;"></div>
    <button onclick="checkout()" id="checkoutBtn" style="width:100%;margin-top:16px;padding:14px;background:linear-gradient(135deg,#e91e8c,#f06292);color:#fff;border:none;border-radius:50px;font-weight:700;font-size:1rem;cursor:pointer;display:none;">Checkout 🎉</button>
  </div>
</div>

<!-- Profile Modal -->
<div id="profileModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.4);align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:24px;padding:32px;width:90%;max-width:440px;max-height:90vh;overflow-y:auto;position:relative;">
    <button onclick="closeProfileModal()" style="position:absolute;top:16px;right:20px;background:none;border:none;font-size:1.5rem;cursor:pointer;color:#aaa;">✕</button>
    <div id="profileModalContent"></div>
  </div>
</div>

<script>
const cart = JSON.parse(sessionStorage.getItem('sb_cart') || '[]');
let allProducts = [];
let currentCategory = 'all';

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
  showToast(`${emoji} ${name} ditambahkan!`);
}

function showToast(msg) {
  let t = document.getElementById('toast');
  if (!t) {
    t = document.createElement('div');
    t.id = 'toast';
    t.style.cssText = 'position:fixed;bottom:24px;right:24px;z-index:99999;background:#fff0f5;color:#c2185b;border:1px solid #f8bbd0;padding:12px 20px;border-radius:12px;font-weight:600;box-shadow:0 4px 20px rgba(194,24,91,.15);transition:opacity .3s;font-size:.9rem;';
    document.body.appendChild(t);
  }
  t.textContent = `🛒 ${msg}`;
  t.style.opacity = '1';
  clearTimeout(t._t);
  t._t = setTimeout(() => t.style.opacity = '0', 2500);
}

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
          <button onclick="changeQty(${i.id}, -1)" style="width:28px;height:28px;border-radius:50%;border:1.5px solid #f8bbd0;background:#fff;cursor:pointer;font-size:1rem;line-height:1;">−</button>
          <span style="font-weight:700;min-width:20px;text-align:center;">${i.qty}</span>
          <button onclick="changeQty(${i.id}, 1)" style="width:28px;height:28px;border-radius:50%;border:none;background:#e91e8c;color:#fff;cursor:pointer;font-size:1rem;line-height:1;">+</button>
        </div>
      </div>
    `).join('');
    const total = cart.reduce((s, i) => s + i.price * i.qty, 0);
    totalEl.innerHTML = `Total: ${formatPrice(total)}`;
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

function checkout() { alert('Fitur checkout akan segera hadir! 🎉'); }

function formatPrice(p) { return 'Rp ' + p.toLocaleString('id-ID'); }

function renderProducts(products) {
  const grid = document.getElementById('productsGrid');
  const count = document.getElementById('productsCount');
  count.textContent = `Menampilkan ${products.length} produk`;

  if (products.length === 0) {
    grid.innerHTML = '<div class="empty-state">😢 Produk tidak ditemukan</div>';
    return;
  }

  grid.innerHTML = products.map(p => `
    <div class="product-card">
      <div class="product-card-image">
        ${p.emoji}
        ${p.badge ? `<span class="product-badge">${p.badge}</span>` : ''}
        <button class="product-wishlist" onclick="toggleWishlist(this)">🤍</button>
      </div>
      <div class="product-info">
        <div class="product-name">${p.name}</div>
        <div class="product-desc">${p.description}</div>
        <div class="product-meta">
          <span class="product-price">${formatPrice(p.price)}</span>
          <div style="display:flex;align-items:center;gap:8px">
            <span class="product-rating">⭐ ${p.rating}</span>
            <button class="product-add-btn" onclick="addToCart(${p.id}, '${p.name}', ${p.price}, '${p.emoji}')">+</button>
          </div>
        </div>
      </div>
    </div>
  `).join('');
}

function toggleWishlist(btn) {
  const liked = btn.classList.toggle('liked');
  btn.textContent = liked ? '❤️' : '🤍';
}

function filterAndSearch() {
  const q = document.getElementById('searchInput').value.toLowerCase();
  let result = allProducts;
  if (currentCategory !== 'all') result = result.filter(p => p.category === currentCategory);
  if (q) result = result.filter(p => p.name.toLowerCase().includes(q) || p.description.toLowerCase().includes(q));
  renderProducts(result);
}

async function loadProducts() {
  try {
    const res = await fetch('/api/products');
    const data = await res.json();
    allProducts = data.products || [];
    renderProducts(allProducts);
  } catch {
    document.getElementById('productsGrid').innerHTML = '<div class="empty-state">Gagal memuat produk 😢</div>';
  }
}

async function loadUser() {
  try {
    const res = await fetch('/api/auth/me', { credentials: 'include' });
    const data = await res.json();
    if (!res.ok) { window.location.href = '/login'; return; }
    const u = data.user;
    document.getElementById('navAvatar').textContent = u.name.charAt(0).toUpperCase();
    window._user = u;
  } catch {
    window.location.href = '/login';
  }
}

// Profile Modal
function openProfileModal() {
  const u = window._user;
  if (!u) return;
  document.getElementById('profileModalContent').innerHTML = `
    <div style="text-align:center;margin-bottom:24px;">
      <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#f8bbd0,#e91e8c);display:flex;align-items:center;justify-content:center;font-size:2rem;margin:0 auto 12px;">
        ${u.role === 'admin' ? '👑' : '🌸'}
      </div>
      <div style="font-weight:800;font-size:1.1rem;">${u.name}</div>
      <div style="color:#aaa;font-size:.875rem;">${u.email}</div>
      <span style="display:inline-block;margin-top:6px;padding:4px 12px;background:#fce4ec;color:#e91e8c;border-radius:50px;font-size:.8rem;font-weight:700;">${u.role === 'admin' ? '👑 Admin' : '🛍️ Customer'}</span>
    </div>
    <div id="profileView">
      <div style="margin-bottom:16px;">
        <label style="font-size:.8rem;font-weight:700;color:#aaa;text-transform:uppercase;">Nama</label>
        <div style="padding:10px 14px;background:#fafafa;border-radius:10px;margin-top:4px;font-weight:600;">${u.name}</div>
      </div>
      <div style="margin-bottom:16px;">
        <label style="font-size:.8rem;font-weight:700;color:#aaa;text-transform:uppercase;">Email</label>
        <div style="padding:10px 14px;background:#fafafa;border-radius:10px;margin-top:4px;font-weight:600;">${u.email}</div>
      </div>
      <div style="margin-bottom:16px;">
        <label style="font-size:.8rem;font-weight:700;color:#aaa;text-transform:uppercase;">No. Telepon</label>
        <div style="padding:10px 14px;background:#fafafa;border-radius:10px;margin-top:4px;font-weight:600;">${u.phone || '-'}</div>
      </div>
      <div style="margin-bottom:24px;">
        <label style="font-size:.8rem;font-weight:700;color:#aaa;text-transform:uppercase;">Alamat</label>
        <div style="padding:10px 14px;background:#fafafa;border-radius:10px;margin-top:4px;font-weight:600;">${u.address || '-'}</div>
      </div>
      <div id="profileMsg" style="margin-bottom:12px;"></div>
      <button onclick="showEditProfile()" style="width:100%;padding:12px;background:linear-gradient(135deg,#e91e8c,#f06292);color:#fff;border:none;border-radius:50px;font-weight:700;cursor:pointer;margin-bottom:10px;">✏️ Edit Profil</button>
      <button onclick="showChangePassword()" style="width:100%;padding:12px;background:#fff;color:#e91e8c;border:1.5px solid #f8bbd0;border-radius:50px;font-weight:700;cursor:pointer;margin-bottom:10px;">🔒 Ubah Password</button>
      <button onclick="handleLogout()" style="width:100%;padding:12px;background:#fff;color:#888;border:1.5px solid #eee;border-radius:50px;font-weight:700;cursor:pointer;margin-bottom:10px;">🚪 Logout</button>
      <button onclick="confirmDelete()" style="width:100%;padding:12px;background:#fff;color:#e53935;border:1.5px solid #ffcdd2;border-radius:50px;font-weight:700;cursor:pointer;">🗑️ Hapus Akun</button>
    </div>
  `;
  document.getElementById('profileModal').style.display = 'flex';
}

function showEditProfile() {
  const u = window._user;
  document.getElementById('profileView').innerHTML = `
    <div style="margin-bottom:14px;">
      <label style="font-size:.8rem;font-weight:700;color:#aaa;text-transform:uppercase;">Nama</label>
      <input id="editName" value="${u.name}" style="width:100%;padding:10px 14px;border:1.5px solid #f8bbd0;border-radius:10px;margin-top:4px;font-size:.95rem;box-sizing:border-box;outline:none;">
    </div>
    <div style="margin-bottom:14px;">
      <label style="font-size:.8rem;font-weight:700;color:#aaa;text-transform:uppercase;">No. Telepon</label>
      <input id="editPhone" value="${u.phone || ''}" placeholder="08xxxxxxxxxx" style="width:100%;padding:10px 14px;border:1.5px solid #f8bbd0;border-radius:10px;margin-top:4px;font-size:.95rem;box-sizing:border-box;outline:none;">
    </div>
    <div style="margin-bottom:20px;">
      <label style="font-size:.8rem;font-weight:700;color:#aaa;text-transform:uppercase;">Alamat</label>
      <textarea id="editAddress" placeholder="Alamat lengkap..." style="width:100%;padding:10px 14px;border:1.5px solid #f8bbd0;border-radius:10px;margin-top:4px;font-size:.95rem;box-sizing:border-box;outline:none;resize:vertical;min-height:80px;">${u.address || ''}</textarea>
    </div>
    <div id="profileMsg" style="margin-bottom:12px;"></div>
    <button onclick="saveProfile()" style="width:100%;padding:12px;background:linear-gradient(135deg,#e91e8c,#f06292);color:#fff;border:none;border-radius:50px;font-weight:700;cursor:pointer;margin-bottom:10px;">💾 Simpan</button>
    <button onclick="openProfileModal()" style="width:100%;padding:12px;background:#fff;color:#888;border:1.5px solid #eee;border-radius:50px;font-weight:700;cursor:pointer;">← Batal</button>
  `;
}

function showChangePassword() {
  document.getElementById('profileView').innerHTML = `
    <div style="margin-bottom:14px;">
      <label style="font-size:.8rem;font-weight:700;color:#aaa;text-transform:uppercase;">Password Lama</label>
      <input id="pwCurrent" type="password" placeholder="••••••••" style="width:100%;padding:10px 14px;border:1.5px solid #f8bbd0;border-radius:10px;margin-top:4px;font-size:.95rem;box-sizing:border-box;outline:none;">
    </div>
    <div style="margin-bottom:14px;">
      <label style="font-size:.8rem;font-weight:700;color:#aaa;text-transform:uppercase;">Password Baru</label>
      <input id="pwNew" type="password" placeholder="Min. 8 karakter + angka" style="width:100%;padding:10px 14px;border:1.5px solid #f8bbd0;border-radius:10px;margin-top:4px;font-size:.95rem;box-sizing:border-box;outline:none;">
    </div>
    <div style="margin-bottom:20px;">
      <label style="font-size:.8rem;font-weight:700;color:#aaa;text-transform:uppercase;">Konfirmasi Password</label>
      <input id="pwConfirm" type="password" placeholder="Ulangi password baru" style="width:100%;padding:10px 14px;border:1.5px solid #f8bbd0;border-radius:10px;margin-top:4px;font-size:.95rem;box-sizing:border-box;outline:none;">
    </div>
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

  const res = await fetch('/api/auth/profile', {
    method: 'PUT',
    credentials: 'include',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ name, phone, address }),
  });
  const data = await res.json();

  if (res.ok) {
    window._user = { ...window._user, name, phone, address };
    document.getElementById('navAvatar').textContent = name.charAt(0).toUpperCase();
    showMsg('Profil berhasil diperbarui! 🌸', 'success');
    setTimeout(openProfileModal, 1200);
  } else {
    showMsg(data.message || 'Gagal menyimpan.', 'error');
  }
}

async function savePassword() {
  const currentPassword = document.getElementById('pwCurrent').value;
  const newPassword     = document.getElementById('pwNew').value;
  const confirmPassword = document.getElementById('pwConfirm').value;

  if (!currentPassword || !newPassword || !confirmPassword) return showMsg('Semua field wajib diisi.', 'error');
  if (newPassword.length < 8) return showMsg('Password baru minimal 8 karakter.', 'error');
  if (!/(?=.*[A-Za-z])(?=.*\d)/.test(newPassword)) return showMsg('Password harus mengandung huruf dan angka.', 'error');
  if (newPassword !== confirmPassword) return showMsg('Konfirmasi password tidak cocok.', 'error');

  const res = await fetch('/api/auth/change-password', {
    method: 'POST',
    credentials: 'include',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ currentPassword, newPassword, confirmPassword }),
  });
  const data = await res.json();

  if (res.ok) {
    showMsg('Password berhasil diubah! 🔐', 'success');
    setTimeout(openProfileModal, 1200);
  } else {
    showMsg(data.message || 'Gagal mengubah password.', 'error');
  }
}

function confirmDelete() {
  if (confirm('Yakin ingin menghapus akun? Tindakan ini tidak bisa dibatalkan!')) {
    handleDeleteAccount();
  }
}

async function handleDeleteAccount() {
  const res = await fetch('/api/auth/account', { method: 'DELETE', credentials: 'include' });
  if (res.ok) {
    sessionStorage.clear();
    window.location.href = '/login?info=' + encodeURIComponent('Akun berhasil dihapus. Sampai jumpa! 👋');
  } else {
    showMsg('Gagal menghapus akun.', 'error');
  }
}

async function handleLogout() {
  await fetch('/api/auth/logout', { method: 'POST', credentials: 'include' });
  sessionStorage.clear();
  window.location.href = '/login';
}

function closeProfileModal() { document.getElementById('profileModal').style.display = 'none'; }

// Event listeners
document.addEventListener('DOMContentLoaded', () => {
  loadUser().then(() => {
    document.getElementById('navAvatar').addEventListener('click', openProfileModal);
  });
  loadProducts();
  updateCartBadge();

  document.getElementById('navCart').addEventListener('click', openCart);

  document.getElementById('searchInput').addEventListener('input', filterAndSearch);

  document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      currentCategory = btn.dataset.cat;
      filterAndSearch();
    });
  });

  // Tutup modal kalau klik backdrop
  document.getElementById('cartModal').addEventListener('click', e => { if (e.target === document.getElementById('cartModal')) closeCart(); });
  document.getElementById('profileModal').addEventListener('click', e => { if (e.target === document.getElementById('profileModal')) closeProfileModal(); });
});
</script>

@endsection