<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Alter enum to include all possible values temporarily so we can update safely
        DB::statement("ALTER TABLE barang MODIFY COLUMN kondisi ENUM('Aman', 'Rusak', 'Baik', 'Rusak berat') DEFAULT 'Baik'");
        
        // 2. Update existing data
        DB::table('barang')->where('kondisi', 'Aman')->update(['kondisi' => 'Baik']);
        DB::table('barang')->where('kondisi', 'Rusak')->update(['kondisi' => 'Rusak berat']);
        
        // 3. Restrict enum to only the new values
        DB::statement("ALTER TABLE barang MODIFY COLUMN kondisi ENUM('Baik', 'Rusak berat') DEFAULT 'Baik'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE barang MODIFY COLUMN kondisi ENUM('Aman', 'Rusak', 'Baik', 'Rusak berat') DEFAULT 'Aman'");
        
        DB::table('barang')->where('kondisi', 'Baik')->update(['kondisi' => 'Aman']);
        DB::table('barang')->where('kondisi', 'Rusak berat')->update(['kondisi' => 'Rusak']);
        
        DB::statement("ALTER TABLE barang MODIFY COLUMN kondisi ENUM('Aman', 'Rusak') DEFAULT 'Aman'");
    }
};
