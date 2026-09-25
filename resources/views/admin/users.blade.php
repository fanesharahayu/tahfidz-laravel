@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')
<div class="page-head">
    <div>
        <h2>Kelola Pengguna</h2>
        <p class="muted">Tambah dan hapus akun musyrif, wali, santri, admin.</p>
    </div>
</div>

<div class="card">
    <h3>Daftar Pengguna</h3>
    <div class="table-wrap"><table>
        <tr><th>Nama</th><th>Username</th><th>Email</th><th>Role</th><th>Aksi</th></tr>
        @forelse ($users as $u)
            <tr>
                <td><strong>{{ $u->nama }}</strong></td>
                <td>{{ $u->username }}</td>
                <td>{{ $u->email }}</td>
                <td><span class="badge badge-gray">{{ $u->role }}</span></td>
                <td style="white-space:nowrap">
                    <a class="btn btn-secondary btn-sm" href="{{ route('admin.users.edit', $u->id) }}"><i data-lucide="pencil"></i> Edit</a>
                    <form action="{{ url('/admin/users/' . $u->id) }}" method="POST" class="inline-form" data-confirm="Akun {{ $u->nama }} beserta seluruh datanya akan dihapus permanen.">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="muted">Belum ada pengguna.</td></tr>
        @endforelse
    </table></div>
</div>

<div class="card">
    <h3>Tambah Pengguna</h3>
    <form action="{{ url('/admin/users') }}" method="POST">
        @csrf
        <div class="form-grid">
            <div>
                <label>Nama</label>
                <input class="input" type="text" name="nama" value="{{ old('nama') }}" required>
            </div>
            <div>
                <label>Username</label>
                <input class="input" type="text" name="username" value="{{ old('username') }}" required>
            </div>
            <div>
                <label>Email</label>
                <input class="input" type="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div>
                <label>Role</label>
                <select class="input" name="role" required>
                    <option value="musyrif" @selected(old('role') === 'musyrif')>Musyrif</option>
                    <option value="wali" @selected(old('role') === 'wali')>Wali Santri</option>
                    <option value="santri" @selected(old('role') === 'santri')>Santri</option>
                    <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                </select>
            </div>
            <div class="full">
                <label>Password (min 6)</label>
                <input class="input" type="password" name="password" required>
            </div>
        </div>
        <button class="btn" type="submit">+ Tambah User</button>
    </form>
</div>
@endsection
