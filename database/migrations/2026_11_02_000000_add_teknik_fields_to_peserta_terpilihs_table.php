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
            // Kolom Teknik Sudut Merah
            if (!Schema::hasColumn('peserta_terpilihs', 'merah_mujoken_kachi')) {
                $table->integer('merah_mujoken_kachi')->default(0)->after('keterangan_menang');
            }
            if (!Schema::hasColumn('peserta_terpilihs', 'merah_ippon')) {
                $table->integer('merah_ippon')->default(0)->after('merah_mujoken_kachi');
            }
            if (!Schema::hasColumn('peserta_terpilihs', 'merah_waza_ari')) {
                $table->integer('merah_waza_ari')->default(0)->after('merah_ippon');
            }
            if (!Schema::hasColumn('peserta_terpilihs', 'merah_batsu_5')) {
                $table->integer('merah_batsu_5')->default(0)->after('merah_waza_ari');
            }
            if (!Schema::hasColumn('peserta_terpilihs', 'merah_batsu_10')) {
                $table->integer('merah_batsu_10')->default(0)->after('merah_batsu_5');
            }
            if (!Schema::hasColumn('peserta_terpilihs', 'merah_yusei_kachi')) {
                $table->integer('merah_yusei_kachi')->default(0)->after('merah_batsu_10');
            }

            // Kolom Teknik Sudut Putih
            if (!Schema::hasColumn('peserta_terpilihs', 'putih_mujoken_kachi')) {
                $table->integer('putih_mujoken_kachi')->default(0)->after('merah_yusei_kachi');
            }
            if (!Schema::hasColumn('peserta_terpilihs', 'putih_ippon')) {
                $table->integer('putih_ippon')->default(0)->after('putih_mujoken_kachi');
            }
            if (!Schema::hasColumn('peserta_terpilihs', 'putih_waza_ari')) {
                $table->integer('putih_waza_ari')->default(0)->after('putih_ippon');
            }
            if (!Schema::hasColumn('peserta_terpilihs', 'putih_batsu_5')) {
                $table->integer('putih_batsu_5')->default(0)->after('putih_waza_ari');
            }
            if (!Schema::hasColumn('peserta_terpilihs', 'putih_batsu_10')) {
                $table->integer('putih_batsu_10')->default(0)->after('putih_batsu_5');
            }
            if (!Schema::hasColumn('peserta_terpilihs', 'putih_yusei_kachi')) {
                $table->integer('putih_yusei_kachi')->default(0)->after('putih_batsu_10');
            }

            // Penyesuaian jenis kemenangan / keterangan_menang menjadi VARCHAR(100) NULL
            if (Schema::hasColumn('peserta_terpilihs', 'keterangan_menang')) {
                $table->string('keterangan_menang', 100)->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peserta_terpilihs', function (Blueprint $table) {
            $columns = [
                'merah_mujoken_kachi',
                'merah_ippon',
                'merah_waza_ari',
                'merah_batsu_5',
                'merah_batsu_10',
                'merah_yusei_kachi',
                'putih_mujoken_kachi',
                'putih_ippon',
                'putih_waza_ari',
                'putih_batsu_5',
                'putih_batsu_10',
                'putih_yusei_kachi',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('peserta_terpilihs', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
