<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ruangan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_ruangan')->unique();
            $table->string('nama_ruangan');
            $table->integer('kapasitas');
            $table->string('gedung');
            $table->string('lantai');
            $table->enum('jenis', ['Kelas','Laboratorium','Aula','Lainnya'])->default('Kelas');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('ruangan'); }
};