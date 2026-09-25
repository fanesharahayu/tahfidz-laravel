@extends('layouts.app')

@section('title', 'Target Hafalan')

@section('content')
<div class="page-head">
    <div>
        <h2>Target Hafalan</h2>
        <p class="muted">{{ count($targets ?? []) }} target tercatat.</p>
    </div>
    <div>
        <a class="btn btn-sm" href="{{ route('admin.target.create') }}">+ Tambah Target</a>
    </div>
</div>

<div class="card">
    <h3>Daftar Target</h3>
    <div class="table-wrap"><table>
        <tr><th>Santri</th><th>Target Juz</th><th>Periode</th><th>Mulai</th><th>Selesai</th><th>Aksi</th></tr>
        @forelse (($targets ?? []) as $t)
            <tr>
                <td><strong>{{ $t->santri->user->nama ?? '-' }}</strong></td>
                <td><span class="badge badge-blue">{{ $t->target_juz }} juz</span></td>
                <td>{{ $t->periode ?? '-' }}</td>
                <td>{{ $t->tanggal_mulai ? $t->tanggal_mulai->format('Y-m-d') : '-' }}</td>
                <td>{{ $t->tanggal_selesai ? $t->tanggal_selesai->format('Y-m-d') : '-' }}</td>
                <td style="white-space:nowrap">
                    <a class="btn btn-secondary btn-sm" href="{{ route('admin.target.edit', $t->id) }}"><i data-lucide="pencil"></i> Edit</a>
                    <form action="{{ url('/admin/target/' . $t->id) }}" method="POST" class="inline-form" data-confirm="Target hafalan ini akan dihapus permanen.">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="muted">Belum ada target.</td></tr>
        @endforelse
    </table></div>
</div>
@endsection
