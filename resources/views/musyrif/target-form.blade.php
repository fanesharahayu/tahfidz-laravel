@extends('layouts.app')

@section('title', 'Ubah Target')

@section('content')
<div class="page-head">
    <div>
        <h2>Ubah Target</h2>
        <p class="muted">{{ $target->santri->user->nama ?? 'Santri' }}</p>
    </div>
    <div>
        <a class="btn btn-secondary btn-sm" href="{{ route('musyrif.targets.index') }}"><i data-lucide="arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="card" style="max-width:640px">
    <form method="POST" action="{{ route('musyrif.target.update', $target->id) }}">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <div>
                <label>Target Juz</label>
                <input class="input" type="number" name="target_juz" min="1" max="30" value="{{ old('target_juz', $target->target_juz) }}" required>
            </div>
            <div>
                <label>Periode</label>
                <input class="input" type="text" name="periode" value="{{ old('periode', $target->periode) }}" placeholder="cth: 2025-2026 Semester Ganjil">
            </div>
            <div>
                <label>Tanggal Mulai</label>
                <input class="input" type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $target->tanggal_mulai) }}">
            </div>
            <div>
                <label>Tanggal Selesai</label>
                <input class="input" type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $target->tanggal_selesai) }}">
            </div>
        </div>
        <button class="btn" type="submit">Simpan Perubahan</button>
    </form>
</div>
@endsection
