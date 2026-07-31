<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosen_id')->constrained('dosen')->onDelete('cascade');
            $table->foreignId('jadwal_id')->nullable()->constrained('jadwal_kuliah')->onDelete('set null');
            $table->string('judul');
            $table->text('isi');
            $table->enum('prioritas', ['rendah','sedang','tinggi'])->default('sedang');
            $table->date('tgl_posting');
            $table->date('tgl_kadaluarsa')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pengumuman'); }
};