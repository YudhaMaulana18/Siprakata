@extends('layouts.App')
@section('title','Daftar Permission') @section('page-title','Manajemen Hak Akses — Permission')
@section('content')
<div class="row g-3">
    @foreach($permissions as $module => $perms)
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header" style="background:#1a237e; color:#fff">
                <i class="bi bi-grid-fill me-2"></i>
                <strong style="text-transform:capitalize">{{ $module }}</strong>
                <span class="badge bg-light text-dark float-end">{{ count($perms) }}</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Permission</th><th>Nama</th></tr></thead>
                    <tbody>
                        @foreach($perms as $p)
                        <tr>
                            <td><code style="font-size:11px">{{ $p->name }}</code></td>
                            <td style="font-size:12px">{{ $p->display_name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection