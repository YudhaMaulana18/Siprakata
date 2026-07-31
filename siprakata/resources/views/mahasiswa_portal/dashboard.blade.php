@extends('layouts.Mahasiswa')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between mb-4 gap-2">
    <div>
        <h4 style="font-weight:800; color:var(--primary); margin:0;font-size:clamp(1rem,4vw,1.4rem);">
            Selamat datang, {{ Auth::user()->name }}!
        </h4>
        <p style="color:var(--text-muted); font-size:0.88rem; margin:4px 0 0;">
            Pantau kegiatan akademik Anda dari sini.
        </p>
    </div>
    <div class="text-muted" style="font-size:0.82rem;white-space:nowrap;">
        <i class="bi bi-calendar3"></i>
        {{ now()->translatedFormat('l, d F Y') }}
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #0f172a, #1e3a8a);">
            <i class="bi bi-card-checklist stat-icon"></i>
            <h3>{{ $krsCount ?? 0 }}</h3>
            <p>KRS Saya</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #047857, #10b981);">
            <i class="bi bi-calendar3-week-fill stat-icon"></i>
            <h3>{{ $jadwalCount ?? 0 }}</h3>
            <p>Jadwal Aktif</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #b45309, #f59e0b);">
            <i class="bi bi-clipboard2-check-fill stat-icon"></i>
            <h3>{{ $presensiCount ?? 0 }}</h3>
            <p>Presensi</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #7c3aed, #a78bfa);">
            <i class="bi bi-graph-up stat-icon"></i>
            <h3>{{ $nilaiCount ?? 0 }}</h3>
            <p>Nilai Masuk</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history me-2"></i>KRS Terbaru
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Mata Kuliah</th>
                                <th>Dosen</th>
                                <th>SKS</th>
                                <th>Status</th>
                                <th>Validasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($krsTerbaru as $k)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ optional($k->matakuliah)->nama_mk ?? '-' }}</td>
                                <td>{{ optional($k->dosen)->nama ?? '-' }}</td>
                                <td><span class="badge bg-warning text-dark">{{ optional($k->matakuliah)->sks ?? 0 }} SKS</span></td>
                                <td>
                                    @if($k->status === 'aktif')
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Selesai</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $k->status_validasi==='disetujui'?'success':($k->status_validasi==='pending'?'warning':'danger') }}">
                                        {{ ucfirst($k->status_validasi) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">Belum ada KRS.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-megaphone-fill me-2"></i>Pengumuman
            </div>
            <div class="card-body">
                @forelse(\App\Models\Pengumuman::latest()->take(5)->get() as $p)
                <div class="d-flex gap-3 mb-3 pb-3" style="{{ !$loop->last?'border-bottom:1px solid #f1f5f9;':'' }}">
                    <div style="width:40px;height:40px;border-radius:10px;background:rgba(4,120,87,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#047857;font-size:0.9rem;">
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
                <div class="text-center text-muted py-4">Belum ada pengumuman</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
