@extends('emails.layout', ['subtitle' => 'Satu langkah lagi untuk bergabung!'])

@section('body')
<h2 style="color:#be185d;margin:0 0 16px;font-family:Georgia,serif;">Halo, {{ $name }}! 👋</h2>
<p style="color:#6b7280;line-height:1.8;margin:0 0 16px;">Selamat datang di <strong>SweetBloom</strong>! Akunmu hampir siap. 🎉</p>
<p style="color:#6b7280;line-height:1.8;margin:0 0 24px;">Klik tombol di bawah untuk mengkonfirmasi alamat email dan mengaktifkan akun kamu.</p>

<div style="text-align:center;margin:32px 0;">
  <a href="{{ $confirmUrl }}" style="background:linear-gradient(135deg,#ec4899,#be185d);color:#ffffff;text-decoration:none;padding:14px 40px;border-radius:50px;font-weight:600;font-size:15px;display:inline-block;">
    ✨ Konfirmasi Akun Saya
  </a>
</div>
<p style="color:#9ca3af;font-size:12px;text-align:center;margin:0;">Atau salin: <a href="{{ $confirmUrl }}" style="color:#ec4899;">{{ $confirmUrl }}</a></p>

<div style="margin-top:32px;padding:16px;background:#fdf2f8;border-radius:12px;border-left:4px solid #f9a8d4;">
  <p style="color:#9ca3af;font-size:13px;margin:0;">⏰ Link berlaku selama <strong>24 jam</strong>.<br>Abaikan jika kamu tidak mendaftar di SweetBloom.</p>
</div>
@endsection