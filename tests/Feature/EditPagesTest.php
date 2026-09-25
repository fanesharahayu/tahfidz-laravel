<?php

namespace Tests\Feature;

use App\Models\Santri;
use App\Models\Setoran;
use App\Models\TargetHafalan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditPagesTest extends TestCase
{
    use RefreshDatabase;

    private function seedMusyrifSantri(): array
    {
        $musyrif = User::factory()->create(['role' => 'musyrif']);
        $santriUser = User::factory()->create(['role' => 'santri']);
        $santri = Santri::create(['user_id' => $santriUser->id, 'musyrif_id' => $musyrif->id, 'target_juz' => 30]);
        $setoran = Setoran::create([
            'santri_id' => $santri->id, 'musyrif_id' => $musyrif->id,
            'juz' => 1, 'surah' => 'Al-Fatihah', 'jenis' => 'hafalan_baru', 'nilai' => 'lancar',
        ]);
        $target = TargetHafalan::create(['santri_id' => $santri->id, 'target_juz' => 5]);

        return [$musyrif, $santriUser, $santri, $setoran, $target];
    }

    public function test_admin_edit_pages_render_with_buttons(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [$musyrif, $santriUser, $santri, $setoran] = $this->seedMusyrifSantri();

        $this->actingAs($admin)->get('/admin/santri')->assertOk()->assertSee('Edit');
        $this->actingAs($admin)->get('/admin/santri/create')->assertOk();
        $this->actingAs($admin)->get("/admin/santri/{$santri->id}/edit")->assertOk()->assertSee($santriUser->nama);
        $this->actingAs($admin)->get('/admin/setoran')->assertOk()->assertSee('Edit');
        $this->actingAs($admin)->get('/admin/setoran/create')->assertOk();
        $this->actingAs($admin)->get("/admin/setoran/{$setoran->id}/edit")->assertOk()->assertSee('Al-Fatihah');
        $this->actingAs($admin)->get("/admin/users/{$musyrif->id}/edit")->assertOk()->assertSee($musyrif->nama);
        $this->actingAs($admin)->get('/admin/dashboard')->assertOk()->assertSee('Edit');
    }

    public function test_admin_update_flows(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [$musyrif, $santriUser, $santri, $setoran] = $this->seedMusyrifSantri();

        $this->actingAs($admin)->put("/admin/users/{$musyrif->id}", [
            'nama' => 'Nama Baru', 'username' => $musyrif->username,
            'email' => $musyrif->email, 'role' => 'musyrif',
        ])->assertRedirect('/admin/users');
        $this->assertEquals('Nama Baru', $musyrif->fresh()->nama);

        $this->actingAs($admin)->put("/admin/santri/{$santri->id}", [
            'nama' => 'Santri Baru', 'kelas' => 'XI-B', 'target_juz' => 10,
        ])->assertSessionHas('status');
        $this->assertEquals('XI-B', $santri->fresh()->kelas);

        $this->actingAs($admin)->put("/admin/setoran/{$setoran->id}", [
            'nilai' => 'perlu_ulang',
        ])->assertSessionHas('status');
        $this->assertEquals('perlu_ulang', $setoran->fresh()->nilai);

        $this->actingAs($admin)->delete("/admin/santri/{$santri->id}")->assertRedirect('/admin/santri');
        $this->assertNull(User::find($santriUser->id));
    }

    public function test_musyrif_edit_pages_scoped(): void
    {
        [$musyrif, $santriUser, $santri, $setoran, $target] = $this->seedMusyrifSantri();
        $musyrifLain = User::factory()->create(['role' => 'musyrif']);
        $setoranLain = Setoran::create([
            'santri_id' => $santri->id, 'musyrif_id' => $musyrifLain->id,
            'juz' => 2, 'surah' => 'Al-Baqarah', 'jenis' => 'tambahan', 'nilai' => 'lancar',
        ]);

        $this->actingAs($musyrif)->get("/musyrif/setoran/{$setoran->id}/edit")->assertOk()->assertSee('Al-Fatihah');
        $this->actingAs($musyrif)->get("/musyrif/setoran/{$setoranLain->id}/edit")->assertNotFound();
        $this->actingAs($musyrif)->get("/musyrif/target/{$target->id}/edit")->assertOk();

        $this->actingAs($musyrif)->put("/musyrif/setoran/{$setoran->id}", [
            'juz' => 1, 'surah' => 'Al-Fatihah', 'nilai' => 'cukup_lancar',
        ])->assertRedirect('/musyrif/setoran');
        $this->assertEquals('cukup_lancar', $setoran->fresh()->nilai);

        $this->actingAs($musyrif)->put("/musyrif/target/{$target->id}", [
            'target_juz' => 8,
        ])->assertRedirect('/musyrif/targets');
        $this->assertEquals(8, $target->fresh()->target_juz);
    }

    public function test_non_admin_ditolak(): void
    {
        $santri = User::factory()->create(['role' => 'santri']);

        $this->actingAs($santri)->get('/admin/santri')->assertForbidden();
        $this->actingAs($santri)->get('/admin/setoran')->assertForbidden();
        $this->actingAs($santri)->get("/admin/users/{$santri->id}/edit")->assertForbidden();
    }

    public function test_admin_create_pages_dan_update_target_wali(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [$musyrif, $santriUser, $santri, $setoran, $target] = $this->seedMusyrifSantri();
        $wali = User::factory()->create(['role' => 'wali']);
        $link = \App\Models\WaliSantri::create([
            'wali_user_id' => $wali->id, 'santri_id' => $santri->id, 'relasi' => 'Ayah',
        ]);

        // Halaman tambah khusus (bukan form menempel di index).
        $this->actingAs($admin)->get('/admin/users/create')->assertOk()->assertSee('Tambah Pengguna');
        $this->actingAs($admin)->get('/admin/target/create')->assertOk()->assertSee('Tambah Target');
        $this->actingAs($admin)->get('/admin/wali-link/create')->assertOk()->assertSee('Tambah Hubungan');
        $this->actingAs($admin)->get('/admin/users')->assertOk()->assertSee('Tambah Pengguna');
        $this->actingAs($admin)->get('/admin/targets')->assertOk()->assertSee('Tambah Target');
        $this->actingAs($admin)->get('/admin/wali-links')->assertOk()->assertSee('Tambah Hubungan');

        // Index tidak lagi memuat form tambah menempel.
        $this->actingAs($admin)->get('/admin/users')->assertDontSee('name="password"');
        $this->actingAs($admin)->get('/admin/targets')->assertDontSee('name="santri_id"');

        // Ubah target + hubungan.
        $this->actingAs($admin)->get("/admin/target/{$target->id}/edit")->assertOk();
        $this->actingAs($admin)->put("/admin/target/{$target->id}", [
            'target_juz' => 12, 'periode' => 'Genap',
        ])->assertRedirect('/admin/targets');
        $this->assertEquals(12, $target->fresh()->target_juz);

        $this->actingAs($admin)->get("/admin/wali-link/{$link->id}/edit")->assertOk();
        $this->actingAs($admin)->put("/admin/wali-link/{$link->id}", [
            'relasi' => 'Ibu',
        ])->assertRedirect('/admin/wali-links');
        $this->assertEquals('Ibu', $link->fresh()->relasi);
    }
}
