@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="/css/style.css">

<!-- Navbar -->
<nav class="navbar">
  <a href="#" class="nav-logo">Sweet<span>Bloom</span> ✦</a>
  <ul class="nav-links">
    <li><a href="#" class="active">Home</a></li>
    <li><a href="#menu">Menu</a></li>
    <li><a href="#">Profile</a></li>
  </ul>
  <div class="nav-actions">
    <button class="nav-cart">🛒 <span class="cart-badge">0</span></button>
    <button class="nav-avatar" id="navAvatar">?</button>
  </div>
</nav>

<!-- HOME SECTION -->
<div id="section-home">

  <!-- Hero -->
  <div class="hero">
    <div>
      <div class="hero-badge">🌸 Handcrafted with Love</div>
      <h1 class="hero-title">Freshly Baked<br><span>Happiness</span> 🍓</h1>
      <p class="hero-subtitle">Kue handmade dengan cinta untuk setiap momen spesialmu. Dibuat segar setiap hari!</p>
      <div class="hero-actions">
        <button class="btn-hero-primary" onclick="document.getElementById('menu').scrollIntoView({behavior:'smooth'})">Explore Menu 🎂</button>
        <button class="btn-hero-secondary">Lihat Promo</button>
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
      <a href="/product" class="section-link">Lihat semua →</a>
    </div>
    <div class="products-grid">
      <div class="product-card">
        <div class="product-card-image">
          🍓
          <span class="product-badge">BEST</span>
          <button class="product-wishlist">🤍</button>
        </div>
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
        <div class="product-card-image">
          🍫
          <button class="product-wishlist">🤍</button>
        </div>
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
        <div class="product-card-image">
          🎂
          <span class="product-badge">NEW</span>
          <button class="product-wishlist">🤍</button>
        </div>
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
        <div class="product-card-image">
          🧁
          <button class="product-wishlist">🤍</button>
        </div>
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

</div><!-- end section-home -->

<!-- PROFILE SECTION -->
<div id="section-profile" style="display:none; padding: 40px 0 60px;">
  <div class="profile-layout">

    <!-- Sidebar -->
    <aside class="profile-sidebar">
      <div class="avatar-circle">
        <span id="avatarEmoji">🌸</span>
        <span class="avatar-edit">✏️</span>
      </div>
      <div class="profile-name" id="sidebarName">Loading...</div>
      <div class="profile-email-text" id="sidebarEmail"></div>
      <span class="profile-badge-tag" id="sidebarRole">Customer</span>
      <ul class="profile-nav" style="margin-top:28px">
        <li><a href="#" class="active" onclick="switchTab('info',this);return false;">👤 Informasi Akun</a></li>
        <li><a href="#" onclick="switchTab('password',this);return false;">🔑 Ubah Password</a></li>
        <li><a href="#" onclick="switchTab('orders',this);return false;">📦 Pesanan Saya</a></li>
        <li><a href="#" onclick="handleLogout();return false;" style="color:#e53935;">🚪 Logout</a></li>
      </ul>
    </aside>

    <!-- Main Content -->
    <div class="profile-main">
      <div id="profileAlert" class="hidden"></div>

      <!-- Tab: Info -->
      <div id="tab-info">
        <h2 class="profile-section-title">👤 Informasi Akun</h2>
        <div class="profile-form">
          <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" id="infoName" placeholder="Nama kamu">
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" id="infoEmail" placeholder="Email" disabled style="opacity:.6;cursor:not-allowed;">
          </div>
          <button class="btn-save" onclick="handleSaveInfo()">💾 Simpan Perubahan</button>
        </div>

        <hr style="margin:40px 0;border:none;border-top:1px solid #fce4ec;">

        <h3 style="font-size:1rem;font-weight:700;color:#e53935;margin-bottom:16px;">⚠️ Danger Zone</h3>
        <button onclick="confirmDeleteAccount()" style="padding:12px 24px;background:#fff;color:#e53935;border:1.5px solid #ffcdd2;border-radius:50px;font-weight:700;cursor:pointer;">🗑️ Hapus Akun</button>
      </div>

      <!-- Tab: Password -->
      <div id="tab-password" style="display:none;">
        <h2 class="profile-section-title">🔑 Ubah Password</h2>
        <div class="profile-form">
          <div class="form-group">
            <label>Password Saat Ini</label>
            <div class="input-wrapper">
              <input type="password" id="pwCurrent" placeholder="••••••••">
              <button type="button" class="btn-eye">👁️</button>
            </div>
          </div>
          <div class="form-group">
            <label>Password Baru</label>
            <div class="input-wrapper">
              <input type="password" id="pwNew" placeholder="Min. 8 karakter + angka">
              <button type="button" class="btn-eye">👁️</button>
            </div>
          </div>
          <div class="form-group">
            <label>Konfirmasi Password Baru</label>
            <div class="input-wrapper">
              <input type="password" id="pwConfirm" placeholder="Ulangi password baru">
              <button type="button" class="btn-eye">👁️</button>
            </div>
          </div>
          <button class="btn-save" id="pwSubmitBtn" onclick="handleChangePassword()">🔒 Ubah Password</button>
        </div>
      </div>

      <!-- Tab: Orders -->
      <div id="tab-orders" style="display:none;">
        <h2 class="profile-section-title">📦 Pesanan Saya</h2>
        <div style="text-align:center;padding:60px 0;color:#bbb;">
          <div style="font-size:3rem;margin-bottom:16px;">📦</div>
          <div style="font-size:1.1rem;font-weight:600;">Belum ada pesanan</div>
          <div style="font-size:.9rem;margin-top:8px;">Yuk, mulai belanja kue favoritmu!</div>
          <button onclick="showSection('home')" style="margin-top:24px;padding:12px 32px;background:linear-gradient(135deg,#e91e8c,#f06292);color:#fff;border:none;border-radius:50px;font-weight:700;cursor:pointer;">Belanja Sekarang 🎂</button>
        </div>
      </div>

    </div><!-- end profile-main -->
  </div><!-- end profile-layout -->
</div><!-- end section-profile -->

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

<!-- Delete Account Modal -->
<div id="deleteModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:24px;padding:32px;width:90%;max-width:400px;text-align:center;">
    <div style="font-size:3rem;margin-bottom:16px;">⚠️</div>
    <h3 style="font-size:1.2rem;font-weight:800;margin-bottom:8px;">Hapus Akun?</h3>
    <p style="color:#888;font-size:.9rem;margin-bottom:24px;">Tindakan ini tidak bisa dibatalkan. Semua data akun kamu akan dihapus permanen.</p>
    <div style="display:flex;gap:12px;">
      <button onclick="closeDeleteModal()" style="flex:1;padding:12px;background:#f5f5f5;border:none;border-radius:50px;font-weight:700;cursor:pointer;">Batal</button>
      <button id="deleteConfirmBtn" onclick="handleDeleteAccount()" style="flex:1;padding:12px;background:#e53935;color:#fff;border:none;border-radius:50px;font-weight:700;cursor:pointer;">Ya, Hapus Akun</button>
    </div>
  </div>
</div>

<script src="/js/dashboard.js"></script>

@endsection