@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')
<div class="page-head">
    <div>
        <h2>Kelola Pengguna</h2>
        <p class="muted">{{ $users->count() }} akun · tambah dan hapus akun musyrif, wali, santri, admin.</p>
    </div>
    <div>
        <a class="btn btn-sm" href="{{ route('admin.users.create') }}">+ Tambah Pengguna</a>
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
@endsection
