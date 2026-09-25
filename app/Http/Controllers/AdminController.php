<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $santri = \App\Models\Santri::with(['user', 'musyrif'])->orderBy('kelas')->get();
        $setoran = \App\Models\Setoran::with(['santri.user', 'musyrif'])->latest()->get();
        $targets = \App\Models\TargetHafalan::with('santri.user')->latest()->get();
        $users = \App\Models\User::orderBy('role')->orderBy('nama')->get(['id', 'nama', 'username', 'email', 'role']);

        return view('admin.dashboard', [
            'jumlahSantri' => $santri->count(),
            'jumlahSetoran' => $setoran->count(),
            'jumlahMusyrif' => $users->where('role', 'musyrif')->count(),
            'jumlahWali' => $users->where('role', 'wali')->count(),
            'santri' => $santri,
            'setoran' => $setoran->take(20),
            'targets' => $targets->take(20),
            'users' => $users,
        ]);
    }
}
