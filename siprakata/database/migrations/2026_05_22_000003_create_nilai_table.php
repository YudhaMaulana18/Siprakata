<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('krs_id')->constrained('transaksi_krs')->onDelete('cascade');
            $table->decimal('nilai_tugas',  5, 2)->default(0);
            $table->decimal('nilai_uts',    5, 2)->default(0);
            $table->decimal('nilai_uas',    5, 2)->default(0);
            $table->decimal('nilai_akhir',  5, 2)->default(0); // dihitung otomatis di Model
            $table->string('grade', 2)->nullable();             // dihitung otomatis di Model
            $table->timestamps();
            $table->unique('krs_id'); // 1 mahasiswa, 1 matkul, 1 nilai
        });
    }
    public function down(): void { Schema::dropIfExists('nilai'); }
};