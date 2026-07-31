<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JadwalKuliah;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JadwalKuliahController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $query = JadwalKuliah::with(['matakuliah', 'dosen', 'ruanganRef', 'tahunAjaran']);

            if ($request->has('hari') && $request->hari) {
                $query->where('hari', $request->hari);
            }

            if ($request->has('matakuliah_id') && $request->matakuliah_id) {
                $ids = is_array($request->matakuliah_id) ? $request->matakuliah_id : explode(',', $request->matakuliah_id);
                $query->whereIn('matakuliah_id', $ids);
            }

            $data = $query->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Data jadwal kuliah berhasil diambil',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data jadwal kuliah: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $data = JadwalKuliah::with(['matakuliah', 'dosen', 'ruanganRef', 'tahunAjaran', 'presensi', 'materi', 'pengumuman'])
                ->withCount('presensi')
                ->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Data jadwal kuliah berhasil diambil',
                'data' => $data,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data jadwal kuliah tidak ditemukan',
                'data' => null,
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data jadwal kuliah: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'matakuliah_id' => 'required|exists:matakuliah,id',
            'dosen_id' => 'required|exists:dosen,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required|string',
            'jam_selesai' => 'required|string|after:jam_mulai',
            'ruangan_id' => 'nullable|exists:ruangan,id',
            'tahun_ajaran_id' => 'nullable|exists:tahun_ajaran,id',
        ]);

        if (isset($validated['ruangan_id']) && $validated['ruangan_id']) {
            $ruangan = \App\Models\Ruangan::find($validated['ruangan_id']);
            $validated['ruangan'] = $ruangan ? $ruangan->nama_ruangan : null;
        }

        if (isset($validated['tahun_ajaran_id']) && $validated['tahun_ajaran_id']) {
            $tahunAjaran = \App\Models\TahunAjaran::find($validated['tahun_ajaran_id']);
            if ($tahunAjaran) {
                $validated['tahun_ajaran'] = $tahunAjaran->tahun;
                $validated['semester'] = $tahunAjaran->semester;
            }
        }

        $data = JadwalKuliah::create($validated);
        $data->load(['matakuliah', 'dosen', 'ruanganRef', 'tahunAjaran']);

        return response()->json([
            'status' => 'success',
            'message' => 'Data jadwal kuliah berhasil dibuat',
            'data' => $data,
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $jadwal = JadwalKuliah::findOrFail($id);

            $validated = $request->validate([
                'matakuliah_id' => 'sometimes|required|exists:matakuliah,id',
                'dosen_id' => 'sometimes|required|exists:dosen,id',
                'hari' => 'sometimes|required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
                'jam_mulai' => 'sometimes|required|string',
                'jam_selesai' => 'sometimes|required|string|after:jam_mulai',
                'ruangan_id' => 'sometimes|nullable|exists:ruangan,id',
                'tahun_ajaran_id' => 'sometimes|nullable|exists:tahun_ajaran,id',
            ]);

            if (isset($validated['ruangan_id']) && $validated['ruangan_id']) {
                $ruangan = \App\Models\Ruangan::find($validated['ruangan_id']);
                $validated['ruangan'] = $ruangan ? $ruangan->nama_ruangan : null;
            }

            if (isset($validated['tahun_ajaran_id']) && $validated['tahun_ajaran_id']) {
                $tahunAjaran = \App\Models\TahunAjaran::find($validated['tahun_ajaran_id']);
                if ($tahunAjaran) {
                    $validated['tahun_ajaran'] = $tahunAjaran->tahun;
                    $validated['semester'] = $tahunAjaran->semester;
                }
            }

            $jadwal->update($validated);
            $jadwal->load(['matakuliah', 'dosen', 'ruanganRef', 'tahunAjaran']);

            return response()->json([
                'status' => 'success',
                'message' => 'Data jadwal kuliah berhasil diupdate',
                'data' => $jadwal,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data jadwal kuliah tidak ditemukan',
                'data' => null,
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengupdate data jadwal kuliah: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $jadwal = JadwalKuliah::findOrFail($id);
            $jadwal->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data jadwal kuliah berhasil dihapus',
                'data' => null,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data jadwal kuliah tidak ditemukan',
                'data' => null,
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data jadwal kuliah: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }
}
