<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Tahfidz') - Monitoring Hafalan</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: system-ui, -apple-system, Segoe UI, Roboto, sans-serif; margin: 0; background: #f6f7f9; color: #1f2937; }
        .nav { background: #14532d; color: #fff; padding: 12px 20px; display: flex; justify-content: space-between; align-items: center; }
        .nav a { color: #fff; text-decoration: none; margin-left: 12px; }
        .container { max-width: 1000px; margin: 24px auto; padding: 0 16px; }
        .card { background: #fff; border-radius: 10px; padding: 20px; margin-bottom: 16px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px; margin-bottom: 16px; }
        .stat { background: #fff; border-radius: 10px; padding: 16px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .stat .num { font-size: 28px; font-weight: 700; }
        .stat .lbl { color: #6b7280; font-size: 13px; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th, td { text-align: left; padding: 8px; border-bottom: 1px solid #e5e7eb; }
        .btn { display: inline-block; background: #14532d; color: #fff; padding: 8px 14px; border-radius: 8px; text-decoration: none; border: 0; cursor: pointer; }
        .btn-danger { background: #b91c1c; }
        .input { width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; margin: 6px 0 12px; }
        .alert { background: #fef2f2; color: #991b1b; padding: 10px 14px; border-radius: 8px; margin-bottom: 12px; }
        .success { background: #f0fdf4; color: #166534; padding: 10px 14px; border-radius: 8px; margin-bottom: 12px; }
        .muted { color: #6b7280; font-size: 13px; }
    </style>
</head>
<body>
<div class="nav">
    <div><strong>Tahfidz</strong> <span class="muted" style="color:#bbf7d0">Monitoring Hafalan</span></div>
    <div>
        @auth
            <span>{{ auth()->user()->nama }} ({{ auth()->user()->role }})</span>
            <a href="{{ route('password.edit') }}">Ganti Password</a>
            <form action="{{ route('logout') }}" method="POST" style="display:inline">
                @csrf
                <button class="btn btn-danger" type="submit" style="padding:4px 10px">Logout</button>
            </form>
        @endauth
    </div>
</div>
<div class="container">
    @if (session('status'))
        <div class="success">{{ session('status') }}</div>
    @endif
    @yield('content')
</div>
</body>
</html>
