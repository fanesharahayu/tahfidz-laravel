@extends('layouts.app')

@section('title', 'Kelola Setoran')

@section('content')
<div class="page-head">
    <div>
        <h2>Kelola Setoran</h2>
        <p class="muted">{{ $setoran->count() }} setoran tercatat.</p>
    </div>
    <div>
        <a class="btn btn-sm" href="{{ route('admin.setoran.create') }}">+ Catat Setoran</a>
    </div>
</div>

<div class="card">
    <div class="table-wrap"><table>
        <tr><th>Santri</th><th>Juz</th><th>Surah</th><th>Jenis</th><th>Nilai</th><th>Musyrif</th><th>Aksi</th></tr>
        @forelse ($setoran as $st)
            <tr>
                <td><strong>{{ $st->santri->user->nama ?? '-' }}</strong></td>
                <td><span class="badge badge-blue">Juz {{ $st->juz }}</span></td>
                <td>{{ $st->surah }}</td>
                <td><span class="badge badge-gray">{{ $st->jenis }}</span></td>
                <td>
                    @if ($st->nilai === 'lancar')
                        <span class="badge badge-green">lancar</span>
                    @elseif ($st->nilai === 'cukup_lancar')
                        <span class="badge badge-amber">cukup lancar</span>
                    @else
                        <span class="badge badge-red">{{ $st->nilai }}</span>
                    @endif
                </td>
                <td>{{ $st->musyrif->nama ?? '-' }}</td>
                <td style="white-space:nowrap">
                    <a class="btn btn-secondary btn-sm" href="{{ route('admin.setoran.edit', $st->id) }}"><i data-lucide="pencil"></i> Edit</a>
                    <form action="{{ route('admin.setoran.destroy', $st->id) }}" method="POST" class="inline-form" data-confirm="Setoran Juz {{ $st->juz }} ({{ $st->surah }}) akan dihapus permanen.">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="muted">Belum ada setoran.</td></tr>
        @endforelse
    </table></div>
</div>
@endsection
