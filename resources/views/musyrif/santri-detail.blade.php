@extends('layouts.app')

@section('title', 'Detail Santri')

@section('content')
<h2>{{ $santri->user->nama ?? 'Santri' }}</h2>
<p class="muted">Kelas {{ $santri->kelas ?? '-' }} | NIS {{ $santri->nis ?? '-' }} | Progress {{ $progress['juzTercapai'] }}/{{ $progress['targetJuz'] }} juz ({{ $progress['persenJuz'] }}%)</p>

<div class="card">
    <h3>Target Hafalan</h3>
    <table>
        <tr><th>Target</th><th>Periode</th><th>Mulai</th><th>Selesai</th></tr>
        @forelse ($targets as $t)
            <tr><td>{{ $t->target_juz }} juz</td><td>{{ $t->periode ?? '-' }}</td><td>{{ $t->tanggal_mulai ?? '-' }}</td><td>{{ $t->tanggal_selesai ?? '-' }}</td></tr>
        @empty
            <tr><td colspan="4" class="muted">Belum ada target.</td></tr>
        @endforelse
    </table>
</div>

<div class="card">
    <h3>Riwayat Setoran</h3>
    <table>
        <tr><th>Juz</th><th>Surah</th><th>Ayat</th><th>Jenis</th><th>Nilai</th><th>Catatan</th></tr>
        @forelse ($setoran as $st)
            <tr>
                <td>{{ $st->juz }}</td><td>{{ $st->surah }}</td>
                <td>{{ $st->ayat_awal }}-{{ $st->ayat_akhir }}</td>
                <td>{{ $st->jenis }}</td><td>{{ $st->nilai }}</td><td>{{ $st->catatan ?? '-' }}</td>
            </tr>
        @empty
            <tr><td colspan="6" class="muted">Belum ada setoran.</td></tr>
        @endforelse
    </table>
</div>
@endsection
