@extends('layouts.app')

@section('title', 'Hubungan Wali-Santri')

@section('content')
<h2>Hubungan Wali-Santri</h2>

@if (session('status'))
    <div class="success">{{ session('status') }}</div>
@endif

@if ($errors->any())
    <div class="alert">
        <ul style="margin:0;padding-left:18px">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <h3>Daftar Hubungan</h3>
    <table>
        <tr><th>Wali</th><th>Santri</th><th>Relasi</th><th>Aksi</th></tr>
        @forelse (($links ?? []) as $l)
            <tr>
                <td>{{ $l->wali->nama ?? '-' }}</td>
                <td>{{ $l->santri->user->nama ?? '-' }}</td>
                <td>{{ $l->relasi ?? '-' }}</td>
                <td>
                    <form action="{{ url('/admin/wali-link/' . $l->id) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit" onclick="return confirm('Hapus hubungan ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="muted">Belum ada hubungan wali-santri.</td></tr>
        @endforelse
    </table>
</div>

<div class="card">
    <h3>Tambah Hubungan</h3>
    <form action="{{ url('/admin/wali-link') }}" method="POST">
        @csrf
        <label>Wali</label>
        <select class="input" name="wali_user_id" required>
            <option value="">-- Pilih wali --</option>
            @foreach (($waliList ?? []) as $w)
                <option value="{{ $w->id }}" {{ (string) old('wali_user_id') === (string) $w->id ? 'selected' : '' }}>{{ $w->nama }}</option>
            @endforeach
        </select>
        <label>Santri</label>
        <select class="input" name="santri_id" required>
            <option value="">-- Pilih santri --</option>
            @foreach (($santriList ?? []) as $s)
                <option value="{{ $s->id }}" {{ (string) old('santri_id') === (string) $s->id ? 'selected' : '' }}>{{ $s->user->nama ?? ('Santri #' . $s->id) }}</option>
            @endforeach
        </select>
        <label>Relasi</label>
        <input class="input" type="text" name="relasi" value="{{ old('relasi', 'Wali Santri') }}">
        <button class="btn" type="submit">Buat Hubungan</button>
    </form>
</div>
@endsection
