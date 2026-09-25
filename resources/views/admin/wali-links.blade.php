@extends('layouts.app')

@section('title', 'Hubungan Wali-Santri')

@section('content')
<div class="page-head">
    <div>
        <h2>Hubungan Wali–Santri</h2>
        <p class="muted">{{ count($links ?? []) }} hubungan tercatat.</p>
    </div>
    <div>
        <a class="btn btn-sm" href="{{ route('admin.wali-links.create') }}">+ Tambah Hubungan</a>
    </div>
</div>

<div class="card">
    <h3>Daftar Hubungan</h3>
    <div class="table-wrap"><table>
        <tr><th>Wali</th><th>Santri</th><th>Relasi</th><th>Aksi</th></tr>
        @forelse (($links ?? []) as $l)
            <tr>
                <td><strong>{{ $l->wali->nama ?? '-' }}</strong></td>
                <td>{{ $l->santri->user->nama ?? '-' }}</td>
                <td><span class="badge badge-gray">{{ $l->relasi ?? '-' }}</span></td>
                <td style="white-space:nowrap">
                    <a class="btn btn-secondary btn-sm" href="{{ route('admin.wali-links.edit', $l->id) }}"><i data-lucide="pencil"></i> Edit</a>
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
@endsection
