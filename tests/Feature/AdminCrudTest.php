<?php

namespace Tests\Feature;

use App\Http\Controllers\AdminController;
use App\Models\Santri;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->registerAdminTestRoutes();
    }

    protected function registerAdminTestRoutes(): void
    {
        Route::middleware(['auth', 'role:admin'])->group(function () {
            Route::get('/admin/users', [AdminController::class, 'usersIndex']);
            Route::post('/admin/users', [AdminController::class, 'usersStore']);
            Route::delete('/admin/users/{id}', [AdminController::class, 'usersDestroy']);
            Route::post('/admin/santri', [AdminController::class, 'santriStore']);
            Route::match(['put', 'patch'], '/admin/santri/{id}', [AdminController::class, 'santriUpdate']);
            Route::post('/admin/target', [AdminController::class, 'targetStore']);
            Route::delete('/admin/target/{id}', [AdminController::class, 'targetDestroy']);
            Route::get('/admin/wali-links', [AdminController::class, 'waliLinksIndex']);
            Route::post('/admin/wali-link', [AdminController::class, 'waliLinksStore']);
            Route::delete('/admin/wali-link/{id}', [AdminController::class, 'waliLinksDestroy']);
            Route::post('/admin/setoran', [AdminController::class, 'setoranStore']);
            Route::match(['put', 'patch'], '/admin/setoran/{id}', [AdminController::class, 'setoranUpdate']);
            Route::delete('/admin/setoran/{id}', [AdminController::class, 'setoranDestroy']);
        });
    }

    protected function makeAdmin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_create_musyrif_user(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->post('/admin/users', [
            'nama' => 'Musyrif Baru',
            'username' => 'musyrifbaru',
            'email' => 'musyrifbaru@example.com',
            'password' => 'secret123',
            'role' => 'musyrif',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'User berhasil dibuat');
        $this->assertDatabaseHas('users', ['username' => 'musyrifbaru', 'role' => 'musyrif']);

        $user = User::where('username', 'musyrifbaru')->first();
        $this->assertNotNull($user);
        $this->assertDatabaseHas('musyrif', ['user_id' => $user->id]);
    }

    public function test_admin_can_create_santri(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->post('/admin/santri', [
            'nama' => 'Santri Baru',
            'username' => 'santribaru',
            'email' => 'santribaru@example.com',
            'password' => 'secret123',
            'kelas' => 'X-A',
            'target_juz' => 5,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Santri berhasil ditambahkan');
        $this->assertDatabaseHas('users', ['username' => 'santribaru', 'role' => 'santri']);

        $user = User::where('username', 'santribaru')->first();
        $this->assertDatabaseHas('santri', ['user_id' => $user->id, 'kelas' => 'X-A']);
    }

    public function test_admin_can_create_target(): void
    {
        $admin = $this->makeAdmin();
        $santriUser = User::factory()->create(['role' => 'santri']);
        $santri = Santri::create(['user_id' => $santriUser->id, 'target_juz' => 30]);

        $response = $this->actingAs($admin)->post('/admin/target', [
            'santri_id' => $santri->id,
            'target_juz' => 5,
            'periode' => 'Semester 1',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Target hafalan disimpan');
        $this->assertDatabaseHas('target_hafalan', ['santri_id' => $santri->id, 'target_juz' => 5]);
        $this->assertDatabaseHas('santri', ['id' => $santri->id, 'target_juz' => 5]);
    }

    public function test_admin_can_create_setoran(): void
    {
        $admin = $this->makeAdmin();
        $musyrif = User::factory()->create(['role' => 'musyrif']);
        $santriUser = User::factory()->create(['role' => 'santri']);
        $santri = Santri::create(['user_id' => $santriUser->id, 'target_juz' => 30]);

        $response = $this->actingAs($admin)->post('/admin/setoran', [
            'santri_id' => $santri->id,
            'musyrif_id' => $musyrif->id,
            'juz' => 1,
            'surah' => 'Al-Fatihah',
            'ayat_awal' => 1,
            'ayat_akhir' => 7,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Setoran dicatat');
        $this->assertDatabaseHas('setoran', [
            'santri_id' => $santri->id,
            'musyrif_id' => $musyrif->id,
            'juz' => 1,
            'surah' => 'Al-Fatihah',
        ]);
    }

    public function test_admin_can_create_wali_link(): void
    {
        $admin = $this->makeAdmin();
        $wali = User::factory()->create(['role' => 'wali']);
        $santriUser = User::factory()->create(['role' => 'santri']);
        $santri = Santri::create(['user_id' => $santriUser->id, 'target_juz' => 30]);

        $response = $this->actingAs($admin)->post('/admin/wali-link', [
            'wali_user_id' => $wali->id,
            'santri_id' => $santri->id,
            'relasi' => 'Ayah',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Hubungan wali-santri dibuat');
        $this->assertDatabaseHas('wali_santri', [
            'wali_user_id' => $wali->id,
            'santri_id' => $santri->id,
        ]);
    }

    public function test_non_admin_forbidden_to_post_admin(): void
    {
        $santriUser = User::factory()->create(['role' => 'santri']);
        Santri::create(['user_id' => $santriUser->id, 'target_juz' => 30]);

        $this->actingAs($santriUser)->post('/admin/users', [
            'nama' => 'X',
            'username' => 'xuser',
            'email' => 'x@example.com',
            'password' => 'secret123',
            'role' => 'musyrif',
        ])->assertForbidden();

        $this->actingAs($santriUser)->post('/admin/santri', [
            'nama' => 'Y',
            'username' => 'yuser',
            'email' => 'y@example.com',
            'password' => 'secret123',
        ])->assertForbidden();
    }
}
