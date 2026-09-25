@extends('layouts.app')

@section('title', 'Ubah Setoran')

@section('content')
<div class="page-head">
    <div>
        <h2>Ubah Setoran</h2>
        <p class="muted">{{ $setoran->santri->user->nama ?? 'Santri' }} · Juz {{ $setoran->juz }} · {{ $setoran->surah }}</p>
    </div>
    <div>
        <a class="btn btn-secondary btn-sm" href="{{ route('musyrif.setoran.index') }}"><i data-lucide="arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="card" style="max-width:640px">
    <form method="POST" action="{{ route('musyrif.setoran.update', $setoran->id) }}">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <div>
                <label>Juz</label>
                <input class="input" type="number" name="juz" min="1" max="30" value="{{ old('juz', $setoran->juz) }}" required>
            </div>
            <div>
                <label>Surah</label>
                <input class="input" type="text" name="surah" value="{{ old('surah', $setoran->surah) }}" required>
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
                    @foreach (['hafalan_baru' => 'Hafalan Baru', 'tambahan' => 'Tambahan', 'murajaah' => 'Murajaah'] as $v => $l)
                        <option value="{{ $v }}" @selected(old('jenis', $setoran->jenis) === $v)>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Nilai</label>
                <select class="input" name="nilai">
                    @foreach (['lancar' => 'Lancar', 'cukup_lancar' => 'Cukup Lancar', 'perlu_ulang' => 'Perlu Ulang'] as $v => $l)
                        <option value="{{ $v }}" @selected(old('nilai', $setoran->nilai) === $v)>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="full">
                <label>Catatan</label>
                <input class="input" type="text" name="catatan" value="{{ old('catatan', $setoran->catatan) }}">
            </div>
        </div>
        <button class="btn" type="submit">Simpan Perubahan</button>
    </form>
</div>
@endsection
