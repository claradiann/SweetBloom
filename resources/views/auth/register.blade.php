<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar - SweetBloom</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="auth-layout">

  <!-- LEFT BANNER -->
  <div class="auth-banner">
    <div class="auth-banner-logo">
      SweetBloom ✦
      <span>Handcrafted Cakes</span>
    </div>

    <div class="auth-banner-cakes">
      <div class="cake-icon">🎂</div>
      <div class="cake-icon">🧁</div>
      <div class="cake-icon">🍰</div>
    </div>

    <p class="auth-banner-text">
      Bergabunglah dengan ribuan pelanggan bahagia kami dan dapatkan diskon 15% untuk pesanan pertama! 🎁
    </p>
  </div>

  <!-- RIGHT FORM -->
  <div class="auth-form-side">
    <div class="auth-card">

      <div class="auth-card-header">
        <h1>Buat akun baru 🎂</h1>
        <p>Daftar gratis dan mulai pesan kue!</p>
      </div>

      <form>

        <div class="form-group">
          <label class="form-label">Nama Lengkap</label>
          <input type="text" class="form-input" placeholder="Nama kamu">
        </div>

        <div class="form-group">
          <label class="form-label">Email</label>
          <div class="input-wrapper">
            <input type="email" class="form-input" placeholder="nama@email.com">
            <span class="input-icon">✉️</span>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Password</label>
          <div class="input-wrapper">
            <input type="password" class="form-input" placeholder="Min. 8 karakter">
            <span class="input-icon">👁️</span>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Konfirmasi Password</label>
          <div class="input-wrapper">
            <input type="password" class="form-input" placeholder="Ulangi password">
            <span class="input-icon">🔒</span>
          </div>
        </div>

        <div class="checkbox-group">
          <input type="checkbox">
          <label>
            Saya setuju dengan <a href="#">Syarat & Ketentuan</a> dan <a href="#">Kebijakan Privasi</a>
          </label>
        </div>

        <button class="btn-primary">Daftar Sekarang 🌸</button>

      </form>

      <div class="auth-footer">
        Sudah punya akun? <a href="{{ route('login') }}">Login di sini!</a>
      </div>

    </div>
  </div>

</div>

</body>
</html>