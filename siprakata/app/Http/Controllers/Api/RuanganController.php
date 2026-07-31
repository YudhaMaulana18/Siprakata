<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function index(Request $request)
    {
        $query = Ruangan::query();

        if ($request->has('search') && $request->search !== null) {
            $search = $request->search;
            $query->where('nama_ruangan', 'like', "%{$search}%");
        }

        $ruangan = $query->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data ruangan berhasil diambil.',
            'data' => $ruangan,
        ], 200);
    }

    public function show($id)
    {
        $ruangan = Ruangan::withCount('jadwal')->find($id);

        if (!$ruangan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ruangan tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data ruangan berhasil diambil.',
            'data' => $ruangan,
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_ruangan' => 'required|string|unique:ruangans,kode_ruangan',
            'nama_ruangan' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
            'gedung' => 'nullable|string|max:255',
            'lantai' => 'nullable|integer',
            'jenis' => 'nullable|string|max:100',
        ]);

        $ruangan = Ruangan::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Ruangan berhasil ditambahkan.',
            'data' => $ruangan,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $ruangan = Ruangan::find($id);

        if (!$ruangan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ruangan tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $validated = $request->validate([
            'kode_ruangan' => 'sometimes|required|string|unique:ruangans,kode_ruangan,' . $id,
            'nama_ruangan' => 'sometimes|required|string|max:255',
            'kapasitas' => 'sometimes|required|integer|min:1',
            'gedung' => 'nullable|string|max:255',
            'lantai' => 'nullable|integer',
            'jenis' => 'nullable|string|max:100',
        ]);

        $ruangan->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Ruangan berhasil diperbarui.',
            'data' => $ruangan,
        ], 200);
    }

    public function destroy($id)
    {
        $ruangan = Ruangan::find($id);

        if (!$ruangan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ruangan tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $ruangan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Ruangan berhasil dihapus.',
            'data' => null,
        ], 200);
    }
}
