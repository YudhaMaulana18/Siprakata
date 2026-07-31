<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Pengumuman::with(['dosen', 'jadwal.matakuliah'])->orderBy('tgl_posting', 'desc');

            if ($request->has('prioritas') && $request->prioritas) {
                $query->where('prioritas', $request->prioritas);
            }

            $data = $query->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Data pengumuman berhasil diambil',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data pengumuman: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $data = Pengumuman::with(['dosen', 'jadwal.matakuliah'])->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Data pengumuman berhasil diambil',
                'data' => $data,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data pengumuman tidak ditemukan',
                'data' => null,
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data pengumuman: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'dosen_id' => 'required|exists:dosen,id',
            'jadwal_id' => 'nullable|exists:jadwal_kuliah,id',
            'judul' => 'required|string',
            'isi' => 'required|string',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
            'tgl_posting' => 'required|date',
            'tgl_kadaluarsa' => 'nullable|date|after:tgl_posting',
        ]);

        $data = Pengumuman::create($validated);
        $data->load(['dosen', 'jadwal.matakuliah']);

        return response()->json([
            'status' => 'success',
            'message' => 'Data pengumuman berhasil dibuat',
            'data' => $data,
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $pengumuman = Pengumuman::findOrFail($id);

            $validated = $request->validate([
                'dosen_id' => 'sometimes|required|exists:dosen,id',
                'jadwal_id' => 'sometimes|nullable|exists:jadwal_kuliah,id',
                'judul' => 'sometimes|required|string',
                'isi' => 'sometimes|required|string',
                'prioritas' => 'sometimes|required|in:rendah,sedang,tinggi',
                'tgl_posting' => 'sometimes|required|date',
                'tgl_kadaluarsa' => 'sometimes|nullable|date|after:tgl_posting',
            ]);

            $pengumuman->update($validated);
            $pengumuman->load(['dosen', 'jadwal.matakuliah']);

            return response()->json([
                'status' => 'success',
                'message' => 'Data pengumuman berhasil diupdate',
                'data' => $pengumuman,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data pengumuman tidak ditemukan',
                'data' => null,
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengupdate data pengumuman: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $pengumuman = Pengumuman::findOrFail($id);
            $pengumuman->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data pengumuman berhasil dihapus',
                'data' => null,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data pengumuman tidak ditemukan',
                'data' => null,
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data pengumuman: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }
}
