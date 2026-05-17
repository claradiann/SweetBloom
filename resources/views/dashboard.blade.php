@extends('layouts.app')

@section('content')

<!-- Navbar -->
<nav class="navbar" id="mainNav">
  <a href="#" class="nav-logo">Sweet<span>Bloom</span> <span class="logo-dot"></span></a>
  <ul class="nav-links">
    <li><a href="#" class="active" id="linkHome">Home</a></li>
    <li><a href="#menu" id="linkMenu">Menu</a></li>
    <li><a href="#" id="linkProfile">Profile</a></li>
  </ul>
  <div class="nav-actions">
    <button class="nav-cart">🛒 <span class="cart-badge">0</span></button>
    <button class="nav-avatar" id="navAvatar">?</button>
    <button class="btn-logout" onclick="handleLogout()">Logout</button>
  </div>
</nav>

<!-- HOME SECTION -->
<div id="section-home">

  <!-- Hero -->
  <div class="hero">
    <div>
      <div class="hero-badge">🌸 Handcrafted with Love</div>
      <h1 class="hero-title">Freshly Baked<br><span>Happiness</span> <span class="wave">🍓</span></h1>
      <p class="hero-subtitle">Kue handmade dengan cinta untuk setiap momen spesialmu. Dibuat segar setiap hari dengan bahan premium!</p>
      <div class="hero-actions">
        <button class="btn-hero-primary" onclick="document.getElementById('menu').scrollIntoView({behavior:'smooth'})">Explore Menu 🎂</button>
        <button class="btn-hero-secondary">Lihat Promo ✨</button>
      </div>
    </div>
    <div class="hero-visual">
      <div class="hero-cake-main">🎂</div>
      <div class="hero-float-tag tag-rating"><span class="tag-star">⭐</span> 4.9 Rating</div>
      <div class="hero-float-tag tag-orders">🛍️ 1.200+ Orders</div>
    </div>
  </div>

  <!-- Stats -->
  <div class="stats-bar">
    <div class="stat-item"><div class="stat-number">1.2K+</div><div class="stat-label">Happy Customers</div></div>
    <div class="stat-item"><div class="stat-number">50+</div><div class="stat-label">Varian Kue</div></div>
    <div class="stat-item"><div class="stat-number">4.9⭐</div><div class="stat-label">Avg Rating</div></div>
    <div class="stat-item"><div class="stat-number">3 Jam</div><div class="stat-label">Estimasi Kirim</div></div>
  </div>

  <!-- Products -->
  <div class="section" id="menu">
    <div class="section-header">
      <h2 class="section-title">✨ Best Seller <span>Cakes</span></h2>
      <a href="/products" class="section-link">Lihat semua →</a>
    </div>
    <div class="products-grid">
      <div class="product-card">
        <div class="product-card-image">🍓<span class="product-badge">BEST</span><button class="product-wishlist">🤍</button></div>
        <div class="product-info">
          <div class="product-name">Strawberry Bliss</div>
          <div class="product-desc">Kue lembut dengan topping stroberi segar pilihan</div>
          <div class="product-meta">
            <span class="product-price">Rp 120.000</span>
            <div style="display:flex;align-items:center;gap:8px">
              <span class="product-rating">⭐ 4.9</span>
              <button class="product-add-btn">+</button>
            </div>
          </div>
        </div>
      </div>
      <div class="product-card">
        <div class="product-card-image">🍫<button class="product-wishlist">🤍</button></div>
        <div class="product-info">
          <div class="product-name">Choco Heaven</div>
          <div class="product-desc">Lelehan coklat belgia di setiap lapisan</div>
          <div class="product-meta">
            <span class="product-price">Rp 95.000</span>
            <div style="display:flex;align-items:center;gap:8px">
              <span class="product-rating">⭐ 4.8</span>
              <button class="product-add-btn">+</button>
            </div>
          </div>
        </div>
      </div>
      <div class="product-card">
        <div class="product-card-image">🎂<span class="product-badge">NEW</span><button class="product-wishlist">🤍</button></div>
        <div class="product-info">
          <div class="product-name">Birthday Special</div>
          <div class="product-desc">Custom kue ulang tahun sesuai permintaan</div>
          <div class="product-meta">
            <span class="product-price">Rp 200.000</span>
            <div style="display:flex;align-items:center;gap:8px">
              <span class="product-rating">⭐ 5.0</span>
              <button class="product-add-btn">+</button>
            </div>
          </div>
        </div>
      </div>
      <div class="product-card">
        <div class="product-card-image">🧁<button class="product-wishlist">🤍</button></div>
        <div class="product-info">
          <div class="product-name">Vanilla Dream</div>
          <div class="product-desc">Cupcake vanilla klasik dengan buttercream premium</div>
          <div class="product-meta">
            <span class="product-price">Rp 45.000</span>
            <div style="display:flex;align-items:center;gap:8px">
              <span class="product-rating">⭐ 4.7</span>
              <button class="product-add-btn">+</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="site-footer">
    <div class="footer-inner">
      <div class="footer-brand">
        <div class="nav-logo" style="font-size:1.3rem">Sweet<span>Bloom</span> <span class="logo-dot"></span></div>
        <p>Toko kue handmade terbaik dengan cinta di setiap gigitan. Dibuat segar setiap hari untuk kebahagiaan Anda.</p>
      </div>
      <div class="footer-col">
        <h4>Menu</h4>
        <ul><li><a href="#">Semua Kue</a></li><li><a href="#">Best Seller</a></li><li><a href="#">Custom Cake</a></li><li><a href="#">Cupcakes</a></li></ul>
      </div>
      <div class="footer-col">
        <h4>Info</h4>
        <ul><li><a href="#">Tentang Kami</a></li><li><a href="#">Pengiriman</a></li><li><a href="#">FAQ</a></li><li><a href="#">Kontak</a></li></ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 2026 SweetBloom. All rights reserved.</span>
      <span>Made with <span class="footer-hearts">♥</span> in Indonesia</span>
    </div>
  </footer>
</div>

<!-- PROFILE SECTION -->
<div id="section-profile" style="display:none; padding: 40px 0 60px;">
  <div class="profile-layout">
    <aside class="profile-sidebar">
      <div class="avatar-circle"><span id="avatarEmoji">🌸</span><span class="avatar-edit">✏️</span></div>
      <div class="profile-name" id="sidebarName">Loading...</div>
      <div class="profile-email-text" id="sidebarEmail"></div>
      <span class="profile-badge-tag" id="sidebarRole">Customer</span>
      <ul class="profile-nav" style="margin-top:24px">
        <li><a href="#" class="active" onclick="switchTab('info',this);return false;">👤 Informasi Akun</a></li>
        <li><a href="#" onclick="switchTab('password',this);return false;">🔑 Ubah Password</a></li>
        <li><a href="#" onclick="switchTab('orders',this);return false;">📦 Pesanan Saya</a></li>
      </ul>
    </aside>
    <div class="profile-main">
      <div id="profileAlert" class="hidden"></div>
      <div id="tab-info">
        <h2 class="profile-section-title">👤 Informasi Akun</h2>
        <div class="form-group"><label class="form-label">Nama</label><input id="infoName" type="text" class="form-input" placeholder="Nama lengkap"></div>
        <div class="form-group"><label class="form-label">Email</label><input id="infoEmail" type="email" class="form-input" disabled></div>
        <button onclick="handleSaveInfo()" class="btn-primary" style="margin-top:8px;">💾 Simpan Perubahan</button>
      </div>
      <div id="tab-password" style="display:none;">
        <h2 class="profile-section-title">🔑 Ubah Password</h2>
        <div class="form-group"><label class="form-label">Password Saat Ini</label><div class="input-wrapper"><input id="pwCurrent" type="password" class="form-input" placeholder="••••••••"><button class="btn-eye" type="button">👁️</button></div></div>
        <div class="form-group"><label class="form-label">Password Baru</label><div class="input-wrapper"><input id="pwNew" type="password" class="form-input" placeholder="Min. 8 karakter + angka"><button class="btn-eye" type="button">👁️</button></div></div>
        <div class="form-group"><label class="form-label">Konfirmasi Password Baru</label><div class="input-wrapper"><input id="pwConfirm" type="password" class="form-input" placeholder="Ulangi password baru"><button class="btn-eye" type="button">👁️</button></div></div>
        <button id="pwSubmitBtn" onclick="handleChangePassword()" class="btn-primary" style="margin-top:8px;">🔒 Ubah Password</button>
      </div>
      <div id="tab-orders" style="display:none;">
        <h2 class="profile-section-title">📦 Pesanan Saya</h2>
        <div style="text-align:center;padding:48px 0;color:var(--text-muted)">
          <div style="font-size:3rem;margin-bottom:14px;opacity:.5">📦</div>
          <div style="font-weight:600;color:var(--text-light)">Belum ada pesanan</div>
          <div style="font-size:.85rem;margin-top:8px">Yuk mulai belanja kue favoritmu!</div>
        </div>
      </div>
      <div style="margin-top:32px;padding:22px;border:1.5px solid #ffc2d4;border-radius:16px;background:#fff5f8">
        <div style="font-weight:700;color:#be123c;margin-bottom:6px;font-size:.9rem">⚠️ Danger Zone</div>
        <p style="font-size:.83rem;color:var(--text-light);margin-bottom:14px">Menghapus akun bersifat permanen dan tidak bisa dibatalkan.</p>
        <button onclick="confirmDeleteAccount()" style="padding:10px 22px;background:white;color:#ef4444;border:1.5px solid #ffc2d4;border-radius:50px;font-weight:600;cursor:pointer;font-size:.83rem;font-family:'Poppins',sans-serif;transition:.2s">🗑️ Hapus Akun</button>
      </div>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.35);backdrop-filter:blur(4px);align-items:center;justify-content:center;">
  <div style="background:white;border-radius:24px;padding:32px;width:90%;max-width:380px;text-align:center;box-shadow:0 24px 64px rgba(0,0,0,.15);animation:ci .3s ease">
    <div style="font-size:3rem;margin-bottom:14px">⚠️</div>
    <h3 style="font-weight:700;margin-bottom:8px;font-family:'Playfair Display',serif;font-size:1.2rem">Hapus Akun?</h3>
    <p style="color:var(--text-light);font-size:.88rem;margin-bottom:22px">Tindakan ini permanen. Semua data kamu akan hilang.</p>
    <div style="display:flex;gap:10px">
      <button onclick="closeDeleteModal()" style="flex:1;padding:12px;background:#f3eff1;border:none;border-radius:50px;font-weight:600;cursor:pointer;font-family:'Poppins',sans-serif;transition:.2s">Batal</button>
      <button id="deleteConfirmBtn" onclick="handleDeleteAccount()" style="flex:1;padding:12px;background:linear-gradient(135deg,#ef4444,#dc2626);color:white;border:none;border-radius:50px;font-weight:600;cursor:pointer;font-family:'Poppins',sans-serif;transition:.2s">Ya, Hapus</button>
    </div>
  </div>
</div>

<!-- Cart Modal -->
<div id="cartModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.35);backdrop-filter:blur(4px);align-items:center;justify-content:center;">
  <div style="background:white;border-radius:24px;padding:28px;width:90%;max-width:400px;max-height:80vh;overflow-y:auto;position:relative;box-shadow:0 24px 64px rgba(0,0,0,.15)">
    <button onclick="closeCart()" style="position:absolute;top:14px;right:18px;background:none;border:none;font-size:1.3rem;cursor:pointer;color:#c9a8b8;transition:.2s">✕</button>
    <h2 style="font-size:1.15rem;font-weight:700;margin-bottom:18px;font-family:'Playfair Display',serif">🛒 Keranjang Belanja</h2>
    <div id="cartItems"></div>
    <div id="cartTotal" style="margin-top:18px;padding-top:14px;border-top:1px solid var(--pink-100);font-weight:700;font-size:1.05rem;color:var(--rose)"></div>
    <button onclick="checkout()" id="checkoutBtn" style="width:100%;margin-top:14px;padding:13px;background:linear-gradient(135deg,#f43f6b,#d92462);color:white;border:none;border-radius:50px;font-weight:600;font-size:.9rem;cursor:pointer;display:none;font-family:'Poppins',sans-serif;box-shadow:0 4px 16px rgba(244,63,107,.25)">Checkout 🎉</button>
  </div>
</div>

<!-- Profile Modal -->
<div id="profileModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.35);backdrop-filter:blur(4px);align-items:center;justify-content:center;">
  <div style="background:white;border-radius:24px;padding:28px;width:90%;max-width:420px;max-height:90vh;overflow-y:auto;position:relative;box-shadow:0 24px 64px rgba(0,0,0,.15)">
    <button onclick="closeProfileModal()" style="position:absolute;top:14px;right:18px;background:none;border:none;font-size:1.3rem;cursor:pointer;color:#c9a8b8">✕</button>
    <div id="profileModalContent"></div>
  </div>
</div>

<script src="/js/dashboard.js"></script>
<script>
function checkout() { alert('Fitur checkout akan segera hadir! 🎉'); }
// Navbar scroll effect
window.addEventListener('scroll', () => {
  document.getElementById('mainNav').classList.toggle('scrolled', window.scrollY > 10);
});
</script>

@endsection