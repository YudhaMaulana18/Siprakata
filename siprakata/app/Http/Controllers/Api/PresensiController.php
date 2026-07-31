<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Presensi::with(['jadwal.matakuliah', 'mahasiswa']);

            if ($request->has('jadwal_id') && $request->jadwal_id) {
                $query->where('jadwal_id', $request->jadwal_id);
            }

            if ($request->has('mahasiswa_id') && $request->mahasiswa_id) {
                $query->where('mahasiswa_id', $request->mahasiswa_id);
            }

            $data = $query->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Data presensi berhasil diambil',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data presensi: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $data = Presensi::with(['jadwal.matakuliah', 'mahasiswa'])->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Data presensi berhasil diambil',
                'data' => $data,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data presensi tidak ditemukan',
                'data' => null,
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data presensi: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'jadwal_id' => 'required|exists:jadwal_kuliah,id',
            'mahasiswa_id' => 'required|exists:mahasiswa,id',
            'tanggal' => 'required|date',
            'pertemuan_ke' => 'required|integer|min:1|max:16',
            'status_hadir' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable|string',
        ]);

        $exists = Presensi::where('jadwal_id', $validated['jadwal_id'])
            ->where('mahasiswa_id', $validated['mahasiswa_id'])
            ->where('pertemuan_ke', $validated['pertemuan_ke'])
            ->first();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data presensi sudah ada untuk jadwal, mahasiswa, dan pertemuan ini',
                'data' => null,
            ], 422);
        }

        $data = Presensi::create($validated);
        $data->load(['jadwal.matakuliah', 'mahasiswa']);

        return response()->json([
            'status' => 'success',
            'message' => 'Data presensi berhasil dibuat',
            'data' => $data,
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $presensi = Presensi::findOrFail($id);

            $validated = $request->validate([
                'jadwal_id' => 'sometimes|required|exists:jadwal_kuliah,id',
                'mahasiswa_id' => 'sometimes|required|exists:mahasiswa,id',
                'tanggal' => 'sometimes|required|date',
                'pertemuan_ke' => 'sometimes|required|integer|min:1|max:16',
                'status_hadir' => 'sometimes|required|in:hadir,izin,sakit,alpha',
                'keterangan' => 'sometimes|nullable|string',
            ]);

            $presensi->update($validated);
            $presensi->load(['jadwal.matakuliah', 'mahasiswa']);

            return response()->json([
                'status' => 'success',
                'message' => 'Data presensi berhasil diupdate',
                'data' => $presensi,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data presensi tidak ditemukan',
                'data' => null,
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengupdate data presensi: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $presensi = Presensi::findOrFail($id);
            $presensi->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data presensi berhasil dihapus',
                'data' => null,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data presensi tidak ditemukan',
                'data' => null,
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data presensi: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }
}
