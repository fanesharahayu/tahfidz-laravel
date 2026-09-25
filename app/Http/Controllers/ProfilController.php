<?php

namespace App\Http\Controllers;

use App\Helpers\UserAgentParser;
use App\Models\Santri;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
            'sessions' => $this->activeSessions($request),
        ]);
    }

    /**
     * Akhiri semua sesi lain milik user (berlaku semua role).
     * Verifikasi password dulu, lalu hapus baris sesi lain + cabut remember-token.
     */
    public function destroyOthers(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        $user = $request->user();

        if (! Hash::check($request->input('password'), $user->password)) {
            return back()->withErrors(['password' => 'Password salah. Sesi lain tidak diakhiri.']);
        }

        // Cabut cookie "ingat saya" di perangkat lain.
        try {
            Auth::logoutOtherDevices($request->input('password'));
        } catch (\Throwable $e) {
            // lanjut: sesi database tetap dibersihkan di bawah
        }

        // Hapus sesi database milik perangkat lain (sesi ini dipertahankan).
        try {
            DB::table('sessions')
                ->where('user_id', $user->id)
                ->where('id', '!=', $request->session()->getId())
                ->delete();
        } catch (\Throwable $e) {
            // driver bukan database: tidak ada yang perlu dihapus
        }

        return back()->with('status', 'Semua sesi lain berhasil diakhiri.');
    }

    /**
     * Daftar sesi aktif user dari tabel sessions (SESSION_DRIVER=database).
     * Aman saat driver bukan database (mis. testing): kembalikan kosong.
     */
    private function activeSessions(Request $request): array
    {
        try {
            $rows = DB::table('sessions')
                ->where('user_id', $request->user()->id)
                ->orderByDesc('last_activity')
                ->get();
        } catch (\Throwable $e) {
            return [];
        }

        $now = now();
        $currentId = $request->session()->getId();

        return $rows->map(function ($s) use ($now, $currentId) {
            $parsed = UserAgentParser::parse($s->user_agent ?? '');
            $lastActive = Carbon::createFromTimestamp($s->last_activity);
            $loginAt = $this->loginAtFromPayload($s->payload ?? '');

            return [
                'os' => $parsed['os'],
                'browser' => $parsed['browser'],
                'device' => $parsed['device'],
                'ip' => $s->ip_address ?? '-',
                'login_at' => $loginAt,
                'last_active' => $lastActive,
                'is_current' => $s->id === $currentId,
                'duration' => $loginAt ? self::formatDuration($loginAt, $now) : '-',
            ];
        })->all();
    }

    private function loginAtFromPayload(string $payload): ?Carbon
    {
        try {
            $data = unserialize(base64_decode($payload));
            if (is_array($data) && isset($data['login_at'])) {
                return Carbon::parse($data['login_at']);
            }
        } catch (\Throwable $e) {
            // abaikan, anggap tidak ada info login
        }

        return null;
    }

    private static function formatDuration(Carbon $from, Carbon $to): string
    {
        $diff = $from->diff($to);
        $parts = [];
        if ($diff->d > 0) $parts[] = $diff->d . ' hari';
        if ($diff->h > 0) $parts[] = $diff->h . ' jam';
        if ($diff->i > 0 || empty($parts)) $parts[] = $diff->i . ' menit';

        return implode(' ', array_slice($parts, 0, 2));
    }
}
