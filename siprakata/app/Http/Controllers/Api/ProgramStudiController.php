<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class ProgramStudiController extends Controller
{
    public function index(Request $request)
    {
        $query = ProgramStudi::query();

        if ($request->has('search') && $request->search !== null) {
            $search = $request->search;
            $query->where('nama_prodi', 'like', "%{$search}%");
        }

        $programStudi = $query->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data program studi berhasil diambil.',
            'data' => $programStudi,
        ], 200);
    }

    public function show($id)
    {
        $programStudi = ProgramStudi::withCount('mahasiswa', 'dosen', 'matakuliah')->find($id);

        if (!$programStudi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Program studi tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data program studi berhasil diambil.',
            'data' => $programStudi,
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_prodi' => 'required|string|max:255',
            'kode_prodi' => 'required|string|unique:program_studis,kode_prodi',
            'jenjang' => 'nullable|string|max:50',
            'fakultas' => 'nullable|string|max:255',
        ]);

        $programStudi = ProgramStudi::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Program studi berhasil ditambahkan.',
            'data' => $programStudi,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $programStudi = ProgramStudi::find($id);

        if (!$programStudi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Program studi tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $validated = $request->validate([
            'nama_prodi' => 'sometimes|required|string|max:255',
            'kode_prodi' => 'sometimes|required|string|unique:program_studis,kode_prodi,' . $id,
            'jenjang' => 'nullable|string|max:50',
            'fakultas' => 'nullable|string|max:255',
        ]);

        $programStudi->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Program studi berhasil diperbarui.',
            'data' => $programStudi,
        ], 200);
    }

    public function destroy($id)
    {
        $programStudi = ProgramStudi::find($id);

        if (!$programStudi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Program studi tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $programStudi->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Program studi berhasil dihapus.',
            'data' => null,
        ], 200);
    }
}
