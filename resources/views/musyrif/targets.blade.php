@extends('layouts.app')

@section('title', 'Target Hafalan Binaan')

@section('content')
<h2>Target Hafalan Binaan</h2>
<p class="muted">
    <a href="{{ route('musyrif.dashboard') }}">Dashboard</a> |
    <a href="{{ route('musyrif.binaan.index') }}">Binaan</a> |
    <a href="{{ route('musyrif.setoran.index') }}">Setoran</a> |
    <a href="{{ route('musyrif.wali.index') }}">Wali</a>
</p>

@if ($errors->any())
    <div class="alert">{{ $errors->first() }}</div>
@endif

<div class="card">
    <h3>Buat Target</h3>
    <form method="POST" action="{{ route('musyrif.target.store') }}">
        @csrf
        <label>Santri</label>
        <select class="input" name="santri_id" required>
            @foreach ($binaan as $s)
                <option value="{{ $s->id }}">{{ $s->user->nama ?? '-' }} ({{ $s->kelas ?? '-' }})</option>
            @endforeach
        </select>
        <label>Target Juz</label>
        <input class="input" type="number" name="target_juz" min="1" max="30" value="{{ old('target_juz') }}" required>
        <label>Periode</label>
        <input class="input" type="text" name="periode" value="{{ old('periode') }}">
        <div style="display:flex;gap:12px">
            <div style="flex:1">
                <label>Tanggal Mulai</label>
                <input class="input" type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}">
            </div>
            <div style="flex:1">
                <label>Tanggal Selesai</label>
                <input class="input" type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}">
            </div>
        </div>
        <button class="btn" type="submit">Simpan Target</button>
    </form>
</div>

<div class="card">
    <h3>Daftar Target</h3>
    <table>
        <tr><th>Santri</th><th>Target</th><th>Periode</th><th>Mulai</th><th>Selesai</th><th>Aksi</th></tr>
        @forelse ($targets as $t)
            <tr>
                <td>{{ $t->santri->user->nama ?? '-' }}</td>
                <td>{{ $t->target_juz }} juz</td>
                <td>{{ $t->periode ?? '-' }}</td>
                <td>{{ $t->tanggal_mulai ?? '-' }}</td>
                <td>{{ $t->tanggal_selesai ?? '-' }}</td>
                <td>
                    <form method="POST" action="{{ route('musyrif.target.destroy', $t->id) }}" onsubmit="return confirm('Hapus target ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="muted">Belum ada target.</td></tr>
        @endforelse
    </table>
</div>
@endsection
