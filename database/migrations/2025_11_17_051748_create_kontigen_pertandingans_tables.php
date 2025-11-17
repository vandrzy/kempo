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
        Schema::create('kontigen_pertandingans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('list_kategori_id');
            $table->unsignedBigInteger('kabupaten_id')->nullable();
            $table->integer('urutan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kontigen_pertandingans_tables');
    }
};
