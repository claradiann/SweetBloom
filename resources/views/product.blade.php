@extends('layouts.app')

@section('content')
<style>
  .products-page{max-width:1200px;margin:0 auto;padding:32px 24px 80px}
  .products-page-header{text-align:center;margin-bottom:36px}
  .products-page-header h1{font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;color:var(--text-dark,#1a0a12)}
  .products-page-header h1 span{color:var(--rose,#f43f6b);font-style:italic}
  .products-page-header p{color:var(--text-light,#a07a8e);margin-top:8px;font-size:.9rem}
  .products-toolbar{display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin-bottom:28px}
  .products-search{flex:1;min-width:200px;padding:12px 20px;border:1.5px solid #ffe0eb;border-radius:50px;font-size:.9rem;outline:none;font-family:'Poppins',sans-serif;transition:.25s;background:white}
  .products-search:focus{border-color:#f43f6b;box-shadow:0 0 0 3px rgba(244,63,107,.1)}
  .products-search::placeholder{color:#c9a8b8}
  .filter-btn{padding:9px 20px;border-radius:50px;border:1.5px solid #ffe0eb;background:white;cursor:pointer;font-size:.82rem;font-weight:600;color:#a07a8e;transition:all .25s;font-family:'Poppins',sans-serif}
  .filter-btn.active,.filter-btn:hover{background:linear-gradient(135deg,#f43f6b,#d92462);color:white;border-color:transparent;box-shadow:0 4px 12px rgba(244,63,107,.2)}
  .products-count{color:#c9a8b8;font-size:.82rem;margin-bottom:14px}
  .products-grid-full{display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:20px}
  .empty-state{text-align:center;padding:60px 0;color:#c9a8b8;font-size:1rem}
  .back-btn{display:inline-flex;align-items:center;gap:6px;color:#f43f6b;font-weight:600;text-decoration:none;margin-bottom:28px;font-size:.9rem;transition:.2s}
  .back-btn:hover{color:#d92462;gap:10px}
</style>

<!-- Navbar -->
<nav class="navbar" id="mainNav">
  <a href="/dashboard" class="nav-logo">Sweet<span>Bloom</span> <span class="logo-dot"></span></a>
  <ul class="nav-links">
    <li><a href="/dashboard">Home</a></li>
    <li><a href="#" class="active">Menu</a></li>
  </ul>
  <div class="nav-actions">
    <button class="nav-cart" id="navCart">🛒 <span class="cart-badge">0</span></button>
    <button class="nav-avatar" id="navAvatar">?</button>
  </div>
</nav>

<div class="products-page">
  <a href="/dashboard" class="back-btn">← Kembali ke Home</a>
  <div class="products-page-header">
    <h1>✨ Semua <span>Produk</span> Kami</h1>
    <p>Dibuat segar setiap hari dengan bahan-bahan pilihan 🌸</p>
  </div>
  <div class="products-toolbar">
    <input type="text" class="products-search" id="searchInput" placeholder="🔍 Cari kue favoritmu...">
    <button class="filter-btn active" data-cat="all">Semua</button>
    <button class="filter-btn" data-cat="cake">Cake</button>
    <button class="filter-btn" data-cat="cupcake">Cupcake</button>
    <button class="filter-btn" data-cat="mousse">Mousse</button>
  </div>
  <div class="products-count" id="productsCount"></div>
  <div class="products-grid-full" id="productsGrid"></div>
</div>

<!-- Footer -->
<footer class="site-footer">
  <div class="footer-inner">
    <div class="footer-brand">
      <div class="nav-logo" style="font-size:1.3rem">Sweet<span>Bloom</span> <span class="logo-dot"></span></div>
      <p>Toko kue handmade terbaik dengan cinta di setiap gigitan.</p>
    </div>
    <div class="footer-col"><h4>Menu</h4><ul><li><a href="#">Semua Kue</a></li><li><a href="#">Best Seller</a></li><li><a href="#">Custom Cake</a></li></ul></div>
    <div class="footer-col"><h4>Info</h4><ul><li><a href="#">Tentang Kami</a></li><li><a href="#">Pengiriman</a></li><li><a href="#">Kontak</a></li></ul></div>
  </div>
  <div class="footer-bottom">
    <span>© 2026 SweetBloom. All rights reserved.</span>
    <span>Made with <span class="footer-hearts">♥</span> in Indonesia</span>
  </div>
</footer>

<!-- Cart Modal -->
<div id="cartModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.35);backdrop-filter:blur(4px);align-items:center;justify-content:center;">
  <div style="background:white;border-radius:24px;padding:28px;width:90%;max-width:400px;max-height:80vh;overflow-y:auto;position:relative;box-shadow:0 24px 64px rgba(0,0,0,.15)">
    <button onclick="closeCart()" style="position:absolute;top:14px;right:18px;background:none;border:none;font-size:1.3rem;cursor:pointer;color:#c9a8b8">✕</button>
    <h2 style="font-size:1.15rem;font-weight:700;margin-bottom:18px;font-family:'Playfair Display',serif">🛒 Keranjang Belanja</h2>
    <div id="cartItems"></div>
    <div id="cartTotal" style="margin-top:18px;padding-top:14px;border-top:1px solid #ffe0eb;font-weight:700;font-size:1.05rem;color:#f43f6b"></div>
    <button onclick="checkout()" id="checkoutBtn" style="width:100%;margin-top:14px;padding:13px;background:linear-gradient(135deg,#f43f6b,#d92462);color:white;border:none;border-radius:50px;font-weight:600;font-size:.9rem;cursor:pointer;display:none;font-family:'Poppins',sans-serif">Checkout 🎉</button>
  </div>
</div>

<!-- Profile Modal -->
<div id="profileModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.35);backdrop-filter:blur(4px);align-items:center;justify-content:center;">
  <div style="background:white;border-radius:24px;padding:28px;width:90%;max-width:420px;max-height:90vh;overflow-y:auto;position:relative;box-shadow:0 24px 64px rgba(0,0,0,.15)">
    <button onclick="closeProfileModal()" style="position:absolute;top:14px;right:18px;background:none;border:none;font-size:1.3rem;cursor:pointer;color:#c9a8b8">✕</button>
    <div id="profileModalContent"></div>
  </div>
</div>

<script>
const cart = JSON.parse(sessionStorage.getItem('sb_cart') || '[]');
let allProducts = [];
let currentCategory = 'all';

function saveCart() { sessionStorage.setItem('sb_cart', JSON.stringify(cart)); }
function updateCartBadge() { const total = cart.reduce((s, i) => s + i.qty, 0); document.querySelectorAll('.cart-badge').forEach(el => el.textContent = total); }
function addToCart(id, name, price, emoji) {
  const ex = cart.find(i => i.id === id);
  if (ex) ex.qty++; else cart.push({ id, name, price, emoji, qty: 1 });
  saveCart(); updateCartBadge(); showToast(`${emoji} ${name} ditambahkan!`);
}
function showToast(msg) {
  let t = document.getElementById('toast');
  if (!t) { t = document.createElement('div'); t.id = 'toast'; t.className = 'toast'; document.body.appendChild(t); }
  t.textContent = `🛒 ${msg}`; t.style.opacity = '1';
  clearTimeout(t._t); t._t = setTimeout(() => t.style.opacity = '0', 2500);
}
function openCart() {
  const modal = document.getElementById('cartModal'), itemsEl = document.getElementById('cartItems'), totalEl = document.getElementById('cartTotal'), checkoutBtn = document.getElementById('checkoutBtn');
  if (cart.length === 0) { itemsEl.innerHTML = '<div style="text-align:center;padding:40px 0;color:#c9a8b8">Keranjang masih kosong 🛒</div>'; totalEl.innerHTML = ''; checkoutBtn.style.display = 'none'; }
  else {
    itemsEl.innerHTML = cart.map(i => `<div style="display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid #ffe0eb"><span style="font-size:1.8rem">${i.emoji}</span><div style="flex:1"><div style="font-weight:600;font-size:.9rem">${i.name}</div><div style="color:#f43f6b;font-weight:600;font-size:.85rem">${formatPrice(i.price)}</div></div><div style="display:flex;align-items:center;gap:8px"><button onclick="changeQty(${i.id},-1)" style="width:28px;height:28px;border-radius:50%;border:1.5px solid #ffe0eb;background:white;cursor:pointer;font-size:1rem">−</button><span style="font-weight:700;min-width:20px;text-align:center">${i.qty}</span><button onclick="changeQty(${i.id},1)" style="width:28px;height:28px;border-radius:50%;border:none;background:#f43f6b;color:white;cursor:pointer;font-size:1rem">+</button></div></div>`).join('');
    totalEl.innerHTML = `Total: ${formatPrice(cart.reduce((s, i) => s + i.price * i.qty, 0))}`; checkoutBtn.style.display = 'block';
  }
  modal.style.display = 'flex';
}
function changeQty(id, delta) { const idx = cart.findIndex(i => i.id === id); if (idx === -1) return; cart[idx].qty += delta; if (cart[idx].qty <= 0) cart.splice(idx, 1); saveCart(); updateCartBadge(); openCart(); }
function closeCart() { document.getElementById('cartModal').style.display = 'none'; }
function checkout() { alert('Fitur checkout akan segera hadir! 🎉'); }
function formatPrice(p) { return 'Rp ' + p.toLocaleString('id-ID'); }
function toggleWishlist(btn) { const liked = btn.classList.toggle('liked'); btn.textContent = liked ? '❤️' : '🤍'; }

function renderProducts(products) {
  const grid = document.getElementById('productsGrid'), count = document.getElementById('productsCount');
  count.textContent = `Menampilkan ${products.length} produk`;
  if (products.length === 0) { grid.innerHTML = '<div class="empty-state">😢 Produk tidak ditemukan</div>'; return; }
  grid.innerHTML = products.map(p => `<div class="product-card"><div class="product-card-image">${p.emoji}${p.badge ? `<span class="product-badge">${p.badge}</span>` : ''}<button class="product-wishlist" onclick="toggleWishlist(this)">🤍</button></div><div class="product-info"><div class="product-name">${p.name}</div><div class="product-desc">${p.description}</div><div class="product-meta"><span class="product-price">${formatPrice(p.price)}</span><div style="display:flex;align-items:center;gap:8px"><span class="product-rating">⭐ ${p.rating}</span><button class="product-add-btn" onclick="addToCart(${p.id},'${p.name}',${p.price},'${p.emoji}')">+</button></div></div></div></div>`).join('');
}
function filterAndSearch() {
  const q = document.getElementById('searchInput').value.toLowerCase();
  let result = allProducts;
  if (currentCategory !== 'all') result = result.filter(p => p.category === currentCategory);
  if (q) result = result.filter(p => p.name.toLowerCase().includes(q) || p.description.toLowerCase().includes(q));
  renderProducts(result);
}
async function loadProducts() {
  try { const res = await fetch('/api/products'); const data = await res.json(); allProducts = data.products || []; renderProducts(allProducts); }
  catch { document.getElementById('productsGrid').innerHTML = '<div class="empty-state">Gagal memuat produk 😢</div>'; }
}
async function loadUser() {
  try { const res = await fetch('/api/auth/me', { credentials: 'include' }); const data = await res.json(); if (!res.ok) { window.location.href = '/login'; return; } const u = data.user; document.getElementById('navAvatar').textContent = u.name.charAt(0).toUpperCase(); window._user = u; }
  catch { window.location.href = '/login'; }
}
function openProfileModal() {
  const u = window._user; if (!u) return;
  document.getElementById('profileModalContent').innerHTML = `<div style="text-align:center;margin-bottom:24px"><div style="width:68px;height:68px;border-radius:50%;background:linear-gradient(135deg,#ffc2d4,#f43f6b);display:flex;align-items:center;justify-content:center;font-size:1.8rem;margin:0 auto 12px;box-shadow:0 4px 20px rgba(244,63,107,.2)">${u.role==='admin'?'👑':'🌸'}</div><div style="font-weight:700;font-size:1.05rem">${u.name}</div><div style="color:#a07a8e;font-size:.83rem">${u.email}</div><span style="display:inline-block;margin-top:6px;padding:4px 14px;background:#fff5f8;color:#f43f6b;border-radius:50px;font-size:.78rem;font-weight:600;border:1px solid #ffe0eb">${u.role==='admin'?'👑 Admin':'🛍️ Customer'}</span></div><div id="profileView"><div style="margin-bottom:14px"><label style="font-size:.78rem;font-weight:600;color:#a07a8e;text-transform:uppercase;letter-spacing:.3px">Nama</label><div style="padding:10px 14px;background:#faf8f9;border-radius:10px;margin-top:4px;font-weight:600;font-size:.9rem">${u.name}</div></div><div style="margin-bottom:14px"><label style="font-size:.78rem;font-weight:600;color:#a07a8e;text-transform:uppercase;letter-spacing:.3px">Email</label><div style="padding:10px 14px;background:#faf8f9;border-radius:10px;margin-top:4px;font-weight:600;font-size:.9rem">${u.email}</div></div><div style="margin-bottom:14px"><label style="font-size:.78rem;font-weight:600;color:#a07a8e;text-transform:uppercase;letter-spacing:.3px">No. Telepon</label><div style="padding:10px 14px;background:#faf8f9;border-radius:10px;margin-top:4px;font-weight:600;font-size:.9rem">${u.phone||'-'}</div></div><div style="margin-bottom:20px"><label style="font-size:.78rem;font-weight:600;color:#a07a8e;text-transform:uppercase;letter-spacing:.3px">Alamat</label><div style="padding:10px 14px;background:#faf8f9;border-radius:10px;margin-top:4px;font-weight:600;font-size:.9rem">${u.address||'-'}</div></div><div id="profileMsg" style="margin-bottom:12px"></div><button onclick="showEditProfile()" style="width:100%;padding:12px;background:linear-gradient(135deg,#f43f6b,#d92462);color:white;border:none;border-radius:50px;font-weight:600;cursor:pointer;margin-bottom:8px;font-family:'Poppins',sans-serif;box-shadow:0 4px 16px rgba(244,63,107,.2)">✏️ Edit Profil</button><button onclick="showChangePassword()" style="width:100%;padding:12px;background:white;color:#f43f6b;border:1.5px solid #ffe0eb;border-radius:50px;font-weight:600;cursor:pointer;margin-bottom:8px;font-family:'Poppins',sans-serif">🔒 Ubah Password</button><button onclick="handleLogout()" style="width:100%;padding:12px;background:white;color:#a07a8e;border:1.5px solid #e8e0e4;border-radius:50px;font-weight:600;cursor:pointer;margin-bottom:8px;font-family:'Poppins',sans-serif">🚪 Logout</button><button onclick="confirmDelete()" style="width:100%;padding:12px;background:white;color:#ef4444;border:1.5px solid #ffc2d4;border-radius:50px;font-weight:600;cursor:pointer;font-family:'Poppins',sans-serif">🗑️ Hapus Akun</button></div>`;
  document.getElementById('profileModal').style.display = 'flex';
}
function showEditProfile() { const u = window._user; document.getElementById('profileView').innerHTML = `<div style="margin-bottom:14px"><label style="font-size:.78rem;font-weight:600;color:#a07a8e;text-transform:uppercase">Nama</label><input id="editName" value="${u.name}" style="width:100%;padding:10px 14px;border:1.5px solid #ffe0eb;border-radius:10px;margin-top:4px;font-size:.9rem;box-sizing:border-box;outline:none;font-family:'Poppins',sans-serif"></div><div style="margin-bottom:14px"><label style="font-size:.78rem;font-weight:600;color:#a07a8e;text-transform:uppercase">No. Telepon</label><input id="editPhone" value="${u.phone||''}" placeholder="08xxxxxxxxxx" style="width:100%;padding:10px 14px;border:1.5px solid #ffe0eb;border-radius:10px;margin-top:4px;font-size:.9rem;box-sizing:border-box;outline:none;font-family:'Poppins',sans-serif"></div><div style="margin-bottom:18px"><label style="font-size:.78rem;font-weight:600;color:#a07a8e;text-transform:uppercase">Alamat</label><textarea id="editAddress" placeholder="Alamat lengkap..." style="width:100%;padding:10px 14px;border:1.5px solid #ffe0eb;border-radius:10px;margin-top:4px;font-size:.9rem;box-sizing:border-box;outline:none;resize:vertical;min-height:70px;font-family:'Poppins',sans-serif">${u.address||''}</textarea></div><div id="profileMsg" style="margin-bottom:12px"></div><button onclick="saveProfile()" style="width:100%;padding:12px;background:linear-gradient(135deg,#f43f6b,#d92462);color:white;border:none;border-radius:50px;font-weight:600;cursor:pointer;margin-bottom:8px;font-family:'Poppins',sans-serif">💾 Simpan</button><button onclick="openProfileModal()" style="width:100%;padding:12px;background:white;color:#a07a8e;border:1.5px solid #e8e0e4;border-radius:50px;font-weight:600;cursor:pointer;font-family:'Poppins',sans-serif">← Batal</button>`; }
function showChangePassword() { document.getElementById('profileView').innerHTML = `<div style="margin-bottom:14px"><label style="font-size:.78rem;font-weight:600;color:#a07a8e;text-transform:uppercase">Password Lama</label><input id="pwCurrent" type="password" placeholder="••••••••" style="width:100%;padding:10px 14px;border:1.5px solid #ffe0eb;border-radius:10px;margin-top:4px;font-size:.9rem;box-sizing:border-box;outline:none;font-family:'Poppins',sans-serif"></div><div style="margin-bottom:14px"><label style="font-size:.78rem;font-weight:600;color:#a07a8e;text-transform:uppercase">Password Baru</label><input id="pwNew" type="password" placeholder="Min. 8 karakter + angka" style="width:100%;padding:10px 14px;border:1.5px solid #ffe0eb;border-radius:10px;margin-top:4px;font-size:.9rem;box-sizing:border-box;outline:none;font-family:'Poppins',sans-serif"></div><div style="margin-bottom:18px"><label style="font-size:.78rem;font-weight:600;color:#a07a8e;text-transform:uppercase">Konfirmasi Password</label><input id="pwConfirm" type="password" placeholder="Ulangi password baru" style="width:100%;padding:10px 14px;border:1.5px solid #ffe0eb;border-radius:10px;margin-top:4px;font-size:.9rem;box-sizing:border-box;outline:none;font-family:'Poppins',sans-serif"></div><div id="profileMsg" style="margin-bottom:12px"></div><button onclick="savePassword()" style="width:100%;padding:12px;background:linear-gradient(135deg,#f43f6b,#d92462);color:white;border:none;border-radius:50px;font-weight:600;cursor:pointer;margin-bottom:8px;font-family:'Poppins',sans-serif">🔒 Ubah Password</button><button onclick="openProfileModal()" style="width:100%;padding:12px;background:white;color:#a07a8e;border:1.5px solid #e8e0e4;border-radius:50px;font-weight:600;cursor:pointer;font-family:'Poppins',sans-serif">← Batal</button>`; }
function showMsg(msg, type) { const el = document.getElementById('profileMsg'); if (!el) return; const bg = type === 'success' ? '#ecfdf5' : '#fff0f3'; const color = type === 'success' ? '#166534' : '#be123c'; el.innerHTML = `<div style="padding:10px 14px;border-radius:10px;background:${bg};color:${color};font-size:.84rem;font-weight:600">${msg}</div>`; }
async function saveProfile() { const name=document.getElementById('editName').value.trim(),phone=document.getElementById('editPhone').value.trim(),address=document.getElementById('editAddress').value.trim(); if(!name||name.length<2)return showMsg('Nama minimal 2 karakter.','error'); const res=await fetch('/api/auth/profile',{method:'PUT',credentials:'include',headers:{'Content-Type':'application/json'},body:JSON.stringify({name,phone,address})}); const data=await res.json(); if(res.ok){window._user={...window._user,name,phone,address};document.getElementById('navAvatar').textContent=name.charAt(0).toUpperCase();showMsg('Profil berhasil diperbarui! 🌸','success');setTimeout(openProfileModal,1200);}else{showMsg(data.message||'Gagal menyimpan.','error');} }
async function savePassword() { const currentPassword=document.getElementById('pwCurrent').value,newPassword=document.getElementById('pwNew').value,confirmPassword=document.getElementById('pwConfirm').value; if(!currentPassword||!newPassword||!confirmPassword)return showMsg('Semua field wajib diisi.','error'); if(newPassword.length<8)return showMsg('Password baru minimal 8 karakter.','error'); if(!/(?=.*[A-Za-z])(?=.*\d)/.test(newPassword))return showMsg('Password harus mengandung huruf dan angka.','error'); if(newPassword!==confirmPassword)return showMsg('Konfirmasi password tidak cocok.','error'); const res=await fetch('/api/auth/change-password',{method:'POST',credentials:'include',headers:{'Content-Type':'application/json'},body:JSON.stringify({currentPassword,newPassword,confirmPassword})}); const data=await res.json(); if(res.ok){showMsg('Password berhasil diubah! 🔐','success');setTimeout(openProfileModal,1200);}else{showMsg(data.message||'Gagal mengubah password.','error');} }
function confirmDelete() { if (confirm('Yakin ingin menghapus akun? Tindakan ini tidak bisa dibatalkan!')) handleDeleteAccount(); }
async function handleDeleteAccount() { const res = await fetch('/api/auth/account', { method: 'DELETE', credentials: 'include' }); if (res.ok) { sessionStorage.clear(); window.location.href = '/login?info=' + encodeURIComponent('Akun berhasil dihapus. Sampai jumpa! 👋'); } else showMsg('Gagal menghapus akun.', 'error'); }
async function handleLogout() { await fetch('/api/auth/logout', { method: 'POST', credentials: 'include' }); sessionStorage.clear(); window.location.href = '/login'; }
function closeProfileModal() { document.getElementById('profileModal').style.display = 'none'; }

document.addEventListener('DOMContentLoaded', () => {
  loadUser().then(() => { document.getElementById('navAvatar').addEventListener('click', openProfileModal); });
  loadProducts(); updateCartBadge();
  document.getElementById('navCart').addEventListener('click', openCart);
  document.getElementById('searchInput').addEventListener('input', filterAndSearch);
  document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', () => { document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active')); btn.classList.add('active'); currentCategory = btn.dataset.cat; filterAndSearch(); });
  });
  document.getElementById('cartModal').addEventListener('click', e => { if (e.target === document.getElementById('cartModal')) closeCart(); });
  document.getElementById('profileModal').addEventListener('click', e => { if (e.target === document.getElementById('profileModal')) closeProfileModal(); });
  // Navbar scroll
  window.addEventListener('scroll', () => { document.getElementById('mainNav').classList.toggle('scrolled', window.scrollY > 10); });
});
</script>
@endsection