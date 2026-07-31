@extends('layouts.Mahasiswa')
@section('title', 'Prediksi Kelayakan')
@section('page-title', 'Prediksi Kelayakan')
@section('content')
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #047857, #10b981);">
            <i class="bi bi-clipboard2-check-fill stat-icon"></i>
            <h3>{{ $kelayakan->count() }}</h3>
            <p>Total Prediksi</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #1d4ed8, #60a5fa);">
            <i class="bi bi-check-circle-fill stat-icon"></i>
            <h3>{{ $kelayakan->where('predikat', 'Layak')->count() }}</h3>
            <p>Layak</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #b45309, #f59e0b);">
            <i class="bi bi-exclamation-circle-fill stat-icon"></i>
            <h3>{{ $kelayakan->where('predikat', 'Cukup Layak')->count() }}</h3>
            <p>Cukup Layak</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #be123c, #fb7185);">
            <i class="bi bi-x-circle-fill stat-icon"></i>
            <h3>{{ $kelayakan->where('predikat', 'Tidak Layak')->count() }}</h3>
            <p>Tidak Layak</p>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div></div>
    <a href="{{ route('mhs.kelayakan.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Prediksi Baru
    </a>
</div>

<div class="card">
    <div class="card-header"><i class="bi bi-clipboard2-check-fill me-2"></i>Riwayat Prediksi Kelayakan</div>
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Mata Kuliah</th>
                    <th>IPK</th>
                    <th>IPS</th>
                    <th>SKS</th>
                    <th>Kehadiran</th>
                    <th>Skor</th>
                    <th>Predikat</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kelayakan as $k)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ optional($k->matakuliah)->nama_mk ?? '-' }}</td>
                    <td>{{ $k->ipk }}</td>
                    <td>{{ $k->ips }}</td>
                    <td>{{ $k->jumlah_sks }}</td>
                    <td>{{ $k->kehadiran }}%</td>
                    <td><strong>{{ number_format((float)$k->skor, 2) }}</strong></td>
                    <td>
                        <span class="badge bg-{{ $k->predikat=='Layak'?'success':($k->predikat=='Cukup Layak'?'warning':'danger') }}">
                            {{ $k->predikat }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($k->created_at)->translatedFormat('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center text-muted py-4">Belum ada prediksi.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection
