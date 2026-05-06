<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login – SweetBloom</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --pink:   #e8537a;
      --pink-d: #c2185b;
      --pink-l: #fce4ec;
      --cream:  #fff8f3;
      --dark:   #2d1a26;
      --mid:    #6b4157;
      --light:  #b07a92;
      --white:  #ffffff;
    }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--cream);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
    }

    /* petals */
    .petals { position: fixed; inset: 0; pointer-events: none; z-index: 0; overflow: hidden; }
    .petal {
      position: absolute; width: 10px; height: 15px;
      background: #f8bbd0; border-radius: 50% 50% 50% 0;
      opacity: 0; animation: fall linear infinite;
    }
    .petal:nth-child(1)  { left:5%;  animation-duration:8s;  animation-delay:0s;   }
    .petal:nth-child(2)  { left:15%; animation-duration:10s; animation-delay:2s;   }
    .petal:nth-child(3)  { left:30%; animation-duration:7s;  animation-delay:4s;   }
    .petal:nth-child(4)  { left:50%; animation-duration:9s;  animation-delay:1s;   }
    .petal:nth-child(5)  { left:65%; animation-duration:11s; animation-delay:3s;   }
    .petal:nth-child(6)  { left:80%; animation-duration:8s;  animation-delay:5s;   }
    .petal:nth-child(7)  { left:90%; animation-duration:6s;  animation-delay:2.5s; }
    @keyframes fall {
      0%   { transform: translateY(-20px) rotate(0deg);   opacity: 0; }
      10%  { opacity: .3; }
      90%  { opacity: .15; }
      100% { transform: translateY(105vh) rotate(360deg); opacity: 0; }
    }

    /* layout */
    .card {
      position: relative; z-index: 1;
      display: grid; grid-template-columns: 1fr 1fr;
      max-width: 900px; width: 100%;
      border-radius: 24px;
      overflow: hidden;
      box-shadow: 0 24px 64px rgba(45,26,38,.18);
    }

    /* banner */
    .banner {
      background: linear-gradient(145deg, #f48fb1 0%, #e8537a 45%, #c2185b 100%);
      padding: 56px 40px;
      display: flex; flex-direction: column;
      align-items: center; justify-content: center;
      gap: 28px; text-align: center;
      position: relative; overflow: hidden;
    }
    .banner::before {
      content:''; position:absolute; width:320px; height:320px;
      border-radius:50%; background:rgba(255,255,255,.08);
      top:-80px; right:-80px;
    }
    .banner-logo {
      font-family: 'Playfair Display', serif;
      font-size: 2rem; font-weight: 700;
      color: #fff; letter-spacing: -.5px;
    }
    .banner-logo span {
      display: block; font-size: .85rem;
      font-family: 'DM Sans', sans-serif; font-weight: 400;
      opacity: .85; letter-spacing: 2px; text-transform: uppercase; margin-top: 4px;
    }
    .banner-emojis { font-size: 3rem; display: flex; gap: 16px; }
    .banner-text { color: rgba(255,255,255,.9); font-size: .95rem; line-height: 1.7; max-width: 240px; }

    /* form side */
    .form-side {
      background: var(--white);
      padding: 52px 48px;
      display: flex; flex-direction: column; justify-content: center;
    }
    .form-header { margin-bottom: 32px; }
    .form-header h1 { font-family:'Playfair Display',serif; font-size:1.75rem; color:var(--dark); margin-bottom:6px; }
    .form-header p  { color: var(--light); font-size: .9rem; }

    /* alert */
    .alert-box { display:none; margin-bottom:16px; padding:12px 16px; border-radius:10px; font-size:.875rem; }
    .alert-box.show { display:flex; align-items:center; gap:10px; }
    .alert-error   { background:#fff0f0; color:#c0392b; border:1px solid #fecaca; }
    .alert-success { background:#f0fff4; color:#276749; border:1px solid #a8f0c6; }
    .alert-info    { background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; }

    /* form elements */
    .form-group { margin-bottom: 18px; }
    .form-label { display:block; font-size:.825rem; font-weight:600; color:var(--mid); margin-bottom:7px; }
    .input-wrap { position:relative; }
    .form-input {
      width:100%; padding:11px 40px 11px 14px;
      border:1.5px solid #f0d8e3; border-radius:10px;
      font-size:.9rem; color:var(--dark);
      background:#fff; outline:none; transition:.2s;
    }
    .form-input:focus { border-color:var(--pink); box-shadow:0 0 0 3px rgba(232,83,122,.12); }
    .input-icon {
      position:absolute; right:12px; top:50%; transform:translateY(-50%);
      font-size:1rem; cursor:pointer; user-select:none;
    }

    /* checkbox */
    .check-row { display:flex; align-items:center; gap:10px; margin-bottom:20px; }
    .check-row input[type=checkbox] { width:16px; height:16px; accent-color:var(--pink); cursor:pointer; }
    .check-row label { font-size:.85rem; color:var(--mid); cursor:pointer; }

    /* forgot */
    .forgot-link { display:block; text-align:right; font-size:.82rem; color:var(--pink); text-decoration:none; margin-top:-8px; margin-bottom:20px; }
    .forgot-link:hover { text-decoration:underline; }

    /* buttons */
    .btn-primary {
      width:100%; padding:13px;
      background:linear-gradient(135deg, var(--pink) 0%, var(--pink-d) 100%);
      color:#fff; font-size:.95rem; font-weight:600;
      border:none; border-radius:12px; cursor:pointer;
      transition:.2s; letter-spacing:.3px;
    }
    .btn-primary:hover   { opacity:.9; transform:translateY(-1px); box-shadow:0 6px 20px rgba(232,83,122,.35); }
    .btn-primary:active  { transform:translateY(0); }
    .btn-primary:disabled{ opacity:.65; cursor:not-allowed; transform:none; }

    /* divider */
    .divider { display:flex; align-items:center; gap:12px; margin:20px 0; }
    .divider-line { flex:1; height:1px; background:#f0d8e3; }
    .divider-text { font-size:.78rem; color:var(--light); white-space:nowrap; }

    /* footer */
    .form-footer { text-align:center; margin-top:24px; font-size:.875rem; color:var(--mid); }
    .form-footer a { color:var(--pink); font-weight:600; text-decoration:none; }
    .form-footer a:hover { text-decoration:underline; }

    /* responsive */
    @media (max-width: 680px) {
      .card { grid-template-columns: 1fr; }
      .banner { display: none; }
      .form-side { padding: 40px 28px; }
    }
  </style>
</head>
<body>

<div class="petals">
  <div class="petal"></div><div class="petal"></div><div class="petal"></div>
  <div class="petal"></div><div class="petal"></div><div class="petal"></div>
  <div class="petal"></div>
</div>

<div class="card">

  <!-- BANNER -->
  <div class="banner">
    <div class="banner-logo">SweetBloom ✦<span>Handcrafted Cakes</span></div>
    <div class="banner-emojis">🍰 🎂 🧁</div>
    <p class="banner-text">Setiap gigitan adalah keajaiban kecil yang dibuat dengan cinta. Temukan kue impianmu di sini! 🌸</p>
  </div>

  <!-- FORM SIDE -->
  <div class="form-side">

    <div class="form-header">
      <h1>Selamat datang! 🌸</h1>
      <p>Masuk untuk melanjutkan pesananmu</p>
    </div>

    <!-- Alert -->
    <div id="alertBox" class="alert-box"></div>

    <!-- Query param messages -->
    @if(request('success') === 'confirmed')
      <div class="alert-box show alert-success">✅ Email berhasil dikonfirmasi! Silakan login.</div>
    @elseif(request('error') === 'token_expired')
      <div class="alert-box show alert-error">❌ Link konfirmasi sudah kadaluarsa. Minta link baru.</div>
    @elseif(request('error') === 'invalid_token')
      <div class="alert-box show alert-error">❌ Link konfirmasi tidak valid.</div>
    @elseif(request('info') === 'already_confirmed')
      <div class="alert-box show alert-info">ℹ️ Akun sudah dikonfirmasi. Silakan login.</div>
    @endif

    <form id="loginForm" onsubmit="handleLogin(event)">
      @csrf

      <div class="form-group">
        <label class="form-label">Email</label>
        <div class="input-wrap">
          <input type="email" id="email" class="form-input" placeholder="nama@email.com" required>
          <span class="input-icon">✉️</span>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Password</label>
        <div class="input-wrap">
          <input type="password" id="password" class="form-input" placeholder="Masukkan password" required>
          <span class="input-icon" id="togglePass" onclick="togglePassword('password','togglePass')" style="cursor:pointer">👁️</span>
        </div>
      </div>

      <a href="{{ route('forgot-password') }}" class="forgot-link">Lupa password?</a>

      <div class="check-row">
        <input type="checkbox" id="remember">
        <label for="remember">Ingat saya</label>
      </div>

      <button type="submit" class="btn-primary" id="loginBtn">Masuk Sekarang 🌸</button>
    </form>

    <div class="form-footer">
      Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang!</a>
    </div>

  </div>
</div>

<script>
function togglePassword(inputId, iconId) {
  const input = document.getElementById(inputId);
  const icon  = document.getElementById(iconId);
  if (input.type === 'password') { input.type = 'text'; icon.textContent = '🙈'; }
  else                           { input.type = 'password'; icon.textContent = '👁️'; }
}

function showAlert(type, msg) {
  const box = document.getElementById('alertBox');
  box.className = 'alert-box show alert-' + type;
  box.textContent = msg;
}

async function handleLogin(e) {
  e.preventDefault();
  const btn = document.getElementById('loginBtn');
  btn.disabled = true; btn.textContent = 'Memproses...';

  try {
    const res = await fetch('/api/auth/login', {
      method: 'POST',
      credentials: 'include',          // ← tambah ini
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        email:    document.getElementById('email').value,
        password: document.getElementById('password').value
      })
    });

    const data = await res.json();

    if (data.success) {
      showAlert('success', data.message);
      setTimeout(() => { window.location.href = '/dashboard'; }, 800);
    } else {
      showAlert('error', data.message);
    }
  } catch (err) {
    showAlert('error', 'Terjadi kesalahan. Coba lagi.');
  } finally {
    btn.disabled = false; btn.textContent = 'Masuk Sekarang 🌸';
  }
}
</script>

</body>
</html>