<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pertandingan_pesertas', function (Blueprint $table) {
            if (!Schema::hasColumn('pertandingan_pesertas', 'waktu')) {
                $table->string('waktu')->nullable()->after('peserta_id');
            }
            if (!Schema::hasColumn('pertandingan_pesertas', 'nilai_1')) {
                $table->decimal('nilai_1', 8, 2)->nullable();
            }
            if (!Schema::hasColumn('pertandingan_pesertas', 'nilai_2')) {
                $table->decimal('nilai_2', 8, 2)->nullable();
            }
            if (!Schema::hasColumn('pertandingan_pesertas', 'nilai_3')) {
                $table->decimal('nilai_3', 8, 2)->nullable();
            }
            if (!Schema::hasColumn('pertandingan_pesertas', 'nilai_4')) {
                $table->decimal('nilai_4', 8, 2)->nullable();
            }
            if (!Schema::hasColumn('pertandingan_pesertas', 'nilai_5')) {
                $table->decimal('nilai_5', 8, 2)->nullable();
            }
            if (!Schema::hasColumn('pertandingan_pesertas', 'minus')) {
                $table->decimal('minus', 8, 2)->nullable()->default(0);
            }
            if (!Schema::hasColumn('pertandingan_pesertas', 'total_nilai')) {
                $table->decimal('total_nilai', 8, 2)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('pertandingan_pesertas', function (Blueprint $table) {
            $cols = ['waktu', 'nilai_1', 'nilai_2', 'nilai_3', 'nilai_4', 'nilai_5', 'minus', 'total_nilai'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('pertandingan_pesertas', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
