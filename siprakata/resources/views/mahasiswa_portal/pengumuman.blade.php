@extends('layouts.Mahasiswa')
@section('title', 'Pengumuman')
@section('page-title', 'Pengumuman')
@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-megaphone-fill me-2"></i>Daftar Pengumuman</div>
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Dosen</th>
                    <th>Kelas</th>
                    <th>Prioritas</th>
                    <th>Tgl Posting</th>
                    <th>Kadaluarsa</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengumuman as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $p->judul }}</strong>
                        @if($p->isi)<br><small class="text-muted">{{ Str::limit($p->isi, 60) }}</small>@endif
                    </td>
                    <td>{{ optional($p->dosen)->nama ?? '-' }}</td>
                    <td>{{ $p->jadwal?->matakuliah->nama_mk ?? 'Semua Kelas' }}</td>
                    <td>
                        @php $pc=['rendah'=>'secondary','sedang'=>'warning','tinggi'=>'danger'][$p->prioritas] ?? 'secondary' @endphp
                        <span class="badge bg-{{ $pc }}">{{ ucfirst($p->prioritas) }}</span>
                    </td>
                    <td>{{ $p->tgl_posting ? \Carbon\Carbon::parse($p->tgl_posting)->format('d/m/Y') : \Carbon\Carbon::parse($p->created_at)->format('d/m/Y') }}</td>
                    <td>{{ $p->tgl_kadaluarsa ? \Carbon\Carbon::parse($p->tgl_kadaluarsa)->format('d/m/Y') : '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada pengumuman.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection
