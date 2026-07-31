<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model {
    protected $table = 'dosen';
    protected $fillable = ['NIDN','nama','email','no_hp','jabatan','prodi_id'];

    public function prodi()       { return $this->belongsTo(ProgramStudi::class, 'prodi_id'); }
    public function krs()         { return $this->hasMany(TransaksiKrs::class); }
    public function jadwal()      { return $this->hasMany(JadwalKuliah::class); }
    public function pengumuman()  { return $this->hasMany(Pengumuman::class); }
}