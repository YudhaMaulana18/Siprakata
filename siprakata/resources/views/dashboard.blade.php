@extends('layouts.App')

@section('page-title', 'Dashboard')
@section('page-title-icon')
<i class="bi bi-grid"></i>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 style="font-weight:800; color:var(--primary); margin:0;">
            Selamat datang, {{ Auth::user()->name }}!
        </h4>
        <p style="color:var(--text-muted); font-size:0.88rem; margin:4px 0 0;">
            Kelola data akademik Anda dari panel ini.
        </p>
    </div>
    <div class="text-muted" style="font-size:0.82rem;">
        <i class="bi bi-calendar3"></i>
        {{ now()->translatedFormat('l, d F Y') }}
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #0f172a, #1e3a8a);">
            <i class="bi bi-people-fill stat-icon"></i>
            <h3>{{ \App\Models\Mahasiswa::count() }}</h3>
            <p>Mahasiswa</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #b45309, #f59e0b);">
            <i class="bi bi-person-badge-fill stat-icon"></i>
            <h3>{{ \App\Models\Dosen::count() }}</h3>
            <p>Dosen</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #047857, #10b981);">
            <i class="bi bi-journal-bookmark-fill stat-icon"></i>
            <h3>{{ \App\Models\Matakuliah::count() }}</h3>
            <p>Mata Kuliah</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #7c3aed, #a78bfa);">
            <i class="bi bi-card-checklist stat-icon"></i>
            <h3>{{ \App\Models\TransaksiKrs::count() }}</h3>
            <p>Transaksi KRS</p>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #0369a1, #38bdf8);">
            <i class="bi bi-calendar3-week-fill stat-icon"></i>
            <h3>{{ \App\Models\JadwalKuliah::count() }}</h3>
            <p>Jadwal Kuliah</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #be123c, #fb7185);">
            <i class="bi bi-clipboard2-check-fill stat-icon"></i>
            <h3>{{ \App\Models\Presensi::count() }}</h3>
            <p>Presensi</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #1d4ed8, #60a5fa);">
            <i class="bi bi-graph-up stat-icon"></i>
            <h3>{{ \App\Models\Nilai::count() }}</h3>
            <p>Nilai</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #9333ea, #c084fc);">
            <i class="bi bi-megaphone-fill stat-icon"></i>
            <h3>{{ \App\Models\Pengumuman::count() }}</h3>
            <p>Pengumuman</p>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #065f46, #34d399);">
            <i class="bi bi-clipboard2-check-fill stat-icon"></i>
            <h3>{{ \App\Models\Kelayakan::count() }}</h3>
            <p>Prediksi Kelayakan</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #9a3412, #fb923c);">
            <i class="bi bi-building stat-icon"></i>
            <h3>{{ \App\Models\ProgramStudi::count() }}</h3>
            <p>Program Studi</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #4338ca, #818cf8);">
            <i class="bi bi-door-open-fill stat-icon"></i>
            <h3>{{ \App\Models\Ruangan::count() }}</h3>
            <p>Ruangan</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #164e63, #22d3ee);">
            <i class="bi bi-calendar-range-fill stat-icon"></i>
            <h3>{{ \App\Models\TahunAjaran::count() }}</h3>
            <p>Tahun Ajaran</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history me-2"></i>Aktivitas Terbaru
            </div>
            <div class="card-body p-0">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width:55px;">#</th>
                            <th>Mahasiswa</th>
                            <th>Mata Kuliah</th>
                            <th>Tahun Ajaran</th>
                            <th style="width:90px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
@forelse(\App\Models\TransaksiKrs::with(['mahasiswa','matakuliah'])->latest()->take(8)->get() as $i => $krs)
                        <tr>
                            <td style="color:var(--text-muted);">{{ $i+1 }}</td>
                            <td style="font-weight:600;">{{ $krs->mahasiswa->nama ?? '-' }}</td>
                            <td>{{ $krs->matakuliah->nama_mk ?? '-' }}</td>
                            <td style="font-size:0.82rem;">{{ $krs->tahun_ajaran ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $krs->status_validasi==='disetujui'?'success':($krs->status_validasi==='pending'?'warning':'secondary') }}">
                                    {{ ucfirst($krs->status_validasi) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4" style="font-size:0.88rem;">
                                <i class="bi bi-inbox d-block mb-2" style="font-size:2rem; opacity:0.3;"></i>
                                Belum ada transaksi KRS
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-megaphone-fill me-2"></i>Pengumuman Terbaru
            </div>
            <div class="card-body">
                @forelse(\App\Models\Pengumuman::latest()->take(5)->get() as $p)
                <div class="d-flex gap-3 mb-3 pb-3" style="{{ !$loop->last?'border-bottom:1px solid #f1f5f9;':'' }}">
                    <div style="width:40px;height:40px;border-radius:10px;background:rgba(200,169,81,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--accent);font-size:0.9rem;">
                        <i class="bi bi-megaphone"></i>
                    </div>
                    <div>
                        <div style="font-weight:700;font-size:0.85rem;color:var(--text-dark);line-height:1.3;">
                            {{ $p->judul }}
                        </div>
                        <div style="font-size:0.75rem;color:var(--text-muted);margin-top:3px;">
                            {{ $p->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4" style="font-size:0.88rem;">
                    <i class="bi bi-megaphone d-block mb-2" style="font-size:2rem;opacity:0.3;"></i>
                    Belum ada pengumuman
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
