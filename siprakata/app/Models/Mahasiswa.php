<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model {
    protected $table = 'mahasiswa';
    protected $fillable = ['NIM','nama','alamat','email','no_hp','jenis_kelamin','angkatan','status','prodi_id'];

    public function prodi()     { return $this->belongsTo(ProgramStudi::class, 'prodi_id'); }
    public function krs()       { return $this->hasMany(TransaksiKrs::class); }
    public function presensi()  { return $this->hasMany(Presensi::class); }
}