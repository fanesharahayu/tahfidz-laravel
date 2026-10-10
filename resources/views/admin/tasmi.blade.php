@extends('layouts.app')

@section('title', 'Data Tasmi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Data Tasmi</h3>
    <a href="{{ route('admin.tasmi.create') }}" class="btn btn-primary">Tambah Tasmi</a>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Santri</th>
                        <th>Musyrif</th>
                        <th>Juz</th>
                        <th>Surah</th>
                        <th>Nilai</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tasmi as $index => $t)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $t->santri->user->nama ?? '-' }}</td>
                            <td>{{ $t->musyrif->nama ?? '-' }}</td>
                            <td>{{ $t->juz }}{{ $t->juz_akhir ? ' - ' . $t->juz_akhir : '' }}</td>
                            <td>{{ $t->surah ?? '-' }}{{ $t->surah_akhir ? ' - ' . $t->surah_akhir : '' }}</td>
                            <td>
                                <span class="badge {{ $t->nilai === 'lancar' ? 'bg-success' : ($t->nilai === 'cukup_lancar' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ $t->nilai }}
                                </span>
                            </td>
                            <td>{{ $t->tanggal_tasmi ? \Carbon\Carbon::parse($t->tanggal_tasmi)->format('d/m/Y') : \Carbon\Carbon::parse($t->created_at)->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.tasmi.edit', $t->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('admin.tasmi.destroy', $t->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data tasmi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data tasmi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection