<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SessionLogoutTest extends TestCase
{
    use RefreshDatabase;

    private function fakeSession(string $id, int $userId): void
    {
        DB::table('sessions')->insert([
            'id' => $id,
            'user_id' => $userId,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/153.0',
            'payload' => base64_encode(serialize(['login_at' => now()->toDateTimeString()])),
            'last_activity' => time(),
        ]);
    }

    public function test_password_benar_mengakhiri_sesi_lain(): void
    {
        $user = User::factory()->create(['role' => 'musyrif']);
        $this->fakeSession('sesi-lain-1', $user->id);
        $this->fakeSession('sesi-lain-2', $user->id);

        $response = $this->actingAs($user)->from('/profil')->delete('/profil/sesi-lain', ['password' => 'password']);

        $response->assertRedirect('/profil');
        $response->assertSessionHas('status', 'Semua sesi lain berhasil diakhiri.');
        $this->assertEquals(0, DB::table('sessions')->where('user_id', $user->id)->count());
    }

    public function test_password_salah_sesi_tetap_ada(): void
    {
        $user = User::factory()->create(['role' => 'santri']);
        $this->fakeSession('sesi-lain-9', $user->id);

        $response = $this->actingAs($user)->delete('/profil/sesi-lain', ['password' => 'salah']);

        $response->assertSessionHasErrors('password');
        $this->assertEquals(1, DB::table('sessions')->where('user_id', $user->id)->count());
    }

    public function test_berlaku_semua_role(): void
    {
        foreach (['admin', 'santri', 'wali'] as $role) {
            $user = User::factory()->create(['role' => $role]);
            $this->fakeSession("sesi-{$role}", $user->id);

            $this->actingAs($user)
                ->delete('/profil/sesi-lain', ['password' => 'password'])
                ->assertSessionHas('status');

            $this->assertEquals(0, DB::table('sessions')->where('user_id', $user->id)->count());
        }
    }
}
