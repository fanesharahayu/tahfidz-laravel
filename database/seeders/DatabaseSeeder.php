<?php

namespace Database\Seeders;

use App\Models\Musyrif;
use App\Models\Santri;
use App\Models\User;
use App\Models\WaliSantri;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'nama' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        $musyrifUser = User::firstOrCreate(
            ['username' => 'musyrif1'],
            [
                'name' => 'Musyrif Satu',
                'nama' => 'Musyrif Satu',
                'email' => 'musyrif1@example.com',
                'password' => Hash::make('musyrif123'),
                'role' => 'musyrif',
            ]
        );

        Musyrif::updateOrCreate(
            ['user_id' => $musyrifUser->id],
            ['spesialisasi' => 'Tahfidz']
        );

        $santriUser = User::firstOrCreate(
            ['username' => 'santri1'],
            [
                'name' => 'Santri Satu',
                'nama' => 'Santri Satu',
                'email' => 'santri1@example.com',
                'password' => Hash::make('santri123'),
                'role' => 'santri',
            ]
        );

        $santri = Santri::updateOrCreate(
            ['user_id' => $santriUser->id],
            [
                'musyrif_id' => $musyrifUser->id,
                'nis' => 'S001',
                'kelas' => 'X-A',
                'target_juz' => 30,
                'tanggal_bergabung' => now()->toDateString(),
            ]
        );

        $waliUser = User::firstOrCreate(
            ['username' => 'wali1'],
            [
                'name' => 'Wali Satu',
                'nama' => 'Wali Satu',
                'email' => 'wali1@example.com',
                'password' => Hash::make('wali123'),
                'role' => 'wali',
            ]
        );

        WaliSantri::firstOrCreate(
            [
                'wali_user_id' => $waliUser->id,
                'santri_id' => $santri->id,
            ],
            ['relasi' => 'Ayah']
        );
    }
}
