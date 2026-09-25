<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Santri;
use App\Models\Setoran;

class ReportController extends Controller
{
    // Untuk laporan per santri (tetap download PDF)
    public function downloadTahfidzReport($santriId)
    {
        $santri = Santri::with('user', 'setoran')->findOrFail($santriId);
        $reportData = [
            'title' => 'Laporan Tahfidz Santri',
            'date' => date('d F Y'),
            'santri' => $santri->user->nama ?? 'N/A',
            'kelas' => $santri->kelas ?? 'N/A',
            'hafalan' => $santri->setoran->map(function ($s) {
                return ['surah' => $s->surah, 'juz' => $s->juz, 'status' => $s->nilai];
            })->toArray()
        ];
        $pdf = Pdf::loadView('reports.tahfidz_report_pdf', $reportData);
        return $pdf->download('laporan-tahfidz-' . str_replace(' ', '-', strtolower($santri->user->nama ?? 'laporan')) . '.pdf');
    }

    // Untuk CETAK SEMUA (Preview HTML)
    public function downloadAllTahfidzReports()
    {
        $allSantri = Santri::with(['user', 'setoran' => function ($q) {
            $q->orderBy('created_at', 'desc');
        }])->get();

        $reports = $allSantri->map(function ($santri) {
            return [
                'santri' => $santri->user->nama ?? 'N/A',
                'kelas' => $santri->kelas ?? 'N/A',
                'hafalan' => $santri->setoran->map(function ($s) {
                    return ['surah' => $s->surah, 'juz' => $s->juz, 'status' => $s->nilai];
                })->toArray()
            ];
        })->toArray();

        $reportData = [
            'title' => 'Laporan Tahfidz Seluruh Santri',
            'date' => date('d F Y'),
            'allReports' => $reports
        ];

        // MENGEMBALIKAN VIEW HTML UNTUK PREVIEW
        return view('reports.all_tahfidz_preview', $reportData);
    }
}
