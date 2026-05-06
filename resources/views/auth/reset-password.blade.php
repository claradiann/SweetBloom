<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password – SweetBloom</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    :root{--pink:#e8537a;--pink-d:#c2185b;--cream:#fff8f3;--dark:#2d1a26;--mid:#6b4157;--light:#b07a92;--white:#ffffff}
    body{font-family:'DM Sans',sans-serif;background:var(--cream);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px}

    .petals{position:fixed;inset:0;pointer-events:none;z-index:0;overflow:hidden}
    .petal{position:absolute;width:10px;height:15px;background:#f8bbd0;border-radius:50% 50% 50% 0;opacity:0;animation:fall linear infinite}
    .petal:nth-child(1){left:10%;animation-duration:8s;animation-delay:0s}
    .petal:nth-child(2){left:40%;animation-duration:10s;animation-delay:2s}
    .petal:nth-child(3){left:70%;animation-duration:7s;animation-delay:1s}
    @keyframes fall{0%{transform:translateY(-20px) rotate(0deg);opacity:0}10%{opacity:.3}90%{opacity:.15}100%{transform:translateY(105vh) rotate(360deg);opacity:0}}

    .card{position:relative;z-index:1;background:var(--white);border-radius:24px;padding:52px 48px;max-width:460px;width:100%;box-shadow:0 24px 64px rgba(45,26,38,.18);text-align:center}

    .icon-circle{width:72px;height:72px;background:linear-gradient(135deg,#fce4ec,#f8bbd0);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2rem;margin:0 auto 24px}

    .card h1{font-family:'Playfair Display',serif;font-size:1.65rem;color:var(--dark);margin-bottom:8px}
    .card p{color:var(--light);font-size:.9rem;line-height:1.6;margin-bottom:28px}

    .alert-box{display:none;padding:12px 16px;border-radius:10px;font-size:.875rem;margin-bottom:16px;text-align:left}
    .alert-box.show{display:flex;align-items:center;gap:10px}
    .alert-error  {background:#fff0f0;color:#c0392b;border:1px solid #fecaca}
    .alert-success{background:#f0fff4;color:#276749;border:1px solid #a8f0c6}

    .form-group{margin-bottom:16px;text-align:left}
    .form-label{display:block;font-size:.825rem;font-weight:600;color:var(--mid);margin-bottom:7px}
    .input-wrap{position:relative}
    .form-input{width:100%;padding:11px 40px 11px 14px;border:1.5px solid #f0d8e3;border-radius:10px;font-size:.9rem;color:var(--dark);background:#fff;outline:none;transition:.2s}
    .form-input:focus{border-color:var(--pink);box-shadow:0 0 0 3px rgba(232,83,122,.12)}
    .input-icon{position:absolute;right:12px;top:50%;transform:translateY(-50%);font-size:1rem;cursor:pointer}

    .strength-bar{display:flex;gap:4px;margin-top:7px}
    .strength-seg{height:4px;flex:1;border-radius:4px;background:#f0d8e3;transition:.3s}
    .strength-seg.weak{background:#e74c3c}
    .strength-seg.medium{background:#f39c12}
    .strength-seg.strong{background:#27ae60}
    .strength-label{font-size:.75rem;color:var(--light);margin-top:4px}

    .btn-primary{width:100%;padding:13px;background:linear-gradient(135deg,var(--pink) 0%,var(--pink-d) 100%);color:#fff;font-size:.95rem;font-weight:600;border:none;border-radius:12px;cursor:pointer;transition:.2s;margin-top:8px}
    .btn-primary:hover{opacity:.9;transform:translateY(-1px);box-shadow:0 6px 20px rgba(232,83,122,.35)}
    .btn-primary:disabled{opacity:.65;cursor:not-allowed;transform:none}

    .back-link{display:inline-flex;align-items:center;gap:6px;margin-top:24px;color:var(--pink);font-size:.875rem;font-weight:600;text-decoration:none}
    .back-link:hover{text-decoration:underline}

    /* token missing state */
    .no-token{text-align:center;padding:16px 0}
    .no-token p{color:var(--light);margin-bottom:16px}
  </style>
</head>
<body>

<div class="petals">
  <div class="petal"></div><div class="petal"></div><div class="petal"></div>
</div>

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
// Ambil token dari URL
const token = new URLSearchParams(window.location.search).get('token');
if (!token) {
  document.getElementById('formWrap').innerHTML =
    '<div class="no-token"><p>Link reset password tidak valid atau sudah digunakan.</p>' +
    '<p>Silakan <a href="/forgot-password" style="color:var(--pink);font-weight:600">minta link baru</a>.</p></div>';
} else {
  document.getElementById('token').value = token;
}

function togglePw(id, icon) {
  const inp = document.getElementById(id);
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

async function handleReset(e) {
  e.preventDefault();
  const pass    = document.getElementById('password').value;
  const confirm = document.getElementById('confirmPassword').value;

  if (pass !== confirm) {
    const box = document.getElementById('alertBox');
    box.className = 'alert-box show alert-error';
    box.textContent = '❌ Konfirmasi password tidak cocok.';
    return;
  }

  const btn = document.getElementById('resetBtn');
  btn.disabled = true; btn.textContent = 'Menyimpan...';

  try {
    const res = await fetch('/api/auth/reset-password', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({
        token,
        password:        pass,
        confirmPassword: confirm
      })
    });
    const data = await res.json();
    const box = document.getElementById('alertBox');
    if (data.success) {
      box.className = 'alert-box show alert-success';
      box.textContent = '✅ ' + data.message;
      setTimeout(() => { window.location.href = '/login'; }, 2000);
    } else {
      box.className = 'alert-box show alert-error';
      box.textContent = '❌ ' + data.message;
    }
  } catch {
    const box = document.getElementById('alertBox');
    box.className = 'alert-box show alert-error';
    box.textContent = '❌ Terjadi kesalahan. Coba lagi.';
  } finally {
    btn.disabled = false; btn.textContent = 'Simpan Password Baru 🌸';
  }
}
</script>

</body>
</html>