@extends('layouts.app')

@section('title', 'Dashboard Musyrif')

@section('content')
<div class="page-head">
    <div>
        <h2>Dashboard Musyrif</h2>
        <p class="muted">{{ $binaan->count() }} santri binaan · {{ $setoran->count() }} setoran terbaru</p>
    </div>
    <div>
        <a class="btn btn-sm" href="{{ route('musyrif.setoran.index') }}">✎ Catat Setoran</a>
        <a class="btn btn-secondary btn-sm" href="{{ route('musyrif.binaan.index') }}">Kelola Binaan</a>
    </div>
</div>

<div class="card">
    <h3>Santri Binaan</h3>
    <div class="table-wrap"><table>
        <tr><th>Nama</th><th>Kelas</th><th>Progress</th><th>Setoran</th><th></th></tr>
        @forelse ($binaan as $s)
            <tr>
                <td><strong>{{ $s->user->nama ?? '-' }}</strong></td>
                <td>{{ $s->kelas ?? '-' }}</td>
                <td>
                    <div class="progress-row">
                        <div class="progress"><div class="bar" style="width:{{ $s->progress['persenJuz'] ?? 0 }}%"></div></div>
                        <small>{{ $s->progress['juzTercapai'] ?? 0 }}/{{ $s->progress['targetJuz'] ?? 30 }} juz</small>
                    </div>
                </td>
                <td><span class="badge badge-blue">{{ $s->setoranCount ?? 0 }}x</span></td>
                <td><a class="btn btn-secondary btn-sm" href="{{ route('musyrif.santri.detail', $s->id) }}">Detail</a></td>
            </tr>
        @empty
            <tr><td colspan="5" class="muted">Belum ada santri binaan. <a href="{{ route('musyrif.binaan.index') }}">Ambil binaan di sini</a>.</td></tr>
        @endforelse
    </table></div>
</div>

<div class="card">
    <h3>Riwayat Setoran</h3>
    <div class="table-wrap"><table>
        <tr><th>Santri</th><th>Juz</th><th>Surah</th><th>Nilai</th><th>Tanggal</th></tr>
        @forelse ($setoran as $st)
            <tr>
                <td>{{ $st->santri->user->nama ?? '-' }}</td>
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
                <td class="muted">{{ $st->created_at }}</td>
            </tr>
        @empty
            <tr><td colspan="5" class="muted">Belum ada setoran.</td></tr>
        @endforelse
    </table></div>
</div>
@endsection
