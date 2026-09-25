@extends('layouts.app')

@section('title', 'Profil')

@section('content')
<div class="card" style="max-width:560px; margin:0 auto;">
    <div style="text-align:center; margin-bottom:24px;">
        <div style="width:84px;height:84px;border-radius:50%;background:#14532d;color:#fff;font-size:38px;font-weight:700;display:inline-flex;align-items:center;justify-content:center;">
            {{ strtoupper(substr($user->nama ?? $user->name ?? '?', 0, 1)) }}
        </div>
        <h2 style="margin-top:14px;">{{ $user->nama ?? $user->name }}</h2>
        <p class="muted">{{ $user->role }}</p>
    </div>
    <table>
        <tbody>
            <tr><th style="width:160px;">Nama</th><td>{{ $user->nama ?? $user->name }}</td></tr>
            <tr><th>Username</th><td>{{ $user->username }}</td></tr>
            <tr><th>Email</th><td>{{ $user->email }}</td></tr>
            <tr><th>Role</th><td>{{ $user->role }}</td></tr>
            @if ($user->role === 'santri' && $santri)
                <tr><th>NIS</th><td>{{ $santri->nis ?? '-' }}</td></tr>
                <tr><th>Kelas</th><td>{{ $santri->kelas ?? '-' }}</td></tr>
                <tr><th>Musyrif</th><td>{{ $santri->musyrif->nama ?? $santri->musyrif->name ?? '-' }}</td></tr>
                <tr><th>Target Juz</th><td>{{ $santri->target_juz ?? '-' }}</td></tr>
            @endif
            @if ($user->role === 'musyrif')
                <tr><th>Spesialisasi</th><td>{{ $musyrifProfile->spesialisasi ?? '-' }}</td></tr>
                <tr><th>Jumlah Binaan</th><td>{{ $jumlahBinaan }}</td></tr>
            @endif
        </tbody>
    </table>
    <div style="margin-top:24px;">
        <a href="{{ route('password.edit') }}" class="btn">Ganti Password</a>
    </div>
</div>
@endsection
