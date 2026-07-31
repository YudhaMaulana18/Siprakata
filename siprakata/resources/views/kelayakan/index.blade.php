@extends('layouts.App')
@section('title', 'Prediksi Kelulusan')
@section('page-title', 'Prediksi Kelulusan Mata Kuliah (Fuzzy Logic)')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clipboard2-check me-2"></i>Daftar Prediksi Kelulusan</span>
        <div class="d-flex gap-2">
            <a href="{{ route('kelayakan.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-calculator me-1"></i>Analisis Manual
            </a>
            <form action="{{ route('kelayakan.batch') }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="tahun_ajaran" value="{{ $tahunAjaran }}">
                <input type="hidden" name="semester" value="{{ $semester }}">
                <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Analisis batch untuk semua kombinasi mahasiswa × mata kuliah?')">
                    <i class="bi bi-lightning me-1"></i>Batch Semua
                </button>
            </form>
        </div>
    </div>

    <div class="card-body pb-0">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <select name="tahun_ajaran" class="form-select form-select-sm">
                    @foreach(['2025/2026','2024/2025','2023/2024'] as $ta)
                        <option {{ $ta === $tahunAjaran ? 'selected' : '' }}>{{ $ta }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="semester" class="form-select form-select-sm">
                    <option {{ 'Ganjil' === $semester ? 'selected' : '' }}>Ganjil</option>
                    <option {{ 'Genap' === $semester ? 'selected' : '' }}>Genap</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary btn-sm"><i class="bi bi-funnel me-1"></i>Filter</button>
            </div>
        </form>
    </div>

    <div class="card-body pt-0">
        @php
            $lulus = $kelayakan->where('hasil_prediksi', 'lulus')->count();
            $cukup = $kelayakan->where('hasil_prediksi', 'cukup')->count();
            $tidakLulus = $kelayakan->where('hasil_prediksi', 'tidak_lulus')->count();
        @endphp
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg,#0f172a,#1e293b);">
                    <i class="bi bi-people-fill stat-icon"></i>
                    <h3>{{ $kelayakan->count() }}</h3>
                    <p>Total Dianalisis</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg,#10b981,#059669);">
                    <i class="bi bi-check-circle-fill stat-icon"></i>
                    <h3>{{ $lulus }}</h3>
                    <p>Lulus</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg,#f59e0b,#d97706);">
                    <i class="bi bi-dash-circle-fill stat-icon"></i>
                    <h3>{{ $cukup }}</h3>
                    <p>Cukup</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg,#f43f5e,#e11d48);">
                    <i class="bi bi-x-circle-fill stat-icon"></i>
                    <h3>{{ $tidakLulus }}</h3>
                    <p>Tidak Lulus</p>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th width="50">NO</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Mata Kuliah</th>
                        <th>Kehadiran</th>
                        <th>Nilai Tugas</th>
                        <th>Keaktifan</th>
                        <th>Skor Prediksi</th>
                        <th>Hasil</th>
                        <th width="80">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kelayakan as $k)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $k->mahasiswa->NIM }}</td>
                        <td>{{ $k->mahasiswa->nama }}</td>
                        <td><strong>{{ $k->matakuliah->nama_mk ?? '-' }}</strong></td>
                        <td>
                            <div class="progress" style="height: 22px; min-width: 80px; border-radius:8px; background:#f1f5f9;">
                                <div class="progress-bar {{ $k->kehadiran >= 75 ? 'bg-success' : ($k->kehadiran >= 50 ? 'bg-warning' : 'bg-danger') }}"
                                     style="width: {{ $k->kehadiran }}%; border-radius:8px; font-size:0.75rem; font-weight:600;">
                                    {{ number_format($k->kehadiran, 1) }}%
                                </div>
                            </div>
                        </td>
                        <td><strong>{{ number_format($k->nilai_tugas, 1) }}</strong></td>
                        <td><strong>{{ number_format($k->keaktifan_diskusi, 1) }}</strong></td>
                        <td>
                            <strong class="{{ $k->skor_prediksi >= 60 ? 'text-success' : ($k->skor_prediksi >= 40 ? 'text-warning' : 'text-danger') }}">
                                {{ number_format($k->skor_prediksi, 2) }}
                            </strong>
                        </td>
                        <td>
                            @if($k->hasil_prediksi === 'lulus')
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Lulus</span>
                            @elseif($k->hasil_prediksi === 'cukup')
                                <span class="badge bg-warning text-dark"><i class="bi bi-dash-circle me-1"></i>Cukup</span>
                            @else
                                <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Tidak Lulus</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('kelayakan.detail', $k->id) }}" class="btn btn-outline-info btn-sm" title="Detail Perhitungan">
                                <i class="bi bi-search"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>Belum ada data prediksi. Silakan lakukan analisis.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
