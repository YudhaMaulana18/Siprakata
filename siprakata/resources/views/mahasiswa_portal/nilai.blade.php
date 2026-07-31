@extends('layouts.Mahasiswa')
@section('title', 'Nilai')
@section('page-title', 'Nilai')
@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-graph-up me-2"></i>Daftar Nilai</div>
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Mata Kuliah</th>
                    <th>Dosen</th>
                    <th>Tugas (30%)</th>
                    <th>UTS (30%)</th>
                    <th>UAS (40%)</th>
                    <th>Nilai Akhir</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nilai as $n)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ optional(optional($n->krs)->matakuliah)->nama_mk ?? '-' }}</td>
                    <td>{{ optional(optional($n->krs)->dosen)->nama ?? '-' }}</td>
                    <td>{{ $n->nilai_tugas ?? '-' }}</td>
                    <td>{{ $n->nilai_uts ?? '-' }}</td>
                    <td>{{ $n->nilai_uas ?? '-' }}</td>
                    <td><strong>{{ number_format((float)($n->nilai_akhir ?? 0), 2) }}</strong></td>
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
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Belum ada nilai.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection
