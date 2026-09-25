@extends('layouts.app')

@section('title', 'Ganti Password')

@section('content')
<div class="page-head">
    <div>
        <h2>Ganti Password</h2>
        <p class="muted">Minimal 6 karakter. Jangan bagikan password ke siapa pun.</p>
    </div>
</div>
<div class="card" style="max-width:480px">
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <label>Password lama</label>
        <input class="input" type="password" name="oldPassword" required autofocus>
        <label>Password baru (min. 6)</label>
        <input class="input" type="password" name="newPassword" required>
        <label>Konfirmasi password baru</label>
        <input class="input" type="password" name="newPassword_confirmation" required>
        <button class="btn" type="submit" style="width:100%">Simpan Password</button>
    </form>
</div>
@endsection
