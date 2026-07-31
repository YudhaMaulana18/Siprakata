<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TransaksiKrs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransaksiKrsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $query = TransaksiKrs::with(['mahasiswa', 'matakuliah', 'dosen']);

            if ($request->has('search') && $request->search) {
                $query->whereHas('mahasiswa', function ($q) use ($request) {
                    $q->where('nama', 'like', '%' . $request->search . '%');
                });
            }

            $data = $query->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Data KRS berhasil diambil',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data KRS: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $data = TransaksiKrs::with(['mahasiswa', 'matakuliah', 'dosen', 'nilai'])->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Data KRS berhasil diambil',
                'data' => $data,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data KRS tidak ditemukan',
                'data' => null,
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data KRS: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswa,id',
            'matakuliah_id' => 'required|exists:matakuliah,id',
            'dosen_id' => 'required|exists:dosen,id',
            'tahun_ajaran' => 'required|string',
            'semester' => 'required|string',
        ]);

        $exists = TransaksiKrs::where('mahasiswa_id', $validated['mahasiswa_id'])
            ->where('matakuliah_id', $validated['matakuliah_id'])
            ->where('tahun_ajaran', $validated['tahun_ajaran'])
            ->where('semester', $validated['semester'])
            ->first();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data KRS sudah ada untuk mahasiswa, matakuliah, tahun ajaran, dan semester yang sama',
                'data' => null,
            ], 422);
        }

        $validated['status'] = 'aktif';
        $validated['status_validasi'] = 'pending';

        $data = TransaksiKrs::create($validated);
        $data->load(['mahasiswa', 'matakuliah', 'dosen']);

        return response()->json([
            'status' => 'success',
            'message' => 'Data KRS berhasil dibuat',
            'data' => $data,
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $krs = TransaksiKrs::findOrFail($id);

            $validated = $request->validate([
                'mahasiswa_id' => 'sometimes|required|exists:mahasiswa,id',
                'matakuliah_id' => 'sometimes|required|exists:matakuliah,id',
                'dosen_id' => 'sometimes|required|exists:dosen,id',
                'tahun_ajaran' => 'sometimes|required|string',
                'semester' => 'sometimes|required|string',
                'status' => 'sometimes|string',
                'status_validasi' => 'sometimes|string',
                'catatan_validasi' => 'sometimes|nullable|string',
            ]);

            $krs->update($validated);
            $krs->load(['mahasiswa', 'matakuliah', 'dosen', 'nilai']);

            return response()->json([
                'status' => 'success',
                'message' => 'Data KRS berhasil diupdate',
                'data' => $krs,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data KRS tidak ditemukan',
                'data' => null,
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengupdate data KRS: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $krs = TransaksiKrs::findOrFail($id);
            $krs->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data KRS berhasil dihapus',
                'data' => null,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data KRS tidak ditemukan',
                'data' => null,
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data KRS: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function validasi($id): JsonResponse
    {
        try {
            $data = TransaksiKrs::with(['mahasiswa', 'matakuliah', 'dosen', 'nilai'])->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Data KRS untuk validasi berhasil diambil',
                'data' => $data,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data KRS tidak ditemukan',
                'data' => null,
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data KRS: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function prosesValidasi(Request $request, $id): JsonResponse
    {
        try {
            $krs = TransaksiKrs::findOrFail($id);

            $validated = $request->validate([
                'status_validasi' => 'required|in:disetujui,ditolak',
                'catatan_validasi' => 'nullable|string',
            ]);

            $validated['tgl_validasi'] = now();

            $krs->update($validated);
            $krs->load(['mahasiswa', 'matakuliah', 'dosen', 'nilai']);

            return response()->json([
                'status' => 'success',
                'message' => 'Validasi KRS berhasil diproses',
                'data' => $krs,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data KRS tidak ditemukan',
                'data' => null,
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal: ' . $e->getMessage(),
                'data' => null,
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memproses validasi KRS: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }
}
