@extends('layouts.app')

@php($tasmi = $tasmi ?? null)

@section('title', $tasmi ? 'Edit Tasmi' : 'Tambah Tasmi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>{{ $tasmi ? 'Edit Tasmi' : 'Tambah Tasmi' }}</h3>
    <a href="{{ route('admin.tasmi.index') }}" class="btn btn-secondary">Kembali</a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ $tasmi ? route('admin.tasmi.update', $tasmi->id) : route('admin.tasmi.store') }}">
            @csrf
            @if ($tasmi)
                @method('PUT')
            @endif

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Santri <span class="text-danger">*</span></label>
                    <select name="santri_id" class="form-select" required>
                        <option value="">Pilih Santri</option>
                        @foreach ($santriList as $s)
                            <option value="{{ $s->id }}" {{ (string) old('santri_id', $tasmi->santri_id ?? '') == (string) $s->id ? 'selected' : '' }}>
                                {{ $s->user->nama ?? $s->user->name }} ({{ $s->nis ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Musyrif <span class="text-danger">*</span></label>
                    <select name="musyrif_id" class="form-select" required>
                        <option value="">Pilih Musyrif</option>
                        @foreach ($musyrifList as $m)
                            <option value="{{ $m->id }}" {{ (string) old('musyrif_id', $tasmi->musyrif_id ?? '') == (string) $m->id ? 'selected' : '' }}>
                                {{ $m->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Juz <span class="text-danger">*</span></label>
                    <input type="number" name="juz" class="form-control" min="1" max="30" value="{{ old('juz', $tasmi->juz ?? '') }}" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Surah <span class="text-danger">*</span></label>
                    <input type="text" name="surah" class="form-control" maxlength="50" value="{{ old('surah', $tasmi->surah ?? '') }}" required>
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label">Ayat Awal</label>
                    <input type="number" name="ayat_awal" class="form-control" min="0" value="{{ old('ayat_awal', $tasmi->ayat_awal ?? 0) }}">
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label">Ayat Akhir</label>
                    <input type="number" name="ayat_akhir" class="form-control" min="0" value="{{ old('ayat_akhir', $tasmi->ayat_akhir ?? 0) }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Nilai</label>
                    @php($nilaiLama = old('nilai', $tasmi->nilai ?? 'lancar'))
                    <select name="nilai" class="form-select">
                        <option value="lancar" {{ $nilaiLama === 'lancar' ? 'selected' : '' }}>Lancar</option>
                        <option value="cukup_lancar" {{ $nilaiLama === 'cukup_lancar' ? 'selected' : '' }}>Cukup Lancar</option>
                        <option value="perlu_ulang" {{ $nilaiLama === 'perlu_ulang' ? 'selected' : '' }}>Perlu Ulang</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Tanggal Tasmi</label>
                    <input type="date" name="tanggal_tasmi" class="form-control" value="{{ old('tanggal_tasmi', ($tasmi && $tasmi->tanggal_tasmi) ? \Carbon\Carbon::parse($tasmi->tanggal_tasmi)->format('Y-m-d') : now()->format('Y-m-d')) }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Catatan</label>
                <textarea name="catatan" class="form-control" rows="3">{{ old('catatan', $tasmi->catatan ?? '') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">{{ $tasmi ? 'Update' : 'Simpan' }}</button>
        </form>
    </div>
</div>
@endsection