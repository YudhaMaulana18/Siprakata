@extends('layouts.App')
@section('title', 'Transaksi KRS')
@section('page-title', 'Transaksi KRS')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-card-checklist me-2"></i>Daftar KRS (Kartu Rencana Studi)</span>
        <a href="{{ route('krs.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Tambah KRS
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th width="50">NO</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Kode MK</th>
                        <th>Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Dosen</th>
                        <th>Tahun Ajaran</th>
                        <th>Semester</th>
                        <th>Status</th>
                        <th>Validasi</th>
                        <th>Catatan Validasi</th>
                        <th>Tgl Validasi</th>
                        @if(Auth::user()->isAdmin() || Auth::user()->isDosen())
                        <th width="120">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($krs as $k)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $k->mahasiswa->NIM }}</td>
                        <td>{{ $k->mahasiswa->nama }}</td>
                        <td>{{ $k->matakuliah->kode_mk }}</td>
                        <td>{{ $k->matakuliah->nama_mk }}</td>
                        <td><span class="badge bg-warning text-dark">{{ $k->matakuliah->sks }} SKS</span></td>
                        <td>{{ $k->dosen->nama }}</td>
                        <td>{{ $k->tahun_ajaran }}</td>
                        <td>{{ $k->semester }}</td>
                        <td>
                            @if($k->status === 'aktif')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Selesai</span>
                            @endif
                        </td>
                        <td>
                            @if($k->status_validasi === 'disetujui')
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Disetujui</span>
                            @elseif($k->status_validasi === 'ditolak')
                                <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Ditolak</span>
                            @else
                                <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>Pending</span>
                            @endif
                        </td>
                        <td>{{ $k->catatan_validasi ?? '-' }}</td>
                        <td>{{ $k->tgl_validasi ? \Carbon\Carbon::parse($k->tgl_validasi)->format('d/m/Y') : '-' }}</td>
                        @if(Auth::user()->isAdmin() || Auth::user()->isDosen())
                        <td class="text-nowrap">
                            @if($k->status_validasi === 'pending')
                            <a href="{{ route('krs.validasi', $k->id) }}" class="btn btn-info btn-sm" title="Validasi">
                                <i class="bi bi-shield-check"></i>
                            </a>
                            @endif
                            @if(Auth::user()->isAdmin() || Auth::user()->isDosen() || ($k->status_validasi === 'pending'))
                            <a href="{{ route('krs.edit', $k->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <form action="{{ route('krs.destroy', $k->id) }}" method="POST" class="d-inline ms-1">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin hapus KRS ini?')">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="14" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>Belum ada data KRS
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
