@extends('layouts.App')
@section('title', 'Presensi')
@section('page-title', 'Data Presensi')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clipboard2-check-fill me-2"></i>Daftar Presensi</span>
        @if(Auth::user()->isAdmin() || Auth::user()->isDosen())
        <a href="{{ route('presensi.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Catat Presensi
        </a>
        @endif
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Mata Kuliah</th>
                    <th>Tanggal</th>
                    <th>Pertemuan</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                    @if(Auth::user()->isAdmin() || Auth::user()->isDosen())<th>Aksi</th>@endif
                </tr>
            </thead>
            <tbody>
                @forelse($presensi as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->mahasiswa->NIM }}</td>
                    <td>{{ $p->mahasiswa->nama }}</td>
                    <td>{{ $p->jadwal->matakuliah->nama_mk }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</td>
                    <td class="text-center">Ke-{{ $p->pertemuan_ke }}</td>
                    <td>
                        @php
                            $badgeMap = ['hadir'=>'success','izin'=>'warning','sakit'=>'info','alpha'=>'danger'];
                            $badge = $badgeMap[$p->status_hadir] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $badge }}">{{ ucfirst($p->status_hadir) }}</span>
                    </td>
                    <td>{{ $p->keterangan ?? '-' }}</td>
                    @if(Auth::user()->isAdmin() || Auth::user()->isDosen())
                    <td class="text-nowrap">
                        <a href="{{ route('presensi.edit', $p->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil-fill"></i></a>
                        <form action="{{ route('presensi.destroy', $p->id) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                        </form>
                    </td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="9" class="text-center text-muted py-4">Belum ada data presensi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection