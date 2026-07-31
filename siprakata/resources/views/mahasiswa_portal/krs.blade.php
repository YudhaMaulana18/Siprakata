@extends('layouts.Mahasiswa')
@section('title', 'KRS Saya')
@section('page-title', 'Kartu Rencana Studi (KRS)')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-card-checklist me-2"></i>Daftar KRS Saya</span>
        <a href="{{ route('mhs.krs.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Ajukan KRS
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode MK</th>
                    <th>Mata Kuliah</th>
                    <th>SKS</th>
                    <th>Dosen</th>
                    <th>Tahun Ajaran</th>
                    <th>Semester</th>
                    <th>Status</th>
                    <th>Validasi</th>
                    <th>Catatan</th>
                    <th>Tgl Validasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($krs as $k)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ optional($k->matakuliah)->kode_mk ?? '-' }}</td>
                    <td>{{ optional($k->matakuliah)->nama_mk ?? '-' }}</td>
                    <td><span class="badge bg-warning text-dark">{{ optional($k->matakuliah)->sks ?? 0 }} SKS</span></td>
                    <td>{{ optional($k->dosen)->nama ?? '-' }}</td>
                    <td>{{ $k->tahun_ajaran }}</td>
                    <td>{{ $k->semester }}</td>
                    <td>
                        @if($k->status === 'aktif')
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Selesai</span>
                        @endif
                    </td>
                    <td>
                        @if($k->status_validasi === 'disetujui')
                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Disetujui</span>
                        @elseif($k->status_validasi === 'ditolak')
                            <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Ditolak</span>
                        @else
                            <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>Pending</span>
                        @endif
                    </td>
                    <td>{{ $k->catatan_validasi ?? '-' }}</td>
                    <td>{{ $k->tgl_validasi ? \Carbon\Carbon::parse($k->tgl_validasi)->format('d/m/Y') : '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="11" class="text-center text-muted py-4">Belum ada KRS.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection
