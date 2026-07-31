@extends('layouts.Mahasiswa')
@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')
@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card text-center">
            <div class="card-body py-5">
                <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#047857,#10b981);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;color:#fff;font-size:2rem;font-weight:700;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <h5 style="font-weight:700;">{{ Auth::user()->name }}</h5>
                <p style="color:var(--text-muted);font-size:0.85rem;">Mahasiswa</p>
                @if($mhs)
                <p style="font-size:0.85rem;margin:0;">
                    <strong>NIM:</strong> {{ $mhs->NIM }}<br>
                    <strong>Program Studi:</strong> {{ $mhs->prodi->nama_prodi ?? '-' }}
                </p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-person-fill me-2"></i>Informasi Lengkap</div>
            <div class="card-body">
                @if($mhs)
                <table class="table table-borderless mb-0">
                    <tr>
                        <td style="width:180px;font-weight:600;color:var(--text-muted);">NIM</td>
                        <td>{{ $mhs->NIM }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:600;color:var(--text-muted);">Nama Lengkap</td>
                        <td>{{ $mhs->nama }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:600;color:var(--text-muted);">Email</td>
                        <td>{{ Auth::user()->email }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:600;color:var(--text-muted);">No. HP</td>
                        <td>{{ $mhs->no_hp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:600;color:var(--text-muted);">Alamat</td>
                        <td>{{ $mhs->alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:600;color:var(--text-muted);">Jenis Kelamin</td>
                        <td>{{ $mhs->jenis_kelamin == 'L' ? 'Laki-laki' : ($mhs->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:600;color:var(--text-muted);">Angkatan</td>
                        <td>{{ $mhs->angkatan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:600;color:var(--text-muted);">Status</td>
                        <td><span class="badge bg-{{ $mhs->status=='aktif'?'success':'secondary' }}">{{ ucfirst($mhs->status ?? 'aktif') }}</span></td>
                    </tr>
                    <tr>
                        <td style="font-weight:600;color:var(--text-muted);">Program Studi</td>
                        <td>{{ $mhs->prodi->nama_prodi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:600;color:var(--text-muted);">Fakultas</td>
                        <td>{{ $mhs->prodi->fakultas ?? '-' }}</td>
                    </tr>
                </table>
                @else
                <p class="text-muted text-center py-3">Data mahasiswa tidak ditemukan.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
