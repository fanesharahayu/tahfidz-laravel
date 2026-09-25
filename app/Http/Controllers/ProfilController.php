<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $santri = null;
        $musyrifProfile = null;
        $jumlahBinaan = 0;

        if ($user->role === 'santri') {
            $santri = Santri::with(['user', 'musyrif'])
                ->where('user_id', $user->id)
                ->first();
        } elseif ($user->role === 'musyrif') {
            $user->load('musyrifProfile');
            $musyrifProfile = $user->musyrifProfile;
            $jumlahBinaan = Santri::where('musyrif_id', $user->id)->count();
        }

        return view('profil.show', [
            'user' => $user,
            'santri' => $santri,
            'musyrifProfile' => $musyrifProfile,
            'jumlahBinaan' => $jumlahBinaan,
        ]);
    }
}
