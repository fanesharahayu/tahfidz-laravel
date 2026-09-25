<?php

namespace App\Http\Controllers;

use App\Models\Musyrif;
use App\Models\Santri;
use App\Models\Setoran;
use App\Models\TargetHafalan;
use App\Models\User;
use App\Models\WaliSantri;
use Illuminate\Database\QueryException;
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

    public function usersIndex()
    {
        $users = User::orderBy('role')->orderBy('nama')->get(['id', 'nama', 'username', 'email', 'role']);

        return view('admin.users', ['users' => $users]);
    }

    public function usersStore(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,musyrif,santri,wali',
        ], [
            'nama.required' => 'Semua field wajib diisi',
            'username.required' => 'Semua field wajib diisi',
            'email.required' => 'Semua field wajib diisi',
            'password.required' => 'Semua field wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'username.unique' => 'Username atau email sudah ada',
            'email.unique' => 'Username atau email sudah ada',
            'role.required' => 'Role tidak valid',
            'role.in' => 'Role tidak valid',
        ]);

        $user = User::create([
            'name' => $validated['nama'],
            'nama' => $validated['nama'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role'],
        ]);

        if ($validated['role'] === 'musyrif') {
            Musyrif::create(['user_id' => $user->id]);
        } elseif ($validated['role'] === 'santri') {
            Santri::create(['user_id' => $user->id, 'target_juz' => 30]);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'User berhasil dibuat', 'id' => $user->id], 201);
        }

        return redirect()->back()->with('status', 'User berhasil dibuat');
    }

    public function usersDestroy(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'User berhasil dihapus']);
        }

        return redirect()->back()->with('status', 'User berhasil dihapus');
    }

    public function santriStore(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'nis' => 'nullable|string|max:20',
            'kelas' => 'nullable|string|max:50',
            'target_juz' => 'nullable|integer|min:1|max:30',
            'musyrif_id' => 'nullable|exists:users,id',
            'tanggal_bergabung' => 'nullable|date',
        ], [
            'nama.required' => 'Field nama, username, email, password wajib diisi',
            'username.required' => 'Field nama, username, email, password wajib diisi',
            'email.required' => 'Field nama, username, email, password wajib diisi',
            'password.required' => 'Field nama, username, email, password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'username.unique' => 'Username atau email sudah ada',
            'email.unique' => 'Username atau email sudah ada',
        ]);

        $user = User::create([
            'name' => $validated['nama'],
            'nama' => $validated['nama'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'santri',
        ]);

        Santri::create([
            'user_id' => $user->id,
            'nis' => $validated['nis'] ?? null,
            'kelas' => $validated['kelas'] ?? null,
            'target_juz' => $validated['target_juz'] ?? 30,
            'musyrif_id' => $validated['musyrif_id'] ?? null,
            'tanggal_bergabung' => $validated['tanggal_bergabung'] ?? null,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Santri berhasil ditambahkan', 'id' => $user->id], 201);
        }

        return redirect()->back()->with('status', 'Santri berhasil ditambahkan');
    }

    public function santriUpdate(Request $request, $id)
    {
        $santri = Santri::findOrFail($id);

        $validated = $request->validate([
            'nis' => 'nullable|string|max:20',
            'kelas' => 'nullable|string|max:50',
            'target_juz' => 'nullable|integer|min:1|max:30',
            'musyrif_id' => 'nullable|exists:users,id',
            'tanggal_bergabung' => 'nullable|date',
            'nama' => 'nullable|string|max:100',
        ]);

        $santri->update([
            'nis' => $validated['nis'] ?? null,
            'kelas' => $validated['kelas'] ?? null,
            'target_juz' => $validated['target_juz'] ?? 30,
            'musyrif_id' => $validated['musyrif_id'] ?? null,
            'tanggal_bergabung' => $validated['tanggal_bergabung'] ?? null,
        ]);

        if (! empty($validated['nama'])) {
            $santri->user()->update(['nama' => $validated['nama'], 'name' => $validated['nama']]);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Santri berhasil diperbarui']);
        }

        return redirect()->back()->with('status', 'Santri berhasil diperbarui');
    }

    public function targetIndex()
    {
        $targets = TargetHafalan::with('santri.user')->latest()->get();
        $santriList = Santri::with('user')->get();

        return view('admin.targets', [
            'targets' => $targets,
            'santriList' => $santriList,
        ]);
    }

    public function targetStore(Request $request)
    {
        $validated = $request->validate([
            'santri_id' => 'required|exists:santri,id',
            'target_juz' => 'required|integer|min:1|max:30',
            'periode' => 'nullable|string|max:50',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
        ], [
            'santri_id.required' => 'Santri dan target juz wajib diisi',
            'target_juz.required' => 'Santri dan target juz wajib diisi',
        ]);

        $target = TargetHafalan::create([
            'santri_id' => $validated['santri_id'],
            'target_juz' => $validated['target_juz'],
            'periode' => $validated['periode'] ?? null,
            'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
            'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
        ]);

        Santri::where('id', $validated['santri_id'])->update(['target_juz' => $validated['target_juz']]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Target hafalan disimpan', 'id' => $target->id], 201);
        }

        return redirect()->back()->with('status', 'Target hafalan disimpan');
    }

    public function targetDestroy(Request $request, $id)
    {
        $target = TargetHafalan::findOrFail($id);
        $target->delete();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Target dihapus']);
        }

        return redirect()->back()->with('status', 'Target dihapus');
    }

    public function waliLinksIndex()
    {
        $links = WaliSantri::with(['wali', 'santri.user'])->orderBy('id')->get();
        $waliList = User::where('role', 'wali')->orderBy('nama')->get(['id', 'nama', 'username']);
        $santriList = Santri::with('user')->get();

        return view('admin.wali-links', [
            'links' => $links,
            'waliList' => $waliList,
            'santriList' => $santriList,
        ]);
    }

    public function waliLinksStore(Request $request)
    {
        $validated = $request->validate([
            'wali_user_id' => 'required|exists:users,id',
            'santri_id' => 'required|exists:santri,id',
            'relasi' => 'nullable|string|max:50',
        ], [
            'wali_user_id.required' => 'Wali dan santri wajib dipilih',
            'santri_id.required' => 'Wali dan santri wajib dipilih',
        ]);

        try {
            $link = WaliSantri::create([
                'wali_user_id' => $validated['wali_user_id'],
                'santri_id' => $validated['santri_id'],
                'relasi' => $validated['relasi'] ?? 'Wali Santri',
            ]);
        } catch (QueryException $e) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Hubungan sudah ada atau data tidak valid'], 400);
            }

            return redirect()->back()->withErrors(['wali_user_id' => 'Hubungan sudah ada atau data tidak valid'])->withInput();
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Hubungan wali-santri dibuat', 'id' => $link->id], 201);
        }

        return redirect()->back()->with('status', 'Hubungan wali-santri dibuat');
    }

    public function waliLinksDestroy(Request $request, $id)
    {
        $link = WaliSantri::findOrFail($id);
        $link->delete();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Hubungan dihapus']);
        }

        return redirect()->back()->with('status', 'Hubungan dihapus');
    }

    public function setoranStore(Request $request)
    {
        $validated = $request->validate([
            'santri_id' => 'required|exists:santri,id',
            'musyrif_id' => 'required|exists:users,id',
            'juz' => 'required|integer|min:1|max:30',
            'surah' => 'required|string|max:50',
            'ayat_awal' => 'nullable|integer|min:0',
            'ayat_akhir' => 'nullable|integer|min:0',
            'jenis' => 'nullable|in:hafalan_baru,tambahan,murajaah',
            'nilai' => 'nullable|in:lancar,cukup_lancar,perlu_ulang',
            'catatan' => 'nullable|string',
        ], [
            'santri_id.required' => 'Santri, musyrif, juz, dan surah wajib diisi',
            'musyrif_id.required' => 'Santri, musyrif, juz, dan surah wajib diisi',
            'juz.required' => 'Santri, musyrif, juz, dan surah wajib diisi',
            'surah.required' => 'Santri, musyrif, juz, dan surah wajib diisi',
            'santri_id.exists' => 'Santri tidak ditemukan',
            'musyrif_id.exists' => 'Musyrif tidak ditemukan',
        ]);

        $musyrif = User::where('id', $validated['musyrif_id'])->where('role', 'musyrif')->first();
        if (! $musyrif) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Musyrif tidak ditemukan'], 404);
            }

            return redirect()->back()->withErrors(['musyrif_id' => 'Musyrif tidak ditemukan'])->withInput();
        }

        $setoran = Setoran::create([
            'santri_id' => $validated['santri_id'],
            'musyrif_id' => $validated['musyrif_id'],
            'juz' => $validated['juz'],
            'surah' => $validated['surah'],
            'ayat_awal' => $validated['ayat_awal'] ?? 0,
            'ayat_akhir' => $validated['ayat_akhir'] ?? 0,
            'jenis' => $validated['jenis'] ?? 'hafalan_baru',
            'nilai' => $validated['nilai'] ?? 'lancar',
            'catatan' => $validated['catatan'] ?? null,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Setoran dicatat', 'id' => $setoran->id], 201);
        }

        return redirect()->back()->with('status', 'Setoran dicatat');
    }

    public function setoranUpdate(Request $request, $id)
    {
        $setoran = Setoran::find($id);

        if (! $setoran) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Setoran tidak ditemukan'], 404);
            }

            abort(404, 'Setoran tidak ditemukan');
        }

        $validated = $request->validate([
            'musyrif_id' => 'nullable|exists:users,id',
            'juz' => 'nullable|integer|min:1|max:30',
            'surah' => 'nullable|string|max:50',
            'ayat_awal' => 'nullable|integer|min:0',
            'ayat_akhir' => 'nullable|integer|min:0',
            'jenis' => 'nullable|in:hafalan_baru,tambahan,murajaah',
            'nilai' => 'nullable|in:lancar,cukup_lancar,perlu_ulang',
            'catatan' => 'nullable|string',
        ]);

        $setoran->update(array_filter([
            'musyrif_id' => $validated['musyrif_id'] ?? $setoran->musyrif_id,
            'juz' => $validated['juz'] ?? $setoran->juz,
            'surah' => $validated['surah'] ?? $setoran->surah,
            'ayat_awal' => $validated['ayat_awal'] ?? $setoran->ayat_awal,
            'ayat_akhir' => $validated['ayat_akhir'] ?? $setoran->ayat_akhir,
            'jenis' => $validated['jenis'] ?? $setoran->jenis,
            'nilai' => $validated['nilai'] ?? $setoran->nilai,
            'catatan' => array_key_exists('catatan', $validated) ? $validated['catatan'] : $setoran->catatan,
        ], fn ($v) => $v !== null));

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Setoran diperbarui']);
        }

        return redirect()->back()->with('status', 'Setoran diperbarui');
    }

    public function setoranDestroy(Request $request, $id)
    {
        $setoran = Setoran::findOrFail($id);
        $setoran->delete();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Setoran dihapus']);
        }

        return redirect()->back()->with('status', 'Setoran dihapus');
    }
}
