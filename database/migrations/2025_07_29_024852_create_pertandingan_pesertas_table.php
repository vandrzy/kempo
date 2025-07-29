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
        Schema::create('pertandingan_pesertas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peserta_id');
            $table->string('sudah_terpilih')->default('false');
            $table->unsignedBigInteger('pertandingan_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertandingan_pesertas');
    }
};
