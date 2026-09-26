<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Preview Laporan Tahfidz</title>
    <style>
        :root { 
            --green-900: #052e1b; --green-800: #065f46; --green-700: #047857;
            --green-50: #ecfdf5; --bg: #f3f5f4; --ink: #1f2937; --muted: #6b7280; --white: #ffffff;
        }
        body { font-family: "Segoe UI", system-ui, sans-serif; background: var(--bg); color: var(--ink); margin: 0; padding: 0; }
        
        /* Toolbar/Navbar */
        .toolbar { 
            position: sticky; top: 0; background: var(--white); padding: 16px 32px; 
            display: flex; justify-content: space-between; align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05); z-index: 100;
        }
        .toolbar-title { font-weight: 800; color: var(--green-900); font-size: 1.1rem; }
        
        /* Actions */
        .actions { display: flex; gap: 12px; align-items: center; }
        .filter-group { display: flex; gap: 8px; align-items: center; }
        .input-styled { 
            padding: 8px 14px; border: 1px solid #d1d5db; border-radius: 8px; 
            font-size: 14px; outline: none; transition: border 0.2s;
        }
        .input-styled:focus { border-color: var(--green-700); ring: 2px solid var(--green-100); }
        .btn { 
            padding: 8px 18px; border-radius: 8px; font-weight: 600; cursor: pointer; border: none;
            transition: background 0.2s; display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-primary { background: var(--green-700); color: #fff; }
        .btn-primary:hover { background: var(--green-800); }
        .btn-secondary { background: #f3f4f6; color: var(--ink); }
        .btn-secondary:hover { background: #e5e7eb; }
        
        /* A4 Page */
        .page-container { padding: 40px 20px; }
        .page {
            background: white; width: 210mm; min-height: 297mm;
            margin: 0 auto; padding: 20mm; box-sizing: border-box;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        .report-header { text-align: center; margin-bottom: 30px; border-bottom: 3px double #e5e7eb; padding-bottom: 20px; }
        .report-header h1 { margin: 0; color: var(--green-900); font-size: 24px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: var(--green-50); color: var(--green-900); padding: 12px; border: 1px solid #d1d5db; font-size: 13px; text-transform: uppercase; }
        td { padding: 10px; border: 1px solid #d1d5db; font-size: 13px; }
        
        .santri-block { margin-bottom: 30px; page-break-inside: avoid; }
        .santri-info { background: var(--green-50); padding: 10px 14px; border-radius: 6px; border-left: 4px solid var(--green-700); font-weight: 700; color: var(--green-900); margin-bottom: 8px; }

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
        <div class="toolbar-title">Preview Laporan Tahfidz</div>
        <div class="actions">
            <div class="filter-group">
                <input type="text" id="filterNama" class="input-styled" placeholder="Cari santri..." onkeyup="applyFilter()">
                <select id="filterKelas" class="input-styled" onchange="applyFilter()">
                    <option value="">Kelas</option>
                    @php $kelass = collect($allReports)->pluck('kelas')->unique()->filter(); @endphp
                    @foreach($kelass as $k) <option value="{{ $k }}">{{ $k }}</option> @endforeach
                </select>
            </div>
            <button class="btn btn-primary" onclick="window.print()">Cetak / Simpan</button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="page-container">
        <div class="page">
            <div class="report-header">
                <h1>{{ $title }}</h1>
                <p style="color:var(--muted); margin-top:5px;">Mawa'izh Al-Qur'an Tahfidz Center</p>
            </div>
            
            <div class="meta" style="display:flex; justify-content:space-between; margin-bottom:20px; font-size:14px; color:var(--muted);">
                <span>Tanggal: <strong>{{ $date }}</strong></span>
                <span>Total: <strong id="totalCount" style="color:var(--green-700)">{{ count($allReports) }}</strong></span>
            </div>

            <div id="santriList">
                @foreach($allReports as $data)
                    <div class="santri-block" data-nama="{{ strtolower($data['santri']) }}" data-kelas="{{ $data['kelas'] }}">
                        <div class="santri-info">
                            {{ $data['santri'] }} <span style="font-weight:400; opacity:0.7;">— {{ $data['kelas'] }}</span>
                        </div>
                        <table>
                            <thead>
                                <tr><th>Surah</th><th>Juz</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                @forelse($data['hafalan'] as $h)
                                    <tr>
                                        <td>{{ $h['surah'] }}</td>
                                        <td>Juz {{ $h['juz'] }}</td>
                                        <td>{{ ucfirst($h['status']) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" style="text-align:center; color:var(--muted)">Belum ada data setoran.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        function applyFilter() {
            const nameQuery = document.getElementById('filterNama').value.toLowerCase();
            const classQuery = document.getElementById('filterKelas').value;
            const blocks = document.querySelectorAll('.santri-block');
            let visibleCount = 0;
            blocks.forEach(block => {
                const name = block.getAttribute('data-nama');
                const kelas = block.getAttribute('data-kelas');
                if (name.includes(nameQuery) && (classQuery === "" || kelas === classQuery)) {
                    block.style.display = 'block';
                    visibleCount++;
                } else {
                    block.style.display = 'none';
                }
            });
            document.getElementById('totalCount').innerText = visibleCount;
        }
    </script>
</body>
</html>
