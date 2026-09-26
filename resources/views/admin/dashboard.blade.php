@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="page-head">
    <div>
        <h2>Dashboard Admin</h2>
        <p class="muted">Ringkasan santri, setoran, dan pengguna.</p>
    </div>
    <div>
        <a class="btn btn-secondary btn-sm" href="{{ route('admin.users.index') }}">+ Kelola Pengguna</a>
        <a class="btn btn-secondary btn-sm" href="{{ route('admin.wali-links.index') }}">Wali–Santri</a>
    </div>
</div>

<div class="stats">
    <div class="stat"><div class="num">{{ $jumlahSantri }}</div><div class="lbl">Santri</div></div>
    <div class="stat"><div class="num">{{ $jumlahSetoran }}</div><div class="lbl">Setoran</div></div>
    <div class="stat"><div class="num">{{ $jumlahMusyrif }}</div><div class="lbl">Musyrif</div></div>
    <div class="stat"><div class="num">{{ $jumlahWali }}</div><div class="lbl">Wali</div></div>
</div>

<div class="card">
    <div class="page-head" style="margin-bottom:8px">
        <h3 style="margin:0">Santri</h3>
        <a class="btn btn-secondary btn-sm" href="{{ route('admin.santri.index') }}">Kelola <i data-lucide="arrow-right"></i></a>
    </div>
    <div class="table-wrap"><table>
        <tr><th>Nama</th><th>NIS</th><th>Kelas</th><th>Target</th><th>Musyrif</th><th>Aksi</th></tr>
        @forelse ($santri as $s)
            <tr>
                <td><strong>{{ $s->user->nama ?? '-' }}</strong></td>
                <td>{{ $s->nis ?? '-' }}</td>
                <td>{{ $s->kelas ?? '-' }}</td>
                <td><span class="badge badge-blue">{{ $s->target_juz }} juz</span></td>
                <td>{{ $s->musyrif->nama ?? '-' }}</td>
                <td><a class="btn btn-secondary btn-sm" href="{{ route('admin.santri.edit', $s->id) }}"><i data-lucide="pencil"></i> Edit</a> <a class="btn btn-primary btn-sm" href="{{ route('admin.reports.tahfidz', $s->id) }}"><i data-lucide="download"></i> Cetak</a></td>
            </tr>
        @empty
            <tr><td colspan="6" class="muted">Belum ada data santri.</td></tr>
        @endforelse
    </table></div>
</div>

<div class="card">
    <div class="page-head" style="margin-bottom:8px">
        <h3 style="margin:0">Setoran Terbaru</h3>
        <a class="btn btn-secondary btn-sm" href="{{ route('admin.setoran.index') }}">Kelola <i data-lucide="arrow-right"></i></a>
    </div>
    <div class="table-wrap"><table>
        <tr><th>Santri</th><th>Juz</th><th>Surah</th><th>Jenis</th><th>Nilai</th><th>Aksi</th></tr>
        @forelse ($setoran as $st)
            <tr>
                <td>{{ $st->santri->user->nama ?? '-' }}</td>
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
                <td><a class="btn btn-secondary btn-sm" href="{{ route('admin.setoran.edit', $st->id) }}"><i data-lucide="pencil"></i> Edit</a></td>
            </tr>
        @empty
            <tr><td colspan="6" class="muted">Belum ada setoran.</td></tr>
        @endforelse
    </table></div>
</div>

<div class="card">
    <div class="page-head" style="margin-bottom:8px">
        <h3 style="margin:0">Pengguna</h3>
        <a class="btn btn-secondary btn-sm" href="{{ route('admin.users.index') }}">Kelola <i data-lucide="arrow-right"></i></a>
    </div>
    <div class="table-wrap"><table>
        <tr><th>Nama</th><th>Username</th><th>Email</th><th>Role</th><th>Aksi</th></tr>
        @foreach ($users as $u)
            <tr>
                <td>{{ $u->nama }}</td><td>{{ $u->username }}</td><td>{{ $u->email }}</td>
                <td>
                    @if ($u->role === 'admin')
                        <span class="badge badge-red">admin</span>
                    @elseif ($u->role === 'musyrif')
                        <span class="badge badge-amber">musyrif</span>
                    @elseif ($u->role === 'santri')
                        <span class="badge badge-green">santri</span>
                    @else
                        <span class="badge badge-blue">wali</span>
                    @endif
                </td>
                <td><a class="btn btn-secondary btn-sm" href="{{ route('admin.users.edit', $u->id) }}"><i data-lucide="pencil"></i> Edit</a></td>
            </tr>
        @endforeach
    </table></div>
</div>
@endsection
