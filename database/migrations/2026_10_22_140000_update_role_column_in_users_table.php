<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah tipe kolom role menjadi VARCHAR(50) dengan default 'user'
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 50)->default('user')->change();
        });

        // Migrasikan nilai role lama jika masih integer
        DB::table('users')->where('role', '1')->update(['role' => 'admin']);
        DB::table('users')->where('role', '0')->update(['role' => 'user']);
        DB::table('users')->where('role', '2')->update(['role' => 'operator']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->where('role', 'admin')->update(['role' => '1']);
        DB::table('users')->where('role', 'user')->update(['role' => '0']);
        DB::table('users')->where('role', 'operator')->update(['role' => '2']);

        Schema::table('users', function (Blueprint $table) {
            $table->integer('role')->default(1)->change();
        });
    }
};
