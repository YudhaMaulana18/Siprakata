<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function index(Request $request)
    {
        $query = Dosen::with('prodi');

        if ($request->has('search') && $request->search !== null) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('NIDN', 'like', "%{$search}%");
            });
        }

        $dosen = $query->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data dosen berhasil diambil.',
            'data' => $dosen,
        ], 200);
    }

    public function show($id)
    {
        $dosen = Dosen::with('prodi', 'jadwal.matakuliah')->find($id);

        if (!$dosen) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dosen tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data dosen berhasil diambil.',
            'data' => $dosen,
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'NIDN' => 'required|string|unique:dosens,NIDN',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:dosens,email',
            'no_hp' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:255',
            'prodi_id' => 'required|exists:program_studis,id',
        ]);

        $dosen = Dosen::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Dosen berhasil ditambahkan.',
            'data' => $dosen->load('prodi'),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $dosen = Dosen::find($id);

        if (!$dosen) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dosen tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $validated = $request->validate([
            'NIDN' => 'sometimes|required|string|unique:dosens,NIDN,' . $id,
            'nama' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:dosens,email,' . $id,
            'no_hp' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:255',
            'prodi_id' => 'sometimes|required|exists:program_studis,id',
        ]);

        $dosen->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Dosen berhasil diperbarui.',
            'data' => $dosen->load('prodi'),
        ], 200);
    }

    public function destroy($id)
    {
        $dosen = Dosen::find($id);

        if (!$dosen) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dosen tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $dosen->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Dosen berhasil dihapus.',
            'data' => null,
        ], 200);
    }
}
