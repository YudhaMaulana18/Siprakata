<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MahasiswaController;
use App\Http\Controllers\Api\DosenController;
use App\Http\Controllers\Api\MatakuliahController;
use App\Http\Controllers\Api\ProgramStudiController;
use App\Http\Controllers\Api\TahunAjaranController;
use App\Http\Controllers\Api\RuanganController;
use App\Http\Controllers\Api\TransaksiKrsController;
use App\Http\Controllers\Api\JadwalKuliahController;
use App\Http\Controllers\Api\PresensiController;
use App\Http\Controllers\Api\NilaiController;
use App\Http\Controllers\Api\MateriKuliahController;
use App\Http\Controllers\Api\PengumumanController;
use App\Http\Controllers\Api\KelayakanController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\UserRoleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Siprakata Academic System RESTful API
|
*/

// Public routes
Route::post('login', [AuthController::class, 'login']);

// Authenticated routes
Route::middleware(['api.auth'])->group(function () {

    // Auth
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    // Read-only resources for all authenticated users
    Route::apiResource('mahasiswa', MahasiswaController::class)->only(['index', 'show']);
    Route::apiResource('dosen', DosenController::class)->only(['index', 'show']);
    Route::apiResource('matakuliah', MatakuliahController::class)->only(['index', 'show']);
    Route::apiResource('prodi', ProgramStudiController::class)->only(['index', 'show']);
    Route::apiResource('ruangan', RuanganController::class)->only(['index', 'show']);
    Route::apiResource('tahun-ajaran', TahunAjaranController::class)->only(['index', 'show']);

    // CRUD for KRS (all authenticated users can create, admin can update/delete)
    Route::apiResource('krs', TransaksiKrsController::class)->only(['index', 'show', 'store']);

    // CRUD for Jadwal Kuliah (all authenticated users can create)
    Route::apiResource('jadwal', JadwalKuliahController::class)->only(['index', 'show', 'store']);

    // Read-only for presensi, materi, nilai, pengumuman
    Route::apiResource('presensi', PresensiController::class)->only(['index', 'show']);
    Route::apiResource('materi', MateriKuliahController::class)->only(['index', 'show']);
    Route::apiResource('nilai', NilaiController::class)->only(['index', 'show']);
    Route::apiResource('pengumuman', PengumumanController::class)->only(['index', 'show']);

    // Kelayakan (fuzzy logic) endpoints
    Route::prefix('kelayakan')->group(function () {
        Route::get('/', [KelayakanController::class, 'index']);
        Route::get('create', [KelayakanController::class, 'create']);
        Route::post('proses', [KelayakanController::class, 'proses']);
        Route::post('batch', [KelayakanController::class, 'batchProses']);
        Route::get('{id}', [KelayakanController::class, 'detail']);
        Route::get('mahasiswa/{mahasiswaId}/matakuliah', [KelayakanController::class, 'getMatakuliahByMahasiswa']);
    });

    // Admin + Dosen routes
    Route::middleware(['api.role:admin,dosen'])->group(function () {

        // KRS validation
        Route::get('krs/{id}/validasi', [TransaksiKrsController::class, 'validasi']);
        Route::put('krs/{id}/proses-validasi', [TransaksiKrsController::class, 'prosesValidasi']);

        // Write operations for presensi, materi, nilai, pengumuman (admin+dosen)
        Route::post('presensi', [PresensiController::class, 'store']);
        Route::put('presensi/{id}', [PresensiController::class, 'update']);
        Route::delete('presensi/{id}', [PresensiController::class, 'destroy']);

        Route::post('materi', [MateriKuliahController::class, 'store']);
        Route::put('materi/{id}', [MateriKuliahController::class, 'update']);
        Route::delete('materi/{id}', [MateriKuliahController::class, 'destroy']);

        Route::post('nilai', [NilaiController::class, 'store']);
        Route::put('nilai/{id}', [NilaiController::class, 'update']);
        Route::delete('nilai/{id}', [NilaiController::class, 'destroy']);

        Route::post('pengumuman', [PengumumanController::class, 'store']);
        Route::put('pengumuman/{id}', [PengumumanController::class, 'update']);
        Route::delete('pengumuman/{id}', [PengumumanController::class, 'destroy']);
    });

    // Admin only routes
    Route::middleware(['api.role:admin'])->group(function () {

        // CRUD for master data (admin only)
        Route::post('mahasiswa', [MahasiswaController::class, 'store']);
        Route::put('mahasiswa/{id}', [MahasiswaController::class, 'update']);
        Route::delete('mahasiswa/{id}', [MahasiswaController::class, 'destroy']);

        Route::post('dosen', [DosenController::class, 'store']);
        Route::put('dosen/{id}', [DosenController::class, 'update']);
        Route::delete('dosen/{id}', [DosenController::class, 'destroy']);

        Route::post('matakuliah', [MatakuliahController::class, 'store']);
        Route::put('matakuliah/{id}', [MatakuliahController::class, 'update']);
        Route::delete('matakuliah/{id}', [MatakuliahController::class, 'destroy']);

        Route::post('prodi', [ProgramStudiController::class, 'store']);
        Route::put('prodi/{id}', [ProgramStudiController::class, 'update']);
        Route::delete('prodi/{id}', [ProgramStudiController::class, 'destroy']);

        Route::post('ruangan', [RuanganController::class, 'store']);
        Route::put('ruangan/{id}', [RuanganController::class, 'update']);
        Route::delete('ruangan/{id}', [RuanganController::class, 'destroy']);

        Route::post('tahun-ajaran', [TahunAjaranController::class, 'store']);
        Route::put('tahun-ajaran/{id}', [TahunAjaranController::class, 'update']);
        Route::delete('tahun-ajaran/{id}', [TahunAjaranController::class, 'destroy']);

        // Admin can update/delete KRS and Jadwal
        Route::put('krs/{id}', [TransaksiKrsController::class, 'update']);
        Route::delete('krs/{id}', [TransaksiKrsController::class, 'destroy']);

        Route::put('jadwal/{id}', [JadwalKuliahController::class, 'update']);
        Route::delete('jadwal/{id}', [JadwalKuliahController::class, 'destroy']);

        // Full CRUD for roles
        Route::apiResource('roles', RoleController::class);

        // Permissions (index only)
        Route::apiResource('permissions', PermissionController::class)->only(['index']);

        // Full CRUD for user-roles
        Route::apiResource('user-roles', UserRoleController::class);
    });
});
