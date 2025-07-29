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
        Schema::create('pesertas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('jenis_kelamin',['Laki-Laki', 'Perempuan'])->nullable();
            $table->enum('golongan_darah',['A', 'B', 'AB', 'O'])->nullable();
            $table->string('nik');
            $table->string('tingkat');
            $table->string('status');
            $table->string('tempat');
            $table->date('tanggal_lahir');
            $table->string('alamat');
            $table->string('telepon');
            $table->text('keterangan');
            $table->string('foto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesertas');
    }
};
