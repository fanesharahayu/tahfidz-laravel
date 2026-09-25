@extends('layouts.app')

@section('title', 'Santri Binaan')

@section('content')
<h2>Santri Binaan</h2>
<p class="muted">
    <a href="{{ route('musyrif.dashboard') }}">Dashboard</a> |
    <a href="{{ route('musyrif.setoran.index') }}">Setoran</a> |
    <a href="{{ route('musyrif.targets.index') }}">Target</a> |
    <a href="{{ route('musyrif.wali.index') }}">Wali</a>
</p>

@if ($errors->any())
    <div class="alert">{{ $errors->first() }}</div>
@endif

<div class="card">
    <h3>Daftar Binaan ({{ $binaan->count() }})</h3>
    <table>
        <tr><th>Nama</th><th>NIS</th><th>Kelas</th><th>Progress</th><th>Setoran</th><th></th></tr>
        @forelse ($binaan as $s)
            <tr>
                <td>{{ $s->user->nama ?? '-' }}</td>
                <td>{{ $s->nis ?? '-' }}</td>
                <td>{{ $s->kelas ?? '-' }}</td>
                <td>{{ $s->progress['juzTercapai'] ?? 0 }}/{{ $s->progress['targetJuz'] ?? 30 }} juz ({{ $s->progress['persenJuz'] ?? 0 }}%)</td>
                <td>{{ $s->setoranCount ?? 0 }}x</td>
                <td><a class="btn" href="{{ route('musyrif.santri.detail', $s->id) }}">Detail</a></td>
            </tr>
        @empty
            <tr><td colspan="6" class="muted">Belum ada santri binaan.</td></tr>
        @endforelse
    </table>
</div>

<div class="card">
    <h3>Ambil Santri Belum Bermusyrif</h3>
    <table>
        <tr><th>Nama</th><th>NIS</th><th>Kelas</th><th>Aksi</th></tr>
        @forelse ($unassigned as $s)
            <tr>
                <td>{{ $s->user->nama ?? '-' }}</td>
                <td>{{ $s->nis ?? '-' }}</td>
                <td>{{ $s->kelas ?? '-' }}</td>
                <td>
                    <form method="POST" action="{{ route('musyrif.santri.assign', $s->id) }}">
                        @csrf
                        <button class="btn" type="submit">Jadikan Binaan</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="muted">Tidak ada santri yang belum memiliki musyrif.</td></tr>
        @endforelse
    </table>
</div>

<div class="card">
    <h3>Tambah Santri Baru</h3>
    <form method="POST" action="{{ route('musyrif.santri.store') }}">
        @csrf
        <label>Nama</label>
        <input class="input" type="text" name="nama" value="{{ old('nama') }}" required>
        <label>Username</label>
        <input class="input" type="text" name="username" value="{{ old('username') }}" required>
        <label>Email</label>
        <input class="input" type="email" name="email" value="{{ old('email') }}" required>
        <label>Password (min. 6 karakter)</label>
        <input class="input" type="password" name="password" required>
        <div style="display:flex;gap:12px">
            <div style="flex:1">
                <label>NIS</label>
                <input class="input" type="text" name="nis" value="{{ old('nis') }}">
            </div>
            <div style="flex:1">
                <label>Kelas</label>
                <input class="input" type="text" name="kelas" value="{{ old('kelas') }}">
            </div>
            <div style="flex:1">
                <label>Target Juz</label>
                <input class="input" type="number" name="target_juz" min="1" max="30" value="{{ old('target_juz', 30) }}">
            </div>
        </div>
        <button class="btn" type="submit">Tambah Santri</button>
    </form>
</div>
@endsection
