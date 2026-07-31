<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model {
    protected $table = 'ruangan';
    protected $fillable = ['kode_ruangan','nama_ruangan','kapasitas','gedung','lantai','jenis'];

    public function jadwal() { return $this->hasMany(JadwalKuliah::class); }
}