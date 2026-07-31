<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('program_studi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_prodi');
            $table->string('kode_prodi')->unique();
            $table->enum('jenjang', ['D3','S1','S2','S3'])->default('S1');
            $table->string('fakultas');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('program_studi'); }
};