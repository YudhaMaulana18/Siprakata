@extends('layouts.Mahasiswa')
@section('title', 'Materi Kuliah')
@section('page-title', 'Materi Kuliah')
@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-file-earmark-text-fill me-2"></i>Daftar Materi Kuliah</div>
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Mata Kuliah</th>
                    <th>Dosen</th>
                    <th>Pertemuan</th>
                    <th>Judul</th>
                    <th>File / Link</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materi as $m)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ optional(optional($m->jadwal)->matakuliah)->nama_mk ?? '-' }}</td>
                    <td>{{ optional(optional($m->jadwal)->dosen)->nama ?? '-' }}</td>
                    <td class="text-center">Ke-{{ $m->pertemuan_ke }}</td>
                    <td>
                        <strong>{{ $m->judul }}</strong>
                        @if($m->deskripsi)<br><small class="text-muted">{{ Str::limit($m->deskripsi, 60) }}</small>@endif
                    </td>
                    <td>
                        @if($m->file_path)
                            <a href="{{ asset('storage/'.$m->file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm"><i class="bi bi-download me-1"></i>File</a>
                        @endif
                        @if($m->link_materi)
                            <a href="{{ $m->link_materi }}" target="_blank" class="btn btn-outline-info btn-sm"><i class="bi bi-link-45deg me-1"></i>Link</a>
                        @endif
                        @if(!$m->file_path && !$m->link_materi)<span class="text-muted">-</span>@endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada materi.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection
