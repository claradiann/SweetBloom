@extends('emails.layout', ['subtitle' => 'Akun kamu sudah aktif'])

@section('body')
<h2 style="color:#be185d;margin:0 0 16px;font-family:Georgia,serif;">Selamat datang, {{ $name }}! 🎉</h2>
<p style="color:#6b7280;line-height:1.8;margin:0 0 16px;">Akun SweetBloom kamu <strong>sudah aktif</strong> dan siap digunakan! 🌸</p>
<p style="color:#6b7280;line-height:1.8;margin:0 0 24px;">Sekarang kamu bisa menikmati semua produk kue lezat kami.</p>

<div style="text-align:center;margin:32px 0;">
  <a href="{{ $loginUrl }}" style="background:linear-gradient(135deg,#ec4899,#be185d);color:#ffffff;text-decoration:none;padding:14px 40px;border-radius:50px;font-weight:600;font-size:15px;display:inline-block;">
    🌸 Login Sekarang
  </a>
</div>
@endsection