@extends('layouts.App')
@section('title', 'Data Nilai')
@section('page-title', 'Data Nilai Mahasiswa')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-graph-up me-2"></i>Daftar Nilai</span>
        @if(Auth::user()->isAdmin() || Auth::user()->isDosen())
        <a href="{{ route('nilai.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Input Nilai
        </a>
        @endif
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Mata Kuliah</th>
                    <th>Tugas (30%)</th>
                    <th>UTS (30%)</th>
                    <th>UAS (40%)</th>
                    <th>Nilai Akhir</th>
                    <th>Grade</th>
                    @if(Auth::user()->isAdmin() || Auth::user()->isDosen())<th>Aksi</th>@endif
                </tr>
            </thead>
            <tbody>
                @forelse($nilai as $n)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $n->krs->mahasiswa->NIM }}</td>
                    <td>{{ $n->krs->mahasiswa->nama }}</td>
                    <td>{{ $n->krs->matakuliah->nama_mk }}</td>
                    <td>{{ $n->nilai_tugas }}</td>
                    <td>{{ $n->nilai_uts }}</td>
                    <td>{{ $n->nilai_uas }}</td>
                    <td><strong>{{ number_format($n->nilai_akhir, 2) }}</strong></td>
                    <td>
                        @php
                            $g = $n->grade ?? '-';
                            $gradeColor = 'secondary';
                            if(in_array($g, ['A']))           $gradeColor = 'success';
                            elseif(in_array($g, ['B+','B']))  $gradeColor = 'primary';
                            elseif(in_array($g, ['C+','C']))  $gradeColor = 'warning';
                            elseif(in_array($g, ['D','E']))   $gradeColor = 'danger';
                        @endphp
                        <span class="badge bg-{{ $gradeColor }}">{{ $g }}</span>
                    </td>
                    @if(Auth::user()->isAdmin() || Auth::user()->isDosen())
                    <td class="text-nowrap">
                        <a href="{{ route('nilai.edit', $n->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil-fill"></i></a>
                        <form action="{{ route('nilai.destroy', $n->id) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                        </form>
                    </td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="10" class="text-center text-muted py-4">Belum ada data nilai.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection