<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MusyrifController extends Controller
{
    public function dashboard(Request $request)
    {
        $musyrifId = $request->user()->id;

        $binaan = \App\Models\Santri::with('user')
            ->where('musyrif_id', $musyrifId)
            ->orderBy('kelas')
            ->get();

        foreach ($binaan as $s) {
            $setoran = \App\Models\Setoran::where('santri_id', $s->id)->get();
            $s->progress = \App\Helpers\ProgressHelper::computeProgress($setoran, $s->target_juz);
            $s->setoranCount = $setoran->count();
        }

        $setoranRiwayat = \App\Models\Setoran::with('santri.user')
            ->where('musyrif_id', $musyrifId)
            ->latest()
            ->take(20)
            ->get();

        return view('musyrif.dashboard', [
            'binaan' => $binaan,
            'setoran' => $setoranRiwayat,
        ]);
    }

    public function santriDetail(Request $request, int $santriId)
    {
        $santri = \App\Models\Santri::with(['user', 'musyrif'])
            ->where('id', $santriId)
            ->where('musyrif_id', $request->user()->id)
            ->firstOrFail();

        $setoran = \App\Models\Setoran::where('santri_id', $santriId)->latest()->get();
        $targets = \App\Models\TargetHafalan::where('santri_id', $santriId)->latest()->get();

        return view('musyrif.santri-detail', [
            'santri' => $santri,
            'setoran' => $setoran,
            'targets' => $targets,
            'progress' => \App\Helpers\ProgressHelper::computeProgress($setoran, $santri->target_juz),
        ]);
    }
}
