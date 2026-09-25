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
<p class="muted" style="margin-top:14px">Registrasi mandiri dinonaktifkan. Hubungi admin untuk dibuatkan akun.</p>
<p class="muted">Akun demo: <code>admin / admin123</code> · <code>musyrif1 / musyrif123</code> · <code>santri1 / santri123</code> · <code>wali1 / wali123</code></p>
@endsection
