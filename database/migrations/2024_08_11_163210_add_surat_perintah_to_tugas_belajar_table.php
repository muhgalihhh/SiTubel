<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tugas_belajar', function (Blueprint $table) {
            $table->string('surat_perintah')->nullable()->after('surat_keterangan_kesehatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tugas_belajar', function (Blueprint $table) {
            $table->dropColumn('surat_perintah');
        });
    }
};
