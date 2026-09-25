@extends('layouts.app')

@section('title', isset($santri) ? 'Ubah Santri' : 'Tambah Santri')

@section('content')
<div class="page-head">
    <div>
        <h2>{{ isset($santri) ? 'Ubah Santri' : 'Tambah Santri' }}</h2>
        <p class="muted">{{ isset($santri) ? 'Perbarui data, penugasan musyrif, dan target.' : 'Buatkan akun santri baru beserta datanya.' }}</p>
    </div>
</div>

<div class="card">
    @if (isset($santri))
        <form action="{{ url('/admin/santri/' . $santri->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div>
                    <label>Nama</label>
                    <input class="input" type="text" name="nama" value="{{ old('nama', $santri->user->nama ?? '') }}">
                </div>
                <div>
                    <label>NIS</label>
                    <input class="input" type="text" name="nis" value="{{ old('nis', $santri->nis) }}">
                </div>
                <div>
                    <label>Kelas</label>
                    <input class="input" type="text" name="kelas" value="{{ old('kelas', $santri->kelas) }}">
                </div>
                <div>
                    <label>Target Juz</label>
                    <input class="input" type="number" name="target_juz" min="1" max="30" value="{{ old('target_juz', $santri->target_juz ?? 30) }}">
                </div>
                <div>
                    <label>Musyrif</label>
                    <select class="input" name="musyrif_id">
                        <option value="">-- Tanpa musyrif --</option>
                        @foreach (($musyrifList ?? []) as $m)
                            <option value="{{ $m->id }}" {{ (string) old('musyrif_id', $santri->musyrif_id) === (string) $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Tanggal Bergabung</label>
                    <input class="input" type="date" name="tanggal_bergabung" value="{{ old('tanggal_bergabung', optional($santri->tanggal_bergabung)->format('Y-m-d')) }}">
                </div>
            </div>
            <button class="btn" type="submit">Simpan Perubahan</button>
        </form>
    @else
        <form action="{{ url('/admin/santri') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div>
                    <label>Nama</label>
                    <input class="input" type="text" name="nama" value="{{ old('nama') }}" required>
                </div>
                <div>
                    <label>Username</label>
                    <input class="input" type="text" name="username" value="{{ old('username') }}" required>
                </div>
                <div>
                    <label>Email</label>
                    <input class="input" type="email" name="email" value="{{ old('email') }}" required>
                </div>
                <div>
                    <label>Password (min 6)</label>
                    <input class="input" type="password" name="password" required>
                </div>
                <div>
                    <label>NIS</label>
                    <input class="input" type="text" name="nis" value="{{ old('nis') }}">
                </div>
                <div>
                    <label>Kelas</label>
                    <input class="input" type="text" name="kelas" value="{{ old('kelas') }}">
                </div>
                <div>
                    <label>Target Juz</label>
                    <input class="input" type="number" name="target_juz" min="1" max="30" value="{{ old('target_juz', 30) }}">
                </div>
                <div>
                    <label>Musyrif</label>
                    <select class="input" name="musyrif_id">
                        <option value="">-- Tanpa musyrif --</option>
                        @foreach (($musyrifList ?? []) as $m)
                            <option value="{{ $m->id }}" {{ (string) old('musyrif_id') === (string) $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="full">
                    <label>Tanggal Bergabung</label>
                    <input class="input" type="date" name="tanggal_bergabung" value="{{ old('tanggal_bergabung') }}">
                </div>
            </div>
            <button class="btn" type="submit">+ Tambah Santri</button>
        </form>
    @endif
</div>
@endsection
