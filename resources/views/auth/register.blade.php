<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar – SweetBloom</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    :root{--rose:#f43f6b;--pink-d:#d92462;--pink-100:#ffe0eb;--pink-50:#fff5f8;--cream:#fffaf6;--dark:#1a0a12;--mid:#5c3347;--light:#a07a8e;--muted:#c9a8b8;--white:#fff}
    body{font-family:'Poppins',sans-serif;background:var(--cream);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;position:relative;overflow:hidden}

    .bg-blobs{position:fixed;inset:0;z-index:0;pointer-events:none;overflow:hidden}
    .blob{position:absolute;border-radius:50%;filter:blur(80px);opacity:.3;animation:blobFloat 20s ease-in-out infinite}
    .blob-1{width:400px;height:400px;background:#ffc2d4;top:-100px;right:-100px;animation-delay:0s}
    .blob-2{width:300px;height:300px;background:#f0e6ff;bottom:-60px;left:-60px;animation-delay:-7s}
    @keyframes blobFloat{0%,100%{transform:translate(0,0) scale(1)}33%{transform:translate(30px,-40px) scale(1.05)}66%{transform:translate(-20px,30px) scale(.95)}}

    .petals{position:fixed;inset:0;pointer-events:none;z-index:0;overflow:hidden}
    .petal{position:absolute;width:10px;height:14px;background:var(--pink-100);border-radius:50% 50% 50% 0;opacity:0;animation:fall linear infinite}
    .petal:nth-child(1){left:5%;animation-duration:8s;animation-delay:0s}
    .petal:nth-child(2){left:20%;animation-duration:10s;animation-delay:1s}
    .petal:nth-child(3){left:40%;animation-duration:7s;animation-delay:3s}
    .petal:nth-child(4){left:60%;animation-duration:9s;animation-delay:2s}
    .petal:nth-child(5){left:80%;animation-duration:11s;animation-delay:4s}
    @keyframes fall{0%{transform:translateY(-20px) rotate(0deg);opacity:0}10%{opacity:.25}90%{opacity:.1}100%{transform:translateY(105vh) rotate(360deg);opacity:0}}

    .card{position:relative;z-index:1;display:grid;grid-template-columns:1fr 1fr;max-width:880px;width:100%;border-radius:28px;overflow:hidden;box-shadow:0 32px 80px rgba(26,10,18,.12);animation:cardIn .6s ease}
    @keyframes cardIn{from{opacity:0;transform:translateY(20px) scale(.98)}to{opacity:1;transform:translateY(0) scale(1)}}

    .banner{background:linear-gradient(160deg,#ffb6c8 0%,#f43f6b 50%,#d92462 100%);padding:48px 32px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:20px;text-align:center;position:relative;overflow:hidden}
    .banner::before{content:'';position:absolute;width:300px;height:300px;border-radius:50%;background:rgba(255,255,255,.08);top:-80px;right:-80px}
    .banner-logo{font-family:'Playfair Display',serif;font-size:1.9rem;font-weight:700;color:white;position:relative;z-index:1}
    .banner-logo small{display:block;font-size:.75rem;font-family:'Poppins',sans-serif;font-weight:400;opacity:.85;letter-spacing:2.5px;text-transform:uppercase;margin-top:6px}
    .banner-emojis{font-size:2.6rem;display:flex;gap:12px;position:relative;z-index:1}
    .banner-emojis span{animation:bobble 3s ease-in-out infinite}
    .banner-emojis span:nth-child(2){animation-delay:.5s}
    .banner-emojis span:nth-child(3){animation-delay:1s}
    @keyframes bobble{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}
    .banner-badge{background:rgba(255,255,255,.18);backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,.25);border-radius:50px;padding:10px 20px;color:white;font-size:.82rem;font-weight:600;position:relative;z-index:1}
    .banner-text{color:rgba(255,255,255,.9);font-size:.85rem;line-height:1.7;max-width:220px;position:relative;z-index:1}

    .form-side{background:var(--white);padding:40px 40px;display:flex;flex-direction:column;justify-content:center;overflow-y:auto;max-height:100vh}
    .form-header{margin-bottom:22px}
    .form-header h1{font-family:'Playfair Display',serif;font-size:1.6rem;color:var(--dark);margin-bottom:5px}
    .form-header p{color:var(--light);font-size:.87rem}

    .alert-box{display:none;margin-bottom:12px;padding:12px 16px;border-radius:10px;font-size:.84rem;font-weight:500}
    .alert-box.show{display:flex;align-items:center;gap:8px;animation:slideDown .3s ease}
    .alert-error{background:#fff0f3;color:#be123c;border:1px solid #ffc2d4}
    .alert-success{background:#ecfdf5;color:#166534;border:1px solid #a7f3d0}
    @keyframes slideDown{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:translateY(0)}}

    .form-group{margin-bottom:14px}
    .form-label{display:block;font-size:.78rem;font-weight:600;color:var(--mid);margin-bottom:6px;letter-spacing:.3px}
    .input-wrap{position:relative}
    .form-input{width:100%;padding:11px 40px 11px 15px;border:1.5px solid var(--pink-100);border-radius:12px;font-size:.88rem;color:var(--dark);background:var(--white);outline:none;transition:.25s;font-family:'Poppins',sans-serif}
    .form-input:focus{border-color:var(--rose);box-shadow:0 0 0 3px rgba(244,63,107,.1)}
    .form-input::placeholder{color:var(--muted)}
    .input-icon{position:absolute;right:12px;top:50%;transform:translateY(-50%);font-size:1rem;cursor:pointer}

    .strength-bar{display:flex;gap:4px;margin-top:6px}
    .strength-seg{height:4px;flex:1;border-radius:50px;background:#e8e0e4;transition:.3s}
    .strength-seg.weak{background:#ef4444}
    .strength-seg.medium{background:#f59e0b}
    .strength-seg.strong{background:#22c55e}
    .strength-label{font-size:.72rem;color:var(--light);margin-top:3px}

    .check-row{display:flex;align-items:flex-start;gap:10px;margin-bottom:18px}
    .check-row input[type=checkbox]{width:17px;height:17px;accent-color:var(--rose);cursor:pointer;margin-top:2px;flex-shrink:0}
    .check-row label{font-size:.8rem;color:var(--mid);cursor:pointer;line-height:1.5}
    .check-row a{color:var(--rose);text-decoration:none;font-weight:500}
    .check-row a:hover{text-decoration:underline}

    .btn-primary{width:100%;padding:13px;background:linear-gradient(135deg,var(--rose),var(--pink-d));color:white;font-size:.9rem;font-weight:600;border:none;border-radius:12px;cursor:pointer;transition:.25s;font-family:'Poppins',sans-serif;box-shadow:0 4px 16px rgba(244,63,107,.25)}
    .btn-primary:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(244,63,107,.35)}
    .btn-primary:active{transform:translateY(0)}
    .btn-primary:disabled{opacity:.6;cursor:not-allowed;transform:none}

    .form-footer{text-align:center;margin-top:18px;font-size:.85rem;color:var(--mid)}
    .form-footer a{color:var(--rose);font-weight:600;text-decoration:none}
    .form-footer a:hover{color:var(--pink-d)}

    @media(max-width:680px){.card{grid-template-columns:1fr;max-width:420px}.banner{display:none}.form-side{padding:32px 22px}}
  </style>
</head>
<body>

<div class="bg-blobs"><div class="blob blob-1"></div><div class="blob blob-2"></div></div>
<div class="petals"><div class="petal"></div><div class="petal"></div><div class="petal"></div><div class="petal"></div><div class="petal"></div></div>

<div class="card">
  <div class="banner">
    <div class="banner-logo">SweetBloom<small>Handcrafted Cakes</small></div>
    <div class="banner-emojis"><span>🎂</span><span>🧁</span><span>🍰</span></div>
    <div class="banner-badge">🎁 Diskon 15% pesanan pertama!</div>
    <p class="banner-text">Bergabunglah dengan ribuan pelanggan bahagia kami! 💕</p>
  </div>

  <div class="form-side">
    <div class="form-header">
      <h1>Buat Akun Baru 🎂</h1>
      <p>Daftar gratis dan mulai pesan kue!</p>
    </div>

    <div id="alertBox" class="alert-box"></div>

    <form id="registerForm" onsubmit="handleRegister(event)">
      @csrf
      <div class="form-group">
        <label class="form-label">Nama Lengkap</label>
        <div class="input-wrap">
          <input type="text" id="name" class="form-input" placeholder="Nama kamu" required>
          <span class="input-icon">👤</span>
        </div>
      </div>
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
          <input type="password" id="password" class="form-input" placeholder="Min. 8 karakter + angka" required oninput="updateStrength(this.value)">
          <span class="input-icon" onclick="togglePassword('password',this)" style="cursor:pointer">👁️</span>
        </div>
        <div class="strength-bar"><div class="strength-seg" id="s1"></div><div class="strength-seg" id="s2"></div><div class="strength-seg" id="s3"></div><div class="strength-seg" id="s4"></div></div>
        <div class="strength-label" id="strengthLabel"></div>
      </div>
      <div class="form-group">
        <label class="form-label">Konfirmasi Password</label>
        <div class="input-wrap">
          <input type="password" id="confirmPassword" class="form-input" placeholder="Ulangi password" required>
          <span class="input-icon" onclick="togglePassword('confirmPassword',this)" style="cursor:pointer">🔒</span>
        </div>
      </div>
      <div class="check-row">
        <input type="checkbox" id="agree" required>
        <label for="agree">Saya setuju dengan <a href="#">Syarat & Ketentuan</a> dan <a href="#">Kebijakan Privasi</a></label>
      </div>
      <button type="submit" class="btn-primary" id="registerBtn">Daftar Sekarang 🌸</button>
    </form>

    <div class="form-footer">Sudah punya akun? <a href="{{ route('login') }}">Login di sini!</a></div>
  </div>
</div>

<script>
function togglePassword(inputId, icon) {
  const inp = document.getElementById(inputId);
  if (inp.type === 'password') { inp.type = 'text'; icon.textContent = '🙈'; }
  else { inp.type = 'password'; icon.textContent = '👁️'; }
}
function updateStrength(val) {
  let score = 0;
  if (val.length >= 8) score++;
  if (/[A-Z]/.test(val)) score++;
  if (/[0-9]/.test(val)) score++;
  if (/[^A-Za-z0-9]/.test(val)) score++;
  const segs = ['s1','s2','s3','s4'];
  const cls = ['','weak','medium','strong','strong'];
  const labels = ['','Lemah','Cukup','Kuat','Sangat Kuat'];
  segs.forEach((id,i) => {
    document.getElementById(id).className = 'strength-seg' + (i < score ? ' ' + cls[score] : '');
  });
  document.getElementById('strengthLabel').textContent = val.length > 0 ? labels[score] : '';
}
function showAlert(type, msg) {
  const box = document.getElementById('alertBox');
  box.className = 'alert-box show alert-' + type;
  box.textContent = msg;
  box.scrollIntoView({ behavior:'smooth', block:'nearest' });
}
async function handleRegister(e) {
  e.preventDefault();
  const pass = document.getElementById('password').value;
  const confirm = document.getElementById('confirmPassword').value;
  if (pass !== confirm) { showAlert('error', '❌ Konfirmasi password tidak cocok.'); return; }
  const btn = document.getElementById('registerBtn');
  btn.disabled = true; btn.textContent = 'Mendaftarkan...';
  try {
    const res = await fetch('/api/auth/register', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
      body: JSON.stringify({ name: document.getElementById('name').value, email: document.getElementById('email').value, password: pass, confirmPassword: confirm })
    });
    const data = await res.json();
    if (data.success) { showAlert('success', '✅ ' + data.message); document.getElementById('registerForm').reset(); }
    else { showAlert('error', '❌ ' + data.message); }
  } catch (err) { showAlert('error', '❌ Terjadi kesalahan. Coba lagi.'); }
  finally { btn.disabled = false; btn.textContent = 'Daftar Sekarang 🌸'; }
}
</script>
</body>
</html>