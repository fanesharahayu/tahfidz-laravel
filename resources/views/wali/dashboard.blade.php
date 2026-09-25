@extends('layouts.app')

@section('title', 'Dashboard Wali')

@section('content')
<div class="page-head">
    <div>
        <h2>Dashboard Wali Santri</h2>
        <p class="muted">Pantau perkembangan hafalan anak.</p>
    </div>
</div>

@forelse ($children as $s)
    <div class="card">
        <div class="page-head" style="margin-bottom:8px">
            <h3 style="margin:0">{{ $s->user->nama }} <span class="badge badge-gray">{{ $s->relasi }}</span></h3>
            <a class="btn btn-sm" href="{{ route('wali.child.detail', $s->id) }}">Lihat Detail →</a>
        </div>
        <p class="muted">Kelas {{ $s->kelas ?? '-' }} · Musyrif {{ $s->musyrif->nama ?? '-' }} · {{ $s->setoranCount }}x setoran</p>
        <div class="progress-row">
            <div class="progress" style="flex:1"><div class="bar" style="width:{{ $s->progress['persenJuz'] }}%"></div></div>
            <small>{{ $s->progress['juzTercapai'] }}/{{ $s->progress['targetJuz'] }} juz ({{ $s->progress['persenJuz'] }}%)</small>
        </div>
    </div>
@empty
    <div class="card muted">Belum ada anak terhubung. Hubungi admin/musyrif.</div>
@endforelse
@endsection
