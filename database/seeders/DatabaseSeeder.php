<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed Users
        DB::table('users')->insert([
            [
                'nama'       => 'Administrator',
                'username'   => 'admin',
                'password'   => Hash::make('admin123'),
                'role'       => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama'       => 'Petugas Inventaris',
                'username'   => 'petugas',
                'password'   => Hash::make('petugas123'),
                'role'       => 'petugas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Seed Gedung
        DB::table('gedung')->insert([
            [
                'kode_gedung' => 'GDA',
                'nama_gedung' => 'Gedung A - Rektorat',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'kode_gedung' => 'GDB',
                'nama_gedung' => 'Gedung B - Fakultas Teknik',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'kode_gedung' => 'GDC',
                'nama_gedung' => 'Gedung C - Perpustakaan',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
