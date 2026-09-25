@extends('layouts.app')

@section('title', isset($target) ? 'Ubah Target' : 'Tambah Target')

@section('content')
<div class="page-head">
    <div>
        <h2>{{ isset($target) ? 'Ubah Target' : 'Tambah Target' }}</h2>
        <p class="muted">{{ isset($target) ? (($target->santri->user->nama ?? 'Santri') . ' · target ' . $target->target_juz . ' juz') : 'Tetapkan target juz santri per periode.' }}</p>
    </div>
    <div>
        <a class="btn btn-secondary btn-sm" href="{{ route('admin.targets.index') }}"><i data-lucide="arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="card" style="max-width:640px">
    @if (isset($target))
        <form action="{{ url('/admin/target/' . $target->id) }}" method="POST">
            @csrf
            @method('PUT')
    @else
        <form action="{{ url('/admin/target') }}" method="POST">
            @csrf
    @endif
        <div class="form-grid">
            @if (!isset($target))
                <div class="full">
                    <label>Santri</label>
                    <select class="input" name="santri_id" required>
                        <option value="">-- Pilih santri --</option>
                        @foreach (($santriList ?? []) as $s)
                            <option value="{{ $s->id }}" {{ (string) old('santri_id') === (string) $s->id ? 'selected' : '' }}>{{ $s->user->nama ?? ('Santri #' . $s->id) }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div>
                <label>Target Juz</label>
                <input class="input" type="number" name="target_juz" min="1" max="30" value="{{ old('target_juz', $target->target_juz ?? '') }}" required>
            </div>
            <div>
                <label>Periode</label>
                <input class="input" type="text" name="periode" value="{{ old('periode', $target->periode ?? '') }}" placeholder="cth: 2025-2026 Semester Ganjil">
            </div>
            <div>
                <label>Tanggal Mulai</label>
                <input class="input" type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', isset($target) && $target->tanggal_mulai ? $target->tanggal_mulai->format('Y-m-d') : '') }}">
            </div>
            <div>
                <label>Tanggal Selesai</label>
                <input class="input" type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', isset($target) && $target->tanggal_selesai ? $target->tanggal_selesai->format('Y-m-d') : '') }}">
            </div>
        </div>
        <button class="btn" type="submit">{{ isset($target) ? 'Simpan Perubahan' : '+ Simpan Target' }}</button>
    </form>
</div>
@endsection
