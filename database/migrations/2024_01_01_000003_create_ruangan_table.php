<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ruangan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gedung_id');
            $table->string('nama_ruangan', 100);
            $table->string('luas_ruangan', 100)->nullable();
            $table->integer('lantai')->default(1);
            $table->string('pic_ruangan', 100)->nullable();
            $table->string('foto_ruangan', 255)->nullable();
            $table->date('tanggal_pendataan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('gedung_id')->references('id')->on('gedung')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ruangan');
    }
};
