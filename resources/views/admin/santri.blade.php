@extends('layouts.app')

@section('title', 'Kelola Santri')

@section('content')
<div class="page-head">
    <div>
        <h2>Kelola Santri</h2>
        <p class="muted">{{ $santri->count() }} santri terdaftar.</p>
    </div>
    <div>
        <a class="btn btn-sm" href="{{ route('admin.santri.create') }}">+ Tambah Santri</a>
    </div>
</div>

<div class="card">
    <div class="table-wrap"><table>
        <tr><th>Nama</th><th>NIS</th><th>Kelas</th><th>Target</th><th>Musyrif</th><th>Aksi</th></tr>
        @forelse ($santri as $s)
            <tr>
                <td><strong>{{ $s->user->nama ?? '-' }}</strong></td>
                <td>{{ $s->nis ?? '-' }}</td>
                <td>{{ $s->kelas ?? '-' }}</td>
                <td><span class="badge badge-blue">{{ $s->target_juz }} juz</span></td>
                <td>{{ $s->musyrif->nama ?? '-' }}</td>
                <td style="white-space:nowrap">
                    <a class="btn btn-secondary btn-sm" href="{{ route('admin.santri.edit', $s->id) }}"><i data-lucide="pencil"></i> Edit</a>
                    <form action="{{ route('admin.santri.destroy', $s->id) }}" method="POST" class="inline-form" data-confirm="Akun santri {{ $s->user->nama ?? '' }} beserta seluruh datanya akan dihapus permanen.">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="muted">Belum ada data santri.</td></tr>
        @endforelse
    </table></div>
</div>
@endsection
