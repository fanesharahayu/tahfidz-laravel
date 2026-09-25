@extends('layouts.app')

@section('title', 'Target Hafalan')

@section('content')
@php($hideLayoutErrors = true)
<div class="page-head">
    <div>
        <h2>Target Hafalan</h2>
        <p class="muted">Tetapkan target juz per santri per periode.</p>
    </div>
</div>

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
    <h3>Daftar Target</h3>
    <div class="table-wrap"><table>
        <tr><th>Santri</th><th>Target Juz</th><th>Periode</th><th>Mulai</th><th>Selesai</th><th>Aksi</th></tr>
        @forelse (($targets ?? []) as $t)
            <tr>
                <td><strong>{{ $t->santri->user->nama ?? '-' }}</strong></td>
                <td><span class="badge badge-blue">{{ $t->target_juz }} juz</span></td>
                <td>{{ $t->periode ?? '-' }}</td>
                <td>{{ $t->tanggal_mulai ? $t->tanggal_mulai->format('Y-m-d') : '-' }}</td>
                <td>{{ $t->tanggal_selesai ? $t->tanggal_selesai->format('Y-m-d') : '-' }}</td>
                <td>
                    <form action="{{ url('/admin/target/' . $t->id) }}" method="POST" class="inline-form" data-confirm="Target hafalan ini akan dihapus permanen.">
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

<div class="card">
    <h3>Tambah Target</h3>
    <form action="{{ url('/admin/target') }}" method="POST">
        @csrf
        <div class="form-grid">
            <div>
                <label>Santri</label>
                <select class="input" name="santri_id" required>
                    <option value="">-- Pilih santri --</option>
                    @foreach (($santriList ?? []) as $s)
                        <option value="{{ $s->id }}" {{ (string) old('santri_id') === (string) $s->id ? 'selected' : '' }}>{{ $s->user->nama ?? ('Santri #' . $s->id) }}</option>
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
@endsection
