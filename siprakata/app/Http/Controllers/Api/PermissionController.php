<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\JsonResponse;

class PermissionController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $permissions = Permission::all()->groupBy('module');

            return response()->json([
                'status' => 'success',
                'message' => 'Data permission berhasil diambil',
                'data' => $permissions,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data permission',
                'data' => null,
            ], 500);
        }
    }
}
