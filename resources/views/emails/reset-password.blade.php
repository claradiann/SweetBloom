@extends('emails.layout', ['subtitle' => 'Buat password baru'])

@section('body')
<h2 style="color:#be185d;margin:0 0 16px;font-family:Georgia,serif;">Halo, {{ $name }}! 🔐</h2>
<p style="color:#6b7280;line-height:1.8;margin:0 0 16px;">Kami menerima permintaan reset password untuk akun yang terdaftar dengan email ini.</p>
<p style="color:#6b7280;line-height:1.8;margin:0 0 24px;">Klik tombol di bawah untuk membuat password baru.</p>

<div style="text-align:center;margin:32px 0;">
  <a href="{{ $resetUrl }}" style="background:linear-gradient(135deg,#ec4899,#be185d);color:#ffffff;text-decoration:none;padding:14px 40px;border-radius:50px;font-weight:600;font-size:15px;display:inline-block;">
    🔐 Reset Password Saya
  </a>
</div>
<p style="color:#9ca3af;font-size:12px;text-align:center;margin:0;">Atau salin: <a href="{{ $resetUrl }}" style="color:#ec4899;">{{ $resetUrl }}</a></p>

<div style="margin-top:32px;padding:16px;background:#fff7ed;border-radius:12px;border-left:4px solid #fed7aa;">
  <p style="color:#9ca3af;font-size:13px;margin:0;">⏰ Link berlaku selama <strong>1 jam</strong>.<br>Abaikan jika kamu tidak meminta reset password.</p>
</div>
@endsection