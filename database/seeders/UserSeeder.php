<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\PemilikHomestay;
use App\Models\User;
use App\Models\Wisatawan;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'password' => 'admin123',
                'role' => Role::Admin->value,
                'email' => 'admin@homestaygo.id',
                'status_aktif' => true,
            ]
        );

        $pemilik1 = User::updateOrCreate(
            ['username' => 'pemilik1'],
            [
                'password' => 'pemilik123',
                'role' => Role::Pemilik->value,
                'email' => 'wayan@homestaygo.id',
                'status_aktif' => true,
            ]
        );
        PemilikHomestay::updateOrCreate(
            ['id_user' => $pemilik1->id_user],
            [
                'nama_pemilik' => 'Wayan Sutarja',
                'no_hp' => '081234567801',
                'alamat' => 'Banjar Penglipuran, Bangli, Bali',
            ]
        );

        $pemilik2 = User::updateOrCreate(
            ['username' => 'pemilik2'],
            [
                'password' => 'pemilik123',
                'role' => Role::Pemilik->value,
                'email' => 'siti@homestaygo.id',
                'status_aktif' => true,
            ]
        );
        PemilikHomestay::updateOrCreate(
            ['id_user' => $pemilik2->id_user],
            [
                'nama_pemilik' => 'Siti Rahayu',
                'no_hp' => '081234567802',
                'alamat' => 'Desa Jatiluwih, Tabanan, Bali',
            ]
        );

        $wisatawanData = [
            ['username' => 'wisatawan1', 'nama' => 'Ayu Lestari', 'kota' => 'Bandung', 'hp' => '081211110001', 'email' => 'ayu@example.com'],
            ['username' => 'wisatawan2', 'nama' => 'Bagus Pratama', 'kota' => 'Surabaya', 'hp' => '081211110002', 'email' => 'bagus@example.com'],
            ['username' => 'wisatawan3', 'nama' => 'Citra Dewi', 'kota' => 'Jakarta', 'hp' => '081211110003', 'email' => 'citra@example.com'],
        ];

        foreach ($wisatawanData as $data) {
            $user = User::updateOrCreate(
                ['username' => $data['username']],
                [
                    'password' => 'wisatawan123',
                    'role' => Role::Wisatawan->value,
                    'email' => $data['email'],
                    'status_aktif' => true,
                ]
            );

            Wisatawan::updateOrCreate(
                ['id_user' => $user->id_user],
                [
                    'nama_wisatawan' => $data['nama'],
                    'no_hp' => $data['hp'],
                    'asal_kota' => $data['kota'],
                ]
            );
        }
    }
}
