<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login - SweetBloom</title>
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
      <div class="cake-icon">🍰</div>
      <div class="cake-icon">🎂</div>
      <div class="cake-icon">🧁</div>
    </div>

    <p class="auth-banner-text">
      Setiap gigitan adalah keajaiban kecil yang dibuat dengan cinta. Temukan kue impianmu di sini! 🌸
    </p>
  </div>

  <!-- RIGHT FORM -->
  <div class="auth-form-side">
    <div class="auth-card">

      <div class="auth-card-header">
        <h1>Selamat datang kembali! 🌸</h1>
        <p>Masuk untuk melanjutkan pesananmu</p>
      </div>

      <form>

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
            <input type="password" class="form-input" placeholder="Masukkan password">
            <span class="input-icon">👁️</span>
          </div>
        </div>

        <div class="checkbox-group">
          <input type="checkbox">
          <label>Ingat saya</label>
        </div>

        <button class="btn-primary">Masuk Sekarang 🌸</button>

        <div class="divider">
          <div class="divider-line"></div>
          <div class="divider-text">atau</div>
          <div class="divider-line"></div>
        </div>

        <button type="button" class="btn-outline">
          🔵 Masuk dengan Google
        </button>

      </form>

      <div class="auth-footer">
        Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang!</a>
      </div>

    </div>
  </div>

</div>

</body>
</html>