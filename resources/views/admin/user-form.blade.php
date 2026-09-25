@extends('layouts.app')

@section('title', isset($user) ? 'Ubah Pengguna' : 'Tambah Pengguna')

@section('content')
<div class="page-head">
    <div>
        <h2>{{ isset($user) ? 'Ubah Pengguna' : 'Tambah Pengguna' }}</h2>
        <p class="muted">{{ isset($user) ? ($user->nama . ' · ' . $user->username) : 'Buatkan akun musyrif, wali, santri, atau admin baru.' }}</p>
    </div>
    <div>
        <a class="btn btn-secondary btn-sm" href="{{ route('admin.users.index') }}"><i data-lucide="arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="card" style="max-width:640px">
    @if (isset($user))
        <form action="{{ url('/admin/users/' . $user->id) }}" method="POST">
            @csrf
            @method('PUT')
    @else
        <form action="{{ url('/admin/users') }}" method="POST">
            @csrf
    @endif
        <div class="form-grid">
            <div>
                <label>Nama</label>
                <input class="input" type="text" name="nama" value="{{ old('nama', $user->nama ?? '') }}" required>
            </div>
            <div>
                <label>Username</label>
                <input class="input" type="text" name="username" value="{{ old('username', $user->username ?? '') }}" required>
            </div>
            <div>
                <label>Email</label>
                <input class="input" type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
            </div>
            <div>
                <label>Role</label>
                <select class="input" name="role" required>
                    @foreach (['musyrif' => 'Musyrif', 'wali' => 'Wali Santri', 'santri' => 'Santri', 'admin' => 'Admin'] as $v => $l)
                        <option value="{{ $v }}" @selected(old('role', $user->role ?? 'musyrif') === $v)>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="full">
                @if (isset($user))
                    <label>Password baru (kosongkan bila tidak diganti)</label>
                    <input class="input" type="password" name="password" placeholder="min. 6 karakter">
                @else
                    <label>Password (min 6)</label>
                    <input class="input" type="password" name="password" required>
                @endif
            </div>
        </div>
        <button class="btn" type="submit">{{ isset($user) ? 'Simpan Perubahan' : '+ Tambah User' }}</button>
    </form>
</div>
@endsection
