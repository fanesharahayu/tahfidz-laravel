<?php

namespace App\Http\Controllers;

use App\Helpers\ProgressHelper;
use App\Models\Santri;
use App\Models\Setoran;
use App\Models\TargetHafalan;
use App\Models\User;
use App\Models\WaliSantri;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    /**
     * Ambil santri binaan musyrif yang login, 403 jika bukan binaan.
     */
    protected function binaanOrFail(Request $request, int $santriId): Santri
    {
        $santri = Santri::where('id', $santriId)
            ->where('musyrif_id', $request->user()->id)
            ->first();

        if (! $santri) {
            abort(403, 'Santri bukan binaan Anda');
        }

        return $santri;
    }

    /**
     * Daftar santri binaan + santri belum bermusyrif (untuk diambil).
     */
    public function binaanIndex(Request $request)
    {
        $musyrifId = $request->user()->id;

        $binaan = Santri::with('user')
            ->where('musyrif_id', $musyrifId)
            ->orderBy('kelas')
            ->get();

        foreach ($binaan as $s) {
            $setoran = Setoran::where('santri_id', $s->id)->get();
            $s->progress = ProgressHelper::computeProgress($setoran, $s->target_juz);
            $s->setoranCount = $setoran->count();
        }

        $unassigned = Santri::with('user')
            ->whereNull('musyrif_id')
            ->orderBy('kelas')
            ->get();

        return view('musyrif.binaan', [
            'binaan' => $binaan,
            'unassigned' => $unassigned,
        ]);
    }

    /**
     * Tambah santri baru, langsung menjadi binaan musyrif ini.
     */
    public function santriStore(Request $request)
    {
        $request->merge([
            'nis' => $request->filled('nis') ? $request->input('nis') : null,
        ]);

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'nis' => 'nullable|string|max:20|unique:santri,nis',
            'kelas' => 'nullable|string|max:50',
            'target_juz' => 'nullable|integer|min:1|max:30',
        ], [
            'nama.required' => 'Nama wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah terdaftar',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        $user = User::create([
            'name' => $validated['nama'],
            'nama' => $validated['nama'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'santri',
        ]);

        Santri::create([
            'user_id' => $user->id,
            'musyrif_id' => $request->user()->id,
            'nis' => $validated['nis'] ?? null,
            'kelas' => $validated['kelas'] ?? null,
            'target_juz' => $validated['target_juz'] ?? 30,
        ]);

        return redirect()->route('musyrif.binaan.index')
            ->with('status', 'Santri berhasil ditambahkan ke binaan Anda');
    }

    /**
     * Daftar santri terdaftar yang belum memiliki musyrif.
     */
    public function unassigned(Request $request)
    {
        $rows = Santri::with('user')
            ->whereNull('musyrif_id')
            ->orderBy('kelas')
            ->get();

        return response()->json($rows);
    }

    /**
     * Ambil santri tanpa musyrif sebagai binaan musyrif ini.
     */
    public function assign(Request $request, int $santriId)
    {
        $santri = Santri::where('id', $santriId)->whereNull('musyrif_id')->first();

        if (! $santri) {
            abort(404, 'Santri tidak ditemukan atau sudah memiliki musyrif');
        }

        $santri->update(['musyrif_id' => $request->user()->id]);

        return redirect()->route('musyrif.binaan.index')
            ->with('status', 'Santri berhasil diangkat menjadi binaan Anda');
    }

    /**
     * Riwayat setoran yang dicatat musyrif ini + form catat.
     */
    public function setoranIndex(Request $request)
    {
        $musyrifId = $request->user()->id;

        $setoran = Setoran::with('santri.user')
            ->where('musyrif_id', $musyrifId)
            ->latest()
            ->take(100)
            ->get();

        $binaan = Santri::with('user')
            ->where('musyrif_id', $musyrifId)
            ->orderBy('kelas')
            ->get();

        return view('musyrif.setoran', [
            'setoran' => $setoran,
            'binaan' => $binaan,
        ]);
    }

    /**
     * Catat setoran hafalan untuk santri binaan.
     */
    public function setoranStore(Request $request, int $santriId)
    {
        $this->binaanOrFail($request, $santriId);

        $validated = $request->validate([
            'juz' => 'required|integer|min:1|max:30',
            'surah' => 'required|string|max:50',
            'ayat_awal' => 'nullable|integer|min:0',
            'ayat_akhir' => 'nullable|integer|min:0',
            'jenis' => 'nullable|in:hafalan_baru,tambahan,murajaah',
            'nilai' => 'nullable|in:lancar,cukup_lancar,perlu_ulang',
            'catatan' => 'nullable|string',
        ], [
            'juz.required' => 'Juz wajib diisi',
            'surah.required' => 'Surah wajib diisi',
        ]);

        Setoran::create([
            'santri_id' => $santriId,
            'musyrif_id' => $request->user()->id,
            'juz' => $validated['juz'],
            'surah' => $validated['surah'],
            'ayat_awal' => $validated['ayat_awal'] ?? 0,
            'ayat_akhir' => $validated['ayat_akhir'] ?? 0,
            'jenis' => $validated['jenis'] ?? 'hafalan_baru',
            'nilai' => $validated['nilai'] ?? 'lancar',
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('musyrif.setoran.index')
            ->with('status', 'Setoran berhasil dicatat');
    }

    /**
     * Ubah setoran milik musyrif ini.
     */
    public function setoranUpdate(Request $request, int $id)
    {
        $setoran = Setoran::where('id', $id)
            ->where('musyrif_id', $request->user()->id)
            ->first();

        if (! $setoran) {
            abort(404, 'Setoran tidak ditemukan');
        }

        $validated = $request->validate([
            'juz' => 'sometimes|required|integer|min:1|max:30',
            'surah' => 'sometimes|required|string|max:50',
            'ayat_awal' => 'sometimes|nullable|integer|min:0',
            'ayat_akhir' => 'sometimes|nullable|integer|min:0',
            'jenis' => 'sometimes|nullable|in:hafalan_baru,tambahan,murajaah',
            'nilai' => 'sometimes|nullable|in:lancar,cukup_lancar,perlu_ulang',
            'catatan' => 'sometimes|nullable|string',
        ], [
            'juz.required' => 'Juz wajib diisi',
            'surah.required' => 'Surah wajib diisi',
        ]);

        $setoran->update($validated);

        return redirect()->route('musyrif.setoran.index')
            ->with('status', 'Setoran diperbarui');
    }

    /**
     * Hapus setoran milik musyrif ini.
     */
    public function setoranDestroy(Request $request, int $id)
    {
        $setoran = Setoran::where('id', $id)
            ->where('musyrif_id', $request->user()->id)
            ->first();

        if (! $setoran) {
            abort(404, 'Setoran tidak ditemukan');
        }

        $setoran->delete();

        return redirect()->route('musyrif.setoran.index')
            ->with('status', 'Setoran dihapus');
    }

    /**
     * Daftar target hafalan santri binaan.
     */
    public function targetIndex(Request $request)
    {
        $musyrifId = $request->user()->id;

        $targets = TargetHafalan::with('santri.user')
            ->whereHas('santri', fn ($q) => $q->where('musyrif_id', $musyrifId))
            ->latest()
            ->get();

        $binaan = Santri::with('user')
            ->where('musyrif_id', $musyrifId)
            ->orderBy('kelas')
            ->get();

        return view('musyrif.targets', [
            'targets' => $targets,
            'binaan' => $binaan,
        ]);
    }

    /**
     * Buat target hafalan untuk santri binaan.
     */
    public function targetStore(Request $request)
    {
        $validated = $request->validate([
            'santri_id' => 'required|exists:santri,id',
            'target_juz' => 'required|integer|min:1|max:30',
            'periode' => 'nullable|string|max:50',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
        ], [
            'santri_id.required' => 'Santri wajib diisi',
            'target_juz.required' => 'Target juz wajib diisi',
        ]);

        $santri = $this->binaanOrFail($request, (int) $validated['santri_id']);

        TargetHafalan::create([
            'santri_id' => $santri->id,
            'target_juz' => $validated['target_juz'],
            'periode' => $validated['periode'] ?? null,
            'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
            'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
        ]);

        $santri->update(['target_juz' => $validated['target_juz']]);

        return redirect()->route('musyrif.targets.index')
            ->with('status', 'Target hafalan disimpan');
    }

    /**
     * Hapus target milik santri binaan.
     */
    public function targetDestroy(Request $request, int $id)
    {
        $musyrifId = $request->user()->id;

        $target = TargetHafalan::where('id', $id)
            ->whereHas('santri', fn ($q) => $q->where('musyrif_id', $musyrifId))
            ->first();

        if (! $target) {
            abort(404, 'Target tidak ditemukan');
        }

        $target->delete();

        return redirect()->route('musyrif.targets.index')
            ->with('status', 'Target dihapus');
    }

    /**
     * Relasi wali-santri untuk santri binaan + daftar akun wali.
     */
    public function waliLinksIndex(Request $request)
    {
        $musyrifId = $request->user()->id;

        $links = WaliSantri::with(['wali', 'santri.user'])
            ->whereHas('santri', fn ($q) => $q->where('musyrif_id', $musyrifId))
            ->latest()
            ->get()
            ->sortBy(fn ($l) => $l->santri->user->nama ?? '')
            ->values();

        $binaan = Santri::with('user')
            ->where('musyrif_id', $musyrifId)
            ->orderBy('kelas')
            ->get();

        $walis = User::where('role', 'wali')
            ->orderBy('nama')
            ->get(['id', 'nama', 'username', 'email']);

        return view('musyrif.wali', [
            'links' => $links,
            'binaan' => $binaan,
            'walis' => $walis,
        ]);
    }

    /**
     * Buat hubungan wali-santri untuk santri binaan.
     */
    public function waliLinkStore(Request $request)
    {
        $validated = $request->validate([
            'wali_user_id' => 'required|exists:users,id',
            'santri_id' => 'required|exists:santri,id',
            'relasi' => 'nullable|string|max:50',
        ], [
            'wali_user_id.required' => 'Wali wajib dipilih',
            'santri_id.required' => 'Santri wajib dipilih',
        ]);

        $this->binaanOrFail($request, (int) $validated['santri_id']);

        try {
            WaliSantri::create([
                'wali_user_id' => $validated['wali_user_id'],
                'santri_id' => $validated['santri_id'],
                'relasi' => $validated['relasi'] ?? 'Wali Santri',
            ]);
        } catch (QueryException $e) {
            return back()
                ->withErrors(['wali_user_id' => 'Hubungan sudah ada atau data tidak valid'])
                ->withInput();
        }

        return redirect()->route('musyrif.wali.index')
            ->with('status', 'Hubungan wali-santri dibuat');
    }

    /**
     * Hapus hubungan wali-santri milik santri binaan.
     */
    public function waliLinkDestroy(Request $request, int $id)
    {
        $musyrifId = $request->user()->id;

        $link = WaliSantri::where('id', $id)
            ->whereHas('santri', fn ($q) => $q->where('musyrif_id', $musyrifId))
            ->first();

        if (! $link) {
            abort(404, 'Hubungan tidak ditemukan');
        }

        $link->delete();

        return redirect()->route('musyrif.wali.index')
            ->with('status', 'Hubungan dihapus');
    }
}
