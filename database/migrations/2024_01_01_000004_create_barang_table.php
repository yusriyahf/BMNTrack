<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ruangan_id');
            $table->string('nama_barang', 150);
            $table->string('kategori', 100)->nullable();
            $table->integer('jumlah')->default(1);
            $table->enum('kondisi', ['Aman', 'Rusak'])->default('Aman');
            $table->string('foto_barang', 255)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('ruangan_id')->references('id')->on('ruangan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
