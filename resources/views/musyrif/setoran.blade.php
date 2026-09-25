@extends('layouts.app')

@section('title', 'Setoran Binaan')

@section('content')
<h2>Setoran Santri Binaan</h2>
<p class="muted">
    <a href="{{ route('musyrif.dashboard') }}">Dashboard</a> |
    <a href="{{ route('musyrif.binaan.index') }}">Binaan</a> |
    <a href="{{ route('musyrif.targets.index') }}">Target</a> |
    <a href="{{ route('musyrif.wali.index') }}">Wali</a>
</p>

@if ($errors->any())
    <div class="alert">{{ $errors->first() }}</div>
@endif

<div class="card">
    <h3>Catat Setoran</h3>
    <form method="POST" action="" id="form-setoran">
        @csrf
        <label>Santri</label>
        <select class="input" id="select-santri" required>
            @foreach ($binaan as $s)
                <option value="{{ $s->id }}">{{ $s->user->nama ?? '-' }} ({{ $s->kelas ?? '-' }})</option>
            @endforeach
        </select>
        <label>Juz</label>
        <input class="input" type="number" name="juz" min="1" max="30" value="{{ old('juz') }}" required>
        <label>Surah</label>
        <input class="input" type="text" name="surah" value="{{ old('surah') }}" required>
        <div style="display:flex;gap:12px">
            <div style="flex:1">
                <label>Ayat Awal</label>
                <input class="input" type="number" name="ayat_awal" min="0" value="{{ old('ayat_awal', 0) }}">
            </div>
            <div style="flex:1">
                <label>Ayat Akhir</label>
                <input class="input" type="number" name="ayat_akhir" min="0" value="{{ old('ayat_akhir', 0) }}">
            </div>
        </div>
        <div style="display:flex;gap:12px">
            <div style="flex:1">
                <label>Jenis</label>
                <select class="input" name="jenis">
                    <option value="hafalan_baru">Hafalan Baru</option>
                    <option value="tambahan">Tambahan</option>
                    <option value="murajaah">Murajaah</option>
                </select>
            </div>
            <div style="flex:1">
                <label>Nilai</label>
                <select class="input" name="nilai">
                    <option value="lancar">Lancar</option>
                    <option value="cukup_lancar">Cukup Lancar</option>
                    <option value="perlu_ulang">Perlu Ulang</option>
                </select>
            </div>
        </div>
        <label>Catatan</label>
        <input class="input" type="text" name="catatan" value="{{ old('catatan') }}">
        <button class="btn" type="submit">Simpan Setoran</button>
    </form>
    <script>
    (function () {
        var sel = document.getElementById('select-santri');
        var form = document.getElementById('form-setoran');
        function sync() { form.action = "{{ url('/musyrif/santri') }}/" + sel.value + "/setoran"; }
        sel.addEventListener('change', sync); sync();
    })();
    </script>
</div>

<div class="card">
    <h3>Riwayat Setoran</h3>
    <table>
        <tr><th>Santri</th><th>Juz</th><th>Surah</th><th>Ayat</th><th>Jenis</th><th>Nilai</th><th>Tanggal</th><th>Aksi</th></tr>
        @forelse ($setoran as $st)
            <tr>
                <td>{{ $st->santri->user->nama ?? '-' }}</td>
                <td>{{ $st->juz }}</td>
                <td>{{ $st->surah }}</td>
                <td>{{ $st->ayat_awal }}-{{ $st->ayat_akhir }}</td>
                <td>{{ $st->jenis }}</td>
                <td>{{ $st->nilai }}</td>
                <td class="muted">{{ $st->created_at }}</td>
                <td>
                    <form method="POST" action="{{ route('musyrif.setoran.update', $st->id) }}" style="display:inline">
                        @csrf
                        @method('PUT')
                        <input type="number" name="juz" value="{{ $st->juz }}" min="1" max="30" required style="width:52px">
                        <input type="text" name="surah" value="{{ $st->surah }}" required style="width:110px">
                        <select name="nilai">
                            <option value="lancar" @selected($st->nilai === 'lancar')>Lancar</option>
                            <option value="cukup_lancar" @selected($st->nilai === 'cukup_lancar')>Cukup Lancar</option>
                            <option value="perlu_ulang" @selected($st->nilai === 'perlu_ulang')>Perlu Ulang</option>
                        </select>
                        <button class="btn" type="submit">Ubah</button>
                    </form>
                    <form method="POST" action="{{ route('musyrif.setoran.destroy', $st->id) }}" style="display:inline" onsubmit="return confirm('Hapus setoran ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="8" class="muted">Belum ada setoran.</td></tr>
        @endforelse
    </table>
</div>
@endsection
