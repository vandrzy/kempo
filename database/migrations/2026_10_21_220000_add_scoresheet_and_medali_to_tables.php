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
        // Add data_scoresheet to pertandingans if not exists
        if (!Schema::hasColumn('pertandingans', 'data_scoresheet')) {
            Schema::table('pertandingans', function (Blueprint $table) {
                $table->json('data_scoresheet')->nullable();
            });
        }

        // Add data_scoresheet and data_juara to list_kategoris if not exists
        Schema::table('list_kategoris', function (Blueprint $table) {
            if (!Schema::hasColumn('list_kategoris', 'data_scoresheet')) {
                $table->json('data_scoresheet')->nullable();
            }
            if (!Schema::hasColumn('list_kategoris', 'data_juara')) {
                $table->json('data_juara')->nullable();
            }
        });

        // Add medali to pertandingan_pesertas if not exists
        if (!Schema::hasColumn('pertandingan_pesertas', 'medali')) {
            Schema::table('pertandingan_pesertas', function (Blueprint $table) {
                $table->string('medali')->nullable(); // 'emas', 'perak', 'perunggu'
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('pertandingans', 'data_scoresheet')) {
            Schema::table('pertandingans', function (Blueprint $table) {
                $table->dropColumn('data_scoresheet');
            });
        }

        Schema::table('list_kategoris', function (Blueprint $table) {
            if (Schema::hasColumn('list_kategoris', 'data_scoresheet')) {
                $table->dropColumn('data_scoresheet');
            }
            if (Schema::hasColumn('list_kategoris', 'data_juara')) {
                $table->dropColumn('data_juara');
            }
        });

        if (Schema::hasColumn('pertandingan_pesertas', 'medali')) {
            Schema::table('pertandingan_pesertas', function (Blueprint $table) {
                $table->dropColumn('medali');
            });
        }
    }
};
