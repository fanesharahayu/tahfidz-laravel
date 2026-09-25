@extends('layouts.app')

@section('title', 'Detail Santri')

@section('content')
<div class="page-head">
    <div>
        <h2>{{ $santri->user->nama ?? 'Santri' }}</h2>
        <p class="muted">Kelas {{ $santri->kelas ?? '-' }} · NIS {{ $santri->nis ?? '-' }}</p>
    </div>
    <div>
        <a class="btn btn-secondary btn-sm" href="{{ route('musyrif.binaan.index') }}"><i data-lucide="arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="card">
    <div class="progress-row">
        <div class="progress" style="flex:1"><div class="bar" style="width:{{ $progress['persenJuz'] }}%"></div></div>
        <small><strong>{{ $progress['juzTercapai'] }}/{{ $progress['targetJuz'] }} juz</strong> ({{ $progress['persenJuz'] }}%) · {{ $progress['totalSetoran'] }} setoran · {{ $progress['lancar'] }} lancar</small>
    </div>
</div>

<div class="card">
    <h3>Target Hafalan</h3>
    <div class="table-wrap"><table>
        <tr><th>Target</th><th>Periode</th><th>Mulai</th><th>Selesai</th></tr>
        @forelse ($targets as $t)
            <tr>
                <td><span class="badge badge-blue">{{ $t->target_juz }} juz</span></td>
                <td>{{ $t->periode ?? '-' }}</td>
                <td>{{ $t->tanggal_mulai ?? '-' }}</td>
                <td>{{ $t->tanggal_selesai ?? '-' }}</td>
            </tr>
        @empty
            <tr><td colspan="4" class="muted">Belum ada target.</td></tr>
        @endforelse
    </table></div>
</div>

<div class="card">
    <h3>Riwayat Setoran</h3>
    <div class="table-wrap"><table>
        <tr><th>Juz</th><th>Surah</th><th>Ayat</th><th>Jenis</th><th>Nilai</th><th>Catatan</th></tr>
        @forelse ($setoran as $st)
            <tr>
                <td><span class="badge badge-blue">Juz {{ $st->juz }}</span></td><td>{{ $st->surah }}</td>
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
                <td>{{ $st->catatan ?? '-' }}</td>
            </tr>
        @empty
            <tr><td colspan="6" class="muted">Belum ada setoran.</td></tr>
        @endforelse
    </table></div>
</div>
@endsection
