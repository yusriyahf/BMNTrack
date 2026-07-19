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
                'password'   => Hash::make('adminbmn2026'),
                'role'       => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama'       => 'Petugas Inventaris',
                'username'   => 'petugas',
                'password'   => Hash::make('petugasbmn2026'),
                'role'       => 'petugas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Seed Gedung
        DB::table('gedung')->insert([
            [
                'kode_gedung' => 'GDA',
                'nama_gedung' => 'Gedung AT',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'kode_gedung' => 'GDB',
                'nama_gedung' => 'Gedung Graha Polinema',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            
        ]);
    }
}
