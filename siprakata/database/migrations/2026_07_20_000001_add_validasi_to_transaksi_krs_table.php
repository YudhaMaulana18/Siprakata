<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi_krs', function (Blueprint $table) {
            $table->enum('status_validasi', ['pending','disetujui','ditolak'])->default('pending')->after('status');
            $table->text('catatan_validasi')->nullable()->after('status_validasi');
            $table->timestamp('tgl_validasi')->nullable()->after('catatan_validasi');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_krs', function (Blueprint $table) {
            $table->dropColumn(['status_validasi', 'catatan_validasi', 'tgl_validasi']);
        });
    }
};
