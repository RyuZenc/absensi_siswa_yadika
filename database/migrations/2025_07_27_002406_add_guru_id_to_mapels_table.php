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
        Schema::table('mapels', function (Blueprint $table) {
            // Add guru_id column
            $table->foreignId('guru_id')->nullable()->constrained('gurus')->onDelete('set null')->after('nama_mapel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mapels', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['guru_id']);
            // Drop the column
            $table->dropColumn('guru_id');
        });
    }
};
