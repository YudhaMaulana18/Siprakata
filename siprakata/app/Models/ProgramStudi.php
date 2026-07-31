<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model {
    protected $table = 'program_studi';
    protected $fillable = ['nama_prodi','kode_prodi','jenjang','fakultas'];

    public function mahasiswa() { return $this->hasMany(Mahasiswa::class, 'prodi_id'); }
    public function dosen()     { return $this->hasMany(Dosen::class, 'prodi_id'); }
    public function matakuliah(){ return $this->hasMany(Matakuliah::class, 'prodi_id'); }
}