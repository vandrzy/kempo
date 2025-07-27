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
        Schema::create('bukus', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->unsignedBigInteger('penulis_id');
            $table->unsignedBigInteger('user_id');
            // $table->unsignedBigInteger('kategori_id');
            $table->string('publisher');
            $table->integer('tahun');
            $table->text('deskripsi');
            $table->text('cover');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
