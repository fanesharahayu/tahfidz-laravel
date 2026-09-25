@extends('layouts.app')

@section('title', 'Profil')

@section('content')
<div class="page-head">
    <div>
        <h2>Profil Saya</h2>
        <p class="muted">Informasi akun dan data tahfidz.</p>
    </div>
</div>
<div class="card" style="max-width:600px">
    <div style="display:flex;gap:16px;align-items:center;margin-bottom:16px">
        <div class="avatar" style="width:72px;height:72px;font-size:30px">
            {{ strtoupper(substr($user->nama ?? $user->name ?? '?', 0, 1)) }}
        </div>
        <div>
            <div style="font-size:20px;font-weight:800">{{ $user->nama ?? $user->name }}</div>
            <div><span class="badge badge-green">{{ $user->role }}</span> <span class="muted">{{ $user->username }} · {{ $user->email }}</span></div>
        </div>
    </div>
    <div class="table-wrap"><table>
        <tbody>
            <tr><th style="width:170px">Nama</th><td>{{ $user->nama ?? $user->name }}</td></tr>
            <tr><th>Username</th><td>{{ $user->username }}</td></tr>
            <tr><th>Email</th><td>{{ $user->email }}</td></tr>
            <tr><th>Role</th><td><span class="badge badge-gray">{{ $user->role }}</span></td></tr>
            @if ($user->role === 'santri' && $santri)
                <tr><th>NIS</th><td>{{ $santri->nis ?? '-' }}</td></tr>
                <tr><th>Kelas</th><td>{{ $santri->kelas ?? '-' }}</td></tr>
                <tr><th>Musyrif</th><td>{{ $santri->musyrif->nama ?? $santri->musyrif->name ?? '-' }}</td></tr>
                <tr><th>Target Juz</th><td><span class="badge badge-blue">{{ $santri->target_juz ?? '-' }} juz</span></td></tr>
            @endif
            @if ($user->role === 'musyrif')
                <tr><th>Spesialisasi</th><td>{{ $musyrifProfile->spesialisasi ?? '-' }}</td></tr>
                <tr><th>Jumlah Binaan</th><td><span class="badge badge-blue">{{ $jumlahBinaan }} santri</span></td></tr>
            @endif
        </tbody>
    </table></div>
    <div style="margin-top:16px;display:flex;gap:8px">
        <a href="{{ route('password.edit') }}" class="btn btn-secondary"><i data-lucide="key-round"></i> Ganti Password</a>
    </div>
</div>

<div class="card" style="max-width:600px">
    <h3>Sesi Aktif ({{ count($sessions) }})</h3>
    <p class="muted">Perangkat yang sedang login memakai akun ini.</p>
    @if (empty($sessions))
        <p class="muted">Tidak ada data sesi. (Aktif bila penyimpanan sesi memakai database.)</p>
    @else
        <div class="table-wrap"><table>
            <tr><th>Perangkat</th><th>IP</th><th>Masuk</th><th>Terakhir aktif</th><th>Durasi</th><th>Status</th></tr>
            @foreach ($sessions as $s)
                <tr @if ($s['is_current']) style="background:var(--green-50)" @endif>
                    <td>
                        <div style="display:flex;gap:8px;align-items:center">
                            <i data-lucide="{{ \App\Helpers\UserAgentParser::deviceIcon($s['device']) }}"></i>
                            <div><strong>{{ $s['os'] }}</strong><br><span class="muted">{{ $s['browser'] }} · {{ $s['device'] }}</span></div>
                        </div>
                    </td>
                    <td>{{ $s['ip'] }}</td>
                    <td class="muted">{{ $s['login_at'] ? $s['login_at']->format('d M Y H:i') : '-' }}</td>
                    <td class="muted">{{ $s['last_active']->locale('id')->diffForHumans() }}<br>{{ $s['last_active']->format('H:i') }}</td>
                    <td>{{ $s['duration'] }}</td>
                    <td>
                        @if ($s['is_current'])
                            <span class="badge badge-green">Perangkat ini</span>
                        @else
                            <span class="badge badge-gray">Lainnya</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table></div>
    @endif
</div>
@endsection
