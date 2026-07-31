<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Matakuliah extends Model {
    protected $table = 'matakuliah';
    protected $fillable = ['kode_mk','nama_mk','sks','semester','prodi_id'];

    public function prodi()  { return $this->belongsTo(ProgramStudi::class, 'prodi_id'); }
    public function krs()    { return $this->hasMany(TransaksiKrs::class); }
    public function jadwal() { return $this->hasMany(JadwalKuliah::class); }
}