<!DOCTYPE html>
<html>
<head>
    <title>{{ $title ?? 'Laporan Tahfidz' }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; margin: 20px; font-size: 12px; }
        h1 { text-align: center; color: #333; }
        .santri-section { margin-top: 30px; border-bottom: 2px solid #eee; padding-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>{{ $title ?? 'Laporan Tahfidz' }}</h1>
    <p>Tanggal Cetak: {{ $date ?? 'N/A' }}</p>

    @foreach($allReports as $data)
        <div class="santri-section">
            <h2>Santri: {{ $data['santri'] }} ({{ $data['kelas'] }})</h2>
            <table>
                <thead>
                    <tr><th>Surah</th><th>Juz</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse($data['hafalan'] as $item)
                        <tr>
                            <td>{{ $item['surah'] }}</td>
                            <td>{{ $item['juz'] }}</td>
                            <td>{{ $item['status'] }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3">Tidak ada data hafalan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endforeach
</body>
</html>
