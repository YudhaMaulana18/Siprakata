<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiKrs extends Model
{
    protected $table = 'transaksi_krs';
    protected $fillable = [
        'mahasiswa_id',
        'matakuliah_id',
        'dosen_id',
        'tahun_ajaran',
        'semester',
        'status',
        'status_validasi',
        'catatan_validasi',
        'tgl_validasi',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function nilai()
    {
        return $this->hasOne(Nilai::class, 'krs_id');
    }
}