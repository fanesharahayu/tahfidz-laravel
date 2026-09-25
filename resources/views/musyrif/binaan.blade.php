@extends('layouts.app')

@section('title', 'Santri Binaan')

@section('content')
@php($hideLayoutErrors = true)
<div class="page-head">
    <div>
        <h2>Santri Binaan</h2>
        <p class="muted">{{ $binaan->count() }} binaan · {{ $unassigned->count() }} menunggu musyrif</p>
    </div>
</div>

@if ($errors->any())
    <div class="alert">{{ $errors->first() }}</div>
@endif

<div class="card">
    <h3>Daftar Binaan ({{ $binaan->count() }})</h3>
    <div class="table-wrap"><table>
        <tr><th>Nama</th><th>NIS</th><th>Kelas</th><th>Progress</th><th>Setoran</th><th></th></tr>
        @forelse ($binaan as $s)
            <tr>
                <td><strong>{{ $s->user->nama ?? '-' }}</strong></td>
                <td>{{ $s->nis ?? '-' }}</td>
                <td>{{ $s->kelas ?? '-' }}</td>
                <td>
                    <div class="progress-row">
                        <div class="progress"><div class="bar" style="width:{{ $s->progress['persenJuz'] ?? 0 }}%"></div></div>
                        <small>{{ $s->progress['juzTercapai'] ?? 0 }}/{{ $s->progress['targetJuz'] ?? 30 }}</small>
                    </div>
                </td>
                <td><span class="badge badge-blue">{{ $s->setoranCount ?? 0 }}x</span></td>
                <td><a class="btn btn-secondary btn-sm" href="{{ route('musyrif.santri.detail', $s->id) }}">Detail</a></td>
            </tr>
        @empty
            <tr><td colspan="6" class="muted">Belum ada santri binaan.</td></tr>
        @endforelse
    </table></div>
</div>

<div class="card">
    <h3>Ambil Santri Belum Bermusyrif</h3>
    <div class="table-wrap"><table>
        <tr><th>Nama</th><th>NIS</th><th>Kelas</th><th>Aksi</th></tr>
        @forelse ($unassigned as $s)
            <tr>
                <td>{{ $s->user->nama ?? '-' }}</td>
                <td>{{ $s->nis ?? '-' }}</td>
                <td>{{ $s->kelas ?? '-' }}</td>
                <td>
                    <form method="POST" action="{{ route('musyrif.santri.assign', $s->id) }}" class="inline-form">
                        @csrf
                        <button class="btn btn-sm" type="submit">+ Jadikan Binaan</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="muted">Tidak ada santri yang belum memiliki musyrif.</td></tr>
        @endforelse
    </table></div>
</div>

<div class="card">
    <h3>Tambah Santri Baru</h3>
    <form method="POST" action="{{ route('musyrif.santri.store') }}">
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
                <label>Password (min. 6 karakter)</label>
                <input class="input" type="password" name="password" required>
            </div>
            <div>
                <label>NIS</label>
                <input class="input" type="text" name="nis" value="{{ old('nis') }}">
            </div>
            <div>
                <label>Kelas</label>
                <input class="input" type="text" name="kelas" value="{{ old('kelas') }}">
            </div>
            <div class="full">
                <label>Target Juz</label>
                <input class="input" type="number" name="target_juz" min="1" max="30" value="{{ old('target_juz', 30) }}">
            </div>
        </div>
        <button class="btn" type="submit">+ Tambah Santri</button>
    </form>
</div>
@endsection
