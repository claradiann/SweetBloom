<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password – SweetBloom</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    :root{--rose:#f43f6b;--pink-d:#d92462;--pink-100:#ffe0eb;--cream:#fffaf6;--dark:#1a0a12;--mid:#5c3347;--light:#a07a8e;--muted:#c9a8b8;--white:#fff}
    body{font-family:'Poppins',sans-serif;background:var(--cream);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;position:relative;overflow:hidden}
    .bg-blobs{position:fixed;inset:0;z-index:0;pointer-events:none;overflow:hidden}
    .blob{position:absolute;border-radius:50%;filter:blur(80px);opacity:.25;animation:bf 20s ease-in-out infinite}
    .blob-1{width:350px;height:350px;background:#ffc2d4;top:-80px;right:-80px}
    .blob-2{width:280px;height:280px;background:#e6fff5;bottom:-60px;left:-60px;animation-delay:-10s}
    @keyframes bf{0%,100%{transform:translate(0,0) scale(1)}50%{transform:translate(30px,-30px) scale(1.05)}}
    .petals{position:fixed;inset:0;pointer-events:none;z-index:0;overflow:hidden}
    .petal{position:absolute;width:10px;height:14px;background:var(--pink-100);border-radius:50% 50% 50% 0;opacity:0;animation:fall linear infinite}
    .petal:nth-child(1){left:10%;animation-duration:8s}.petal:nth-child(2){left:40%;animation-duration:10s;animation-delay:2s}.petal:nth-child(3){left:70%;animation-duration:7s;animation-delay:1s}
    @keyframes fall{0%{transform:translateY(-20px) rotate(0deg);opacity:0}10%{opacity:.25}90%{opacity:.1}100%{transform:translateY(105vh) rotate(360deg);opacity:0}}
    .card{position:relative;z-index:1;background:var(--white);border-radius:28px;padding:48px 44px;max-width:440px;width:100%;box-shadow:0 32px 80px rgba(26,10,18,.1);text-align:center;animation:ci .6s ease}
    @keyframes ci{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
    .icon-circle{width:72px;height:72px;background:linear-gradient(135deg,#fff5f8,#ffe0eb);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2rem;margin:0 auto 20px;box-shadow:0 4px 16px rgba(244,63,107,.1)}
    .card h1{font-family:'Playfair Display',serif;font-size:1.6rem;color:var(--dark);margin-bottom:8px}
    .card>p{color:var(--light);font-size:.88rem;line-height:1.6;margin-bottom:24px}
    .alert-box{display:none;padding:12px 16px;border-radius:10px;font-size:.84rem;margin-bottom:14px;text-align:left;font-weight:500}
    .alert-box.show{display:flex;align-items:center;gap:8px;animation:sd .3s ease}
    .alert-error{background:#fff0f3;color:#be123c;border:1px solid #ffc2d4}
    .alert-success{background:#ecfdf5;color:#166534;border:1px solid #a7f3d0}
    @keyframes sd{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:translateY(0)}}
    .form-group{margin-bottom:16px;text-align:left}
    .form-label{display:block;font-size:.8rem;font-weight:600;color:var(--mid);margin-bottom:7px}
    .input-wrap{position:relative}
    .form-input{width:100%;padding:12px 42px 12px 16px;border:1.5px solid var(--pink-100);border-radius:12px;font-size:.9rem;color:var(--dark);background:var(--white);outline:none;transition:.25s;font-family:'Poppins',sans-serif}
    .form-input:focus{border-color:var(--rose);box-shadow:0 0 0 3px rgba(244,63,107,.1)}
    .input-icon{position:absolute;right:14px;top:50%;transform:translateY(-50%);font-size:1rem;cursor:pointer}
    .strength-bar{display:flex;gap:4px;margin-top:6px}
    .strength-seg{height:4px;flex:1;border-radius:50px;background:#e8e0e4;transition:.3s}
    .strength-seg.weak{background:#ef4444}.strength-seg.medium{background:#f59e0b}.strength-seg.strong{background:#22c55e}
    .strength-label{font-size:.72rem;color:var(--light);margin-top:3px}
    .btn-primary{width:100%;padding:14px;background:linear-gradient(135deg,var(--rose),var(--pink-d));color:white;font-size:.9rem;font-weight:600;border:none;border-radius:12px;cursor:pointer;transition:.25s;font-family:'Poppins',sans-serif;box-shadow:0 4px 16px rgba(244,63,107,.25);margin-top:6px}
    .btn-primary:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(244,63,107,.35)}
    .btn-primary:disabled{opacity:.6;cursor:not-allowed;transform:none}
    .back-link{display:inline-flex;align-items:center;gap:6px;margin-top:24px;color:var(--rose);font-size:.85rem;font-weight:600;text-decoration:none;transition:.2s}
    .back-link:hover{color:var(--pink-d);gap:8px}
    .no-token{padding:16px 0}
    .no-token p{color:var(--light);margin-bottom:12px;font-size:.9rem}
    .no-token a{color:var(--rose);font-weight:600}
  </style>
</head>
<body>
<div class="bg-blobs"><div class="blob blob-1"></div><div class="blob blob-2"></div></div>
<div class="petals"><div class="petal"></div><div class="petal"></div><div class="petal"></div></div>
<div class="card">
  <div class="icon-circle">🔐</div>
  <h1>Buat Password Baru</h1>
  <p>Password baru harus minimal 8 karakter dan mengandung huruf serta angka.</p>
  <div id="alertBox" class="alert-box"></div>
  <div id="formWrap">
    <form id="resetForm" onsubmit="handleReset(event)">
      @csrf
      <input type="hidden" id="token" value="">
      <div class="form-group">
        <label class="form-label">Password Baru</label>
        <div class="input-wrap">
          <input type="password" id="password" class="form-input" placeholder="Min. 8 karakter + angka" required oninput="updateStrength(this.value)">
          <span class="input-icon" onclick="togglePw('password',this)">👁️</span>
        </div>
        <div class="strength-bar"><div class="strength-seg" id="s1"></div><div class="strength-seg" id="s2"></div><div class="strength-seg" id="s3"></div><div class="strength-seg" id="s4"></div></div>
        <div class="strength-label" id="strengthLabel"></div>
      </div>
      <div class="form-group">
        <label class="form-label">Konfirmasi Password</label>
        <div class="input-wrap">
          <input type="password" id="confirmPassword" class="form-input" placeholder="Ulangi password baru" required>
          <span class="input-icon" onclick="togglePw('confirmPassword',this)">👁️</span>
        </div>
      </div>
      <button type="submit" class="btn-primary" id="resetBtn">Simpan Password Baru 🌸</button>
    </form>
  </div>
  <a href="{{ route('login') }}" class="back-link">← Kembali ke Login</a>
</div>
<script>
const token = new URLSearchParams(window.location.search).get('token');
if (!token) {
  document.getElementById('formWrap').innerHTML = '<div class="no-token"><p>Link reset password tidak valid atau sudah digunakan.</p><p>Silakan <a href="/forgot-password">minta link baru</a>.</p></div>';
} else { document.getElementById('token').value = token; }
function togglePw(id, icon) { const inp = document.getElementById(id); if (inp.type === 'password') { inp.type = 'text'; icon.textContent = '🙈'; } else { inp.type = 'password'; icon.textContent = '👁️'; } }
function updateStrength(val) {
  let score = 0;
  if (val.length >= 8) score++; if (/[A-Z]/.test(val)) score++; if (/[0-9]/.test(val)) score++; if (/[^A-Za-z0-9]/.test(val)) score++;
  const segs = ['s1','s2','s3','s4'], cls = ['','weak','medium','strong','strong'], labels = ['','Lemah','Cukup','Kuat','Sangat Kuat'];
  segs.forEach((id,i) => { document.getElementById(id).className = 'strength-seg' + (i < score ? ' ' + cls[score] : ''); });
  document.getElementById('strengthLabel').textContent = val.length > 0 ? labels[score] : '';
}
async function handleReset(e) {
  e.preventDefault();
  const pass = document.getElementById('password').value, confirm = document.getElementById('confirmPassword').value;
  if (pass !== confirm) { const box = document.getElementById('alertBox'); box.className = 'alert-box show alert-error'; box.textContent = '❌ Konfirmasi password tidak cocok.'; return; }
  const btn = document.getElementById('resetBtn'); btn.disabled = true; btn.textContent = 'Menyimpan...';
  try {
    const res = await fetch('/api/auth/reset-password', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }, body: JSON.stringify({ token, password: pass, confirmPassword: confirm }) });
    const data = await res.json(); const box = document.getElementById('alertBox');
    if (data.success) { box.className = 'alert-box show alert-success'; box.textContent = '✅ ' + data.message; setTimeout(() => { window.location.href = '/login'; }, 2000); }
    else { box.className = 'alert-box show alert-error'; box.textContent = '❌ ' + data.message; }
  } catch { const box = document.getElementById('alertBox'); box.className = 'alert-box show alert-error'; box.textContent = '❌ Terjadi kesalahan.'; }
  finally { btn.disabled = false; btn.textContent = 'Simpan Password Baru 🌸'; }
}
</script>
</body>
</html>