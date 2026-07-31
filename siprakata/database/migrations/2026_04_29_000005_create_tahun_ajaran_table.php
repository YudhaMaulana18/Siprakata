<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tahun_ajaran', function (Blueprint $table) {
            $table->id();
            $table->string('tahun');        // contoh: 2025/2026
            $table->enum('semester', ['Ganjil','Genap']);
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->boolean('status_aktif')->default(false);
            $table->timestamps();
            $table->unique(['tahun','semester'], 'ta_unique');
        });
    }
    public function down(): void { Schema::dropIfExists('tahun_ajaran'); }
};