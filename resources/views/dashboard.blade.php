@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="/css/style.css">

<!-- Navbar -->
<nav class="navbar">
  <a href="#" class="nav-logo">Sweet<span>Bloom</span> ✦</a>
  <ul class="nav-links">
    <li><a href="#" class="active">Home</a></li>
    <li><a href="#menu">Menu</a></li>
    <li><a href="#" onclick="showSection('profile')">Profile</a></li>
  </ul>
  <div class="nav-actions">
    <button class="nav-cart">🛒 <span class="cart-badge">0</span></button>
    <button class="nav-avatar" onclick="showSection('profile')" id="navAvatar">?</button>
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
      <a href="#" class="section-link">Lihat semua →</a>
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
        <li><a href="#" class="active" onclick="switchTab('info',this)">👤 Informasi Akun</a></li>
        <li><a href="#" onclick="switchTab('password',this)">🔑 Ubah Password</a></li>
        <li><a href="#" onclick="switchTab('orders',this)">📦 Pesanan Saya