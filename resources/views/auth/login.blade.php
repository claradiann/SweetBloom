<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login – SweetBloom</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    :root{--rose:#f43f6b;--pink-d:#d92462;--pink-100:#ffe0eb;--pink-50:#fff5f8;--cream:#fffaf6;--dark:#1a0a12;--mid:#5c3347;--light:#a07a8e;--muted:#c9a8b8;--white:#fff}
    body{font-family:'Poppins',sans-serif;background:var(--cream);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;position:relative;overflow:hidden}

    /* animated bg */
    .bg-blobs{position:fixed;inset:0;z-index:0;pointer-events:none;overflow:hidden}
    .blob{position:absolute;border-radius:50%;filter:blur(80px);opacity:.3;animation:blobFloat 20s ease-in-out infinite}
    .blob-1{width:400px;height:400px;background:#ffc2d4;top:-100px;left:-100px;animation-delay:0s}
    .blob-2{width:350px;height:350px;background:#f0e6ff;bottom:-80px;right:-80px;animation-delay:-7s}
    .blob-3{width:250px;height:250px;background:#ffe0eb;top:40%;left:60%;animation-delay:-14s}
    @keyframes blobFloat{0%,100%{transform:translate(0,0) scale(1)}33%{transform:translate(30px,-40px) scale(1.05)}66%{transform:translate(-20px,30px) scale(.95)}}

    .petals{position:fixed;inset:0;pointer-events:none;z-index:0;overflow:hidden}
    .petal{position:absolute;width:10px;height:14px;background:var(--pink-100);border-radius:50% 50% 50% 0;opacity:0;animation:fall linear infinite}
    .petal:nth-child(1){left:8%;animation-duration:9s;animation-delay:0s}
    .petal:nth-child(2){left:22%;animation-duration:11s;animation-delay:2s}
    .petal:nth-child(3){left:45%;animation-duration:8s;animation-delay:4s}
    .petal:nth-child(4){left:65%;animation-duration:10s;animation-delay:1s}
    .petal:nth-child(5){left:80%;animation-duration:12s;animation-delay:3s}
    .petal:nth-child(6){left:92%;animation-duration:7s;animation-delay:5s}
    @keyframes fall{0%{transform:translateY(-20px) rotate(0deg);opacity:0}10%{opacity:.25}90%{opacity:.1}100%{transform:translateY(105vh) rotate(360deg);opacity:0}}

    .card{position:relative;z-index:1;display:grid;grid-template-columns:1fr 1fr;max-width:880px;width:100%;border-radius:28px;overflow:hidden;box-shadow:0 32px 80px rgba(26,10,18,.12);animation:cardIn .6s ease}
    @keyframes cardIn{from{opacity:0;transform:translateY(20px) scale(.98)}to{opacity:1;transform:translateY(0) scale(1)}}

    .banner{background:linear-gradient(160deg,#ffb6c8 0%,#f43f6b 50%,#d92462 100%);padding:52px 36px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:24px;text-align:center;position:relative;overflow:hidden}
    .banner::before{content:'';position:absolute;width:300px;height:300px;border-radius:50%;background:rgba(255,255,255,.08);top:-80px;right:-80px}
    .banner::after{content:'';position:absolute;width:200px;height:200px;border-radius:50%;background:rgba(255,255,255,.05);bottom:-60px;left:-60px}
    .banner-logo{font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;color:white;position:relative;z-index:1}
    .banner-logo small{display:block;font-size:.78rem;font-family:'Poppins',sans-serif;font-weight:400;opacity:.85;letter-spacing:2.5px;text-transform:uppercase;margin-top:6px}
    .banner-emojis{font-size:2.8rem;display:flex;gap:14px;position:relative;z-index:1}
    .banner-emojis span{animation:bobble 3s ease-in-out infinite}
    .banner-emojis span:nth-child(2){animation-delay:.5s}
    .banner-emojis span:nth-child(3){animation-delay:1s}
    @keyframes bobble{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}
    .banner-text{color:rgba(255,255,255,.9);font-size:.88rem;line-height:1.7;max-width:220px;position:relative;z-index:1}
    .banner-features{display:flex;flex-direction:column;gap:8px;margin-top:8px;position:relative;z-index:1}
    .banner-feature{display:flex;align-items:center;gap:8px;background:rgba(255,255,255,.15);backdrop-filter:blur(10px);padding:8px 16px;border-radius:50px;font-size:.78rem;color:white;font-weight:500;border:1px solid rgba(255,255,255,.2)}

    .form-side{background:var(--white);padding:48px 44px;display:flex;flex-direction:column;justify-content:center}
    .form-header{margin-bottom:28px}
    .form-header h1{font-family:'Playfair Display',serif;font-size:1.7rem;color:var(--dark);margin-bottom:6px}
    .form-header p{color:var(--light);font-size:.88rem}

    .alert-box{display:none;margin-bottom:14px;padding:12px 16px;border-radius:10px;font-size:.84rem;font-weight:500}
    .alert-box.show{display:flex;align-items:center;gap:8px;animation:slideDown .3s ease}
    .alert-error{background:#fff0f3;color:#be123c;border:1px solid #ffc2d4}
    .alert-success{background:#ecfdf5;color:#166534;border:1px solid #a7f3d0}
    .alert-info{background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe}
    @keyframes slideDown{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:translateY(0)}}

    .form-group{margin-bottom:16px}
    .form-label{display:block;font-size:.8rem;font-weight:600;color:var(--mid);margin-bottom:7px;letter-spacing:.3px}
    .input-wrap{position:relative}
    .form-input{width:100%;padding:12px 42px 12px 16px;border:1.5px solid var(--pink-100);border-radius:12px;font-size:.9rem;color:var(--dark);background:var(--white);outline:none;transition:.25s;font-family:'Poppins',sans-serif}
    .form-input:focus{border-color:var(--rose);box-shadow:0 0 0 3px rgba(244,63,107,.1)}
    .form-input::placeholder{color:var(--muted)}
    .input-icon{position:absolute;right:14px;top:50%;transform:translateY(-50%);font-size:1rem;cursor:pointer;transition:.2s}
    .input-icon:hover{transform:translateY(-50%) scale(1.1)}

    .forgot-link{display:block;text-align:right;font-size:.8rem;color:var(--rose);margin-top:-6px;margin-bottom:16px;font-weight:500;transition:.2s}
    .forgot-link:hover{color:var(--pink-d)}

    .check-row{display:flex;align-items:center;gap:10px;margin-bottom:18px}
    .check-row input[type=checkbox]{width:17px;height:17px;accent-color:var(--rose);cursor:pointer}
    .check-row label{font-size:.83rem;color:var(--mid);cursor:pointer}

    .btn-primary{width:100%;padding:14px;background:linear-gradient(135deg,var(--rose),var(--pink-d));color:white;font-size:.9rem;font-weight:600;border:none;border-radius:12px;cursor:pointer;transition:.25s;font-family:'Poppins',sans-serif;box-shadow:0 4px 16px rgba(244,63,107,.25);position:relative;overflow:hidden}
    .btn-primary::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,rgba(255,255,255,.15),transparent);opacity:0;transition:.25s}
    .btn-primary:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(244,63,107,.35)}
    .btn-primary:hover::before{opacity:1}
    .btn-primary:active{transform:translateY(0)}
    .btn-primary:disabled{opacity:.6;cursor:not-allowed;transform:none}

    .form-footer{text-align:center;margin-top:24px;font-size:.85rem;color:var(--mid)}
    .form-footer a{color:var(--rose);font-weight:600;text-decoration:none;transition:.2s}
    .form-footer a:hover{color:var(--pink-d)}

    @media(max-width:680px){
      .card{grid-template-columns:1fr;max-width:420px}
      .banner{display:none}
      .form-side{padding:36px 24px}
    }
  </style>
</head>
<body>

<div class="bg-blobs"><div class="blob blob-1"></div><div class="blob blob-2"></div><div class="blob blob-3"></div></div>
<div class="petals"><div class="petal"></div><div class="petal"></div><div class="petal"></div><div class="petal"></div><div class="petal"></div><div class="petal"></div></div>

<div class="card">
  <div class="banner">
    <div class="banner-logo">SweetBloom<small>Handcrafted Cakes</small></div>
    <div class="banner-emojis"><span>🍰</span><span>🎂</span><span>🧁</span></div>
    <p class="banner-text">Setiap gigitan adalah keajaiban kecil yang dibuat dengan cinta 🌸</p>
    <div class="banner-features">
      <div class="banner-feature">✨ 50+ Varian Kue</div>
      <div class="banner-feature">🚚 Pengiriman Cepat</div>
      <div class="banner-feature">💝 Dibuat dengan Cinta</div>
    </div>
  </div>

  <div class="form-side">
    <div class="form-header">
      <h1>Selamat Datang! 🌸</h1>
      <p>Masuk untuk melanjutkan pesananmu</p>
    </div>

    <div id="alertBox" class="alert-box"></div>

    @if(request('success') === 'confirmed')
      <div class="alert-box show alert-success">✅ Email berhasil dikonfirmasi! Silakan login.</div>
    @elseif(request('error') === 'token_expired')
      <div class="alert-box show alert-error">❌ Link konfirmasi sudah kadaluarsa.</div>
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
  const icon = document.getElementById(iconId);
  if (input.type === 'password') { input.type = 'text'; icon.textContent = '🙈'; }
  else { input.type = 'password'; icon.textContent = '👁️'; }
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
      method: 'POST', credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email: document.getElementById('email').value, password: document.getElementById('password').value })
    });
    const data = await res.json();
    if (data.success) { showAlert('success', data.message); setTimeout(() => { window.location.href = '/dashboard'; }, 800); }
    else { showAlert('error', data.message); }
  } catch (err) { showAlert('error', 'Terjadi kesalahan. Coba lagi.'); }
  finally { btn.disabled = false; btn.textContent = 'Masuk Sekarang 🌸'; }
}
</script>
</body>
</html>