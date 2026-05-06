<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar – SweetBloom</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --pink:#e8537a; --pink-d:#c2185b; --pink-l:#fce4ec;
      --cream:#fff8f3; --dark:#2d1a26; --mid:#6b4157; --light:#b07a92; --white:#ffffff;
    }
    body { font-family:'DM Sans',sans-serif; background:var(--cream); min-height:100vh; display:flex; align-items:center; justify-content:center; padding:24px; }

    .petals { position:fixed; inset:0; pointer-events:none; z-index:0; overflow:hidden; }
    .petal { position:absolute; width:10px; height:15px; background:#f8bbd0; border-radius:50% 50% 50% 0; opacity:0; animation:fall linear infinite; }
    .petal:nth-child(1){left:5%;animation-duration:8s;animation-delay:0s}
    .petal:nth-child(2){left:20%;animation-duration:10s;animation-delay:1s}
    .petal:nth-child(3){left:40%;animation-duration:7s;animation-delay:3s}
    .petal:nth-child(4){left:60%;animation-duration:9s;animation-delay:2s}
    .petal:nth-child(5){left:75%;animation-duration:11s;animation-delay:4s}
    .petal:nth-child(6){left:88%;animation-duration:8s;animation-delay:5.5s}
    @keyframes fall { 0%{transform:translateY(-20px) rotate(0deg);opacity:0} 10%{opacity:.3} 90%{opacity:.15} 100%{transform:translateY(105vh) rotate(360deg);opacity:0} }

    .card { position:relative; z-index:1; display:grid; grid-template-columns:1fr 1fr; max-width:900px; width:100%; border-radius:24px; overflow:hidden; box-shadow:0 24px 64px rgba(45,26,38,.18); }

    .banner { background:linear-gradient(145deg,#f48fb1 0%,#e8537a 45%,#c2185b 100%); padding:56px 40px; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:28px; text-align:center; position:relative; overflow:hidden; }
    .banner::before { content:''; position:absolute; width:320px; height:320px; border-radius:50%; background:rgba(255,255,255,.08); top:-80px; right:-80px; }
    .banner-logo { font-family:'Playfair Display',serif; font-size:2rem; font-weight:700; color:#fff; }
    .banner-logo span { display:block; font-size:.85rem; font-family:'DM Sans',sans-serif; font-weight:400; opacity:.85; letter-spacing:2px; text-transform:uppercase; margin-top:4px; }
    .banner-emojis { font-size:3rem; display:flex; gap:16px; }
    .banner-text { color:rgba(255,255,255,.9); font-size:.95rem; line-height:1.7; max-width:240px; }
    .banner-badge { background:rgba(255,255,255,.2); border:1px solid rgba(255,255,255,.3); border-radius:20px; padding:8px 18px; color:#fff; font-size:.82rem; font-weight:600; }

    .form-side { background:var(--white); padding:44px 48px; display:flex; flex-direction:column; justify-content:center; }
    .form-header { margin-bottom:24px; }
    .form-header h1 { font-family:'Playfair Display',serif; font-size:1.65rem; color:var(--dark); margin-bottom:6px; }
    .form-header p  { color:var(--light); font-size:.9rem; }

    .alert-box { display:none; margin-bottom:14px; padding:12px 16px; border-radius:10px; font-size:.875rem; }
    .alert-box.show { display:flex; align-items:center; gap:10px; }
    .alert-error   { background:#fff0f0; color:#c0392b; border:1px solid #fecaca; }
    .alert-success { background:#f0fff4; color:#276749; border:1px solid #a8f0c6; }

    .form-group { margin-bottom:15px; }
    .form-label { display:block; font-size:.825rem; font-weight:600; color:var(--mid); margin-bottom:7px; }
    .input-wrap { position:relative; }
    .form-input { width:100%; padding:11px 40px 11px 14px; border:1.5px solid #f0d8e3; border-radius:10px; font-size:.9rem; color:var(--dark); background:#fff; outline:none; transition:.2s; }
    .form-input:focus { border-color:var(--pink); box-shadow:0 0 0 3px rgba(232,83,122,.12); }
    .input-icon { position:absolute; right:12px; top:50%; transform:translateY(-50%); font-size:1rem; }

    /* password strength */
    .strength-bar { display:flex; gap:4px; margin-top:7px; }
    .strength-seg { height:4px; flex:1; border-radius:4px; background:#f0d8e3; transition:.3s; }
    .strength-seg.weak   { background:#e74c3c; }
    .strength-seg.medium { background:#f39c12; }
    .strength-seg.strong { background:#27ae60; }
    .strength-label { font-size:.75rem; color:var(--light); margin-top:4px; }

    .check-row { display:flex; align-items:flex-start; gap:10px; margin-bottom:20px; }
    .check-row input[type=checkbox] { width:16px; height:16px; accent-color:var(--pink); cursor:pointer; margin-top:2px; flex-shrink:0; }
    .check-row label { font-size:.83rem; color:var(--mid); cursor:pointer; line-height:1.5; }
    .check-row a { color:var(--pink); text-decoration:none; }
    .check-row a:hover { text-decoration:underline; }

    .btn-primary { width:100%; padding:13px; background:linear-gradient(135deg,var(--pink) 0%,var(--pink-d) 100%); color:#fff; font-size:.95rem; font-weight:600; border:none; border-radius:12px; cursor:pointer; transition:.2s; letter-spacing:.3px; }
    .btn-primary:hover   { opacity:.9; transform:translateY(-1px); box-shadow:0 6px 20px rgba(232,83,122,.35); }
    .btn-primary:active  { transform:translateY(0); }
    .btn-primary:disabled{ opacity:.65; cursor:not-allowed; transform:none; }

    .form-footer { text-align:center; margin-top:20px; font-size:.875rem; color:var(--mid); }
    .form-footer a { color:var(--pink); font-weight:600; text-decoration:none; }
    .form-footer a:hover { text-decoration:underline; }

    @media (max-width:680px) {
      .card { grid-template-columns:1fr; }
      .banner { display:none; }
      .form-side { padding:36px 24px; }
    }
  </style>
</head>
<body>

<div class="petals">
  <div class="petal"></div><div class="petal"></div><div class="petal"></div>
  <div class="petal"></div><div class="petal"></div><div class="petal"></div>
</div>

<div class="card">

  <!-- BANNER -->
  <div class="banner">
    <div class="banner-logo">SweetBloom ✦<span>Handcrafted Cakes</span></div>
    <div class="banner-emojis">🎂 🧁 🍰</div>
    <div class="banner-badge">🎁 Diskon 15% pesanan pertama!</div>
    <p class="banner-text">Bergabunglah dengan ribuan pelanggan bahagia kami dan mulai pesan kue spesialmu!</p>
  </div>

  <!-- FORM SIDE -->
  <div class="form-side">

    <div class="form-header">
      <h1>Buat akun baru 🎂</h1>
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
        <div class="strength-bar">
          <div class="strength-seg" id="s1"></div>
          <div class="strength-seg" id="s2"></div>
          <div class="strength-seg" id="s3"></div>
          <div class="strength-seg" id="s4"></div>
        </div>
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

    <div class="form-footer">
      Sudah punya akun? <a href="{{ route('login') }}">Login di sini!</a>
    </div>

  </div>
</div>

<script>
function togglePassword(inputId, icon) {
  const input = typeof inputId === 'string' ? document.getElementById(inputId) : inputId;
  const el = typeof inputId === 'string' ? input : inputId;
  // icon is the span element
  const inp = document.getElementById(inputId);
  if (inp.type === 'password') { inp.type = 'text'; icon.textContent = '🙈'; }
  else                         { inp.type = 'password'; icon.textContent = '👁️'; }
}

function updateStrength(val) {
  let score = 0;
  if (val.length >= 8)          score++;
  if (/[A-Z]/.test(val))        score++;
  if (/[0-9]/.test(val))        score++;
  if (/[^A-Za-z0-9]/.test(val)) score++;
  const segs  = ['s1','s2','s3','s4'];
  const cls   = ['','weak','medium','strong','strong'];
  const labels= ['','Lemah','Cukup','Kuat','Sangat Kuat'];
  segs.forEach((id,i) => {
    const el = document.getElementById(id);
    el.className = 'strength-seg' + (i < score ? ' ' + cls[score] : '');
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
  const pass    = document.getElementById('password').value;
  const confirm = document.getElementById('confirmPassword').value;

  if (pass !== confirm) {
    showAlert('error', '❌ Konfirmasi password tidak cocok.');
    return;
  }

  const btn = document.getElementById('registerBtn');
  btn.disabled = true; btn.textContent = 'Mendaftarkan...';

  try {
    const res = await fetch('/api/auth/register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({
        name:            document.getElementById('name').value,
        email:           document.getElementById('email').value,
        password:        pass,
        confirmPassword: confirm
      })
    });

    const data = await res.json();

    if (data.success) {
      showAlert('success', '✅ ' + data.message);
      document.getElementById('registerForm').reset();
    } else {
      showAlert('error', '❌ ' + data.message);
    }
  } catch (err) {
    showAlert('error', '❌ Terjadi kesalahan. Coba lagi.');
  } finally {
    btn.disabled = false; btn.textContent = 'Daftar Sekarang 🌸';
  }
}
</script>

</body>
</html>