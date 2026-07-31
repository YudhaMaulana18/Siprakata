<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Email atau password salah.',
                'data'    => null,
            ], 401);
        }

        if (!$user->isMahasiswa()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Aplikasi ini hanya untuk akun mahasiswa.',
                'data'    => null,
            ], 403);
        }

        $token = $user->createApiToken('mobile');

        return response()->json([
            'status'  => 'success',
            'message' => 'Login berhasil.',
            'data'    => [
                'token' => $token->token,
                'user'  => $user->load('role'),
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $token = $request->bearerToken();

        if ($token) {
            $request->user()->apiTokens()->where('token', $token)->delete();
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Logout berhasil.',
            'data'    => null,
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load('role', 'roles');

        return response()->json([
            'status'  => 'success',
            'message' => 'Data user ditemukan.',
            'data'    => $user,
        ]);
    }
}
