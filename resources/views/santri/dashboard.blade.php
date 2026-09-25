@extends('layouts.app')

@section('title', 'Dashboard Santri')

@section('content')
<h2>Halo, {{ $santri->user->nama }}</h2>
<p class="muted">Musyrif: {{ $santri->musyrif->nama ?? '-' }} | Kelas {{ $santri->kelas ?? '-' }}</p>

<div class="stats">
    <div class="stat"><div class="num">{{ $progress['juzTercapai'] }}/{{ $progress['targetJuz'] }}</div><div class="lbl">Juz ({{ $progress['persenJuz'] }}%)</div></div>
    <div class="stat"><div class="num">{{ $progress['totalSetoran'] }}</div><div class="lbl">Total Setoran</div></div>
    <div class="stat"><div class="num">{{ $progress['lancar'] }}</div><div class="lbl">Lancar</div></div>
</div>

<div class="card">
    <h3>Target Hafalan</h3>
    <table>
        <tr><th>Target</th><th>Periode</th></tr>
        @forelse ($targets as $t)
            <tr><td>{{ $t->target_juz }} juz</td><td>{{ $t->periode ?? '-' }}</td></tr>
        @empty
            <tr><td colspan="2" class="muted">Belum ada target.</td></tr>
        @endforelse
    </table>
</div>

<div class="card">
    <h3>Riwayat Setoran</h3>
    <table>
        <tr><th>Juz</th><th>Surah</th><th>Nilai</th><th>Musyrif</th><th>Tanggal</th></tr>
        @forelse ($setoran as $st)
            <tr><td>{{ $st->juz }}</td><td>{{ $st->surah }}</td><td>{{ $st->nilai }}</td><td>{{ $st->musyrif->nama ?? '-' }}</td><td class="muted">{{ $st->created_at }}</td></tr>
        @empty
            <tr><td colspan="5" class="muted">Belum ada setoran.</td></tr>
        @endforelse
    </table>
</div>
@endsection
