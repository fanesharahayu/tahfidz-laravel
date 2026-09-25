<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Tahfidz') - Monitoring Hafalan Al-Qur'an</title>
    <script>
    try { var t = localStorage.getItem('tahfidz-theme'); if (t) document.documentElement.setAttribute('data-theme', t); } catch (e) {}
    </script>
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
        .snav a svg { width: 18px; height: 18px; flex-shrink: 0; }
        .btn svg { width: 15px; height: 15px; vertical-align: -2px; }
        h2 svg { width: 22px; height: 22px; vertical-align: -3px; }
        .side-foot { padding: 12px; border-top: 1px solid rgba(255,255,255,.12); display: flex; flex-direction: column; gap: 8px; }
        .account-menu .icon-sun, .account-menu .label-light { display: none; }
        [data-theme="dark"] .account-menu .icon-sun, [data-theme="dark"] .account-menu .label-light { display: block; }
        [data-theme="dark"] .account-menu .icon-moon, [data-theme="dark"] .account-menu .label-dark { display: none; }
        .account { position: relative; }
        .account-toggle { width: 100%; display: flex; align-items: center; gap: 10px; background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.14); color: #fff; border-radius: 10px; padding: 8px 10px; cursor: pointer; text-align: left; font-size: 14px; }
        .account-toggle:hover { background: rgba(255,255,255,.15); }
        .account-toggle .account-meta { flex: 1; min-width: 0; display: flex; flex-direction: column; line-height: 1.3; }
        .account-toggle .who { font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .account-toggle > svg { width: 18px; height: 18px; flex-shrink: 0; transition: transform .2s; }
        .account.open .account-toggle > svg { transform: rotate(180deg); }
        .account-menu { opacity: 0; visibility: hidden; transform: translateY(8px); transition: opacity .18s ease, transform .18s ease, visibility .18s; position: absolute; bottom: calc(100% + 8px); left: 0; right: 0; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,.35); }
        .account.open .account-menu { opacity: 1; visibility: visible; transform: none; }
        .account-menu a, .account-menu button { display: flex; align-items: center; gap: 10px; width: 100%; padding: 10px 14px; font-size: 14px; color: var(--ink); text-decoration: none; background: none; border: 0; cursor: pointer; }
        .account-menu a:hover, .account-menu button:hover { background: #f3f4f6; }
        .account-menu a.active { font-weight: 700; color: var(--green-800); }
        .account-menu .logout-btn { color: #b91c1c; font-weight: 600; }
        .account-menu .logout-btn:hover { background: var(--red-bg); }
        .account-menu svg { width: 16px; height: 16px; flex-shrink: 0; }
        .main { flex: 1; min-width: 0; display: flex; flex-direction: column; }
        .topbar { background: var(--card); border-bottom: 1px solid var(--line); padding: 12px 24px; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 5; }
        .topbar .actions { display: flex; align-items: center; gap: 10px; }
        .top-left { display: flex; align-items: center; gap: 10px; min-width: 0; }
        .hamburger { display: none; background: #fff; border: 1px solid var(--line); border-radius: 8px; padding: 7px; cursor: pointer; color: var(--ink); }
        .hamburger svg { width: 20px; height: 20px; display: block; }
        .overlay { display: none; }
        .crumbs { font-size: 14px; display: flex; align-items: center; flex-wrap: wrap; }
        .crumbs a { color: var(--muted); text-decoration: none; }
        .crumbs a:hover { color: var(--green-800); text-decoration: underline; }
        .crumbs .sep { margin: 0 8px; color: #9ca3af; }
        .crumbs .current { font-weight: 700; color: var(--ink); }

        /* ===== Dark mode ===== */
        [data-theme="dark"] {
            --bg: #0b1512; --card: #111f19; --ink: #e7f0eb; --muted: #93a89c; --line: #22362c;
            color-scheme: dark;
        }
        [data-theme="dark"] table { background: var(--card); }
        [data-theme="dark"] th { background: #1c3327; color: #c4d6cb; }
        [data-theme="dark"] .stat .num { color: #a7f3d0; }
        [data-theme="dark"] tbody tr:hover { background: #15241c; }
        [data-theme="dark"] .input, [data-theme="dark"] select.input, [data-theme="dark"] textarea.input { background: #0e1a14; border-color: #2c4a3a; color: var(--ink); }
        [data-theme="dark"] label { color: #c4d6cb; }
        [data-theme="dark"] code { background: #22362c; padding: 1px 5px; border-radius: 4px; }
        [data-theme="dark"] .btn-secondary { background: #14261d; border-color: #2c4a3a; color: #a7f3d0; }
        [data-theme="dark"] .btn-secondary:hover { background: #1a3125; }
        [data-theme="dark"] .subnav a { background: var(--card); border-color: var(--line); color: #c4d6cb; }
        [data-theme="dark"] .badge-gray { background: #22362c; color: #c4d6cb; }
        [data-theme="dark"] .badge-green { background: #064e3b; color: #a7f3d0; }
        [data-theme="dark"] .badge-amber { background: #453003; color: #fcd34d; }
        [data-theme="dark"] .badge-red { background: #450a0a; color: #fca5a5; }
        [data-theme="dark"] .badge-blue { background: #172554; color: #bfdbfe; }
        [data-theme="dark"] .alert { background: #2a0f0f; border-color: #7f1d1d; color: #fca5a5; }
        [data-theme="dark"] .success { background: #052e1b; border-color: #065f46; color: #a7f3d0; }
        [data-theme="dark"] .progress { background: #22362c; }
        [data-theme="dark"] .account-menu { background: #14261d; }
        [data-theme="dark"] .account-menu a, [data-theme="dark"] .account-menu button { color: #e7f0eb; }
        [data-theme="dark"] .account-menu a:hover, [data-theme="dark"] .account-menu button:hover { background: #1d3529; }
        [data-theme="dark"] .account-menu .logout-btn { color: #fca5a5; }
        [data-theme="dark"] .account-menu .logout-btn:hover { background: #450a0a; }
        [data-theme="dark"] .hamburger { background: var(--card); color: var(--ink); }
        [data-theme="dark"] .guest-card { background: var(--card); }
        [data-theme="dark"] .guest-form { color: var(--ink); }
        /* Glow halus saat dark mode: hanya icon menu yang aktif */
        [data-theme="dark"] .snav a.active svg { filter: drop-shadow(0 0 5px rgba(110,231,183,.9)); }
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
            .hamburger { display: inline-flex; }
            .sidebar { position: fixed; left: 0; top: 0; height: 100dvh; width: 260px; z-index: 50; transform: translateX(-105%); transition: transform .25s ease; }
            body.sidebar-open .sidebar { transform: none; box-shadow: 0 0 40px rgba(0,0,0,.35); }
            body.sidebar-open .overlay { display: block; position: fixed; inset: 0; background: rgba(0,0,0,.45); z-index: 40; }
            .snav { flex-direction: column; flex-wrap: nowrap; }
            .snav .group { width: auto; }
            .side-foot { display: block; }
            .form-grid { grid-template-columns: 1fr; }
            .guest-card { flex-direction: column; }
            .container { padding: 16px; }
            .topbar { padding: 10px 14px; }
        }
        @media (max-width: 560px) {
            .topbar .top-user { display: none; }
            .stats { grid-template-columns: repeat(2, 1fr); }
            .page-head h2 { font-size: 20px; }
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
                ['Dashboard', 'admin.dashboard', 'layout-dashboard'],
                ['Pengguna', 'admin.users.index', 'users'],
                ['Santri', 'admin.santri.index', 'graduation-cap'],
                ['Setoran', 'admin.setoran.index', 'notebook-pen'],
                ['Target', 'admin.targets.index', 'target'],
                ['Wali–Santri', 'admin.wali-links.index', 'heart-handshake'],
            ],
            'musyrif' => [
                ['Dashboard', 'musyrif.dashboard', 'layout-dashboard'],
                ['Binaan', 'musyrif.binaan.index', 'graduation-cap'],
                ['Setoran', 'musyrif.setoran.index', 'notebook-pen'],
                ['Target', 'musyrif.targets.index', 'target'],
                ['Wali', 'musyrif.wali.index', 'heart-handshake'],
            ],
            'santri' => [
                ['Dashboard', 'santri.dashboard', 'layout-dashboard'],
            ],
            'wali' => [
                ['Dashboard', 'wali.dashboard', 'layout-dashboard'],
            ],
        ];
        $roleNames = ['admin' => 'Admin', 'musyrif' => 'Musyrif', 'santri' => 'Santri', 'wali' => 'Wali Santri'];
        $breadcrumbMap = [
            'admin.dashboard' => [['Dashboard', null]],
            'admin.users.index' => [['Dashboard', 'admin.dashboard'], ['Pengguna', null]],
            'admin.users.create' => [['Dashboard', 'admin.dashboard'], ['Pengguna', 'admin.users.index'], ['Tambah', null]],
            'admin.users.edit' => [['Dashboard', 'admin.dashboard'], ['Pengguna', 'admin.users.index'], ['Ubah', null]],
            'admin.santri.index' => [['Dashboard', 'admin.dashboard'], ['Santri', null]],
            'admin.santri.create' => [['Dashboard', 'admin.dashboard'], ['Santri', 'admin.santri.index'], ['Tambah', null]],
            'admin.santri.edit' => [['Dashboard', 'admin.dashboard'], ['Santri', 'admin.santri.index'], ['Ubah', null]],
            'admin.setoran.index' => [['Dashboard', 'admin.dashboard'], ['Setoran', null]],
            'admin.setoran.create' => [['Dashboard', 'admin.dashboard'], ['Setoran', 'admin.setoran.index'], ['Tambah', null]],
            'admin.setoran.edit' => [['Dashboard', 'admin.dashboard'], ['Setoran', 'admin.setoran.index'], ['Ubah', null]],
            'admin.targets.index' => [['Dashboard', 'admin.dashboard'], ['Target', null]],
            'admin.target.create' => [['Dashboard', 'admin.dashboard'], ['Target', 'admin.targets.index'], ['Tambah', null]],
            'admin.target.edit' => [['Dashboard', 'admin.dashboard'], ['Target', 'admin.targets.index'], ['Ubah', null]],
            'admin.wali-links.index' => [['Dashboard', 'admin.dashboard'], ['Wali–Santri', null]],
            'admin.wali-links.create' => [['Dashboard', 'admin.dashboard'], ['Wali–Santri', 'admin.wali-links.index'], ['Tambah', null]],
            'admin.wali-links.edit' => [['Dashboard', 'admin.dashboard'], ['Wali–Santri', 'admin.wali-links.index'], ['Ubah', null]],
            'musyrif.dashboard' => [['Dashboard', null]],
            'musyrif.binaan.index' => [['Dashboard', 'musyrif.dashboard'], ['Binaan', null]],
            'musyrif.setoran.index' => [['Dashboard', 'musyrif.dashboard'], ['Setoran', null]],
            'musyrif.setoran.edit' => [['Dashboard', 'musyrif.dashboard'], ['Setoran', 'musyrif.setoran.index'], ['Ubah', null]],
            'musyrif.targets.index' => [['Dashboard', 'musyrif.dashboard'], ['Target', null]],
            'musyrif.target.edit' => [['Dashboard', 'musyrif.dashboard'], ['Target', 'musyrif.targets.index'], ['Ubah', null]],
            'musyrif.wali.index' => [['Dashboard', 'musyrif.dashboard'], ['Wali', null]],
            'musyrif.santri.detail' => [['Dashboard', 'musyrif.dashboard'], ['Binaan', 'musyrif.binaan.index'], ['Detail Santri', null]],
            'santri.dashboard' => [['Dashboard', null]],
            'wali.dashboard' => [['Dashboard', null]],
            'wali.child.detail' => [['Dashboard', 'wali.dashboard'], ['Detail Anak', null]],
            'profil.show' => [['Profil', null]],
            'password.edit' => [['Profil', 'profil.show'], ['Ganti Password', null]],
        ];
        $crumbs = $breadcrumbMap[request()->route()?->getName() ?? ''] ?? [[trim($__env->yieldContent('title', 'Dashboard')), null]];
    @endphp
    <div class="shell">
        <aside class="sidebar">
            <div class="brand">
                <div class="logo">TAH<span>FIDZ</span></div>
                <div class="sub">Monitoring Hafalan Al-Qur'an</div>
            </div>
            <nav class="snav">
                <div class="group">Menu {{ $roleNames[$role] ?? $role }}</div>
                @foreach (($nav[$role] ?? []) as [$label, $route, $icon])
                    <a href="{{ route($route) }}" class="{{ request()->routeIs($route) ? 'active' : '' }}"><i data-lucide="{{ $icon }}"></i>{{ $label }}</a>
                @endforeach
            </nav>
            <div class="side-foot">
                <div class="account" id="account">
                    <div class="account-menu" id="account-menu">
                        <a href="{{ route('profil.show') }}" class="{{ request()->routeIs('profil.show') ? 'active' : '' }}"><i data-lucide="circle-user-round"></i>Profile</a>
                        <button type="button" id="theme-toggle" aria-label="Ganti mode gelap terang">
                            <i data-lucide="moon" class="icon-moon"></i>
                            <i data-lucide="sun" class="icon-sun"></i>
                            <span class="label-dark">Mode Gelap</span>
                            <span class="label-light">Mode Terang</span>
                        </button>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="logout-btn"><i data-lucide="log-out"></i>Logout</button>
                        </form>
                    </div>
                    <button class="account-toggle" id="account-toggle" aria-label="Menu akun">
                        <span class="avatar" style="width:34px;height:34px;font-size:15px">{{ strtoupper(substr(auth()->user()->nama ?? '?', 0, 1)) }}</span>
                        <span class="account-meta">
                            <span class="who">{{ auth()->user()->nama }}</span>
                            <span class="muted" style="color:#a7d8bf">{{ $roleNames[$role] ?? $role }}</span>
                        </span>
                        <i data-lucide="chevron-up"></i>
                    </button>
                </div>
            </div>
        </aside>
        <div class="overlay" id="sidebar-overlay"></div>
        <div class="main">
            <div class="topbar">
                <div class="top-left">
                    <button class="hamburger" id="hamburger" aria-label="Buka tutup menu"><i data-lucide="menu"></i></button>
                    <nav class="crumbs" aria-label="Breadcrumb">
                    @foreach ($crumbs as $i => [$label, $crumbRoute])
                        @if ($i > 0)<span class="sep">/</span>@endif
                        @if ($crumbRoute)
                            <a href="{{ route($crumbRoute) }}">{{ $label }}</a>
                        @else
                            <span class="current">{{ $label }}</span>
                        @endif
                    @endforeach
                </nav>
                </div>
                <div class="actions">
                    <span class="muted top-user">{{ auth()->user()->nama }} · {{ $roleNames[$role] ?? $role }}</span>
                </div>
            </div>
            <div class="container">
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
                @yield('content')
            </div>
        </div>
    </div>
@endguest
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
if (window.lucide) lucide.createIcons();
(function () {
    var root = document.documentElement;
    var themeBtn = document.getElementById('theme-toggle');
    if (themeBtn) themeBtn.addEventListener('click', function () {
        var next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
        root.setAttribute('data-theme', next);
        try { localStorage.setItem('tahfidz-theme', next); } catch (e) {}
    });
})();
(function () {
    function closeSidebar() { document.body.classList.remove('sidebar-open'); }
    var burger = document.getElementById('hamburger');
    if (burger) burger.addEventListener('click', function () {
        document.body.classList.toggle('sidebar-open');
    });
    var overlay = document.getElementById('sidebar-overlay');
    if (overlay) overlay.addEventListener('click', closeSidebar);
    document.querySelectorAll('.snav a').forEach(function (a) {
        a.addEventListener('click', function () {
            if (window.innerWidth <= 860) closeSidebar();
        });
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeSidebar();
            var acc = document.getElementById('account');
            if (acc) acc.classList.remove('open');
        }
    });
    var accToggle = document.getElementById('account-toggle');
    if (accToggle) accToggle.addEventListener('click', function (e) {
        e.stopPropagation();
        document.getElementById('account').classList.toggle('open');
    });
    document.addEventListener('click', function (e) {
        var acc = document.getElementById('account');
        if (acc && !acc.contains(e.target)) acc.classList.remove('open');
    });
})();
document.addEventListener('submit', function (e) {
    var form = e.target;
    if (!form || form.tagName !== 'FORM' || !form.hasAttribute('data-confirm')) return;
    e.preventDefault();
    Swal.fire({
        title: 'Hapus data?',
        text: form.getAttribute('data-confirm'),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#b91c1c',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then(function (result) {
        if (result.isConfirmed) form.submit();
    });
});
</script>
</body>
</html>
