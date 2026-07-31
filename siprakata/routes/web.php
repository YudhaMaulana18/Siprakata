<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MatakuliahController;
use App\Http\Controllers\TransaksiKrsController;
use App\Http\Controllers\JadwalKuliahController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\ProgramStudiController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\MateriKuliahController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\KelayakanController;
use App\Http\Controllers\MahasiswaPortalController;

// ── Landing page (publik) ─────────────────────────────────────────────────────
Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->isMahasiswa()) return redirect()->route('mhs.dashboard');
        return view('dashboard');
    }
    return view('landing');
})->name('home');

Route::get('/landing', function () {
    return view('landing');
});

// ── Auth (publik) ─────────────────────────────────────────────────────────────
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── Semua route wajib login ───────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // ── Dashboard (auth required) ───────────────────────────────────────────
    Route::get('/dashboard', function () {
        if (Auth::user()->isMahasiswa()) return redirect()->route('mhs.dashboard');
        return view('dashboard');
    })->name('dashboard');

    // ── Master Data (admin only) ──────────────────────────────────────────────
    Route::middleware('role:admin')->group(function () {
        Route::get('create-mahasiswa', [MahasiswaController::class, 'create'])->name('create-mahasiswa');
        Route::post('simpan-mahasiswa', [MahasiswaController::class, 'store'])->name('store-mahasiswa');
        Route::get('edit-mahasiswa/{id}', [MahasiswaController::class, 'edit'])->name('edit-mahasiswa');
        Route::match(['POST','PUT'], 'update-mahasiswa/{id}', [MahasiswaController::class, 'update'])->name('update-mahasiswa');
        Route::delete('hapus-mahasiswa/{id}', [MahasiswaController::class, 'destroy'])->name('hapus-mahasiswa');

        Route::resource('dosen', DosenController::class)->except('index','show');
        Route::resource('matakuliah', MatakuliahController::class)->except('index','show');
        Route::resource('prodi', ProgramStudiController::class)->except('index','show');
        Route::resource('ruangan', RuanganController::class)->except('index','show');
        Route::resource('tahun_ajaran', TahunAjaranController::class)->except('index','show');

        // Hak Akses - admin only
        Route::resource('roles', RoleController::class);
        Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
       Route::resource('user-roles', UserRoleController::class)->parameters(['user-roles' => 'user'])->only('index','create','store','edit','update','destroy');
    });

    // View master data
    Route::get('data-mahasiswa', [MahasiswaController::class, 'index'])->name('data-mahasiswa');
    Route::resource('dosen', DosenController::class)->only('index','show');
    Route::resource('matakuliah', MatakuliahController::class)->only('index','show');
    Route::resource('prodi', ProgramStudiController::class)->only('index','show');
    Route::resource('ruangan', RuanganController::class)->only('index','show');
    Route::resource('tahun_ajaran', TahunAjaranController::class)->only('index','show');

    // ── Transaksi KRS ────────────────────────────────────────────────────────
    Route::resource('krs', TransaksiKrsController::class)->parameters(['krs' => 'krs']);

    // ── Validasi KRS oleh Dosen ──────────────────────────────────────────────
    Route::middleware('role:admin,dosen')->group(function () {
        Route::get('krs/{krs}/validasi', [TransaksiKrsController::class, 'validasi'])->name('krs.validasi');
        Route::put('krs/{krs}/proses-validasi', [TransaksiKrsController::class, 'prosesValidasi'])->name('krs.proses_validasi');
    });

    Route::resource('jadwal', JadwalKuliahController::class);

    Route::middleware('role:admin,dosen')->group(function () {
        Route::resource('presensi', PresensiController::class)->except('index','show');
        Route::resource('materi', MateriKuliahController::class)->except('index','show');
        Route::resource('nilai', NilaiController::class)->except('index','show');
        Route::resource('pengumuman', PengumumanController::class)->except('index','show');
    });

    Route::resource('presensi', PresensiController::class)->only('index','show');
    Route::resource('materi', MateriKuliahController::class)->only('index','show');
    Route::resource('nilai', NilaiController::class)->only('index','show');
    Route::resource('pengumuman', PengumumanController::class)->only('index','show');

    // ── Kelayakan Mahasiswa (Fuzzy Logic) ────────────────────────────────────
    Route::get('kelayakan', [KelayakanController::class, 'index'])->name('kelayakan.index');
    Route::get('kelayakan/create', [KelayakanController::class, 'create'])->name('kelayakan.create');
    Route::post('kelayakan/proses', [KelayakanController::class, 'proses'])->name('kelayakan.proses');
    Route::post('kelayakan/batch', [KelayakanController::class, 'batchProses'])->name('kelayakan.batch');
    Route::get('kelayakan/{kelayakan}', [KelayakanController::class, 'detail'])->name('kelayakan.detail');
    Route::get('kelayakan/ajax/matakuliah', [KelayakanController::class, 'getMatakuliahByMahasiswa'])->name('kelayakan.ajax.matakuliah');

    // ── Portal Mahasiswa ──────────────────────────────────────────────────────
    Route::prefix('mahasiswa')->name('mhs.')->middleware('role:mahasiswa')->group(function () {
        Route::get('/dashboard', [MahasiswaPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/krs', [MahasiswaPortalController::class, 'krs'])->name('krs');
        Route::get('/krs/create', [MahasiswaPortalController::class, 'krsCreate'])->name('krs.create');
        Route::post('/krs/store', [MahasiswaPortalController::class, 'krsStore'])->name('krs.store');
        Route::get('/jadwal', [MahasiswaPortalController::class, 'jadwal'])->name('jadwal');
        Route::get('/presensi', [MahasiswaPortalController::class, 'presensi'])->name('presensi');
        Route::get('/nilai', [MahasiswaPortalController::class, 'nilai'])->name('nilai');
        Route::get('/materi', [MahasiswaPortalController::class, 'materi'])->name('materi');
        Route::get('/pengumuman', [MahasiswaPortalController::class, 'pengumuman'])->name('pengumuman');
        Route::get('/kelayakan', [MahasiswaPortalController::class, 'kelayakan'])->name('kelayakan');
        Route::get('/kelayakan/create', [MahasiswaPortalController::class, 'kelayakanCreate'])->name('kelayakan.create');
        Route::post('/kelayakan/proses', [MahasiswaPortalController::class, 'kelayakanProses'])->name('kelayakan.proses');
        Route::get('/profile', [MahasiswaPortalController::class, 'profile'])->name('profile');
    });
});
