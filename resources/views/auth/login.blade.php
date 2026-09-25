@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="card" style="max-width:420px;margin:40px auto">
    <h2>Login Tahfidz</h2>
    <p class="muted">Masuk dengan username atau email.</p>

    @if ($errors->any())
        <div class="alert">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login.attempt') }}">
        @csrf
        <label>Username / Email</label>
        <input class="input" type="text" name="username" value="{{ old('username') }}" required autofocus>
        <label>Password</label>
        <input class="input" type="password" name="password" required>
        <label style="font-size:14px"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
        <button class="btn" type="submit" style="width:100%;margin-top:12px">Masuk</button>
    </form>
    <p class="muted" style="margin-top:12px">Registrasi mandiri dinonaktifkan. Hubungi admin untuk dibuatkan akun.</p>
</div>
@endsection
