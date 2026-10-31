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
        Schema::table('peserta_terpilihs', function (Blueprint $table) {
            if (!Schema::hasColumn('peserta_terpilihs', 'babak')) {
                $table->tinyInteger('babak')->unsigned()->default(1)->after('pertandingan_peserta_kanan_id');
            }
            if (!Schema::hasColumn('peserta_terpilihs', 'nomor_partai')) {
                $table->integer('nomor_partai')->unsigned()->nullable()->after('babak');
            }
            if (!Schema::hasColumn('peserta_terpilihs', 'pemenang_id')) {
                $table->unsignedBigInteger('pemenang_id')->nullable()->after('nomor_partai');
            }
            if (!Schema::hasColumn('peserta_terpilihs', 'skor_kiri')) {
                $table->decimal('skor_kiri', 8, 2)->nullable()->after('pemenang_id');
            }
            if (!Schema::hasColumn('peserta_terpilihs', 'skor_kanan')) {
                $table->decimal('skor_kanan', 8, 2)->nullable()->after('skor_kiri');
            }
            if (!Schema::hasColumn('peserta_terpilihs', 'keterangan_menang')) {
                $table->string('keterangan_menang', 50)->nullable()->after('skor_kanan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peserta_terpilihs', function (Blueprint $table) {
            $cols = ['babak', 'nomor_partai', 'pemenang_id', 'skor_kiri', 'skor_kanan', 'keterangan_menang'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('peserta_terpilihs', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
