@extends('layouts.App')
@section('title','Data Mahasiswa') @section('page-title','Data Mahasiswa')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-people-fill me-2"></i>Daftar Mahasiswa</span>
        @if(Auth::user()->isAdmin())
        <a href="{{ route('create-mahasiswa') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
        @endif
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>NO</th><th>NIM</th><th>Nama</th><th>Jenis Kelamin</th><th>Email</th><th>No. HP</th><th>Alamat</th><th>Angkatan</th><th>Status</th><th>Prodi</th>
                @if(Auth::user()->isAdmin())<th>Aksi</th>@endif
            </tr></thead>
            <tbody>
                @forelse($data as $m)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $m->NIM }}</strong></td>
                    <td>{{ $m->nama }}</td>
                    <td>{{ $m->jenis_kelamin ?? '-' }}</td>
                    <td>{{ $m->email ?? '-' }}</td>
                    <td>{{ $m->no_hp ?? '-' }}</td>
                    <td>{{ $m->alamat ?? '-' }}</td>
                    <td>{{ $m->angkatan ?? '-' }}</td>
                    <td>
                        @if($m->status == 'aktif')
                            <span class="badge bg-success">Aktif</span>
                        @elseif($m->status == 'cuti')
                            <span class="badge bg-warning text-dark">Cuti</span>
                        @elseif($m->status == 'lulus')
                            <span class="badge bg-primary">Lulus</span>
                        @else
                            <span class="badge bg-secondary">Keluar</span>
                        @endif
                    </td>
                    <td>{{ $m->prodi?->nama_prodi ?? '-' }}</td>
                    @if(Auth::user()->isAdmin())
                    <td class="text-nowrap">
                        <div class="d-flex gap-1">
                            <a href="{{ route('edit-mahasiswa',$m->id) }}" class="btn btn-warning btn-sm" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                            <form action="{{ route('hapus-mahasiswa',$m->id) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                @csrf @method('DELETE')<button class="btn btn-danger btn-sm" title="Hapus"><i class="bi bi-trash-fill"></i></button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="11" class="text-center text-muted py-4">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
