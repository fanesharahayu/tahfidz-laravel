<?php

namespace Tests\Feature;

use App\Http\Controllers\MusyrifController;
use App\Models\Santri;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class MusyrifCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Daftarkan route Musyrif CRUD di dalam test saja (tanpa menyentuh routes/web.php).
        // URI + name sama persis dengan daftar route yang harus ditambahkan ke routes/web.php.
        // 1) Keluarkan dulu GET /musyrif/santri/{santriId} bawaan web.php dari koleksi,
        //    supaya GET /musyrif/santri/unassigned tidak tertutup (di web.php nanti
        //    baris unassigned WAJIB ditaruh SEBELUM baris santri.detail).
        $router = app('router');
        $fresh = new \Illuminate\Routing\RouteCollection;
        $deferredDetail = [];
        foreach (Route::getRoutes()->getRoutes() as $route) {
            if ($route->getName() === 'musyrif.santri.detail' && in_array('GET', $route->methods(), true)) {
                $deferredDetail[] = $route;

                continue;
            }
            $fresh->add($route);
        }
        $router->setRoutes($fresh);

        Route::middleware(['web', 'auth', 'role:musyrif'])->prefix('musyrif')->name('musyrif.')->group(function () {
            Route::get('/binaan', [MusyrifController::class, 'binaanIndex'])->name('binaan.index');
            Route::get('/setoran', [MusyrifController::class, 'setoranIndex'])->name('setoran.index');
            Route::post('/santri/{santriId}/setoran', [MusyrifController::class, 'setoranStore'])->name('setoran.store');
            Route::put('/setoran/{id}', [MusyrifController::class, 'setoranUpdate'])->name('setoran.update');
            Route::delete('/setoran/{id}', [MusyrifController::class, 'setoranDestroy'])->name('setoran.destroy');
            Route::get('/targets', [MusyrifController::class, 'targetIndex'])->name('targets.index');
            Route::post('/target', [MusyrifController::class, 'targetStore'])->name('target.store');
            Route::delete('/target/{id}', [MusyrifController::class, 'targetDestroy'])->name('target.destroy');
            Route::post('/santri', [MusyrifController::class, 'santriStore'])->name('santri.store');
            Route::get('/santri/unassigned', [MusyrifController::class, 'unassigned'])->name('santri.unassigned');
            Route::post('/santri/{santriId}/assign', [MusyrifController::class, 'assign'])->name('santri.assign');
            Route::get('/wali', [MusyrifController::class, 'waliLinksIndex'])->name('wali.index');
            Route::post('/wali-link', [MusyrifController::class, 'waliLinkStore'])->name('wali-link.store');
            Route::delete('/wali-link/{id}', [MusyrifController::class, 'waliLinkDestroy'])->name('wali-link.destroy');
        });

        // 2) Daftarkan ulang santri.detail SETELAH unassigned (cermin urutan produksi).
        foreach ($deferredDetail as $route) {
            Route::getRoutes()->add($route);
        }

        // 3) Route yang didaftar setelah boot tidak ikut name lookup otomatis.
        Route::getRoutes()->refreshNameLookups();
    }

    private function musyrif(): User
    {
        return User::factory()->create(['role' => 'musyrif']);
    }

    private function santriBinaan(User $musyrif, array $overrides = []): Santri
    {
        $user = User::factory()->create(['role' => 'santri']);

        return Santri::create(array_merge([
            'user_id' => $user->id,
            'musyrif_id' => $musyrif->id,
            'kelas' => 'X',
            'target_juz' => 30,
        ], $overrides));
    }

    public function test_musyrif_bisa_catat_setoran_untuk_binaan(): void
    {
        $musyrif = $this->musyrif();
        $santri = $this->santriBinaan($musyrif);

        $response = $this->actingAs($musyrif)->post("/musyrif/santri/{$santri->id}/setoran", [
            'juz' => 1,
            'surah' => 'Al-Fatihah',
            'ayat_awal' => 1,
            'ayat_akhir' => 7,
            'jenis' => 'hafalan_baru',
            'nilai' => 'lancar',
            'catatan' => 'Bagus',
        ]);

        $response->assertRedirect('/musyrif/setoran');
        $this->assertDatabaseHas('setoran', [
            'santri_id' => $santri->id,
            'musyrif_id' => $musyrif->id,
            'juz' => 1,
            'surah' => 'Al-Fatihah',
        ]);
    }

    public function test_musyrif_ditolak_catat_setoran_santri_orang_lain(): void
    {
        $musyrif = $this->musyrif();
        $musyrifLain = $this->musyrif();
        $santriOrang = $this->santriBinaan($musyrifLain);

        $response = $this->actingAs($musyrif)->post("/musyrif/santri/{$santriOrang->id}/setoran", [
            'juz' => 2,
            'surah' => 'Al-Baqarah',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('setoran', [
            'santri_id' => $santriOrang->id,
            'musyrif_id' => $musyrif->id,
        ]);
    }

    public function test_setoran_wajib_juz_dan_surah(): void
    {
        $musyrif = $this->musyrif();
        $santri = $this->santriBinaan($musyrif);

        $response = $this->actingAs($musyrif)
            ->from('/musyrif/setoran')
            ->post("/musyrif/santri/{$santri->id}/setoran", []);

        $response->assertRedirect('/musyrif/setoran');
        $response->assertSessionHasErrors(['juz', 'surah']);
    }

    public function test_musyrif_bisa_buat_target_untuk_binaan(): void
    {
        $musyrif = $this->musyrif();
        $santri = $this->santriBinaan($musyrif);

        $response = $this->actingAs($musyrif)->post('/musyrif/target', [
            'santri_id' => $santri->id,
            'target_juz' => 5,
            'periode' => 'Semester 1',
        ]);

        $response->assertRedirect('/musyrif/targets');
        $this->assertDatabaseHas('target_hafalan', [
            'santri_id' => $santri->id,
            'target_juz' => 5,
        ]);
        $this->assertEquals(5, $santri->fresh()->target_juz);
    }

    public function test_musyrif_ditolak_buat_target_santri_orang_lain(): void
    {
        $musyrif = $this->musyrif();
        $santriOrang = $this->santriBinaan($this->musyrif());

        $response = $this->actingAs($musyrif)->post('/musyrif/target', [
            'santri_id' => $santriOrang->id,
            'target_juz' => 5,
        ]);

        $response->assertForbidden();
    }

    public function test_musyrif_bisa_assign_santri_unassigned(): void
    {
        $musyrif = $this->musyrif();
        $user = User::factory()->create(['role' => 'santri']);
        $santri = Santri::create([
            'user_id' => $user->id,
            'musyrif_id' => null,
            'kelas' => 'XI',
            'target_juz' => 30,
        ]);

        $this->actingAs($musyrif)->get('/musyrif/santri/unassigned')
            ->assertOk()
            ->assertSee($user->nama);

        $response = $this->actingAs($musyrif)->post("/musyrif/santri/{$santri->id}/assign");

        $response->assertRedirect('/musyrif/binaan');
        $this->assertEquals($musyrif->id, $santri->fresh()->musyrif_id);
    }

    public function test_halaman_crud_musyrif_bisa_dirender(): void
    {
        $musyrif = $this->musyrif();
        $this->santriBinaan($musyrif);

        $this->actingAs($musyrif)->get('/musyrif/binaan')->assertOk()->assertSee('Santri Binaan');
        $this->actingAs($musyrif)->get('/musyrif/setoran')->assertOk()->assertSee('Catat Setoran');
        $this->actingAs($musyrif)->get('/musyrif/targets')->assertOk()->assertSee('Buat Target');
        $this->actingAs($musyrif)->get('/musyrif/wali')->assertOk()->assertSee('Hubungkan Wali');
    }
}
