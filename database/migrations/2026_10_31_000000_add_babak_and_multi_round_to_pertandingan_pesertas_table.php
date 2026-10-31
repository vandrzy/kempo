<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pertandingan_pesertas', function (Blueprint $table) {
            if (!Schema::hasColumn('pertandingan_pesertas', 'babak')) {
                $table->unsignedTinyInteger('babak')->default(1)->after('urutan');
            }
            if (!Schema::hasColumn('pertandingan_pesertas', 'total_babak_1')) {
                $table->decimal('total_babak_1', 8, 2)->nullable()->after('total_nilai');
            }
            if (!Schema::hasColumn('pertandingan_pesertas', 'total_babak_2')) {
                $table->decimal('total_babak_2', 8, 2)->nullable()->after('total_babak_1');
            }
            if (!Schema::hasColumn('pertandingan_pesertas', 'nilai_akhir')) {
                $table->decimal('nilai_akhir', 8, 2)->nullable()->after('total_babak_2');
            }

            // Index untuk pencarian cepat berbasis jadwal/kategori + peserta + babak
            $table->index(['list_kategori_id', 'peserta_id', 'babak'], 'pp_list_peserta_babak_idx');
            $table->index(['list_kategori_id', 'babak'], 'pp_list_babak_idx');
        });

        // Backfill data lama: isi total_babak_1 dari total_nilai yang sudah ada
        DB::table('pertandingan_pesertas')
            ->whereNotNull('total_nilai')
            ->whereNull('total_babak_1')
            ->update([
                'total_babak_1' => DB::raw('total_nilai'),
                'babak' => 1
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pertandingan_pesertas', function (Blueprint $table) {
            $table->dropIndex('pp_list_peserta_babak_idx');
            $table->dropIndex('pp_list_babak_idx');

            $cols = ['babak', 'total_babak_1', 'total_babak_2', 'nilai_akhir'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('pertandingan_pesertas', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
