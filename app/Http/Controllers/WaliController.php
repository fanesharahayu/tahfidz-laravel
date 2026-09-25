<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WaliController extends Controller
{
    public function dashboard(Request $request)
    {
        $children = \App\Models\WaliSantri::with(['santri.user', 'santri.musyrif'])
            ->where('wali_user_id', $request->user()->id)
            ->get()
            ->map(function ($link) {
                $santri = $link->santri;
                $setoran = \App\Models\Setoran::where('santri_id', $santri->id)->get();
                $santri->progress = \App\Helpers\ProgressHelper::computeProgress($setoran, $santri->target_juz);
                $santri->setoranCount = $setoran->count();
                $santri->targetAktif = \App\Models\TargetHafalan::where('santri_id', $santri->id)->latest()->first();
                $santri->relasi = $link->relasi;

                return $santri;
            });

        return view('wali.dashboard', ['children' => $children]);
    }

    public function childDetail(Request $request, int $santriId)
    {
        $link = \App\Models\WaliSantri::where('wali_user_id', $request->user()->id)
            ->where('santri_id', $santriId)
            ->firstOrFail();

        $santri = \App\Models\Santri::with(['user', 'musyrif'])->findOrFail($santriId);
        $setoran = \App\Models\Setoran::with('musyrif')->where('santri_id', $santriId)->latest()->get();
        $targets = \App\Models\TargetHafalan::where('santri_id', $santriId)->latest()->get();

        return view('wali.child-detail', [
            'relasi' => $link->relasi,
            'santri' => $santri,
            'setoran' => $setoran,
            'targets' => $targets,
            'progress' => \App\Helpers\ProgressHelper::computeProgress($setoran, $santri->target_juz),
        ]);
    }
}
