@extends('layouts.app')

@php($tasmi = $tasmi ?? null)
@php($isEdit = !empty($tasmi))

@section('title', $isEdit ? 'Edit Tasmi' : 'Tambah Tasmi')

@section('content')
<div class="page-head">
    <div>
        <h2>{{ $isEdit ? 'Edit Tasmi' : 'Tambah Tasmi' }}</h2>
        <p class="muted">{{ $isEdit ? 'Perbarui catatan tasmi hafalan santri.' : 'Catat tasmi hafalan baru untuk santri.' }}</p>
    </div>
    <div>
        <a href="{{ route('admin.tasmi.index') }}" class="btn btn-secondary"><i data-lucide="arrow-left"></i> Kembali</a>
    </div>
</div>

<form method="POST" action="{{ $isEdit ? route('admin.tasmi.update', $tasmi->id) : route('admin.tasmi.store') }}">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="card">
        <h3>Data Tasmi</h3>
        <p class="muted">Pilih santri yang bertasmi dan musyrif yang menyimak.</p>
        <div class="form-grid">
            <div>
                <label for="santri_id">Santri <span style="color:var(--red-tx)">*</span></label>
                <select id="santri_id" name="santri_id" class="input" required>
                    <option value="">Pilih Santri</option>
                    @foreach ($santriList as $s)
                        <option value="{{ $s->id }}" {{ (string) old('santri_id', $tasmi->santri_id ?? '') == (string) $s->id ? 'selected' : '' }}>
                            {{ $s->user->nama ?? $s->user->name }} ({{ $s->nis ?? '-' }})
                        </option>
                    @endforeach
                </select>
                @error('santri_id')
                    <small style="color:var(--red-tx);font-size:12px">{{ $message }}</small>
                @enderror
            </div>

            <div>
                <label for="musyrif_id">Musyrif <span style="color:var(--red-tx)">*</span></label>
                <select id="musyrif_id" name="musyrif_id" class="input" required>
                    <option value="">Pilih Musyrif</option>
                    @foreach ($musyrifList as $m)
                        <option value="{{ $m->id }}" {{ (string) old('musyrif_id', $tasmi->musyrif_id ?? '') == (string) $m->id ? 'selected' : '' }}>
                            {{ $m->nama }}
                        </option>
                    @endforeach
                </select>
                @error('musyrif_id')
                    <small style="color:var(--red-tx);font-size:12px">{{ $message }}</small>
                @enderror
            </div>
        </div>
    </div>

    <div class="card">
        <h3>Detail Hafalan</h3>
        <p class="muted">Catat juz, surah, rentang ayat, dan penilaian tasmi.</p>
        <div class="form-grid">
            <div>
                <label for="juz">Juz Awal <span style="color:var(--red-tx)">*</span></label>
                <input id="juz" type="number" name="juz" class="input" min="1" max="30" value="{{ old('juz', $tasmi->juz ?? '') }}" required>
                @error('juz')
                    <small style="color:var(--red-tx);font-size:12px">{{ $message }}</small>
                @enderror
            </div>

            <div>
                <label for="juz_akhir">Juz Akhir</label>
                <input id="juz_akhir" type="number" name="juz_akhir" class="input" min="1" max="30" value="{{ old('juz_akhir', $tasmi->juz_akhir ?? '') }}">
                @error('juz_akhir')
                    <small style="color:var(--red-tx);font-size:12px">{{ $message }}</small>
                @enderror
            </div>

            <div>
                <label for="surah">Surah Awal <span style="color:var(--red-tx)">*</span></label>
                <input id="surah" type="text" name="surah" class="input" maxlength="50" value="{{ old('surah', $tasmi->surah ?? '') }}" required>
                @error('surah')
                    <small style="color:var(--red-tx);font-size:12px">{{ $message }}</small>
                @enderror
            </div>

            <div>
                <label for="surah_akhir">Surah Akhir</label>
                <input id="surah_akhir" type="text" name="surah_akhir" class="input" maxlength="50" value="{{ old('surah_akhir', $tasmi->surah_akhir ?? '') }}">
                @error('surah_akhir')
                    <small style="color:var(--red-tx);font-size:12px">{{ $message }}</small>
                @enderror
            </div>

            <div class="full">
                <label for="nilai">Nilai</label>
                @php($nilaiLama = old('nilai', $tasmi->nilai ?? 'lancar'))
                <select id="nilai" name="nilai" class="input">
                    <option value="lancar" {{ $nilaiLama === 'lancar' ? 'selected' : '' }}>Lancar</option>
                    <option value="cukup_lancar" {{ $nilaiLama === 'cukup_lancar' ? 'selected' : '' }}>Cukup Lancar</option>
                    <option value="perlu_ulang" {{ $nilaiLama === 'perlu_ulang' ? 'selected' : '' }}>Perlu Ulang</option>
                </select>
                @error('nilai')
                    <small style="color:var(--red-tx);font-size:12px">{{ $message }}</small>
                @enderror
            </div>
        </div>
    </div>

    <div class="card">
        <h3>Informasi Tambahan</h3>
        <p class="muted">Tanggal pelaksanaan dan catatan penyimak.</p>
        <div class="form-grid">
            <div>
                <label for="tanggal_tasmi">Tanggal Tasmi</label>
                <input id="tanggal_tasmi" type="date" name="tanggal_tasmi" class="input" value="{{ old('tanggal_tasmi', ($tasmi && $tasmi->tanggal_tasmi) ? \Carbon\Carbon::parse($tasmi->tanggal_tasmi)->format('Y-m-d') : now()->format('Y-m-d')) }}">
                @error('tanggal_tasmi')
                    <small style="color:var(--red-tx);font-size:12px">{{ $message }}</small>
                @enderror
            </div>

            <div class="full">
                <label for="catatan">Catatan</label>
                <textarea id="catatan" name="catatan" class="input" rows="3">{{ old('catatan', $tasmi->catatan ?? '') }}</textarea>
                @error('catatan')
                    <small style="color:var(--red-tx);font-size:12px">{{ $message }}</small>
                @enderror
            </div>
        </div>
    </div>

    <div style="display:flex;gap:10px;flex-wrap:wrap">
        <button type="submit" class="btn"><i data-lucide="save"></i> {{ $isEdit ? 'Update Tasmi' : 'Simpan Tasmi' }}</button>
        <a href="{{ route('admin.tasmi.index') }}" class="btn btn-secondary">Batal</a>
    </div>
</form>
@endsection
