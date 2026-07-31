@extends('layouts.App')
@section('title','Tahun Ajaran') @section('page-title','Tahun Ajaran')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-calendar-range-fill me-2"></i>Daftar Tahun Ajaran</span>
        @if(Auth::user()->isAdmin())
        <a href="{{ route('tahun_ajaran.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
        @endif
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>Tahun</th>
                    <th>Semester</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Status</th>
                    @if(Auth::user()->isAdmin())<th>Aksi</th>@endif
                </tr>
            </thead>
            <tbody>
                @forelse($ta as $t)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $t->tahun }}</strong></td>
                    <td>{{ $t->semester }}</td>
                    <td>{{ \Carbon\Carbon::parse($t->tgl_mulai)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($t->tgl_selesai)->format('d/m/Y') }}</td>
                    <td>
                        @if($t->status_aktif)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Tidak Aktif</span>
                        @endif
                    </td>
                    @if(Auth::user()->isAdmin())
                    <td>
                        <a href="{{ route('tahun_ajaran.edit',$t->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil-fill"></i></a>
                        <form action="{{ route('tahun_ajaran.destroy',$t->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
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