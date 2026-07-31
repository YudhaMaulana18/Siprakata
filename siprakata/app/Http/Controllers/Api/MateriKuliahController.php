<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MateriKuliah;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriKuliahController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $query = MateriKuliah::with(['jadwal.matakuliah', 'jadwal.dosen']);

            if ($request->has('jadwal_id') && $request->jadwal_id) {
                $query->where('jadwal_id', $request->jadwal_id);
            }

            $data = $query->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Data materi kuliah berhasil diambil',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data materi kuliah: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $data = MateriKuliah::with(['jadwal.matakuliah', 'jadwal.dosen'])->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Data materi kuliah berhasil diambil',
                'data' => $data,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data materi kuliah tidak ditemukan',
                'data' => null,
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data materi kuliah: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'jadwal_id' => 'required|exists:jadwal_kuliah,id',
            'pertemuan_ke' => 'required|integer|min:1|max:16',
            'judul' => 'required|string',
            'deskripsi' => 'nullable|string',
            'link_materi' => 'nullable|url',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('materi', 'materi');
            $validated['file_path'] = $path;
        }

        unset($validated['file']);

        $data = MateriKuliah::create($validated);
        $data->load(['jadwal.matakuliah', 'jadwal.dosen']);

        return response()->json([
            'status' => 'success',
            'message' => 'Data materi kuliah berhasil dibuat',
            'data' => $data,
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $materi = MateriKuliah::findOrFail($id);

            $validated = $request->validate([
                'jadwal_id' => 'sometimes|required|exists:jadwal_kuliah,id',
                'pertemuan_ke' => 'sometimes|required|integer|min:1|max:16',
                'judul' => 'sometimes|required|string',
                'deskripsi' => 'sometimes|nullable|string',
                'link_materi' => 'sometimes|nullable|url',
                'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
            ]);

            if ($request->hasFile('file')) {
                if ($materi->file_path) {
                    Storage::disk('materi')->delete($materi->file_path);
                }

                $file = $request->file('file');
                $path = $file->store('materi', 'materi');
                $validated['file_path'] = $path;
            }

            unset($validated['file']);

            $materi->update($validated);
            $materi->load(['jadwal.matakuliah', 'jadwal.dosen']);

            return response()->json([
                'status' => 'success',
                'message' => 'Data materi kuliah berhasil diupdate',
                'data' => $materi,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data materi kuliah tidak ditemukan',
                'data' => null,
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengupdate data materi kuliah: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $materi = MateriKuliah::findOrFail($id);

            if ($materi->file_path) {
                Storage::disk('materi')->delete($materi->file_path);
            }

            $materi->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data materi kuliah berhasil dihapus',
                'data' => null,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data materi kuliah tidak ditemukan',
                'data' => null,
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data materi kuliah: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }
}
