<!DOCTYPE html>
<html>
<head>
    <title>{{ $title ?? 'Laporan Tahfidz' }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif; /* Mendukung karakter non-Latin */
            margin: 20px;
            font-size: 12px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .header-info {
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .header-info div {
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>
<body>
    <h1>{{ $title ?? 'Laporan Tahfidz' }}</h1>

    <div class="header-info">
        <div><strong>Tanggal Cetak:</strong> {{ $date ?? 'N/A' }}</div>
        <div><strong>Nama Santri:</strong> {{ $santri ?? 'N/A' }}</div>
        <div><strong>Kelas:</strong> {{ $kelas ?? 'N/A' }}</div>
    </div>

    <h2>Detail Hafalan</h2>
    <table>
        <thead>
            <tr>
                <th>Surah</th>
                <th>Juz</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($hafalan) && count($hafalan) > 0)
                @foreach($hafalan as $item)
                    <tr>
                        <td>{{ $item['surah'] }}</td>
                        <td>{{ $item['juz'] }}</td>
                        <td>{{ $item['status'] }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="3">Tidak ada data hafalan.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        Laporan ini dibuat secara otomatis oleh Sistem Tahfidz. Hak Cipta &copy; {{ date('Y') }}
    </div>
</body>
</html>
