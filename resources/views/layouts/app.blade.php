<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Tahfidz') - Monitoring Hafalan Al-Qur'an</title>
    <style>
        :root {
            --green-900: #052e1b; --green-800: #065f46; --green-700: #047857;
            --green-600: #059669; --green-100: #d1fae5; --green-50: #ecfdf5;
            --ink: #1f2937; --muted: #6b7280; --line: #e5e7eb;
            --bg: #f3f5f4; --card: #ffffff;
            --amber-bg: #fef3c7; --amber-tx: #92400e;
            --red-bg: #fee2e2; --red-tx: #991b1b;
            --blue-bg: #dbeafe; --blue-tx: #1e40af;
            --radius: 12px;
            --shadow: 0 1px 2px rgba(16,24,40,.06), 0 1px 3px rgba(16,24,40,.1);
        }
        * { box-sizing: border-box; }
        body { font-family: "Segoe UI", system-ui, -apple-system, Roboto, "Helvetica Neue", sans-serif; margin: 0; background: var(--bg); color: var(--ink); }
        a { color: var(--green-700); }

        /* ===== App shell ===== */
        .shell { display: flex; min-height: 100vh; }
        .sidebar { width: 240px; flex-shrink: 0; background: linear-gradient(180deg, var(--green-900), #0b3d26); color: #e7f6ee; display: flex; flex-direction: column; position: sticky; top: 0; height: 100vh; }
        .brand { padding: 20px 18px 14px; border-bottom: 1px solid rgba(255,255,255,.12); }
        .brand .logo { font-size: 20px; font-weight: 800; letter-spacing: .3px; }
        .brand .logo span { color: #6ee7b7; }
        .brand .sub { font-size: 12px; color: #a7d8bf; margin-top: 2px; }
        .snav { padding: 12px 10px; display: flex; flex-direction: column; gap: 2px; overflow-y: auto; flex: 1; }
        .snav .group { font-size: 11px; text-transform: uppercase; letter-spacing: .08em; color: #8fd0ae; padding: 10px 10px 4px; }
        .snav a { color: #dcefe4; text-decoration: none; padding: 9px 12px; border-radius: 8px; font-size: 14px; display: flex; align-items: center; gap: 10px; }
        .snav a:hover { background: rgba(255,255,255,.1); }
        .snav a.active { background: rgba(255,255,255,.16); font-weight: 600; }
        .snav a .ico { width: 20px; text-align: center; opacity: .85; }
        .side-foot { padding: 14px; border-top: 1px solid rgba(255,255,255,.12); font-size: 13px; }
        .side-foot .who { font-weight: 600; }
        .side-foot .role-badge { margin-top: 6px; }
        .main { flex: 1; min-width: 0; display: flex; flex-direction: column; }
        .topbar { background: var(--card); border-bottom: 1px solid var(--line); padding: 12px 24px; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 5; }
        .topbar .page-title { font-size: 18px; font-weight: 700; margin: 0; }
        .topbar .actions { display: flex; align-items: center; gap: 10px; }
        .container { max-width: 1080px; width: 100%; margin: 0 auto; padding: 24px; }

        /* ===== Guest (login) ===== */
        .guest-wrap { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; background: radial-gradient(1000px 500px at 20% 10%, #a7f3d0 0%, transparent 60%), radial-gradient(800px 500px at 90% 90%, #6ee7b7 0%, transparent 55%), var(--green-900); }
        .guest-card { background: #fff; border-radius: 16px; overflow: hidden; display: flex; max-width: 760px; width: 100%; box-shadow: 0 20px 50px rgba(0,0,0,.3); }
        .guest-side { background: linear-gradient(160deg, var(--green-800), var(--green-900)); color: #eafff3; padding: 36px 30px; flex: 1; display: flex; flex-direction: column; justify-content: center; }
        .guest-side h1 { margin: 0 0 8px; font-size: 26px; }
        .guest-side p { color: #bfe6cf; font-size: 14px; line-height: 1.6; }
        .guest-side .verse { margin-top: 18px; font-size: 13px; font-style: italic; border-left: 3px solid #6ee7b7; padding-left: 12px; }
        .guest-form { flex: 1; padding: 36px 32px; }

        /* ===== Components ===== */
        .card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius); padding: 20px; margin-bottom: 16px; box-shadow: var(--shadow); }
        .card h3 { margin: 0 0 12px; font-size: 16px; }
        .page-head { display: flex; justify-content: space-between; align-items: flex-end; gap: 12px; margin-bottom: 16px; flex-wrap: wrap; }
        .page-head h2 { margin: 0; font-size: 24px; }
        .page-head p { margin: 4px 0 0; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px; margin-bottom: 16px; }
        .stat { background: var(--card); border: 1px solid var(--line); border-left: 4px solid var(--green-600); border-radius: var(--radius); padding: 16px; box-shadow: var(--shadow); }
        .stat .num { font-size: 28px; font-weight: 800; color: var(--green-900); }
        .stat .lbl { color: var(--muted); font-size: 13px; margin-top: 2px; }
        .table-wrap { overflow-x: auto; border: 1px solid var(--line); border-radius: 10px; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; background: #fff; }
        th { background: #f8faf9; text-align: left; padding: 10px 12px; font-size: 12px; text-transform: uppercase; letter-spacing: .05em; color: var(--muted); border-bottom: 1px solid var(--line); white-space: nowrap; }
        td { padding: 10px 12px; border-bottom: 1px solid var(--line); vertical-align: middle; }
        tr:last-child td { border-bottom: 0; }
        tbody tr:hover { background: #f9fafb; }
        .btn { display: inline-block; background: var(--green-700); color: #fff; padding: 9px 16px; border-radius: 8px; text-decoration: none; border: 1px solid var(--green-700); cursor: pointer; font-size: 14px; font-weight: 600; }
        .btn:hover { background: var(--green-800); }
        .btn-secondary { background: #fff; color: var(--green-800); border: 1px solid #a7d8bf; }
        .btn-secondary:hover { background: var(--green-50); }
        .btn-danger { background: #fff; color: var(--red-tx); border: 1px solid #fca5a5; }
        .btn-danger:hover { background: var(--red-bg); }
        .btn-sm { padding: 5px 10px; font-size: 13px; border-radius: 7px; }
        .input, select.input, textarea.input { width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; margin: 6px 0 12px; font-size: 14px; background: #fff; }
        .input:focus { outline: 2px solid var(--green-100); border-color: var(--green-600); }
        label { font-size: 13px; font-weight: 600; color: #374151; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0 12px; }
        .form-grid .full { grid-column: 1 / -1; }
        .alert { background: var(--red-bg); color: var(--red-tx); padding: 10px 14px; border-radius: 8px; margin-bottom: 12px; border: 1px solid #fca5a5; }
        .success { background: var(--green-50); color: #166534; padding: 10px 14px; border-radius: 8px; margin-bottom: 12px; border: 1px solid #a7f3d0; }
        .muted { color: var(--muted); font-size: 13px; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; white-space: nowrap; }
        .badge-green { background: var(--green-100); color: #065f46; }
        .badge-amber { background: var(--amber-bg); color: var(--amber-tx); }
        .badge-red { background: var(--red-bg); color: var(--red-tx); }
        .badge-blue { background: var(--blue-bg); color: var(--blue-tx); }
        .badge-gray { background: #f3f4f6; color: #4b5563; }
        .progress { height: 8px; background: #e5e7eb; border-radius: 999px; overflow: hidden; min-width: 90px; }
        .progress .bar { height: 100%; background: linear-gradient(90deg, var(--green-600), #34d399); border-radius: 999px; }
        .progress-row { display: flex; align-items: center; gap: 8px; }
        .progress-row small { color: var(--muted); font-size: 12px; white-space: nowrap; }
        .avatar { border-radius: 50%; background: linear-gradient(135deg, var(--green-600), var(--green-900)); color: #fff; display: inline-flex; align-items: center; justify-content: center; font-weight: 800; }
        .inline-form { display: inline; }
        .subnav { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 14px; }
        .subnav a { padding: 7px 14px; border-radius: 999px; background: #fff; border: 1px solid var(--line); text-decoration: none; font-size: 13px; font-weight: 600; color: #374151; }
        .subnav a:hover { border-color: var(--green-600); color: var(--green-800); }
        .subnav a.active { background: var(--green-800); border-color: var(--green-800); color: #fff; }

        @media (max-width: 860px) {
            .shell { flex-direction: column; }
            .sidebar { width: 100%; height: auto; position: static; }
            .snav { flex-direction: row; flex-wrap: wrap; }
            .snav .group { width: 100%; }
            .side-foot { display: none; }
            .form-grid { grid-template-columns: 1fr; }
            .guest-card { flex-direction: column; }
            .container { padding: 16px; }
        }
    </style>
</head>
<body>
@guest
    <div class="guest-wrap">
        <div class="guest-card">
            <div class="guest-side">
                <div style="font-size:13px;letter-spacing:.15em;color:#6ee7b7;font-weight:700">TAHFIDZ</div>
                <h1>Monitoring Hafalan Al-Qur'an</h1>
                <p>Sistem pencatatan setoran, target hafalan, dan perkembangan santri untuk musyrif, santri, wali, dan admin.</p>
                <div class="verse">"Sebaik-baik kalian adalah yang mempelajari Al-Qur'an dan mengajarkannya." (HR. Bukhari)</div>
            </div>
            <div class="guest-form">
                @yield('content')
            </div>
        </div>
    </div>
@else
    @php
        $role = auth()->user()->role;
        $nav = [
            'admin' => [
                ['Dashboard', route('admin.dashboard'), '▦'],
                ['Pengguna', route('admin.users.index'), '◉'],
                ['Target', url('/admin/dashboard'), '◎'],
                ['Wali–Santri', route('admin.wali-links.index'), '⬔'],
            ],
            'musyrif' => [
                ['Dashboard', route('musyrif.dashboard'), '▦'],
                ['Binaan', route('musyrif.binaan.index'), '◉'],
                ['Setoran', route('musyrif.setoran.index'), '✎'],
                ['Target', route('musyrif.targets.index'), '◎'],
                ['Wali', route('musyrif.wali.index'), '⬔'],
            ],
            'santri' => [
                ['Dashboard', route('santri.dashboard'), '▦'],
            ],
            'wali' => [
                ['Dashboard', route('wali.dashboard'), '▦'],
            ],
        ];
        $roleNames = ['admin' => 'Admin', 'musyrif' => 'Musyrif', 'santri' => 'Santri', 'wali' => 'Wali Santri'];
    @endphp
    <div class="shell">
        <aside class="sidebar">
            <div class="brand">
                <div class="logo">TAH<span>FIDZ</span></div>
                <div class="sub">Monitoring Hafalan Al-Qur'an</div>
            </div>
            <nav class="snav">
                <div class="group">Menu {{ $roleNames[$role] ?? $role }}</div>
                @foreach (($nav[$role] ?? []) as [$label, $url, $ico])
                    <a href="{{ $url }}" class="{{ request()->url() === $url ? 'active' : '' }}"><span class="ico">{{ $ico }}</span>{{ $label }}</a>
                @endforeach
                <div class="group">Akun</div>
                <a href="{{ route('profil.show') }}" class="{{ request()->routeIs('profil.show') ? 'active' : '' }}"><span class="ico">◍</span>Profil</a>
                <a href="{{ route('password.edit') }}" class="{{ request()->routeIs('password.*') ? 'active' : '' }}"><span class="ico">✦</span>Ganti Password</a>
            </nav>
            <div class="side-foot">
                <div class="who">{{ auth()->user()->nama }}</div>
                <div class="muted" style="color:#a7d8bf">{{ auth()->user()->username }}</div>
                <div class="role-badge"><span class="badge badge-green">{{ $roleNames[$role] ?? $role }}</span></div>
                <form action="{{ route('logout') }}" method="POST" style="margin-top:10px">
                    @csrf
                    <button class="btn btn-danger btn-sm" type="submit" style="width:100%">Logout</button>
                </form>
            </div>
        </aside>
        <div class="main">
            <div class="topbar">
                <p class="page-title">@yield('title', 'Dashboard')</p>
                <div class="actions">
                    <span class="muted">{{ auth()->user()->nama }} · {{ $roleNames[$role] ?? $role }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline-form">
                        @csrf
                        <button class="btn btn-danger btn-sm" type="submit">Logout</button>
                    </form>
                </div>
            </div>
            <div class="container">
                @if (session('status'))
                    <div class="success">{{ session('status') }}</div>
                @endif
                @if ($errors->any() && !isset($hideLayoutErrors))
                    <div class="alert">
                        <ul style="margin:0;padding-left:18px">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>
@endguest
</body>
</html>
