<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class JadwalKuliah extends Model {
    protected $table = 'jadwal_kuliah';
    protected $fillable = ['matakuliah_id','dosen_id','ruangan_id','tahun_ajaran_id','hari','jam_mulai','jam_selesai','ruangan','tahun_ajaran','semester'];

    public function matakuliah()  { return $this->belongsTo(Matakuliah::class); }
    public function dosen()       { return $this->belongsTo(Dosen::class); }
    public function ruanganRef()  { return $this->belongsTo(Ruangan::class, 'ruangan_id'); }
    public function tahunAjaran() { return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id'); }
    public function presensi()    { return $this->hasMany(Presensi::class, 'jadwal_id'); }
    public function materi()      { return $this->hasMany(MateriKuliah::class, 'jadwal_id'); }
    public function pengumuman()  { return $this->hasMany(Pengumuman::class, 'jadwal_id'); }
}