<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->string('NIM')->unique();
            $table->string('nama');
            $table->string('alamat');
            $table->string('email')->nullable();
            $table->string('no_hp')->nullable();
            $table->enum('jenis_kelamin', ['L','P'])->nullable();
            $table->year('angkatan')->nullable();
            $table->enum('status', ['aktif','cuti','lulus','keluar'])->default('aktif');
            // prodi_id nullable dulu agar tidak merusak data lama
            $table->foreignId('prodi_id')->nullable()->constrained('program_studi')->onDelete('set null');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('mahasiswa'); }
};