@extends('layouts.App')
@section('title','Mata Kuliah') @section('page-title','Mata Kuliah')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-journal-bookmark-fill me-2"></i>Daftar Mata Kuliah</span>
        @if(Auth::user()->isAdmin())
        <a href="{{ route('matakuliah.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
        @endif
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>NO</th><th>Kode</th><th>Nama MK</th><th>SKS</th><th>Semester</th><th>Prodi</th>
                @if(Auth::user()->isAdmin())<th>Aksi</th>@endif
            </tr></thead>
            <tbody>
                @forelse($matakuliah as $mk)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $mk->kode_mk }}</strong></td>
                    <td>{{ $mk->nama_mk }}</td>
                    <td>{{ $mk->sks }} SKS</td>
                    <td>Sem. {{ $mk->semester }}</td>
                    <td>{{ $mk->prodi?->nama_prodi ?? '-' }}</td>
                    @if(Auth::user()->isAdmin())
                    <td>
                        <a href="{{ route('matakuliah.edit',$mk->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil-fill"></i></a>
                        <form action="{{ route('matakuliah.destroy',$mk->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')<button class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                        </form>
                    </td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection