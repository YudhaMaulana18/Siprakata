@extends('layouts.App')
@section('title', 'Jadwal Kuliah')
@section('page-title', 'Jadwal Kuliah')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-calendar3-week-fill me-2"></i>Daftar Jadwal Kuliah</span>
        @if(Auth::user()->isAdmin() || Auth::user()->isDosen())
        <a href="{{ route('jadwal.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Jadwal
        </a>
        @endif
    </div>
    <div class="card-body p-0">
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
                    @if(Auth::user()->isAdmin() || Auth::user()->isDosen())
                    <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($jadwal as $j)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $j->matakuliah->kode_mk }}</td>
                    <td>{{ $j->matakuliah->nama_mk }}</td>
                    <td>{{ $j->dosen->nama }}</td>
                    <td><span class="badge bg-primary">{{ $j->hari }}</span></td>
                    <td>{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} – {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</td>
                    <td>{{ $j->ruangan }}</td>
                    <td>{{ $j->semester }}</td>
                    <td>{{ $j->tahun_ajaran }}</td>
                    @if(Auth::user()->isAdmin() || Auth::user()->isDosen())
                    <td class="text-nowrap">
                        <a href="{{ route('jadwal.edit', $j->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil-fill"></i></a>
                        <form action="{{ route('jadwal.destroy', $j->id) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Hapus jadwal ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                        </form>
                    </td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="10" class="text-center text-muted py-4">Belum ada data jadwal.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection