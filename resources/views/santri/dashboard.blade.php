@extends('layouts.app')

@section('title', 'Dashboard Santri')

@section('content')
<div class="page-head">
    <div>
        <h2>Halo, {{ $santri->user->nama }} 👋</h2>
        <p class="muted">Musyrif: {{ $santri->musyrif->nama ?? '-' }} · Kelas {{ $santri->kelas ?? '-' }}</p>
    </div>
</div>

<div class="stats">
    <div class="stat"><div class="num">{{ $progress['juzTercapai'] }}/{{ $progress['targetJuz'] }}</div><div class="lbl">Juz ({{ $progress['persenJuz'] }}%)</div></div>
    <div class="stat"><div class="num">{{ $progress['totalSetoran'] }}</div><div class="lbl">Total Setoran</div></div>
    <div class="stat"><div class="num">{{ $progress['lancar'] }}</div><div class="lbl">Lancar</div></div>
</div>

<div class="card">
    <div class="progress-row">
        <div class="progress" style="flex:1"><div class="bar" style="width:{{ $progress['persenJuz'] }}%"></div></div>
        <small>Target {{ $progress['targetJuz'] }} juz</small>
    </div>
</div>

<div class="card">
    <h3>Target Hafalan</h3>
    <div class="table-wrap"><table>
        <tr><th>Target</th><th>Periode</th></tr>
        @forelse ($targets as $t)
            <tr><td><span class="badge badge-blue">{{ $t->target_juz }} juz</span></td><td>{{ $t->periode ?? '-' }}</td></tr>
        @empty
            <tr><td colspan="2" class="muted">Belum ada target.</td></tr>
        @endforelse
    </table></div>
</div>

<div class="card">
    <h3>Riwayat Setoran</h3>
    <div class="table-wrap"><table>
        <tr><th>Juz</th><th>Surah</th><th>Nilai</th><th>Musyrif</th><th>Tanggal</th></tr>
        @forelse ($setoran as $st)
            <tr>
                <td><span class="badge badge-blue">Juz {{ $st->juz }}</span></td>
                <td>{{ $st->surah }}</td>
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
            <tr><td colspan="5" class="muted">Belum ada setoran. Semangat menyetor! 💪</td></tr>
        @endforelse
    </table></div>
</div>
@endsection
