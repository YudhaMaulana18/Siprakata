<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model {
    protected $table = 'tahun_ajaran';
    protected $fillable = ['tahun','semester','tgl_mulai','tgl_selesai','status_aktif'];
    protected $casts = ['status_aktif' => 'boolean', 'tgl_mulai' => 'date', 'tgl_selesai' => 'date'];

    public function getNamaLengkapAttribute() {
        return $this->tahun . ' - ' . $this->semester;
    }
    public function jadwal() { return $this->hasMany(JadwalKuliah::class); }
}