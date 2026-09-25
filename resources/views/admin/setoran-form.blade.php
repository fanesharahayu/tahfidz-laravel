@extends('layouts.app')

@section('title', isset($setoran) ? 'Ubah Setoran' : 'Tambah Setoran')

@section('content')
<div class="page-head">
    <div>
        <h2>{{ isset($setoran) ? 'Ubah Setoran' : 'Tambah Setoran' }}</h2>
        <p class="muted">{{ isset($setoran) ? 'Koreksi data setoran yang sudah tercatat.' : 'Catat setoran hafalan atas nama admin.' }}</p>
    </div>
</div>

<div class="card">
    @if (isset($setoran))
        <form action="{{ url('/admin/setoran/' . $setoran->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="full">
                    <label>Musyrif</label>
                    <select class="input" name="musyrif_id">
                        <option value="">-- Pilih musyrif --</option>
                        @foreach (($musyrifList ?? []) as $m)
                            <option value="{{ $m->id }}" {{ (string) old('musyrif_id', $setoran->musyrif_id) === (string) $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Juz</label>
                    <input class="input" type="number" name="juz" min="1" max="30" value="{{ old('juz', $setoran->juz) }}">
                </div>
                <div>
                    <label>Surah</label>
                    <input class="input" type="text" name="surah" value="{{ old('surah', $setoran->surah) }}">
                </div>
                <div>
                    <label>Ayat Awal</label>
                    <input class="input" type="number" name="ayat_awal" min="0" value="{{ old('ayat_awal', $setoran->ayat_awal) }}">
                </div>
                <div>
                    <label>Ayat Akhir</label>
                    <input class="input" type="number" name="ayat_akhir" min="0" value="{{ old('ayat_akhir', $setoran->ayat_akhir) }}">
                </div>
                <div>
                    <label>Jenis</label>
                    <select class="input" name="jenis">
                        @foreach (['hafalan_baru', 'tambahan', 'murajaah'] as $j)
                            <option value="{{ $j }}" {{ old('jenis', $setoran->jenis) === $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Nilai</label>
                    <select class="input" name="nilai">
                        @foreach (['lancar', 'cukup_lancar', 'perlu_ulang'] as $n)
                            <option value="{{ $n }}" {{ old('nilai', $setoran->nilai) === $n ? 'selected' : '' }}>{{ $n }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="full">
                    <label>Catatan</label>
                    <textarea class="input" name="catatan">{{ old('catatan', $setoran->catatan) }}</textarea>
                </div>
            </div>
            <button class="btn" type="submit">Simpan Perubahan</button>
        </form>
    @else
        <form action="{{ url('/admin/setoran') }}" method="POST">
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
                    <label>Musyrif</label>
                    <select class="input" name="musyrif_id" required>
                        <option value="">-- Pilih musyrif --</option>
                        @foreach (($musyrifList ?? []) as $m)
                            <option value="{{ $m->id }}" {{ (string) old('musyrif_id') === (string) $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Juz</label>
                    <input class="input" type="number" name="juz" min="1" max="30" value="{{ old('juz') }}" required>
                </div>
                <div>
                    <label>Surah</label>
                    <input class="input" type="text" name="surah" value="{{ old('surah') }}" required>
                </div>
                <div>
                    <label>Ayat Awal</label>
                    <input class="input" type="number" name="ayat_awal" min="0" value="{{ old('ayat_awal', 0) }}">
                </div>
                <div>
                    <label>Ayat Akhir</label>
                    <input class="input" type="number" name="ayat_akhir" min="0" value="{{ old('ayat_akhir', 0) }}">
                </div>
                <div>
                    <label>Jenis</label>
                    <select class="input" name="jenis">
                        @foreach (['hafalan_baru', 'tambahan', 'murajaah'] as $j)
                            <option value="{{ $j }}" {{ old('jenis', 'hafalan_baru') === $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Nilai</label>
                    <select class="input" name="nilai">
                        @foreach (['lancar', 'cukup_lancar', 'perlu_ulang'] as $n)
                            <option value="{{ $n }}" {{ old('nilai', 'lancar') === $n ? 'selected' : '' }}>{{ $n }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="full">
                    <label>Catatan</label>
                    <textarea class="input" name="catatan">{{ old('catatan') }}</textarea>
                </div>
            </div>
            <button class="btn" type="submit">Catat Setoran</button>
        </form>
    @endif
</div>
@endsection
