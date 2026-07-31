<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model {
    protected $table = 'pengumuman';
    protected $fillable = ['dosen_id','jadwal_id','judul','isi','prioritas','tgl_posting','tgl_kadaluarsa'];
    protected $casts = ['tgl_posting' => 'date', 'tgl_kadaluarsa' => 'date'];

    public function dosen()  { return $this->belongsTo(Dosen::class); }
    public function jadwal() { return $this->belongsTo(JadwalKuliah::class, 'jadwal_id'); }
}