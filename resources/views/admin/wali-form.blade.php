@extends('layouts.app')

@section('title', isset($link) ? 'Ubah Hubungan' : 'Tambah Hubungan')

@section('content')
<div class="page-head">
    <div>
        <h2>{{ isset($link) ? 'Ubah Hubungan' : 'Tambah Hubungan' }}</h2>
        <p class="muted">{{ isset($link) ? (($link->wali->nama ?? 'Wali') . ' – ' . ($link->santri->user->nama ?? 'Santri')) : 'Hubungkan akun wali dengan santri.' }}</p>
    </div>
    <div>
        <a class="btn btn-secondary btn-sm" href="{{ route('admin.wali-links.index') }}"><i data-lucide="arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="card" style="max-width:640px">
    @if (isset($link))
        <form action="{{ url('/admin/wali-link/' . $link->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="full">
                    <label>Relasi</label>
                    <input class="input" type="text" name="relasi" value="{{ old('relasi', $link->relasi) }}">
                </div>
            </div>
            <button class="btn" type="submit">Simpan Perubahan</button>
        </form>
    @else
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
            <button class="btn" type="submit">+ Buat Hubungan</button>
        </form>
    @endif
</div>
@endsection
