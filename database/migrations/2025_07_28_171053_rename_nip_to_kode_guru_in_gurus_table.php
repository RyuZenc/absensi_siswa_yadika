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
        Schema::table('gurus', function (Blueprint $table) {
            // Ubah nama kolom 'nip' menjadi 'kode_guru'
            $table->renameColumn('nip', 'kode_guru');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            // Kembalikan nama kolom 'kode_guru' menjadi 'nip' jika rollback
            $table->renameColumn('kode_guru', 'nip');
        });
    }
};
