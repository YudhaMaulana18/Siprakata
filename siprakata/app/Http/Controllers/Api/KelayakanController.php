<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kelayakan;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\TransaksiKrs;
use App\Services\FuzzyLogicService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KelayakanController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $tahunAjaran = $request->query('tahun_ajaran', '2025/2026');
            $semester = $request->query('semester', 'Ganjil');

            $kelayakans = Kelayakan::with(['mahasiswa', 'matakuliah'])
                ->where('tahun_ajaran', $tahunAjaran)
                ->where('semester', $semester)
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Data kelayakan berhasil diambil',
                'data' => $kelayakans,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data kelayakan',
                'data' => null,
            ], 500);
        }
    }

    public function create(): JsonResponse
    {
        try {
            $mahasiswas = Mahasiswa::where('status', 'aktif')->get();

            $matakuliahByMahasiswa = [];
            foreach ($mahasiswas as $mhs) {
                $krsList = TransaksiKrs::where('mahasiswa_id', $mhs->id)
                    ->where('status_validasi', 'disetujui')
                    ->with('matakuliah')
                    ->get();
                $matakuliahByMahasiswa[$mhs->id] = $krsList->pluck('matakuliah')->filter()->values();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data form kelayakan berhasil diambil',
                'data' => [
                    'mahasiswa' => $mahasiswas,
                    'matakuliah_by_mahasiswa' => $matakuliahByMahasiswa,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data form kelayakan',
                'data' => null,
            ], 500);
        }
    }

    public function proses(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'mahasiswa_id'   => 'required|exists:mahasiswa,id',
                'matakuliah_id'  => 'required|exists:matakuliah,id',
                'tahun_ajaran'   => 'required|string',
                'semester'       => 'required|string',
            ]);

            $mahasiswa = Mahasiswa::findOrFail($validated['mahasiswa_id']);
            $matakuliah = Matakuliah::findOrFail($validated['matakuliah_id']);

            $krs = TransaksiKrs::where('mahasiswa_id', $mahasiswa->id)
                ->where('matakuliah_id', $matakuliah->id)
                ->where('status_validasi', 'disetujui')
                ->first();

            if (!$krs) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'KRS tidak ditemukan atau belum disetujui',
                    'data' => null,
                ], 404);
            }

            $hasil = FuzzyLogicService::analisis($mahasiswa, $matakuliah);

            $kelayakan = Kelayakan::updateOrCreate(
                [
                    'mahasiswa_id'  => $mahasiswa->id,
                    'matakuliah_id' => $matakuliah->id,
                    'tahun_ajaran'  => $validated['tahun_ajaran'],
                    'semester'      => $validated['semester'],
                ],
                [
                    'kehadiran'          => $hasil['input']['kehadiran'],
                    'nilai_tugas'        => $hasil['input']['nilai_tugas'],
                    'keaktifan_diskusi'  => $hasil['input']['keaktifan_diskusi'],
                    'skor_prediksi'      => $hasil['skor'],
                    'hasil_prediksi'     => $hasil['hasil'],
                    'detail_perhitungan' => json_encode($hasil),
                ]
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Analisis kelayakan berhasil diproses',
                'data' => $kelayakan,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'data' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memproses analisis kelayakan: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function batchProses(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'tahun_ajaran'  => 'required|string',
                'semester'      => 'required|string',
            ]);

            $mahasiswas = Mahasiswa::where('status', 'aktif')->get();
            $count = 0;

            foreach ($mahasiswas as $mhs) {
                $krsList = TransaksiKrs::where('mahasiswa_id', $mhs->id)
                    ->where('tahun_ajaran', $validated['tahun_ajaran'])
                    ->where('semester', $validated['semester'])
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
                            'tahun_ajaran'  => $validated['tahun_ajaran'],
                            'semester'      => $validated['semester'],
                        ],
                        [
                            'kehadiran'          => $hasil['input']['kehadiran'],
                            'nilai_tugas'        => $hasil['input']['nilai_tugas'],
                            'keaktifan_diskusi'  => $hasil['input']['keaktifan_diskusi'],
                            'skor_prediksi'      => $hasil['skor'],
                            'hasil_prediksi'     => $hasil['hasil'],
                            'detail_perhitungan' => json_encode($hasil),
                        ]
                    );
                    $count++;
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => "Batch proses selesai. Total {$count} data diproses",
                'data' => ['count' => $count],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'data' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memproses batch kelayakan: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function detail($id): JsonResponse
    {
        try {
            $kelayakan = Kelayakan::with(['mahasiswa', 'matakuliah'])->findOrFail($id);

            $data = $kelayakan->toArray();
            $data['detail_perhitungan'] = json_decode($kelayakan->detail_perhitungan, true);

            return response()->json([
                'status' => 'success',
                'message' => 'Detail kelayakan berhasil diambil',
                'data' => $data,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data kelayakan tidak ditemukan',
                'data' => null,
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil detail kelayakan',
                'data' => null,
            ], 500);
        }
    }

    public function getMatakuliahByMahasiswa(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'mahasiswa_id' => 'required|exists:mahasiswa,id',
            ]);

            $krsList = TransaksiKrs::where('mahasiswa_id', $request->mahasiswa_id)
                ->where('status_validasi', 'disetujui')
                ->with('matakuliah')
                ->get();

            $matakuliah = $krsList->pluck('matakuliah')->filter()->values();

            return response()->json([
                'status' => 'success',
                'message' => 'Matakuliah berhasil diambil',
                'data' => $matakuliah,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'data' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data matakuliah',
                'data' => null,
            ], 500);
        }
    }
}
