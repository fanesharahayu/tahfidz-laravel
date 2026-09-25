<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tahfidz — Monitoring Hafalan Al-Qur'an Santri</title>
    <meta name="description" content="Sistem monitoring hafalan Al-Qur'an: catat setoran, kelola target juz, dan pantau perkembangan santri untuk admin, musyrif, santri, dan wali.">
    <script>
    try { var t = localStorage.getItem('tahfidz-theme'); if (t) document.documentElement.setAttribute('data-theme', t); } catch (e) {}
    </script>
    <style>
        :root {
            --green-950: #052e1b; --green-900: #0b3d26; --green-800: #065f46; --green-700: #047857;
            --green-600: #059669; --green-300: #6ee7b7; --green-100: #d1fae5; --green-50: #ecfdf5;
            --ink: #1f2937; --muted: #6b7280; --line: #e5e7eb; --bg: #ffffff; --soft: #f3f5f4; --card: #ffffff;
        }
        [data-theme="dark"] {
            --ink: #e7f0eb; --muted: #93a89c; --line: #22362c; --bg: #0b1512; --soft: #0e1a14; --card: #111f19;
            color-scheme: dark;
        }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { font-family: "Segoe UI", system-ui, -apple-system, Roboto, sans-serif; margin: 0; background: var(--bg); color: var(--ink); }
        a { color: var(--green-700); }
        .wrap { max-width: 1120px; margin: 0 auto; padding: 0 24px; }

        /* Navbar */
        .nav { position: sticky; top: 0; z-index: 50; background: color-mix(in srgb, var(--bg) 85%, transparent); backdrop-filter: blur(12px); border-bottom: 1px solid var(--line); }
        .nav-inner { display: flex; align-items: center; justify-content: space-between; height: 64px; }
        .logo { font-size: 22px; font-weight: 800; letter-spacing: .3px; color: var(--ink); text-decoration: none; }
        .logo span { color: var(--green-600); }
        .nav-links { display: flex; align-items: center; gap: 26px; }
        .nav-links a { text-decoration: none; color: var(--muted); font-size: 14px; font-weight: 600; }
        .nav-links a:hover { color: var(--green-700); }
        .nav-cta { display: flex; align-items: center; gap: 10px; }
        .btn { display: inline-flex; align-items: center; gap: 8px; background: var(--green-700); color: #fff; padding: 10px 20px; border-radius: 10px; text-decoration: none; border: 1px solid var(--green-700); font-size: 14px; font-weight: 700; cursor: pointer; }
        .btn:hover { background: var(--green-800); }
        .btn-outline { background: transparent; color: var(--ink); border-color: var(--line); }
        .btn-outline:hover { border-color: var(--green-600); color: var(--green-700); background: transparent; }
        .btn svg { width: 16px; height: 16px; }
        .icon-btn { display: inline-flex; align-items: center; justify-content: center; width: 38px; height: 38px; border-radius: 10px; border: 1px solid var(--line); background: transparent; color: var(--ink); cursor: pointer; }
        .icon-btn svg { width: 18px; height: 18px; }
        .icon-btn .icon-sun, .icon-btn .label-light { display: none; }
        [data-theme="dark"] .icon-btn .icon-sun { display: block; }
        [data-theme="dark"] .icon-btn .icon-moon { display: none; }
        .hamburger { display: none; }
        .section { padding: 72px 0; }
        .eyebrow { display: inline-block; font-size: 12px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; color: var(--green-700); background: var(--green-100); padding: 6px 14px; border-radius: 999px; margin-bottom: 14px; }
        [data-theme="dark"] .eyebrow { background: #064e3b; color: #a7f3d0; }
        h1 { font-size: 46px; line-height: 1.12; margin: 0 0 16px; letter-spacing: -.02em; }
        h1 .hl { color: var(--green-600); }
        h2.sec-title { font-size: 30px; margin: 0 0 10px; letter-spacing: -.01em; }
        .lead { font-size: 17px; color: var(--muted); line-height: 1.7; max-width: 640px; }

        /* Hero */
        .hero { background: radial-gradient(900px 420px at 85% 10%, rgba(110,231,183,.25), transparent 60%), radial-gradient(700px 420px at 10% 90%, rgba(5,150,105,.12), transparent 60%), var(--bg); padding: 84px 0 64px; overflow: hidden; }
        .hero-grid { display: grid; grid-template-columns: 1.1fr .9fr; gap: 48px; align-items: center; }
        .hero-cta { display: flex; gap: 12px; margin-top: 28px; flex-wrap: wrap; }
        .hero-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-top: 36px; }
        .hstat { background: var(--card); border: 1px solid var(--line); border-radius: 14px; padding: 16px; }
        .hstat .num { font-size: 26px; font-weight: 800; color: var(--green-700); }
        [data-theme="dark"] .hstat .num { color: #a7f3d0; }
        .hstat .lbl { font-size: 12px; color: var(--muted); margin-top: 2px; }
        .hero-visual { background: linear-gradient(160deg, var(--green-800), var(--green-950)); border-radius: 20px; padding: 28px; color: #eafff3; box-shadow: 0 24px 60px rgba(4,60,38,.35); }
        [data-theme="dark"] .hero-visual { box-shadow: 0 0 40px rgba(52,211,153,.25); }
        .mock { background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.14); border-radius: 12px; padding: 16px; margin-bottom: 12px; }
        .mock:last-child { margin-bottom: 0; }
        .mock .row { display: flex; justify-content: space-between; align-items: center; font-size: 13px; }
        .mock .bar { height: 8px; background: rgba(255,255,255,.18); border-radius: 999px; margin-top: 10px; overflow: hidden; }
        .mock .bar i { display: block; height: 100%; background: linear-gradient(90deg, #34d399, #a7f3d0); border-radius: 999px; }
        .pill { display: inline-block; font-size: 11px; font-weight: 700; background: rgba(110,231,183,.2); color: #a7f3d0; padding: 3px 10px; border-radius: 999px; }

        /* Cards */
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-top: 32px; }
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-top: 32px; }
        .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-top: 32px; }
        .fcard { background: var(--card); border: 1px solid var(--line); border-radius: 16px; padding: 24px; }
        .fcard:hover { border-color: var(--green-600); }
        .fcard .fic { width: 44px; height: 44px; border-radius: 12px; background: var(--green-100); color: var(--green-800); display: flex; align-items: center; justify-content: center; margin-bottom: 14px; }
        [data-theme="dark"] .fcard .fic { background: #064e3b; color: #a7f3d0; }
        .fcard .fic svg { width: 22px; height: 22px; }
        .fcard h3 { margin: 0 0 8px; font-size: 17px; }
        .fcard p, .fcard li { font-size: 14px; color: var(--muted); line-height: 1.65; }
        .fcard ul { margin: 8px 0 0; padding-left: 18px; }
        .soft-sec { background: var(--soft); }
        .steps { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-top: 32px; counter-reset: step; }
        .step { background: var(--card); border: 1px solid var(--line); border-radius: 16px; padding: 24px; position: relative; }
        .step .n { position: absolute; top: 18px; right: 20px; font-size: 40px; font-weight: 800; color: var(--green-100); }
        [data-theme="dark"] .step .n { color: #14342a; }
        .step h3 { margin: 0 0 8px; font-size: 17px; }
        .step p { font-size: 14px; color: var(--muted); line-height: 1.65; margin: 0; }

        /* Kontak */
        .contact-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-top: 32px; }
        .cta-band { margin-top: 40px; background: linear-gradient(120deg, var(--green-800), var(--green-950)); border-radius: 20px; padding: 40px; color: #eafff3; display: flex; justify-content: space-between; align-items: center; gap: 24px; flex-wrap: wrap; }
        .cta-band h3 { margin: 0 0 8px; font-size: 22px; }
        .cta-band p { margin: 0; color: #bfe6cf; font-size: 14px; }
        .btn-light { background: #fff; border-color: #fff; color: var(--green-900); }
        .btn-light:hover { background: var(--green-100); }

        /* Footer */
        footer { background: var(--green-950); color: #bfe6cf; padding: 48px 0 0; margin-top: 0; }
        .foot-grid { display: grid; grid-template-columns: 1.2fr 1fr 1fr; gap: 32px; }
        footer h4 { color: #fff; margin: 0 0 12px; font-size: 15px; }
        footer p, footer li { font-size: 13px; line-height: 1.8; }
        footer ul { list-style: none; margin: 0; padding: 0; }
        footer a { color: #bfe6cf; text-decoration: none; }
        footer a:hover { color: #fff; }
        .foot-contact li { display: flex; gap: 10px; align-items: flex-start; }
        .foot-contact svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: 4px; }
        .foot-bottom { border-top: 1px solid rgba(255,255,255,.12); margin-top: 36px; padding: 18px 0; display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; font-size: 13px; }

        @media (max-width: 900px) {
            .hero-grid { grid-template-columns: 1fr; }
            .grid-4 { grid-template-columns: repeat(2, 1fr); }
            .grid-3, .steps, .contact-grid { grid-template-columns: 1fr 1fr; }
            .foot-grid { grid-template-columns: 1fr 1fr; }
            h1 { font-size: 36px; }
        }
        @media (max-width: 640px) {
            .nav-links { display: none; position: absolute; top: 64px; left: 0; right: 0; background: var(--bg); border-bottom: 1px solid var(--line); flex-direction: column; align-items: stretch; padding: 12px 24px 18px; gap: 4px; }
            .nav-links.open { display: flex; }
            .nav-links a { padding: 10px 0; border-bottom: 1px solid var(--line); }
            .hamburger { display: inline-flex; }
            .grid-3, .grid-2, .steps, .contact-grid { grid-template-columns: 1fr; }
            .hero-stats { grid-template-columns: repeat(2, 1fr); }
            .foot-grid { grid-template-columns: 1fr; }
            .section { padding: 52px 0; }
            h1 { font-size: 30px; }
            .nav-cta .btn span { display: none; }
        }
    </style>
</head>
<body>
<header class="nav">
    <div class="wrap nav-inner">
        <a class="logo" href="#beranda">TAH<span>FIDZ</span></a>
        <nav class="nav-links" id="nav-links">
            <a href="#fitur">Fitur</a>
            <a href="#peran">Peran</a>
            <a href="#alur">Alur</a>
            <a href="#kontak">Kontak</a>
        </nav>
        <div class="nav-cta">
            <button class="icon-btn" id="theme-toggle" aria-label="Ganti mode gelap terang">
                <i data-lucide="moon" class="icon-moon"></i>
                <i data-lucide="sun" class="icon-sun"></i>
            </button>
            <button class="icon-btn hamburger" id="nav-toggle" aria-label="Buka menu"><i data-lucide="menu"></i></button>
            <a class="btn" href="{{ route('login') }}"><i data-lucide="log-in"></i><span>Masuk Aplikasi</span></a>
        </div>
    </div>
</header>

<section class="hero" id="beranda">
    <div class="wrap hero-grid">
        <div>
            <span class="eyebrow">Sistem Informasi Tahfidz</span>
            <h1>Pantau Hafalan Al-Qur'an Santri <span class="hl">Secara Real-Time</span></h1>
            <p class="lead">Catat setoran, kelola target juz, dan lihat perkembangan hafalan — terhubung untuk admin, musyrif, santri, dan wali santri dalam satu aplikasi.</p>
            <div class="hero-cta">
                <a class="btn" href="{{ route('login') }}"><i data-lucide="log-in"></i>Masuk Aplikasi</a>
                <a class="btn btn-outline" href="#fitur">Pelajari Fitur</a>
            </div>
            <div class="hero-stats">
                <div class="hstat"><div class="num">{{ $jumlahSantri }}</div><div class="lbl">Santri Terdaftar</div></div>
                <div class="hstat"><div class="num">{{ $jumlahSetoran }}</div><div class="lbl">Setoran Tercatat</div></div>
                <div class="hstat"><div class="num">{{ $jumlahMusyrif }}</div><div class="lbl">Musyrif</div></div>
                <div class="hstat"><div class="num">{{ $jumlahWali }}</div><div class="lbl">Wali Santri</div></div>
            </div>
        </div>
        <div class="hero-visual">
            <div class="mock">
                <div class="row"><span>Ahmad Farhan · Juz 3</span><span class="pill">lancar</span></div>
                <div class="bar"><i style="width:72%"></i></div>
            </div>
            <div class="mock">
                <div class="row"><span>Muhammad Rizki · Juz 5</span><span class="pill">lancar</span></div>
                <div class="bar"><i style="width:55%"></i></div>
            </div>
            <div class="mock">
                <div class="row"><span>Target Semester · 6 Juz</span><span class="pill">berjalan</span></div>
                <div class="bar"><i style="width:38%"></i></div>
            </div>
        </div>
    </div>
</section>

<section class="section" id="fitur">
    <div class="wrap">
        <span class="eyebrow">Fitur Unggulan</span>
        <h2 class="sec-title">Semua Kebutuhan Tahfidz dalam Satu Tempat</h2>
        <p class="lead">Dirancang bersama pengajar agar pencatatan hafalan rapi, transparan, dan mudah dipantau.</p>
        <div class="grid-3">
            <div class="fcard"><div class="fic"><i data-lucide="notebook-pen"></i></div><h3>Setoran Digital</h3><p>Musyrif mencatat juz, surah, ayat, jenis setoran, dan nilai langsung selepas simaan — tanpa buku tulis.</p></div>
            <div class="fcard"><div class="fic"><i data-lucide="target"></i></div><h3>Target Juz</h3><p>Tetapkan target hafalan per santri per periode beserta tanggal mulai dan selesai.</p></div>
            <div class="fcard"><div class="fic"><i data-lucide="trending-up"></i></div><h3>Progress Real-Time</h3><p>Capaian juz, persentase target, dan riwayat setoran terhitung otomatis setiap ada catatan baru.</p></div>
            <div class="fcard"><div class="fic"><i data-lucide="users"></i></div><h3>Multi-Peran</h3><p>Hak akses terpisah untuk admin, musyrif, santri, dan wali — setiap peran punya dashboard sendiri.</p></div>
            <div class="fcard"><div class="fic"><i data-lucide="heart-handshake"></i></div><h3>Pantauan Wali</h3><p>Orang tua memantau perkembangan hafalan anak dari rumah, lengkap dengan detail tiap setoran.</p></div>
            <div class="fcard"><div class="fic"><i data-lucide="history"></i></div><h3>Riwayat Lengkap</h3><p>Seluruh jejak setoran, target, dan perubahan tersimpan rapi dan mudah ditelusuri.</p></div>
        </div>
    </div>
</section>

<section class="section soft-sec" id="peran">
    <div class="wrap">
        <span class="eyebrow">Peran Pengguna</span>
        <h2 class="sec-title">Dashboard untuk Setiap Peran</h2>
        <p class="lead">Empat peran, empat pengalaman yang disesuaikan dengan kebutuhan masing-masing.</p>
        <div class="grid-4">
            <div class="fcard"><div class="fic"><i data-lucide="shield-check"></i></div><h3>Admin</h3><ul><li>Kelola pengguna & santri</li><li>Atur target hafalan</li><li>Hubungkan wali–santri</li><li>Statistik menyeluruh</li></ul></div>
            <div class="fcard"><div class="fic"><i data-lucide="graduation-cap"></i></div><h3>Musyrif</h3><ul><li>Kelola santri binaan</li><li>Catat & nilai setoran</li><li>Buat target binaan</li><li>Kelola relasi wali</li></ul></div>
            <div class="fcard"><div class="fic"><i data-lucide="book-open"></i></div><h3>Santri</h3><ul><li>Lihat capaian juz</li><li>Riwayat setoran</li><li>Target periode aktif</li><li>Info musyrif pembimbing</li></ul></div>
            <div class="fcard"><div class="fic"><i data-lucide="heart-handshake"></i></div><h3>Wali Santri</h3><ul><li>Pantau semua anak</li><li>Detail tiap setoran</li><li>Progress per anak</li><li>Target berjalan</li></ul></div>
        </div>
    </div>
</section>

<section class="section" id="alur">
    <div class="wrap">
        <span class="eyebrow">Cara Kerja</span>
        <h2 class="sec-title">Mulai dalam Tiga Langkah</h2>
        <div class="steps">
            <div class="step"><div class="n">1</div><div class="fic" style="width:44px;height:44px;border-radius:12px;background:var(--green-100);color:var(--green-800);display:flex;align-items:center;justify-content:center;margin-bottom:14px"><i data-lucide="user-plus"></i></div><h3>Admin Buatkan Akun</h3><p>Registrasi mandiri dinonaktifkan demi keamanan — admin membuatkan akun santri, musyrif, dan wali.</p></div>
            <div class="step"><div class="n">2</div><div class="fic" style="width:44px;height:44px;border-radius:12px;background:var(--green-100);color:var(--green-800);display:flex;align-items:center;justify-content:center;margin-bottom:14px"><i data-lucide="notebook-pen"></i></div><h3>Musyrif Mencatat</h3><p>Setiap simaan langsung dicatat: juz, surah, ayat, jenis, dan nilai kelancaran hafalan.</p></div>
            <div class="step"><div class="n">3</div><div class="fic" style="width:44px;height:44px;border-radius:12px;background:var(--green-100);color:var(--green-800);display:flex;align-items:center;justify-content:center;margin-bottom:14px"><i data-lucide="trending-up"></i></div><h3>Semua Memantau</h3><p>Santri dan wali melihat progress real-time dari dashboard masing-masing.</p></div>
        </div>
    </div>
</section>

<section class="section soft-sec" id="kontak">
    <div class="wrap">
        <span class="eyebrow">Hubungi Kami</span>
        <h2 class="sec-title">Kontak & Lokasi</h2>
        <p class="lead">Butuh akun, bantuan teknis, atau ingin berdiskusi? Silakan hubungi kami.</p>
        <div class="contact-grid">
            <div class="fcard"><div class="fic"><i data-lucide="map-pin"></i></div><h3>Alamat</h3><p>Jl. Pendidikan Islam No. 45,<br>Kec. Tahfidz, Bandung,<br>Jawa Barat 40115</p></div>
            <div class="fcard"><div class="fic"><i data-lucide="phone"></i></div><h3>Telepon / WhatsApp</h3><p>+62 812-3456-7890<br>Senin–Sabtu, 08.00–16.00 WIB</p></div>
            <div class="fcard"><div class="fic"><i data-lucide="mail"></i></div><h3>Email</h3><p>info@tahfidz.sch.id<br>Respon maks. 1×24 jam kerja</p></div>
        </div>
        <div class="cta-band">
            <div>
                <h3>Siap memantau hafalan dengan rapi?</h3>
                <p>Masuk ke aplikasi atau hubungi admin untuk dibuatkan akun.</p>
            </div>
            <a class="btn btn-light" href="{{ route('login') }}"><i data-lucide="log-in"></i>Masuk Aplikasi</a>
        </div>
    </div>
</section>

<footer>
    <div class="wrap">
        <div class="foot-grid">
            <div>
                <a class="logo" href="#beranda" style="color:#fff">TAH<span style="color:#6ee7b7">FIDZ</span></a>
                <p style="margin-top:12px">Sistem monitoring hafalan Al-Qur'an untuk santri, musyrif, wali santri, dan admin — rapi, transparan, real-time.</p>
            </div>
            <div>
                <h4>Navigasi</h4>
                <ul>
                    <li><a href="#fitur">Fitur</a></li>
                    <li><a href="#peran">Peran</a></li>
                    <li><a href="#alur">Alur</a></li>
                    <li><a href="#kontak">Kontak</a></li>
                    <li><a href="{{ route('login') }}">Masuk Aplikasi</a></li>
                </ul>
            </div>
            <div>
                <h4>Kontak</h4>
                <ul class="foot-contact">
                    <li><i data-lucide="map-pin"></i><span>Jl. Pendidikan Islam No. 45, Bandung, Jawa Barat 40115</span></li>
                    <li><i data-lucide="phone"></i><span>+62 812-3456-7890</span></li>
                    <li><i data-lucide="mail"></i><span>info@tahfidz.sch.id</span></li>
                </ul>
            </div>
        </div>
        <div class="foot-bottom">
            <span>© {{ date('Y') }} Sistem Monitoring Tahfidz. Seluruh hak cipta dilindungi.</span>
            <span>Dibangun dengan Laravel 12</span>
        </div>
    </div>
</footer>

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
    var navToggle = document.getElementById('nav-toggle');
    var navLinks = document.getElementById('nav-links');
    if (navToggle && navLinks) navToggle.addEventListener('click', function () {
        navLinks.classList.toggle('open');
    });
    if (navLinks) navLinks.querySelectorAll('a').forEach(function (a) {
        a.addEventListener('click', function () { navLinks.classList.remove('open'); });
    });
})();
</script>
</body>
</html>
