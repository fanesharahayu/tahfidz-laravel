<?php

namespace App\Http\Controllers;

use App\Helpers\UserAgentParser;
use App\Models\Santri;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
