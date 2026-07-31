@extends('layouts.App')
@section('title','Materi Kuliah') @section('page-title','Materi Kuliah')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-file-earmark-text-fill me-2"></i>Daftar Materi Kuliah</span>
        @if(Auth::user()->isAdmin() || Auth::user()->isDosen())
        <a href="{{ route('materi.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Upload Materi</a>
        @endif
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>NO</th><th>Mata Kuliah</th><th>Dosen</th><th>Pertemuan</th>
                    <th>Judul</th><th>File / Link</th>
                    @if(Auth::user()->isAdmin() || Auth::user()->isDosen())<th>Aksi</th>@endif
                </tr>
            </thead>
            <tbody>
                @forelse($materi as $m)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $m->jadwal->matakuliah->nama_mk }}</td>
                    <td>{{ $m->jadwal->dosen->nama }}</td>
                    <td class="text-center">Ke-{{ $m->pertemuan_ke }}</td>
                    <td><strong>{{ $m->judul }}</strong><br><small class="text-muted">{{ Str::limit($m->deskripsi,60) }}</small></td>
                    <td>
                        @if($m->file_path)
                            <a href="{{ asset('storage/'.$m->file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm"><i class="bi bi-download me-1"></i>File</a>
                        @endif
                        @if($m->link_materi)
                            <a href="{{ $m->link_materi }}" target="_blank" class="btn btn-outline-info btn-sm"><i class="bi bi-link-45deg me-1"></i>Link</a>
                        @endif
                        @if(!$m->file_path && !$m->link_materi)<span class="text-muted">-</span>@endif
                    </td>
                    @if(Auth::user()->isAdmin() || Auth::user()->isDosen())
                    <td>
                        <a href="{{ route('materi.edit',$m->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil-fill"></i></a>
                        <form action="{{ route('materi.destroy',$m->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')<button class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                        </form>
                    </td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada materi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection