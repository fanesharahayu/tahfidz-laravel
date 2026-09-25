@extends('layouts.app')

@section('title', 'Relasi Wali Binaan')

@section('content')
<div class="page-head">
    <div>
        <h2>Relasi Wali Santri Binaan</h2>
        <p class="muted">Hubungkan akun wali dengan santri binaan agar bisa memantau.</p>
    </div>
</div>

<div class="card">
    <h3>Hubungkan Wali – Santri</h3>
    <form method="POST" action="{{ route('musyrif.wali-link.store') }}">
        @csrf
        <div class="form-grid">
            <div>
                <label>Akun Wali</label>
                <select class="input" name="wali_user_id" required>
                    @foreach ($walis as $w)
                        <option value="{{ $w->id }}">{{ $w->nama }} ({{ $w->username }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Santri Binaan</label>
                <select class="input" name="santri_id" required>
                    @foreach ($binaan as $s)
                        <option value="{{ $s->id }}">{{ $s->user->nama ?? '-' }} ({{ $s->kelas ?? '-' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="full">
                <label>Relasi</label>
                <input class="input" type="text" name="relasi" value="{{ old('relasi', 'Wali Santri') }}">
            </div>
        </div>
        <button class="btn" type="submit">Simpan Hubungan</button>
    </form>
</div>

<div class="card">
    <h3>Daftar Relasi</h3>
    <div class="table-wrap"><table>
        <tr><th>Wali</th><th>Santri</th><th>Kelas</th><th>Relasi</th><th>Aksi</th></tr>
        @forelse ($links as $l)
            <tr>
                <td><strong>{{ $l->wali->nama ?? '-' }}</strong></td>
                <td>{{ $l->santri->user->nama ?? '-' }}</td>
                <td>{{ $l->santri->kelas ?? '-' }}</td>
                <td><span class="badge badge-gray">{{ $l->relasi }}</span></td>
                <td>
                    <form method="POST" action="{{ route('musyrif.wali-link.destroy', $l->id) }}" class="inline-form" data-confirm="Hubungan wali-santri ini akan dihapus. Akunnya tidak ikut terhapus.">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="muted">Belum ada relasi wali-santri.</td></tr>
        @endforelse
    </table></div>
</div>
@endsection
