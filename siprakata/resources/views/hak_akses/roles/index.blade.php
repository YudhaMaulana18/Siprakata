@extends('layouts.App')
@section('title','Manajemen Role') @section('page-title','Manajemen Hak Akses — Role')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-shield-lock-fill me-2"></i>Daftar Role</span>
        <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Tambah Role</a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>No</th><th>Nama Role</th><th>Display Name</th><th>Deskripsi</th><th>Jumlah Permission</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($roles as $r)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><span class="badge bg-dark">{{ $r->name }}</span></td>
                    <td><strong>{{ $r->display_name }}</strong></td>
                    <td>{{ $r->description ?? '-' }}</td>
                    <td><span class="badge bg-info">{{ $r->permissions_count }} permission</span></td>
                    <td class="text-nowrap">
                        <a href="{{ route('roles.edit',$r->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil-fill"></i></a>
                        @if(!in_array($r->name, ['admin']))
                        <form action="{{ route('roles.destroy',$r->id) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Hapus role ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada role.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection