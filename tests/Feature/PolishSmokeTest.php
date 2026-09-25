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
        $this->actingAs($admin)->get('/profil')->assertOk();
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
}
