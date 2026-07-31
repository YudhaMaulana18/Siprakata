<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('kelayakan_mahasiswa');

        Schema::create('kelayakan_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->foreignId('matakuliah_id')->constrained('matakuliah')->onDelete('cascade');
            $table->string('tahun_ajaran');
            $table->string('semester');

            // Input fuzzy
            $table->decimal('kehadiran', 5, 2)->default(0);
            $table->decimal('nilai_tugas', 5, 2)->default(0);
            $table->decimal('keaktifan_diskusi', 5, 2)->default(0);

            // Output fuzzy
            $table->decimal('skor_prediksi', 5, 2)->default(0);
            $table->enum('hasil_prediksi', ['lulus', 'tidak_lulus'])->default('tidak_lulus');
            $table->text('detail_perhitungan')->nullable();

            $table->timestamps();

            $table->unique(['mahasiswa_id', 'matakuliah_id', 'tahun_ajaran', 'semester'], 'prediksi_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelayakan_mahasiswa');

        Schema::create('kelayakan_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->string('tahun_ajaran');
            $table->string('semester');
            $table->decimal('ipk', 3, 2)->default(0);
            $table->integer('total_sks')->default(0);
            $table->decimal('persentase_kehadiran', 5, 2)->default(0);
            $table->decimal('skor_kelayakan', 5, 2)->default(0);
            $table->enum('hasil_kelayakan', ['layak', 'tidak_layak'])->default('tidak_layak');
            $table->text('detail_perhitungan')->nullable();
            $table->timestamps();
            $table->unique(['mahasiswa_id', 'tahun_ajaran', 'semester'], 'kelayakan_unique');
        });
    }
};
