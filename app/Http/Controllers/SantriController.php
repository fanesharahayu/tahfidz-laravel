<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SantriController extends Controller
{
    public function dashboard(Request $request)
    {
        $santri = \App\Models\Santri::with(['user', 'musyrif'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $setoran = \App\Models\Setoran::with('musyrif')
            ->where('santri_id', $santri->id)
            ->latest()
            ->get();
        $targets = \App\Models\TargetHafalan::where('santri_id', $santri->id)->latest()->get();

        return view('santri.dashboard', [
            'santri' => $santri,
            'setoran' => $setoran,
            'targets' => $targets,
            'progress' => \App\Helpers\ProgressHelper::computeProgress($setoran, $santri->target_juz),
        ]);
    }
}
