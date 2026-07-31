@extends('layouts.App')
@section('title','Ruangan') @section('page-title','Data Ruangan')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-door-open-fill me-2"></i>Daftar Ruangan</span>
        @if(Auth::user()->isAdmin())
        <a href="{{ route('ruangan.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
        @endif
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>NO</th><th>Kode</th><th>Nama Ruangan</th><th>Jenis</th><th>Kapasitas</th><th>Gedung / Lantai</th>@if(Auth::user()->isAdmin())<th>Aksi</th>@endif</tr></thead>
            <tbody>
                @forelse($ruangan as $r)
                <tr>
                    <td>{{ $loop->iteration }}</td><td><strong>{{ $r->kode_ruangan }}</strong></td>
                    <td>{{ $r->nama_ruangan }}</td>
                    <td><span class="badge bg-secondary">{{ $r->jenis }}</span></td>
                    <td>{{ $r->kapasitas }} org</td>
                    <td>{{ $r->gedung }} / Lt.{{ $r->lantai }}</td>
                    @if(Auth::user()->isAdmin())
                    <td>
                        <a href="{{ route('ruangan.edit',$r->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil-fill"></i></a>
                        <form action="{{ route('ruangan.destroy',$r->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')<button class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                        </form>
                    </td>
                    @endif
                </tr>
                @empty<tr><td colspan="7" class="text-center text-muted py-4">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
