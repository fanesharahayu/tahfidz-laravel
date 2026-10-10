<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Laporan Tahfidz' }}</title>
    <style>
        :root {
            --green-900: #052e1b; --green-800: #065f46; --green-700: #047857;
            --green-50: #ecfdf5; --bg: #f3f5f4; --ink: #1f2937; --muted: #6b7280; --white: #ffffff;
        }
        body { font-family: "Segoe UI", system-ui, sans-serif; background: var(--bg); color: var(--ink); margin: 0; padding: 0; }
        .toolbar {
            position: sticky; top: 0; background: var(--white); padding: 16px 32px;
            display: flex; justify-content: space-between; align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05); z-index: 100;
        }
        .toolbar-title { font-weight: 800; color: var(--green-900); font-size: 1.1rem; }
        .actions { display: flex; gap: 12px; align-items: center; }
        .btn {
            padding: 8px 18px; border-radius: 8px; font-weight: 600; cursor: pointer; border: none;
            transition: background 0.2s; display: inline-flex; align-items: center; gap: 8px; text-decoration: none;
        }
        .btn-primary { background: var(--green-700); color: #fff; }
        .btn-primary:hover { background: var(--green-800); }
        .btn-secondary { background: #f3f4f6; color: var(--ink); }
        .btn-secondary:hover { background: #e5e7eb; }
        .page-container { padding: 40px 20px; }
        .page {
            background: white; width: 210mm; min-height: 297mm;
            margin: 0 auto; padding: 20mm; box-sizing: border-box;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        .report-header { text-align: center; margin-bottom: 30px; border-bottom: 3px double #e5e7eb; padding-bottom: 20px; }
        .report-header h1 { margin: 0; color: var(--green-900); font-size: 24px; }
        .header-info { margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .header-info div { margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: var(--green-50); color: var(--green-900); padding: 12px; border: 1px solid #d1d5db; font-size: 13px; text-transform: uppercase; text-align: left; }
        td { padding: 10px; border: 1px solid #d1d5db; font-size: 13px; }
        .santri-info { background: var(--green-50); padding: 10px 14px; border-radius: 6px; border-left: 4px solid var(--green-700); font-weight: 700; color: var(--green-900); margin-bottom: 8px; }
        .footer { text-align: center; margin-top: 40px; font-size: 10px; color: #777; }
        @media print {
            body { background: white; }
            .toolbar { display: none; }
            .page-container { padding: 0; }
            .page { box-shadow: none; width: 100%; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <div class="toolbar-title">{{ $title ?? 'Laporan Tahfidz' }}</div>
        <div class="actions">
            <button class="btn btn-primary" onclick="window.print()">Cetak / Simpan</button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="page-container">
        <div class="page">
            <div class="report-header">
                <h1>{{ $title ?? 'Laporan Tahfidz' }}</h1>
                <p style="color:var(--muted); margin-top:5px;">Mawa'izh Al-Qur'an Tahfidz Center</p>
            </div>

            <div class="header-info">
                <div><strong>Tanggal Cetak:</strong> {{ $date ?? 'N/A' }}</div>
                <div><strong>Nama Santri:</strong> {{ $santri ?? 'N/A' }}</div>
                <div><strong>Kelas:</strong> {{ $kelas ?? 'N/A' }}</div>
            </div>

            <h2>Detail Hafalan</h2>
            <table>
                <thead>
                    <tr><th>Surah</th><th>Juz</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @if(isset($hafalan) && count($hafalan) > 0)
                        @foreach($hafalan as $item)
                            <tr>
                                <td>{{ $item['surah'] }}</td>
                                <td>Juz {{ $item['juz'] }}</td>
                                <td>{{ ucfirst($item['status']) }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr><td colspan="3" style="text-align:center; color:var(--muted)">Tidak ada data hafalan.</td></tr>
                    @endif
                </tbody>
            </table>

            <div class="footer">
                Laporan ini dibuat secara otomatis oleh Sistem Tahfidz. Hak Cipta &copy; {{ date('Y') }}
            </div>
        </div>
    </div>
</body>
</html>
