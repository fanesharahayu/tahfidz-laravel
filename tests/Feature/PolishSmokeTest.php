<?php

namespace Tests\Feature;

use App\Models\Santri;
use App\Models\User;
use App\Models\WaliSantri;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PolishSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_pages_render(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $musyrif = User::factory()->create(['role' => 'musyrif']);
        $santriUser = User::factory()->create(['role' => 'santri']);
        $santri = Santri::create(['user_id' => $santriUser->id, 'musyrif_id' => $musyrif->id, 'target_juz' => 30]);
        $wali = User::factory()->create(['role' => 'wali']);
        WaliSantri::create(['wali_user_id' => $wali->id, 'santri_id' => $santri->id]);

        $this->actingAs($admin)->get('/admin/dashboard')->assertOk();
        $this->actingAs($admin)->get('/admin/users')->assertOk();
        $this->actingAs($admin)->get('/admin/wali-links')->assertOk();
        $this->actingAs($admin)->get('/profil')->assertOk()->assertSee('Sesi Aktif');
        $this->actingAs($admin)->get('/ganti-password')->assertOk();
        $this->actingAs($musyrif)->get('/musyrif/dashboard')->assertOk();
        $this->actingAs($musyrif)->get('/musyrif/binaan')->assertOk();
        $this->actingAs($musyrif)->get('/musyrif/setoran')->assertOk();
        $this->actingAs($musyrif)->get('/musyrif/targets')->assertOk();
        $this->actingAs($musyrif)->get('/musyrif/wali')->assertOk();
        $this->actingAs($musyrif)->get('/musyrif/santri/' . $santri->id)->assertOk();
        $this->actingAs($musyrif)->get('/profil')->assertOk();

        $this->actingAs($santriUser)->get('/santri/dashboard')->assertOk();
        $this->actingAs($wali)->get('/wali/dashboard')->assertOk();
        $this->actingAs($wali)->get('/wali/anak/' . $santri->id)->assertOk();

        $this->post('/logout');
        $this->get('/login')->assertOk();
    }

    public function test_breadcrumb_matches_active_menu_and_single_logout(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $html = $this->actingAs($admin)->get('/admin/users')->assertOk()->getContent();
        $this->assertStringContainsString('<nav class="crumbs"', $html);
        $this->assertStringContainsString('<span class="current">Pengguna</span>', $html);
        // Tombol logout hanya 1 (di sidebar), tidak ada di topbar kanan atas.
        $this->assertEquals(1, substr_count($html, 'Logout</button>'), 'Logout harus tepat 1 tombol');

        $html = $this->actingAs($admin)->get('/admin/dashboard')->assertOk()->getContent();
        $this->assertStringContainsString('<span class="current">Dashboard</span>', $html);
        // Hamburger + overlay drawer untuk mobile.
        $this->assertStringContainsString('id="hamburger"', $html);
        $this->assertStringContainsString('id="sidebar-overlay"', $html);
        $this->assertStringContainsString('sidebar-open', $html);
    }

    public function test_sidebar_highlights_exactly_one_item(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin = User::factory()->create(['role' => 'admin']);
        $musyrif = User::factory()->create(['role' => 'musyrif']);

        foreach ([
            [$admin, '/admin/dashboard'],
            [$admin, '/admin/users'],
            [$admin, '/admin/targets'],
            [$admin, '/admin/wali-links'],
            [$musyrif, '/musyrif/dashboard'],
            [$musyrif, '/musyrif/targets'],
        ] as [$user, $uri]) {
            $html = $this->actingAs($user)->get($uri)->assertOk()->getContent();
            $this->assertEquals(
                1,
                substr_count($html, 'class="active"'),
                "Sidebar harus highlight tepat 1 item di {$uri}"
            );
        }
    }
}
