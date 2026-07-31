@extends('layouts.App')
@section('title','Pengumuman') @section('page-title','Pengumuman')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-megaphone-fill me-2"></i>Daftar Pengumuman</span>
        @if(Auth::user()->isAdmin() || Auth::user()->isDosen())
        <a href="{{ route('pengumuman.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Buat Pengumuman</a>
        @endif
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>NO</th><th>Judul</th><th>Dosen</th><th>Kelas</th>
                    <th>Prioritas</th><th>Tgl Posting</th><th>Kadaluarsa</th>
                    @if(Auth::user()->isAdmin() || Auth::user()->isDosen())<th>Aksi</th>@endif
                </tr>
            </thead>
            <tbody>
                @forelse($pengumuman as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $p->judul }}</strong><br><small class="text-muted">{{ Str::limit($p->isi,60) }}</small></td>
                    <td>{{ $p->dosen->nama }}</td>
                    <td>{{ $p->jadwal?->matakuliah->nama_mk ?? 'Semua Kelas' }}</td>
                    <td>
                        @php $pc=['rendah'=>'secondary','sedang'=>'warning','tinggi'=>'danger'][$p->prioritas] ?? 'secondary' @endphp
                        <span class="badge bg-{{ $pc }}">{{ ucfirst($p->prioritas) }}</span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($p->tgl_posting)->format('d/m/Y') }}</td>
                    <td>{{ $p->tgl_kadaluarsa ? \Carbon\Carbon::parse($p->tgl_kadaluarsa)->format('d/m/Y') : '-' }}</td>
                    @if(Auth::user()->isAdmin() || Auth::user()->isDosen())
                    <td>
                        <a href="{{ route('pengumuman.edit',$p->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil-fill"></i></a>
                        <form action="{{ route('pengumuman.destroy',$p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')<button class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                        </form>
                    </td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Belum ada pengumuman.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection