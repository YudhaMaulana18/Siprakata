<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Matakuliah;
use Illuminate\Http\Request;

class MatakuliahController extends Controller
{
    public function index(Request $request)
    {
        $query = Matakuliah::with('prodi');

        if ($request->has('search') && $request->search !== null) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_mk', 'like', "%{$search}%")
                  ->orWhere('kode_mk', 'like', "%{$search}%");
            });
        }

        $matakuliah = $query->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data matakuliah berhasil diambil.',
            'data' => $matakuliah,
        ], 200);
    }

    public function show($id)
    {
        $matakuliah = Matakuliah::with('prodi', 'jadwal')->find($id);

        if (!$matakuliah) {
            return response()->json([
                'status' => 'error',
                'message' => 'Matakuliah tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data matakuliah berhasil diambil.',
            'data' => $matakuliah,
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_mk' => 'required|string|unique:matakuliahs,kode_mk',
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer|min:1',
            'semester' => 'nullable|integer',
            'prodi_id' => 'required|exists:program_studis,id',
        ]);

        $matakuliah = Matakuliah::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Matakuliah berhasil ditambahkan.',
            'data' => $matakuliah->load('prodi'),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $matakuliah = Matakuliah::find($id);

        if (!$matakuliah) {
            return response()->json([
                'status' => 'error',
                'message' => 'Matakuliah tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $validated = $request->validate([
            'kode_mk' => 'sometimes|required|string|unique:matakuliahs,kode_mk,' . $id,
            'nama_mk' => 'sometimes|required|string|max:255',
            'sks' => 'sometimes|required|integer|min:1',
            'semester' => 'nullable|integer',
            'prodi_id' => 'sometimes|required|exists:program_studis,id',
        ]);

        $matakuliah->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Matakuliah berhasil diperbarui.',
            'data' => $matakuliah->load('prodi'),
        ], 200);
    }

    public function destroy($id)
    {
        $matakuliah = Matakuliah::find($id);

        if (!$matakuliah) {
            return response()->json([
                'status' => 'error',
                'message' => 'Matakuliah tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $matakuliah->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Matakuliah berhasil dihapus.',
            'data' => null,
        ], 200);
    }
}
