@extends('layouts.app')

@section('title', 'Detail Anak')

@section('content')
<div class="page-head">
    <div>
        <h2>{{ $santri->user->nama }} <span class="badge badge-gray">{{ $relasi }}</span></h2>
        <p class="muted">Kelas {{ $santri->kelas ?? '-' }} · Musyrif {{ $santri->musyrif->nama ?? '-' }}</p>
    </div>
    <div>
        <a class="btn btn-secondary btn-sm" href="{{ route('wali.dashboard') }}"><i data-lucide="arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="card">
    <div class="progress-row">
        <div class="progress" style="flex:1"><div class="bar" style="width:{{ $progress['persenJuz'] }}%"></div></div>
        <small><strong>{{ $progress['juzTercapai'] }}/{{ $progress['targetJuz'] }} juz</strong> ({{ $progress['persenJuz'] }}%) · {{ $progress['totalSetoran'] }} setoran</small>
    </div>
</div>

<div class="card">
    <h3>Riwayat Setoran</h3>
    <div class="table-wrap"><table>
        <tr><th>Juz</th><th>Surah</th><th>Ayat</th><th>Nilai</th><th>Musyrif</th><th>Tanggal</th></tr>
        @forelse ($setoran as $st)
            <tr>
                <td><span class="badge badge-blue">Juz {{ $st->juz }}</span></td>
                <td>{{ $st->surah }}</td>
                <td>Ayat {{ $st->ayat_awal }}{{ $st->ayat_akhir ? ' – ' . $st->ayat_akhir : '' }}</td>
                <td>
                    @if ($st->nilai === 'lancar')
                        <span class="badge badge-green">lancar</span>
                    @elseif ($st->nilai === 'cukup_lancar')
                        <span class="badge badge-amber">cukup lancar</span>
                    @else
                        <span class="badge badge-red">{{ $st->nilai }}</span>
                    @endif
                </td>
                <td>{{ $st->musyrif->nama ?? '-' }}</td>
                <td class="muted">{{ $st->created_at }}</td>
            </tr>
        @empty
            <tr><td colspan="6" class="muted">Belum ada setoran.</td></tr>
        @endforelse
    </table></div>
</div>

<div class="card">
    <h3>Target</h3>
    <div class="table-wrap"><table>
        <tr><th>Target</th><th>Periode</th></tr>
        @forelse ($targets as $t)
            <tr><td><span class="badge badge-blue">{{ $t->target_juz }} juz</span></td><td>{{ $t->periode ?? '-' }}</td></tr>
        @empty
            <tr><td colspan="2" class="muted">Belum ada target.</td></tr>
        @endforelse
    </table></div>
</div>
@endsection
