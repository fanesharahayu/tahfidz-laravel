@extends('layouts.app')

@section('title', 'Dashboard Musyrif')

@section('content')
<h2>Dashboard Musyrif</h2>
<p class="muted">Santri binaan: {{ $binaan->count() }}</p>

<div class="card">
    <h3>Santri Binaan</h3>
    <table>
        <tr><th>Nama</th><th>Kelas</th><th>Progress</th><th>Setoran</th><th></th></tr>
        @forelse ($binaan as $s)
            <tr>
                <td>{{ $s->user->nama ?? '-' }}</td>
                <td>{{ $s->kelas ?? '-' }}</td>
                <td>{{ $s->progress['juzTercapai'] ?? 0 }}/{{ $s->progress['targetJuz'] ?? 30 }} juz ({{ $s->progress['persenJuz'] ?? 0 }}%)</td>
                <td>{{ $s->setoranCount ?? 0 }}x</td>
                <td><a class="btn" href="{{ route('musyrif.santri.detail', $s->id) }}">Detail</a></td>
            </tr>
        @empty
            <tr><td colspan="5" class="muted">Belum ada santri binaan.</td></tr>
        @endforelse
    </table>
</div>

<div class="card">
    <h3>Riwayat Setoran</h3>
    <table>
        <tr><th>Santri</th><th>Juz</th><th>Surah</th><th>Nilai</th><th>Tanggal</th></tr>
        @forelse ($setoran as $st)
            <tr>
                <td>{{ $st->santri->user->nama ?? '-' }}</td>
                <td>{{ $st->juz }}</td>
                <td>{{ $st->surah }}</td>
                <td>{{ $st->nilai }}</td>
                <td class="muted">{{ $st->created_at }}</td>
            </tr>
        @empty
            <tr><td colspan="5" class="muted">Belum ada setoran.</td></tr>
        @endforelse
    </table>
</div>
@endsection
