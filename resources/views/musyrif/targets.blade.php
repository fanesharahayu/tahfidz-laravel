@extends('layouts.app')

@section('title', 'Target Hafalan Binaan')

@section('content')
@php($hideLayoutErrors = true)
<div class="page-head">
    <div>
        <h2>Target Hafalan Binaan</h2>
        <p class="muted">Tetapkan dan kelola target juz santri binaan.</p>
    </div>
</div>

@if ($errors->any())
    <div class="alert">{{ $errors->first() }}</div>
@endif

<div class="card">
    <h3>Buat Target</h3>
    <form method="POST" action="{{ route('musyrif.target.store') }}">
        @csrf
        <div class="form-grid">
            <div>
                <label>Santri</label>
                <select class="input" name="santri_id" required>
                    @foreach ($binaan as $s)
                        <option value="{{ $s->id }}">{{ $s->user->nama ?? '-' }} ({{ $s->kelas ?? '-' }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Target Juz</label>
                <input class="input" type="number" name="target_juz" min="1" max="30" value="{{ old('target_juz') }}" required>
            </div>
            <div class="full">
                <label>Periode</label>
                <input class="input" type="text" name="periode" value="{{ old('periode') }}" placeholder="cth: 2025-2026 Semester Ganjil">
            </div>
            <div>
                <label>Tanggal Mulai</label>
                <input class="input" type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}">
            </div>
            <div>
                <label>Tanggal Selesai</label>
                <input class="input" type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}">
            </div>
        </div>
        <button class="btn" type="submit">Simpan Target</button>
    </form>
</div>

<div class="card">
    <h3>Daftar Target</h3>
    <div class="table-wrap"><table>
        <tr><th>Santri</th><th>Target</th><th>Periode</th><th>Mulai</th><th>Selesai</th><th>Aksi</th></tr>
        @forelse ($targets as $t)
            <tr>
                <td><strong>{{ $t->santri->user->nama ?? '-' }}</strong></td>
                <td><span class="badge badge-blue">{{ $t->target_juz }} juz</span></td>
                <td>{{ $t->periode ?? '-' }}</td>
                <td>{{ $t->tanggal_mulai ?? '-' }}</td>
                <td>{{ $t->tanggal_selesai ?? '-' }}</td>
                <td>
                    <form method="POST" action="{{ route('musyrif.target.destroy', $t->id) }}" class="inline-form" onsubmit="return confirm('Hapus target ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="muted">Belum ada target.</td></tr>
        @endforelse
    </table></div>
</div>
@endsection
