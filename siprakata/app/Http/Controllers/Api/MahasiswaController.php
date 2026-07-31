<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Mahasiswa::with('prodi');

        if ($request->has('search') && $request->search !== null) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('NIM', 'like', "%{$search}%");
            });
        }

        $mahasiswa = $query->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data mahasiswa berhasil diambil.',
            'data' => $mahasiswa,
        ], 200);
    }

    public function show($id)
    {
        $mahasiswa = Mahasiswa::with('prodi', 'krs.matakuliah')->find($id);

        if (!$mahasiswa) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mahasiswa tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data mahasiswa berhasil diambil.',
            'data' => $mahasiswa,
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'NIM' => 'required|string|unique:mahasiswas,NIM',
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'email' => 'required|email|unique:mahasiswas,email',
            'no_hp' => 'nullable|string|max:20',
            'jenis_kelamin' => 'nullable|in:L,P',
            'angkatan' => 'nullable|integer',
            'status' => 'nullable|string',
            'prodi_id' => 'required|exists:program_studis,id',
        ]);

        $mahasiswa = Mahasiswa::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Mahasiswa berhasil ditambahkan.',
            'data' => $mahasiswa->load('prodi'),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::find($id);

        if (!$mahasiswa) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mahasiswa tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $validated = $request->validate([
            'NIM' => 'sometimes|required|string|unique:mahasiswas,NIM,' . $id,
            'nama' => 'sometimes|required|string|max:255',
            'alamat' => 'nullable|string',
            'email' => 'sometimes|required|email|unique:mahasiswas,email,' . $id,
            'no_hp' => 'nullable|string|max:20',
            'jenis_kelamin' => 'nullable|in:L,P',
            'angkatan' => 'nullable|integer',
            'status' => 'nullable|string',
            'prodi_id' => 'sometimes|required|exists:program_studis,id',
        ]);

        $mahasiswa->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Mahasiswa berhasil diperbarui.',
            'data' => $mahasiswa->load('prodi'),
        ], 200);
    }

    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::find($id);

        if (!$mahasiswa) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mahasiswa tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $mahasiswa->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Mahasiswa berhasil dihapus.',
            'data' => null,
        ], 200);
    }
}
