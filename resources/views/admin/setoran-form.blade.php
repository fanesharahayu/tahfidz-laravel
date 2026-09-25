@extends('layouts.app')

@section('title', isset($setoran) ? 'Ubah Setoran' : 'Tambah Setoran')

@section('content')
<h2>{{ isset($setoran) ? 'Ubah Setoran' : 'Tambah Setoran' }}</h2>

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
    @if (isset($setoran))
        <form action="{{ url('/admin/setoran/' . $setoran->id) }}" method="POST">
            @csrf
            @method('PUT')
            <label>Musyrif</label>
            <select class="input" name="musyrif_id">
                <option value="">-- Pilih musyrif --</option>
                @foreach (($musyrifList ?? []) as $m)
                    <option value="{{ $m->id }}" {{ (string) old('musyrif_id', $setoran->musyrif_id) === (string) $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                @endforeach
            </select>
            <label>Juz</label>
            <input class="input" type="number" name="juz" min="1" max="30" value="{{ old('juz', $setoran->juz) }}">
            <label>Surah</label>
            <input class="input" type="text" name="surah" value="{{ old('surah', $setoran->surah) }}">
            <label>Ayat Awal</label>
            <input class="input" type="number" name="ayat_awal" min="0" value="{{ old('ayat_awal', $setoran->ayat_awal) }}">
            <label>Ayat Akhir</label>
            <input class="input" type="number" name="ayat_akhir" min="0" value="{{ old('ayat_akhir', $setoran->ayat_akhir) }}">
            <label>Jenis</label>
            <select class="input" name="jenis">
                @foreach (['hafalan_baru', 'tambahan', 'murajaah'] as $j)
                    <option value="{{ $j }}" {{ old('jenis', $setoran->jenis) === $j ? 'selected' : '' }}>{{ $j }}</option>
                @endforeach
            </select>
            <label>Nilai</label>
            <select class="input" name="nilai">
                @foreach (['lancar', 'cukup_lancar', 'perlu_ulang'] as $n)
                    <option value="{{ $n }}" {{ old('nilai', $setoran->nilai) === $n ? 'selected' : '' }}>{{ $n }}</option>
                @endforeach
            </select>
            <label>Catatan</label>
            <textarea class="input" name="catatan">{{ old('catatan', $setoran->catatan) }}</textarea>
            <button class="btn" type="submit">Simpan Perubahan</button>
        </form>
    @else
        <form action="{{ url('/admin/setoran') }}" method="POST">
            @csrf
            <label>Santri</label>
            <select class="input" name="santri_id" required>
                <option value="">-- Pilih santri --</option>
                @foreach (($santriList ?? []) as $s)
                    <option value="{{ $s->id }}" {{ (string) old('santri_id') === (string) $s->id ? 'selected' : '' }}>{{ $s->user->nama ?? ('Santri #' . $s->id) }}</option>
                @endforeach
            </select>
            <label>Musyrif</label>
            <select class="input" name="musyrif_id" required>
                <option value="">-- Pilih musyrif --</option>
                @foreach (($musyrifList ?? []) as $m)
                    <option value="{{ $m->id }}" {{ (string) old('musyrif_id') === (string) $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                @endforeach
            </select>
            <label>Juz</label>
            <input class="input" type="number" name="juz" min="1" max="30" value="{{ old('juz') }}" required>
            <label>Surah</label>
            <input class="input" type="text" name="surah" value="{{ old('surah') }}" required>
            <label>Ayat Awal</label>
            <input class="input" type="number" name="ayat_awal" min="0" value="{{ old('ayat_awal', 0) }}">
            <label>Ayat Akhir</label>
            <input class="input" type="number" name="ayat_akhir" min="0" value="{{ old('ayat_akhir', 0) }}">
            <label>Jenis</label>
            <select class="input" name="jenis">
                @foreach (['hafalan_baru', 'tambahan', 'murajaah'] as $j)
                    <option value="{{ $j }}" {{ old('jenis', 'hafalan_baru') === $j ? 'selected' : '' }}>{{ $j }}</option>
                @endforeach
            </select>
            <label>Nilai</label>
            <select class="input" name="nilai">
                @foreach (['lancar', 'cukup_lancar', 'perlu_ulang'] as $n)
                    <option value="{{ $n }}" {{ old('nilai', 'lancar') === $n ? 'selected' : '' }}>{{ $n }}</option>
                @endforeach
            </select>
            <label>Catatan</label>
            <textarea class="input" name="catatan">{{ old('catatan') }}</textarea>
            <button class="btn" type="submit">Catat Setoran</button>
        </form>
    @endif
</div>
@endsection
