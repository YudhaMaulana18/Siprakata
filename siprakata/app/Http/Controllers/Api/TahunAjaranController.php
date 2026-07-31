<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjaran = TahunAjaran::orderBy('tahun', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data tahun ajaran berhasil diambil.',
            'data' => $tahunAjaran,
        ], 200);
    }

    public function show($id)
    {
        $tahunAjaran = TahunAjaran::withCount('jadwal')->find($id);

        if (!$tahunAjaran) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tahun ajaran tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data tahun ajaran berhasil diambil.',
            'data' => $tahunAjaran,
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun' => 'required|string|max:20',
            'semester' => 'required|in:Ganjil,Genap',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'status_aktif' => 'nullable|boolean',
        ]);

        $tahunAjaran = TahunAjaran::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Tahun ajaran berhasil ditambahkan.',
            'data' => $tahunAjaran,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $tahunAjaran = TahunAjaran::find($id);

        if (!$tahunAjaran) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tahun ajaran tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $validated = $request->validate([
            'tahun' => 'sometimes|required|string|max:20',
            'semester' => 'sometimes|required|in:Ganjil,Genap',
            'tgl_mulai' => 'sometimes|required|date',
            'tgl_selesai' => 'sometimes|required|date|after_or_equal:tgl_mulai',
            'status_aktif' => 'nullable|boolean',
        ]);

        $tahunAjaran->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Tahun ajaran berhasil diperbarui.',
            'data' => $tahunAjaran,
        ], 200);
    }

    public function destroy($id)
    {
        $tahunAjaran = TahunAjaran::find($id);

        if (!$tahunAjaran) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tahun ajaran tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $tahunAjaran->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Tahun ajaran berhasil dihapus.',
            'data' => null,
        ], 200);
    }
}
