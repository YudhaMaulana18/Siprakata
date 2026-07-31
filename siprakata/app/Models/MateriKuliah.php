<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MateriKuliah extends Model {
    protected $table = 'materi_kuliah';
    protected $fillable = ['jadwal_id','pertemuan_ke','judul','deskripsi','file_path','link_materi'];

    public function jadwal() { return $this->belongsTo(JadwalKuliah::class, 'jadwal_id'); }
}