@extends('layouts.Mahasiswa')
@section('title', 'Jadwal Kuliah')
@section('page-title', 'Jadwal Kuliah')
@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-calendar3-week-fill me-2"></i>Jadwal Kuliah Saya</div>
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode MK</th>
                    <th>Mata Kuliah</th>
                    <th>Dosen</th>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Ruangan</th>
                    <th>Semester</th>
                    <th>Tahun Ajaran</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwal as $j)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ optional($j->matakuliah)->kode_mk ?? '-' }}</td>
                    <td>{{ optional($j->matakuliah)->nama_mk ?? '-' }}</td>
                    <td>{{ optional($j->dosen)->nama ?? '-' }}</td>
                    <td><span class="badge bg-primary">{{ $j->hari }}</span></td>
                    <td>{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} – {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</td>
                    <td>{{ optional($j->ruanganRef)->nama_ruangan ?? $j->ruangan ?? '-' }}</td>
                    <td>{{ $j->semester }}</td>
                    <td>{{ $j->tahun_ajaran }}</td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center text-muted py-4">Belum ada jadwal kuliah.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection
