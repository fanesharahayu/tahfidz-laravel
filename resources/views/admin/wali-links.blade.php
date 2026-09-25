@extends('layouts.app')

@section('title', 'Hubungan Wali-Santri')

@section('content')
@php($hideLayoutErrors = true)
<div class="page-head">
    <div>
        <h2>Hubungan Wali–Santri</h2>
        <p class="muted">Kelola akses wali terhadap santri.</p>
    </div>
</div>

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
    <div class="table-wrap"><table>
        <tr><th>Wali</th><th>Santri</th><th>Relasi</th><th>Aksi</th></tr>
        @forelse (($links ?? []) as $l)
            <tr>
                <td><strong>{{ $l->wali->nama ?? '-' }}</strong></td>
                <td>{{ $l->santri->user->nama ?? '-' }}</td>
                <td><span class="badge badge-gray">{{ $l->relasi ?? '-' }}</span></td>
                <td>
                    <form action="{{ url('/admin/wali-link/' . $l->id) }}" method="POST" class="inline-form" data-confirm="Hubungan wali-santri ini akan dihapus. Akunnya tidak ikut terhapus.">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="muted">Belum ada hubungan wali-santri.</td></tr>
        @endforelse
    </table></div>
</div>

<div class="card">
    <h3>Tambah Hubungan</h3>
    <form action="{{ url('/admin/wali-link') }}" method="POST">
        @csrf
        <div class="form-grid">
            <div>
                <label>Wali</label>
                <select class="input" name="wali_user_id" required>
                    <option value="">-- Pilih wali --</option>
                    @foreach (($waliList ?? []) as $w)
                        <option value="{{ $w->id }}" {{ (string) old('wali_user_id') === (string) $w->id ? 'selected' : '' }}>{{ $w->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Santri</label>
                <select class="input" name="santri_id" required>
                    <option value="">-- Pilih santri --</option>
                    @foreach (($santriList ?? []) as $s)
                        <option value="{{ $s->id }}" {{ (string) old('santri_id') === (string) $s->id ? 'selected' : '' }}>{{ $s->user->nama ?? ('Santri #' . $s->id) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="full">
                <label>Relasi</label>
                <input class="input" type="text" name="relasi" value="{{ old('relasi', 'Wali Santri') }}">
            </div>
        </div>
        <button class="btn" type="submit">Buat Hubungan</button>
    </form>
</div>
@endsection
