<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peserta_terpilihs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pertandingan_id');
            $table->unsignedBigInteger('pertandingan_peserta_kiri_id');
            $table->unsignedBigInteger('pertandingan_peserta_kanan_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta_terpilihs');
    }
};
