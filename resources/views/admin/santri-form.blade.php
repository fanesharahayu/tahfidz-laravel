@extends('layouts.app')

@section('title', isset($santri) ? 'Ubah Santri' : 'Tambah Santri')

@section('content')
<h2>{{ isset($santri) ? 'Ubah Santri' : 'Tambah Santri' }}</h2>

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
    @if (isset($santri))
        <form action="{{ url('/admin/santri/' . $santri->id) }}" method="POST">
            @csrf
            @method('PUT')
            <label>Nama</label>
            <input class="input" type="text" name="nama" value="{{ old('nama', $santri->user->nama ?? '') }}">
            <label>NIS</label>
            <input class="input" type="text" name="nis" value="{{ old('nis', $santri->nis) }}">
            <label>Kelas</label>
            <input class="input" type="text" name="kelas" value="{{ old('kelas', $santri->kelas) }}">
            <label>Target Juz</label>
            <input class="input" type="number" name="target_juz" min="1" max="30" value="{{ old('target_juz', $santri->target_juz ?? 30) }}">
            <label>Musyrif</label>
            <select class="input" name="musyrif_id">
                <option value="">-- Tanpa musyrif --</option>
                @foreach (($musyrifList ?? []) as $m)
                    <option value="{{ $m->id }}" {{ (string) old('musyrif_id', $santri->musyrif_id) === (string) $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                @endforeach
            </select>
            <label>Tanggal Bergabung</label>
            <input class="input" type="date" name="tanggal_bergabung" value="{{ old('tanggal_bergabung', optional($santri->tanggal_bergabung)->format('Y-m-d')) }}">
            <button class="btn" type="submit">Simpan Perubahan</button>
        </form>
    @else
        <form action="{{ url('/admin/santri') }}" method="POST">
            @csrf
            <label>Nama</label>
            <input class="input" type="text" name="nama" value="{{ old('nama') }}" required>
            <label>Username</label>
            <input class="input" type="text" name="username" value="{{ old('username') }}" required>
            <label>Email</label>
            <input class="input" type="email" name="email" value="{{ old('email') }}" required>
            <label>Password (min 6)</label>
            <input class="input" type="password" name="password" required>
            <label>NIS</label>
            <input class="input" type="text" name="nis" value="{{ old('nis') }}">
            <label>Kelas</label>
            <input class="input" type="text" name="kelas" value="{{ old('kelas') }}">
            <label>Target Juz</label>
            <input class="input" type="number" name="target_juz" min="1" max="30" value="{{ old('target_juz', 30) }}">
            <label>Musyrif</label>
            <select class="input" name="musyrif_id">
                <option value="">-- Tanpa musyrif --</option>
                @foreach (($musyrifList ?? []) as $m)
                    <option value="{{ $m->id }}" {{ (string) old('musyrif_id') === (string) $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                @endforeach
            </select>
            <label>Tanggal Bergabung</label>
            <input class="input" type="date" name="tanggal_bergabung" value="{{ old('tanggal_bergabung') }}">
            <button class="btn" type="submit">Tambah Santri</button>
        </form>
    @endif
</div>
@endsection
