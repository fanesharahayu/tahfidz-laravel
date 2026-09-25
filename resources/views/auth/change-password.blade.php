@extends('layouts.app')

@section('title', 'Ganti Password')

@section('content')
<div class="card" style="max-width:480px">
    <h2>Ganti Password</h2>
    @if ($errors->any())
        <div class="alert">{{ $errors->first() }}</div>
    @endif
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <label>Password lama</label>
        <input class="input" type="password" name="oldPassword" required>
        <label>Password baru (min. 6)</label>
        <input class="input" type="password" name="newPassword" required>
        <label>Konfirmasi password baru</label>
        <input class="input" type="password" name="newPassword_confirmation" required>
        <button class="btn" type="submit">Simpan</button>
    </form>
</div>
@endsection
