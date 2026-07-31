@extends('layouts.App')
@section('title','Data Mahasiswa')
@section('page-title','Data Mahasiswa')
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
            <thead><tr><th>NO</th><th>NIM</th><th>Nama</th><th>Email</th><th>No. HP</th><th>Alamat</th><th>Program Studi</th><th>Angkatan</th><th>Status</th>
                @if(Auth::user()->isAdmin())<th>Aksi</th>@endif
            </tr></thead>
            <tbody>
                @forelse($data as $mhs)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $mhs->NIM }}</strong></td>
                    <td>{{ $mhs->nama }}</td>
                    <td>{{ $mhs->email ?? '-' }}</td>
                    <td>{{ $mhs->no_hp ?? '-' }}</td>
                    <td>{{ $mhs->alamat ?? '-' }}</td>
                    <td>{{ $mhs->prodi?->nama_prodi ?? '-' }}</td>
                    <td>{{ $mhs->angkatan ?? '-' }}</td>
                    <td>
                        @php $sc=['aktif'=>'success','cuti'=>'warning','lulus'=>'info','keluar'=>'danger'][$mhs->status??'aktif']??'secondary' @endphp
                        <span class="badge bg-{{ $sc }}">{{ ucfirst($mhs->status??'aktif') }}</span>
                    </td>
                    @if(Auth::user()->isAdmin())
                    <td class="text-nowrap">
                        <div class="d-flex gap-1">
                            <a href="{{ route('edit-mahasiswa',$mhs->id) }}" class="btn btn-warning btn-sm" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                            <form action="{{ route('hapus-mahasiswa',$mhs->id) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                @csrf @method('DELETE')<button class="btn btn-danger btn-sm" title="Hapus"><i class="bi bi-trash-fill"></i></button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="10" class="text-center text-muted py-4">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection