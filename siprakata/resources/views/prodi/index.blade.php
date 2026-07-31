@extends('layouts.App')
@section('title','Program Studi')
@section('page-title','Program Studi')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-building me-2"></i>Daftar Program Studi</span>
        @if(Auth::user()->isAdmin())
        <a href="{{ route('prodi.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
        @endif
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>NO</th><th>Kode</th><th>Nama Prodi</th><th>Jenjang</th><th>Fakultas</th>@if(Auth::user()->isAdmin())<th>Aksi</th>@endif</tr></thead>
            <tbody>
                @forelse($prodi as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $p->kode_prodi }}</strong></td>
                    <td>{{ $p->nama_prodi }}</td>
                    <td><span class="badge bg-info">{{ $p->jenjang }}</span></td>
                    <td>{{ $p->fakultas }}</td>
                    @if(Auth::user()->isAdmin())
                    <td>
                        <a href="{{ route('prodi.edit',$p->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil-fill"></i></a>
                        <form action="{{ route('prodi.destroy',$p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus prodi ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                        </form>
                    </td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
