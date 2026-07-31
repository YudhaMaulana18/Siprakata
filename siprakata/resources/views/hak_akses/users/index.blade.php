@extends('layouts.App')
@section('title','Kelola User') @section('page-title','Manajemen Hak Akses — User & Role')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-people-fill me-2"></i>Daftar User & Role</span>
        <a href="{{ route('user-roles.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Tambah User
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>No</th><th>Nama</th><th>Email</th><th>Role</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $u->name }}</strong></td>
                    <td>{{ $u->email }}</td>
                    <td>
                        @if($u->role)
                            @php
                                $colors = ['admin'=>'danger','operator'=>'warning','dosen'=>'info','mahasiswa'=>'success','guest'=>'secondary'];
                                $color = $colors[$u->role->name] ?? 'dark';
                            @endphp
                            <span class="badge bg-{{ $color }}">{{ $u->role->display_name }}</span>
                        @else
                            <span class="badge bg-secondary">Belum ada role</span>
                        @endif
                    </td>
                    <td class="text-nowrap">
                        <a href="{{ route('user-roles.edit',$u->id) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-fill"></i>
                        </a>
                        @if($u->id !== Auth::id())
                        <form action="{{ route('user-roles.destroy',$u->id) }}" method="POST" class="d-inline ms-1"
                            onsubmit="return confirm('Hapus user {{ $u->name }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Belum ada user.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection