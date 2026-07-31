<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model {
    protected $table    = 'nilai';
    protected $fillable = ['krs_id','nilai_tugas','nilai_uts','nilai_uas','nilai_akhir','grade'];

    public function krs() {
        return $this->belongsTo(TransaksiKrs::class, 'krs_id');
    }

    // Hitung nilai_akhir dan grade otomatis sebelum simpan
    protected static function booted(): void {
        static::saving(function ($nilai) {
            $akhir = ($nilai->nilai_tugas * 0.30)
                   + ($nilai->nilai_uts   * 0.30)
                   + ($nilai->nilai_uas   * 0.40);
            $nilai->nilai_akhir = round($akhir, 2);

            $nilai->grade = match(true) {
                $akhir >= 85 => 'A',
                $akhir >= 75 => 'B+',
                $akhir >= 65 => 'B',
                $akhir >= 55 => 'C+',
                $akhir >= 45 => 'C',
                $akhir >= 35 => 'D',
                default      => 'E',
            };
        });
    }
}