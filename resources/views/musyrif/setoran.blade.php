@extends('layouts.app')

@section('title', 'Setoran Binaan')

@section('content')
<div class="page-head">
    <div>
        <h2>Setoran Santri Binaan</h2>
        <p class="muted">Catat dan kelola setoran hafalan binaan.</p>
    </div>
</div>

<div class="card">
    <h3>Catat Setoran</h3>
    <form method="POST" action="" id="form-setoran">
        @csrf
        <div class="form-grid">
            <div class="full">
                <label>Santri</label>
                <select class="input" id="select-santri" required>
                    @foreach ($binaan as $s)
                        <option value="{{ $s->id }}">{{ $s->user->nama ?? '-' }} ({{ $s->kelas ?? '-' }})</option>
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
                    <option value="hafalan_baru">Hafalan Baru</option>
                    <option value="tambahan">Tambahan</option>
                    <option value="murajaah">Murajaah</option>
                </select>
            </div>
            <div>
                <label>Nilai</label>
                <select class="input" name="nilai">
                    <option value="lancar">Lancar</option>
                    <option value="cukup_lancar">Cukup Lancar</option>
                    <option value="perlu_ulang">Perlu Ulang</option>
                </select>
            </div>
            <div class="full">
                <label>Catatan</label>
                <input class="input" type="text" name="catatan" value="{{ old('catatan') }}">
            </div>
        </div>
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
    <div class="table-wrap"><table>
        <tr><th>Santri</th><th>Juz</th><th>Surah</th><th>Ayat</th><th>Jenis</th><th>Nilai</th><th>Tanggal</th><th>Aksi</th></tr>
        @forelse ($setoran as $st)
            <tr>
                <td>{{ $st->santri->user->nama ?? '-' }}</td>
                <td><span class="badge badge-blue">Juz {{ $st->juz }}</span></td>
                <td>{{ $st->surah }}</td>
                <td>{{ $st->ayat_awal }}-{{ $st->ayat_akhir }}</td>
                <td><span class="badge badge-gray">{{ $st->jenis }}</span></td>
                <td>
                    @if ($st->nilai === 'lancar')
                        <span class="badge badge-green">lancar</span>
                    @elseif ($st->nilai === 'cukup_lancar')
                        <span class="badge badge-amber">cukup lancar</span>
                    @else
                        <span class="badge badge-red">{{ $st->nilai }}</span>
                    @endif
                </td>
                <td class="muted">{{ $st->created_at }}</td>
                <td style="white-space:nowrap">
                    <a class="btn btn-secondary btn-sm" href="{{ route('musyrif.setoran.edit', $st->id) }}"><i data-lucide="pencil"></i> Edit</a>
                    <form method="POST" action="{{ route('musyrif.setoran.destroy', $st->id) }}" class="inline-form" data-confirm="Setoran Juz {{ $st->juz }} ({{ $st->surah }}) akan dihapus permanen.">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="8" class="muted">Belum ada setoran.</td></tr>
        @endforelse
    </table></div>
</div>
@endsection
