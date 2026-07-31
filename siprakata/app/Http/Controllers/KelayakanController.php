<?php

namespace App\Http\Controllers;

use App\Models\Kelayakan;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\TransaksiKrs;
use App\Services\FuzzyLogicService;
use Illuminate\Http\Request;

class KelayakanController extends Controller
{
    public function index(Request $request)
    {
        $tahunAjaran = $request->get('tahun_ajaran', '2025/2026');
        $semester = $request->get('semester', 'Ganjil');

        $kelayakan = Kelayakan::with(['mahasiswa', 'matakuliah'])
            ->where('tahun_ajaran', $tahunAjaran)
            ->where('semester', $semester)
            ->get();

        return view('kelayakan.index', compact('kelayakan', 'tahunAjaran', 'semester'));
    }

    public function create()
    {
        $mahasiswa = Mahasiswa::where('status', 'aktif')->get();

        $matakuliahByMhs = [];
        foreach ($mahasiswa as $mhs) {
            $krsList = TransaksiKrs::where('mahasiswa_id', $mhs->id)
                ->where('status_validasi', 'disetujui')
                ->with('matakuliah')
                ->get();
            $matakuliahByMhs[$mhs->id] = $krsList->pluck('matakuliah')->filter();
        }

        return view('kelayakan.create', compact('mahasiswa', 'matakuliahByMhs'));
    }

    public function proses(Request $request)
    {
        $request->validate([
            'mahasiswa_id'   => 'required|exists:mahasiswa,id',
            'matakuliah_id'  => 'required|exists:matakuliah,id',
            'tahun_ajaran'   => 'required|string',
            'semester'       => 'required|string',
        ]);

        $mahasiswa = Mahasiswa::findOrFail($request->mahasiswa_id);
        $matakuliah = Matakuliah::findOrFail($request->matakuliah_id);

        $krs = TransaksiKrs::where('mahasiswa_id', $mahasiswa->id)
            ->where('matakuliah_id', $matakuliah->id)
            ->where('status_validasi', 'disetujui')
            ->first();

        if (!$krs) {
            return back()->withErrors(['matakuliah_id' => 'Mahasiswa belum mengambil mata kuliah ini atau KRS belum disetujui.'])->withInput();
        }

        $hasil = FuzzyLogicService::analisis($mahasiswa, $matakuliah);

        Kelayakan::updateOrCreate(
            [
                'mahasiswa_id'  => $mahasiswa->id,
                'matakuliah_id' => $matakuliah->id,
                'tahun_ajaran'  => $request->tahun_ajaran,
                'semester'      => $request->semester,
            ],
            [
                'kehadiran'            => $hasil['input']['kehadiran'],
                'nilai_tugas'          => $hasil['input']['nilai_tugas'],
                'keaktifan_diskusi'    => $hasil['input']['keaktifan_diskusi'],
                'skor_prediksi'        => $hasil['skor'],
                'hasil_prediksi'       => $hasil['hasil'],
                'detail_perhitungan'   => json_encode($hasil),
            ]
        );

        return redirect()->route('kelayakan.index', [
            'tahun_ajaran' => $request->tahun_ajaran,
            'semester'     => $request->semester,
        ])->with('success', 'Prediksi kelulusan ' . $mahasiswa->nama . ' pada mata kuliah ' . $matakuliah->nama_mk . ' berhasil dihitung.');
    }

    public function batchProses(Request $request)
    {
        $request->validate([
            'tahun_ajaran'  => 'required|string',
            'semester'      => 'required|string',
        ]);

        $mahasiswaList = Mahasiswa::where('status', 'aktif')->get();
        $count = 0;

        foreach ($mahasiswaList as $mhs) {
            $krsList = TransaksiKrs::where('mahasiswa_id', $mhs->id)
                ->where('tahun_ajaran', $request->tahun_ajaran)
                ->where('semester', $request->semester)
                ->where('status_validasi', 'disetujui')
                ->get();

            foreach ($krsList as $krs) {
                $matakuliah = $krs->matakuliah;
                if (!$matakuliah) continue;

                $hasil = FuzzyLogicService::analisis($mhs, $matakuliah);

                Kelayakan::updateOrCreate(
                    [
                        'mahasiswa_id'  => $mhs->id,
                        'matakuliah_id' => $matakuliah->id,
                        'tahun_ajaran'  => $request->tahun_ajaran,
                        'semester'      => $request->semester,
                    ],
                    [
                        'kehadiran'            => $hasil['input']['kehadiran'],
                        'nilai_tugas'          => $hasil['input']['nilai_tugas'],
                        'keaktifan_diskusi'    => $hasil['input']['keaktifan_diskusi'],
                        'skor_prediksi'        => $hasil['skor'],
                        'hasil_prediksi'       => $hasil['hasil'],
                        'detail_perhitungan'   => json_encode($hasil),
                    ]
                );
                $count++;
            }
        }

        return redirect()->route('kelayakan.index', [
            'tahun_ajaran' => $request->tahun_ajaran,
            'semester'     => $request->semester,
        ])->with('success', "Berhasil menganalisis {$count} kombinasi mahasiswa × mata kuliah secara batch.");
    }

    public function getMatakuliahByMahasiswa(Request $request)
    {
        $krsList = TransaksiKrs::where('mahasiswa_id', $request->mahasiswa_id)
            ->where('status_validasi', 'disetujui')
            ->with('matakuliah')
            ->get();

        $matakuliah = $krsList->pluck('matakuliah')->filter()->values();
        return response()->json($matakuliah);
    }

    public function detail(Kelayakan $kelayakan)
    {
        $kelayakan->load(['mahasiswa', 'matakuliah']);
        $detail = json_decode($kelayakan->detail_perhitungan, true);
        return view('kelayakan.detail', compact('kelayakan', 'detail'));
    }

}
