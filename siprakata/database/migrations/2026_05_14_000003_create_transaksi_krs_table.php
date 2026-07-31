<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_krs', function (Blueprint $table) {
            $table->id();

            // Foreign key ke tabel mahasiswa
            $table->foreignId('mahasiswa_id')
                  ->constrained('mahasiswa')
                  ->onDelete('cascade');

            // Foreign key ke tabel matakuliah
            $table->foreignId('matakuliah_id')
                  ->constrained('matakuliah')
                  ->onDelete('cascade');

            // Foreign key ke tabel dosen
            $table->foreignId('dosen_id')
                  ->constrained('dosen')
                  ->onDelete('cascade');

            $table->string('tahun_ajaran');  // contoh: 2025/2026
            $table->string('semester');       // contoh: Ganjil / Genap
            $table->string('status')->default('aktif'); // aktif / selesai

            $table->timestamps();

            // Satu mahasiswa tidak boleh ambil matkul yang sama di tahun/semester yang sama
            $table->unique(['mahasiswa_id', 'matakuliah_id', 'tahun_ajaran', 'semester'], 'krs_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_krs');
    }
};