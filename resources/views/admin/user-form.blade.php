@extends('layouts.app')

@section('title', 'Ubah Pengguna')

@section('content')
<div class="page-head">
    <div>
        <h2>Ubah Pengguna</h2>
        <p class="muted">{{ $user->nama }} · {{ $user->username }}</p>
    </div>
    <div>
        <a class="btn btn-secondary btn-sm" href="{{ route('admin.users.index') }}"><i data-lucide="arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="card" style="max-width:640px">
    <form action="{{ url('/admin/users/' . $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <div>
                <label>Nama</label>
                <input class="input" type="text" name="nama" value="{{ old('nama', $user->nama) }}" required>
            </div>
            <div>
                <label>Username</label>
                <input class="input" type="text" name="username" value="{{ old('username', $user->username) }}" required>
            </div>
            <div>
                <label>Email</label>
                <input class="input" type="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>
            <div>
                <label>Role</label>
                <select class="input" name="role" required>
                    @foreach (['admin', 'musyrif', 'santri', 'wali'] as $r)
                        <option value="{{ $r }}" @selected(old('role', $user->role) === $r)>{{ ucfirst($r) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="full">
                <label>Password baru (kosongkan bila tidak diganti)</label>
                <input class="input" type="password" name="password" placeholder="min. 6 karakter">
            </div>
        </div>
        <button class="btn" type="submit">Simpan Perubahan</button>
    </form>
</div>
@endsection
