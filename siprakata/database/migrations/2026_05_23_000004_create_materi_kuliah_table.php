<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('materi_kuliah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('jadwal_kuliah')->onDelete('cascade');
            $table->integer('pertemuan_ke');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('file_path')->nullable();  // path file upload
            $table->string('link_materi')->nullable(); // link eksternal (gdrive, youtube, dll)
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('materi_kuliah'); }
};