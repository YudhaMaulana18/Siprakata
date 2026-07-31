<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\TransaksiKrs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $data = Nilai::with(['krs.mahasiswa', 'krs.matakuliah'])->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Data nilai berhasil diambil',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data nilai: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $data = Nilai::with(['krs.mahasiswa', 'krs.matakuliah'])->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Data nilai berhasil diambil',
                'data' => $data,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data nilai tidak ditemukan',
                'data' => null,
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data nilai: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'krs_id' => 'required|exists:transaksi_krs,id|unique:nilai,krs_id',
            'nilai_tugas' => 'required|numeric|min:0|max:100',
            'nilai_uts' => 'required|numeric|min:0|max:100',
            'nilai_uas' => 'required|numeric|min:0|max:100',
        ]);

        $krs = TransaksiKrs::find($validated['krs_id']);
        if ($krs->status_validasi !== 'disetujui') {
            return response()->json([
                'status' => 'error',
                'message' => 'KRS harus disetujui terlebih dahulu sebelum input nilai',
                'data' => null,
            ], 422);
        }

        $data = Nilai::create($validated);
        $data->load(['krs.mahasiswa', 'krs.matakuliah']);

        return response()->json([
            'status' => 'success',
            'message' => 'Data nilai berhasil dibuat',
            'data' => $data,
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $nilai = Nilai::findOrFail($id);

            $validated = $request->validate([
                'krs_id' => 'sometimes|required|exists:transaksi_krs,id',
                'nilai_tugas' => 'sometimes|required|numeric|min:0|max:100',
                'nilai_uts' => 'sometimes|required|numeric|min:0|max:100',
                'nilai_uas' => 'sometimes|required|numeric|min:0|max:100',
            ]);

            $nilai->update($validated);
            $nilai->load(['krs.mahasiswa', 'krs.matakuliah']);

            return response()->json([
                'status' => 'success',
                'message' => 'Data nilai berhasil diupdate',
                'data' => $nilai,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data nilai tidak ditemukan',
                'data' => null,
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengupdate data nilai: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $nilai = Nilai::findOrFail($id);
            $nilai->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data nilai berhasil dihapus',
                'data' => null,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data nilai tidak ditemukan',
                'data' => null,
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data nilai: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }
}
