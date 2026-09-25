@extends('layouts.app')

@section('title', 'Detail Anak')

@section('content')
<h2>{{ $santri->user->nama }} <span class="muted">({{ $relasi }})</span></h2>
<p class="muted">Progress {{ $progress['juzTercapai'] }}/{{ $progress['targetJuz'] }} juz ({{ $progress['persenJuz'] }}%)</p>

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

<div class="card">
    <h3>Target</h3>
    <table>
        <tr><th>Target</th><th>Periode</th></tr>
        @forelse ($targets as $t)
            <tr><td>{{ $t->target_juz }} juz</td><td>{{ $t->periode ?? '-' }}</td></tr>
        @empty
            <tr><td colspan="2" class="muted">Belum ada target.</td></tr>
        @endforelse
    </table>
</div>
@endsection
