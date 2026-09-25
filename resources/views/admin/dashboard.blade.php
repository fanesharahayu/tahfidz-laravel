@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<h2>Dashboard Admin</h2>
<div class="stats">
    <div class="stat"><div class="num">{{ $jumlahSantri }}</div><div class="lbl">Santri</div></div>
    <div class="stat"><div class="num">{{ $jumlahSetoran }}</div><div class="lbl">Setoran</div></div>
    <div class="stat"><div class="num">{{ $jumlahMusyrif }}</div><div class="lbl">Musyrif</div></div>
    <div class="stat"><div class="num">{{ $jumlahWali }}</div><div class="lbl">Wali</div></div>
</div>

<div class="card">
    <h3>Santri</h3>
    <table>
        <tr><th>Nama</th><th>NIS</th><th>Kelas</th><th>Target</th><th>Musyrif</th></tr>
        @forelse ($santri as $s)
            <tr>
                <td>{{ $s->user->nama ?? '-' }}</td>
                <td>{{ $s->nis ?? '-' }}</td>
                <td>{{ $s->kelas ?? '-' }}</td>
                <td>{{ $s->target_juz }} juz</td>
                <td>{{ $s->musyrif->nama ?? '-' }}</td>
            </tr>
        @empty
            <tr><td colspan="5" class="muted">Belum ada data santri.</td></tr>
        @endforelse
    </table>
</div>

<div class="card">
    <h3>Setoran Terbaru</h3>
    <table>
        <tr><th>Santri</th><th>Juz</th><th>Surah</th><th>Jenis</th><th>Nilai</th></tr>
        @forelse ($setoran as $st)
            <tr>
                <td>{{ $st->santri->user->nama ?? '-' }}</td>
                <td>{{ $st->juz }}</td>
                <td>{{ $st->surah }}</td>
                <td>{{ $st->jenis }}</td>
                <td>{{ $st->nilai }}</td>
            </tr>
        @empty
            <tr><td colspan="5" class="muted">Belum ada setoran.</td></tr>
        @endforelse
    </table>
</div>

<div class="card">
    <h3>Pengguna</h3>
    <table>
        <tr><th>Nama</th><th>Username</th><th>Email</th><th>Role</th></tr>
        @foreach ($users as $u)
            <tr><td>{{ $u->nama }}</td><td>{{ $u->username }}</td><td>{{ $u->email }}</td><td>{{ $u->role }}</td></tr>
        @endforeach
    </table>
</div>
@endsection
