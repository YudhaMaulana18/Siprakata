@extends('layouts.Mahasiswa')
@section('title', 'Presensi')
@section('page-title', 'Presensi')
@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-clipboard2-check-fill me-2"></i>Riwayat Presensi</div>
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Mata Kuliah</th>
                    <th>Tanggal</th>
                    <th>Pertemuan</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($presensi as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ optional(optional($p->jadwal)->matakuliah)->nama_mk ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d M Y') }}</td>
                    <td class="text-center">Ke-{{ $p->pertemuan_ke }}</td>
                    <td>
                        @php
                            $cls = ['hadir'=>'success','izin'=>'info','sakit'=>'warning','alpha'=>'danger'][$p->status_hadir]??'secondary';
                        @endphp
                        <span class="badge bg-{{ $cls }}">{{ ucfirst($p->status_hadir) }}</span>
                    </td>
                    <td>{{ $p->keterangan ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada presensi.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection
