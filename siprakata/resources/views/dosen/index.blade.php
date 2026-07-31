@extends('layouts.App')
@section('title','Data Dosen') @section('page-title','Data Dosen')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-person-badge-fill me-2"></i>Daftar Dosen</span>
        @if(Auth::user()->isAdmin())
        <a href="{{ route('dosen.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
        @endif
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>NO</th><th>NIDN</th><th>Nama</th><th>Email</th><th>No. HP</th><th>Jabatan</th><th>Prodi</th>
                @if(Auth::user()->isAdmin())<th>Aksi</th>@endif
            </tr></thead>
            <tbody>
                @forelse($dosen as $d)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $d->NIDN }}</strong></td>
                    <td>{{ $d->nama }}</td>
                    <td>{{ $d->email }}</td>
                    <td>{{ $d->no_hp ?? '-' }}</td>
                    <td>{{ $d->jabatan ?? '-' }}</td>
                    <td>{{ $d->prodi?->nama_prodi ?? '-' }}</td>
                    @if(Auth::user()->isAdmin())
                    <td class="text-nowrap">
                        <div class="d-flex gap-1">
                            <a href="{{ route('dosen.edit',$d->id) }}" class="btn btn-warning btn-sm" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                            <form action="{{ route('dosen.destroy',$d->id) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                @csrf @method('DELETE')<button class="btn btn-danger btn-sm" title="Hapus"><i class="bi bi-trash-fill"></i></button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection