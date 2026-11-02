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
        // 1. Tambahkan index penunjang untuk pencarian & proses swap pada peserta_terpilihs
        Schema::table('peserta_terpilihs', function (Blueprint $table) {
            $table->index(['list_kategori_id', 'babak', 'nomor_partai'], 'idx_pt_kategori_babak_partai');
            $table->index('list_kategori_id', 'idx_pt_list_kategori_id');
            $table->index('pertandingan_peserta_kiri_id', 'idx_pt_kiri_id');
            $table->index('pertandingan_peserta_kanan_id', 'idx_pt_kanan_id');
            $table->index('pemenang_id', 'idx_pt_pemenang_id');
        });

        // 2. Buat tabel audit log untuk melacak perubahan bagan manual oleh admin/operator
        Schema::create('log_perubahan_bagans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('list_kategori_id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('peserta_terpilih_id_asal')->index();
            $table->unsignedBigInteger('peserta_terpilih_id_tujuan')->nullable()->index();
            $table->string('sudut_asal', 10); // 'kiri' atau 'kanan'
            $table->string('sudut_tujuan', 10)->nullable(); // 'kiri' atau 'kanan'
            $table->unsignedBigInteger('atlet_asal_id')->nullable(); // ID dari pertandingan_pesertas
            $table->unsignedBigInteger('atlet_tujuan_id')->nullable(); // ID dari pertandingan_pesertas
            $table->string('tipe_aksi', 30)->default('swap'); // 'swap', 'direct_assign', 'move_bye'
            $table->text('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_perubahan_bagans');

        Schema::table('peserta_terpilihs', function (Blueprint $table) {
            $table->dropIndex('idx_pt_kategori_babak_partai');
            $table->dropIndex('idx_pt_list_kategori_id');
            $table->dropIndex('idx_pt_kiri_id');
            $table->dropIndex('idx_pt_kanan_id');
            $table->dropIndex('idx_pt_pemenang_id');
        });
    }
};
