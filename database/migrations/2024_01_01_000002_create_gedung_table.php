<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gedung', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_gedung', 20)->unique();
            $table->string('nama_gedung', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gedung');
    }
};
