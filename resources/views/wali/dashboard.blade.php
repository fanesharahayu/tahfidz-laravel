@extends('layouts.app')

@section('title', 'Dashboard Wali')

@section('content')
<h2>Dashboard Wali Santri</h2>
@forelse ($children as $s)
    <div class="card">
        <h3>{{ $s->user->nama }} <span class="muted">({{ $s->relasi }})</span></h3>
        <p class="muted">Kelas {{ $s->kelas ?? '-' }} | Musyrif {{ $s->musyrif->nama ?? '-' }} | Progress {{ $s->progress['juzTercapai'] }}/{{ $s->progress['targetJuz'] }} juz ({{ $s->progress['persenJuz'] }}%) | {{ $s->setoranCount }}x setoran</p>
        <a class="btn" href="{{ route('wali.child.detail', $s->id) }}">Lihat Detail</a>
    </div>
@empty
    <div class="card muted">Belum ada anak terhubung. Hubungi admin/musyrif.</div>
@endforelse
@endsection
