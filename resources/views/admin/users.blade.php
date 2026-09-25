@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')
<h2>Kelola Pengguna</h2>

@if (session('status'))
    <div class="success">{{ session('status') }}</div>
@endif

@if ($errors->any())
    <div class="alert">
        <ul style="margin:0;padding-left:18px">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <h3>Daftar Pengguna</h3>
    <table>
        <tr><th>Nama</th><th>Username</th><th>Email</th><th>Role</th><th>Aksi</th></tr>
        @forelse ($users as $u)
            <tr>
                <td>{{ $u->nama }}</td>
                <td>{{ $u->username }}</td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->role }}</td>
                <td>
                    <form action="{{ url('/admin/users/' . $u->id) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit" onclick="return confirm('Hapus user ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="muted">Belum ada pengguna.</td></tr>
        @endforelse
    </table>
</div>

<div class="card">
    <h3>Tambah Pengguna (musyrif / wali / admin / santri)</h3>
    <form action="{{ url('/admin/users') }}" method="POST">
        @csrf
        <label>Nama</label>
        <input class="input" type="text" name="nama" value="{{ old('nama') }}" required>
        <label>Username</label>
        <input class="input" type="text" name="username" value="{{ old('username') }}" required>
        <label>Email</label>
        <input class="input" type="email" name="email" value="{{ old('email') }}" required>
        <label>Password (min 6)</label>
        <input class="input" type="password" name="password" required>
        <label>Role</label>
        <select class="input" name="role" required>
            <option value="musyrif" {{ old('role') === 'musyrif' ? 'selected' : '' }}>musyrif</option>
            <option value="wali" {{ old('role') === 'wali' ? 'selected' : '' }}>wali</option>
            <option value="santri" {{ old('role') === 'santri' ? 'selected' : '' }}>santri</option>
            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>admin</option>
        </select>
        <button class="btn" type="submit">Tambah User</button>
    </form>
</div>
@endsection
