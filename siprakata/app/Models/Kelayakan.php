<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelayakan extends Model
{
    protected $table = 'kelayakan_mahasiswa';
    protected $fillable = [
        'mahasiswa_id', 'matakuliah_id', 'tahun_ajaran', 'semester',
        'kehadiran', 'nilai_tugas', 'keaktifan_diskusi',
        'skor_prediksi', 'hasil_prediksi', 'detail_perhitungan',
    ];

    protected $casts = [
        'kehadiran' => 'float',
        'nilai_tugas' => 'float',
        'keaktifan_diskusi' => 'float',
        'skor_prediksi' => 'float',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class);
    }
}
