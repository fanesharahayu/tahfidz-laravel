@extends('layouts.app')

@section('title', 'Login')

@section('content')
<h2 style="margin:0 0 4px">Selamat datang kembali</h2>
<p class="muted" style="margin-top:0">Masuk dengan username atau email.</p>

@if ($errors->any())
    <div class="alert">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('login.attempt') }}">
    @csrf
    <label>Username / Email</label>
    <input class="input" type="text" name="username" value="{{ old('username') }}" required autofocus placeholder="cth: musyrif1">
    <label>Password</label>
    <input class="input" type="password" name="password" required placeholder="••••••••">
    <label style="font-weight:400;font-size:14px"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
    <button class="btn" type="submit" style="width:100%;margin-top:12px;padding:12px">Masuk <i data-lucide="log-in"></i></button>
</form>

<div class="muted" style="margin-top:14px;text-align:center">
    Belum punya akun?
    <a href="https://wa.me/{{ env('ADMIN_PHONE', '6289527944636') }}?text=Assalamualaikum%20Admin%2C%20saya%20ingin%20minta%20password%20login%20Tahfidz%20Monitor" target="_blank" rel="noopener noreferrer" style="color:var(--green-700);text-decoration:none;font-weight:600" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
        hubungi admin
    </a>
    untuk memiliki password
</div>
@endsection
