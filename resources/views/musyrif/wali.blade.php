@extends('layouts.app')

@section('title', 'Relasi Wali Binaan')

@section('content')
<h2>Relasi Wali Santri Binaan</h2>
<p class="muted">
    <a href="{{ route('musyrif.dashboard') }}">Dashboard</a> |
    <a href="{{ route('musyrif.binaan.index') }}">Binaan</a> |
    <a href="{{ route('musyrif.setoran.index') }}">Setoran</a> |
    <a href="{{ route('musyrif.targets.index') }}">Target</a>
</p>

@if ($errors->any())
    <div class="alert">{{ $errors->first() }}</div>
@endif

<div class="card">
    <h3>Hubungkan Wali - Santri</h3>
    <form method="POST" action="{{ route('musyrif.wali-link.store') }}">
        @csrf
        <label>Akun Wali</label>
        <select class="input" name="wali_user_id" required>
            @foreach ($walis as $w)
                <option value="{{ $w->id }}">{{ $w->nama }} ({{ $w->username }})</option>
            @endforeach
        </select>
        <label>Santri Binaan</label>
        <select class="input" name="santri_id" required>
            @foreach ($binaan as $s)
                <option value="{{ $s->id }}">{{ $s->user->nama ?? '-' }} ({{ $s->kelas ?? '-' }})</option>
            @endforeach
        </select>
        <label>Relasi</label>
        <input class="input" type="text" name="relasi" value="{{ old('relasi', 'Wali Santri') }}">
        <button class="btn" type="submit">Simpan Hubungan</button>
    </form>
</div>

<div class="card">
    <h3>Daftar Relasi</h3>
    <table>
        <tr><th>Wali</th><th>Santri</th><th>Kelas</th><th>Relasi</th><th>Aksi</th></tr>
        @forelse ($links as $l)
            <tr>
                <td>{{ $l->wali->nama ?? '-' }}</td>
                <td>{{ $l->santri->user->nama ?? '-' }}</td>
                <td>{{ $l->santri->kelas ?? '-' }}</td>
                <td>{{ $l->relasi }}</td>
                <td>
                    <form method="POST" action="{{ route('musyrif.wali-link.destroy', $l->id) }}" onsubmit="return confirm('Hapus hubungan ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="muted">Belum ada relasi wali-santri.</td></tr>
        @endforelse
    </table>
</div>
@endsection
