<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelayakan_mahasiswa', function (Blueprint $table) {
            $table->enum('hasil_prediksi', ['tidak_lulus', 'cukup', 'lulus'])->default('tidak_lulus')->change();
        });
    }

    public function down(): void
    {
        Schema::table('kelayakan_mahasiswa', function (Blueprint $table) {
            $table->enum('hasil_prediksi', ['lulus', 'tidak_lulus'])->default('tidak_lulus')->change();
        });
    }
};
