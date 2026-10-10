<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Santri;
use App\Models\Setoran;

class ReportController extends Controller
{
    // Untuk laporan per santri (download PDF jika library tersedia,
    // fallback ke preview HTML printable jika dompdf belum terinstall)
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

        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.tahfidz_report_pdf', $reportData);

            return $pdf->download('laporan-tahfidz-' . str_replace(' ', '-', strtolower($santri->user->nama ?? 'laporan')) . '.pdf');
        }

        // Fallback: tampilkan HTML yang sama (bisa Cetak / Simpan PDF via browser)
        return response()->view('reports.tahfidz_preview', $reportData);
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
