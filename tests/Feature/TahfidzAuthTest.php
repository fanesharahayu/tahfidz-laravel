<?php

namespace Tests\Feature;

use App\Models\Santri;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TahfidzAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_redirects_by_role(): void
    {
        foreach (['admin', 'musyrif', 'santri', 'wali'] as $role) {
            $user = User::factory()->create(['role' => $role]);

            $response = $this->post('/login', [
                'username' => $user->username,
                'password' => 'password',
            ]);

            $expected = match ($role) {
                'admin' => '/admin/dashboard',
                'musyrif' => '/musyrif/dashboard',
                'santri' => '/santri/dashboard',
                'wali' => '/wali/dashboard',
            };

            $response->assertRedirect($expected);
            $this->post('/logout');
        }
    }

    public function test_role_middleware_blocks_wrong_role(): void
    {
        $santri = User::factory()->create(['role' => 'santri']);
        Santri::create(['user_id' => $santri->id, 'target_juz' => 30]);

        $this->actingAs($santri)->get('/admin/dashboard')->assertForbidden();
        $this->actingAs($santri)->get('/santri/dashboard')->assertOk();
    }

    public function test_santri_dashboard_renders(): void
    {
        $user = User::factory()->create(['role' => 'santri']);
        Santri::create(['user_id' => $user->id, 'target_juz' => 30]);

        $this->actingAs($user)->get('/santri/dashboard')->assertOk()->assertSee($user->nama);
    }
}
