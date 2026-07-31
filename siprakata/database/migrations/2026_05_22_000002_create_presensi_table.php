<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('jadwal_kuliah')->onDelete('cascade');
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->date('tanggal');
            $table->integer('pertemuan_ke');
            $table->enum('status_hadir', ['hadir','izin','sakit','alpha'])->default('hadir');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['jadwal_id','mahasiswa_id','pertemuan_ke'], 'presensi_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};