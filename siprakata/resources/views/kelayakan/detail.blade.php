@extends('layouts.App')
@section('title', 'Detail Prediksi Kelulusan')
@section('page-title', 'Detail Perhitungan Fuzzy Prediksi Kelulusan')

@php
    $fuzz = $detail['fuzzification'] ?? [];
    $input = $detail['input'] ?? [];
    $rulesAktif = $detail['rules_aktif'] ?? [];
@endphp

@section('content')
<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-search me-2"></i>Detail Perhitungan Fuzzy</span>
                <a href="{{ route('kelayakan.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>
            <div class="card-body">

                {{-- Info Mahasiswa --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Data Mahasiswa</h6>
                        <table class="table table-borderless table-sm mb-0">
                            <tr><td class="text-muted" width="140">NIM</td><td><strong>{{ $kelayakan->mahasiswa->NIM }}</strong></td></tr>
                            <tr><td class="text-muted">Nama</td><td>{{ $kelayakan->mahasiswa->nama }}</td></tr>
                            <tr><td class="text-muted">Mata Kuliah</td><td><strong>{{ $kelayakan->matakuliah->nama_mk ?? '-' }} ({{ $kelayakan->matakuliah->sks ?? '-' }} SKS)</strong></td></tr>
                            <tr><td class="text-muted">Tahun Ajaran</td><td>{{ $kelayakan->tahun_ajaran }}</td></tr>
                            <tr><td class="text-muted">Semester</td><td>{{ $kelayakan->semester }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Input Fuzzy</h6>
                        <table class="table table-borderless table-sm mb-0">
                            <tr><td class="text-muted" width="160">Kehadiran</td><td><strong>{{ number_format($kelayakan->kehadiran, 1) }}%</strong></td></tr>
                            <tr><td class="text-muted">Nilai Tugas</td><td><strong>{{ number_format($kelayakan->nilai_tugas, 1) }}</strong></td></tr>
                            <tr><td class="text-muted">Keaktifan Diskusi</td><td><strong>{{ number_format($kelayakan->keaktifan_diskusi, 1) }}</strong></td></tr>
                        </table>
                    </div>
                </div>

                <hr>

                {{-- ═══════════════════════════════════════════════════════ --}}
                {{-- 1. FUZZIFICATION --}}
                {{-- ═══════════════════════════════════════════════════════ --}}
                @if(isset($fuzz))
                <h6 class="mb-3" style="font-weight:700;color:var(--text);display:flex;align-items:center;gap:8px;">
                    <span style="width:28px;height:28px;border-radius:6px;background:var(--accent-glow);display:flex;align-items:center;justify-content:center;color:var(--accent);font-size:0.8rem;">1</span>
                    Fuzzification
                </h6>
                <div class="row g-3 mb-4">
                    {{-- Kehadiran --}}
                    <div class="col-md-4">
                        <div style="border-radius:14px;padding:20px;background:linear-gradient(135deg,#f8fafc,#f1f5f9);border:1px solid var(--border);text-align:center;">
                            <h6 style="font-weight:700;color:var(--primary);margin-bottom:12px;">Kehadiran = {{ number_format($input['kehadiran'] ?? 0, 1) }}%</h6>
                            <div class="d-flex justify-content-around">
                                <div><span class="badge bg-danger">{{ $fuzz['kehadiran']['rendah'] ?? 0 }}</span><small class="d-block text-muted">Rendah</small></div>
                                <div><span class="badge bg-warning text-dark">{{ $fuzz['kehadiran']['sedang'] ?? 0 }}</span><small class="d-block text-muted">Sedang</small></div>
                                <div><span class="badge bg-success">{{ $fuzz['kehadiran']['tinggi'] ?? 0 }}</span><small class="d-block text-muted">Tinggi</small></div>
                            </div>
                            <div class="mt-3">
                                @php $val = $input['kehadiran'] ?? 0; $x = ($val / 100) * 280 + 10; @endphp
                                <svg viewBox="0 0 300 120" style="width:100%;max-height:100px;">
                                    <line x1="10" y1="100" x2="290" y2="100" stroke="#cbd5e1" stroke-width="1"/>
                                    <polyline points="10,10 150,10 178,100" fill="none" stroke="#ef4444" stroke-width="2"/>
                                    <polyline points="150,100 178,10 220,10 248,100" fill="none" stroke="#eab308" stroke-width="2"/>
                                    <polyline points="220,100 248,10 290,10" fill="none" stroke="#22c55e" stroke-width="2"/>
                                    <line x1="{{ $x }}" y1="0" x2="{{ $x }}" y2="100" stroke="#0d1b3e" stroke-width="2" stroke-dasharray="4,3"/>
                                    <circle cx="{{ $x }}" cy="100" r="4" fill="#0d1b3e"/>
                                    <text x="{{ $x }}" y="115" text-anchor="middle" font-size="10" fill="#0d1b3e" font-weight="700">{{ number_format($val, 0) }}</text>
                                    <text x="10" y="115" font-size="9" fill="#64748b">0</text>
                                    <text x="150" y="115" text-anchor="middle" font-size="9" fill="#64748b">50</text>
                                    <text x="290" y="115" text-anchor="end" font-size="9" fill="#64748b">100</text>
                                </svg>
                            </div>
                        </div>
                    </div>
                    {{-- Nilai Tugas --}}
                    <div class="col-md-4">
                        <div style="border-radius:14px;padding:20px;background:linear-gradient(135deg,#f8fafc,#f1f5f9);border:1px solid var(--border);text-align:center;">
                            <h6 style="font-weight:700;color:var(--primary);margin-bottom:12px;">Nilai Tugas = {{ number_format($input['nilai_tugas'] ?? 0, 1) }}</h6>
                            <div class="d-flex justify-content-around">
                                <div><span class="badge bg-danger">{{ $fuzz['tugas']['rendah'] ?? 0 }}</span><small class="d-block text-muted">Rendah</small></div>
                                <div><span class="badge bg-warning text-dark">{{ $fuzz['tugas']['sedang'] ?? 0 }}</span><small class="d-block text-muted">Sedang</small></div>
                                <div><span class="badge bg-success">{{ $fuzz['tugas']['tinggi'] ?? 0 }}</span><small class="d-block text-muted">Tinggi</small></div>
                            </div>
                            <div class="mt-3">
                                @php $val = $input['nilai_tugas'] ?? 0; $x = ($val / 100) * 280 + 10; @endphp
                                <svg viewBox="0 0 300 120" style="width:100%;max-height:100px;">
                                    <line x1="10" y1="100" x2="290" y2="100" stroke="#cbd5e1" stroke-width="1"/>
                                    <polyline points="10,10 150,10 178,100" fill="none" stroke="#ef4444" stroke-width="2"/>
                                    <polyline points="150,100 178,10 220,10 248,100" fill="none" stroke="#eab308" stroke-width="2"/>
                                    <polyline points="220,100 248,10 290,10" fill="none" stroke="#22c55e" stroke-width="2"/>
                                    <line x1="{{ $x }}" y1="0" x2="{{ $x }}" y2="100" stroke="#0d1b3e" stroke-width="2" stroke-dasharray="4,3"/>
                                    <circle cx="{{ $x }}" cy="100" r="4" fill="#0d1b3e"/>
                                    <text x="{{ $x }}" y="115" text-anchor="middle" font-size="10" fill="#0d1b3e" font-weight="700">{{ number_format($val, 0) }}</text>
                                    <text x="10" y="115" font-size="9" fill="#64748b">0</text>
                                    <text x="150" y="115" text-anchor="middle" font-size="9" fill="#64748b">50</text>
                                    <text x="290" y="115" text-anchor="end" font-size="9" fill="#64748b">100</text>
                                </svg>
                            </div>
                        </div>
                    </div>
                    {{-- Keaktifan Diskusi --}}
                    <div class="col-md-4">
                        <div style="border-radius:14px;padding:20px;background:linear-gradient(135deg,#f8fafc,#f1f5f9);border:1px solid var(--border);text-align:center;">
                            <h6 style="font-weight:700;color:var(--primary);margin-bottom:12px;">Keaktifan Diskusi = {{ number_format($input['keaktifan_diskusi'] ?? 0, 1) }}</h6>
                            <div class="d-flex justify-content-around">
                                <div><span class="badge bg-danger">{{ $fuzz['diskusi']['rendah'] ?? 0 }}</span><small class="d-block text-muted">Rendah</small></div>
                                <div><span class="badge bg-warning text-dark">{{ $fuzz['diskusi']['sedang'] ?? 0 }}</span><small class="d-block text-muted">Sedang</small></div>
                                <div><span class="badge bg-success">{{ $fuzz['diskusi']['tinggi'] ?? 0 }}</span><small class="d-block text-muted">Tinggi</small></div>
                            </div>
                            <div class="mt-3">
                                @php $val = $input['keaktifan_diskusi'] ?? 0; $x = ($val / 100) * 280 + 10; @endphp
                                <svg viewBox="0 0 300 120" style="width:100%;max-height:100px;">
                                    <line x1="10" y1="100" x2="290" y2="100" stroke="#cbd5e1" stroke-width="1"/>
                                    <polyline points="10,10 122,10 150,100" fill="none" stroke="#ef4444" stroke-width="2"/>
                                    <polyline points="122,100 150,10 206,10 234,100" fill="none" stroke="#eab308" stroke-width="2"/>
                                    <polyline points="206,100 234,10 290,10" fill="none" stroke="#22c55e" stroke-width="2"/>
                                    <line x1="{{ $x }}" y1="0" x2="{{ $x }}" y2="100" stroke="#0d1b3e" stroke-width="2" stroke-dasharray="4,3"/>
                                    <circle cx="{{ $x }}" cy="100" r="4" fill="#0d1b3e"/>
                                    <text x="{{ $x }}" y="115" text-anchor="middle" font-size="10" fill="#0d1b3e" font-weight="700">{{ number_format($val, 0) }}</text>
                                    <text x="10" y="115" font-size="9" fill="#64748b">0</text>
                                    <text x="150" y="115" text-anchor="middle" font-size="9" fill="#64748b">50</text>
                                    <text x="290" y="115" text-anchor="end" font-size="9" fill="#64748b">100</text>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- ═══════════════════════════════════════════════════════ --}}
                {{-- 1b. RUMUS FUNGSI KEANGGOTAAN --}}
                {{-- ═══════════════════════════════════════════════════════ --}}
                <div class="mb-4">
                    <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#rumusSection" aria-expanded="false">
                        <i class="bi bi-equals me-1"></i>Lihat Rumus Fungsi Keanggotaan
                    </button>
                    <div class="collapse mt-3" id="rumusSection">
                        <div class="row g-3">
                            {{-- Rumus Kehadiran --}}
                            <div class="col-md-4">
                                <div style="border-radius:12px;padding:16px;background:#fff;border:1px solid var(--border);font-size:0.82rem;">
                                    <h6 style="font-weight:700;color:var(--primary);margin-bottom:10px;">Kehadiran (0–100%)</h6>
                                    <div style="margin-bottom:8px;">
                                        <span class="badge bg-danger" style="font-size:0.7rem;">Rendah</span> (Linear Turun)
                                        <pre style="background:#f8fafc;padding:6px 8px;border-radius:6px;margin:4px 0 0;font-size:0.75rem;border:1px solid #e2e8f0;">μ = 1          jika x ≤ 50
μ = (60−x)/10  jika 50 &lt; x &lt; 60
μ = 0          jika x ≥ 60</pre>
                                    </div>
                                    <div style="margin-bottom:8px;">
                                        <span class="badge bg-warning text-dark" style="font-size:0.7rem;">Sedang</span> (Trapesium)
                                        <pre style="background:#f8fafc;padding:6px 8px;border-radius:6px;margin:4px 0 0;font-size:0.75rem;border:1px solid #e2e8f0;">μ = 0          jika x ≤ 50
μ = (x−50)/10  jika 50 &lt; x ≤ 60
μ = 1          jika 60 &lt; x ≤ 75
μ = (85−x)/10  jika 75 &lt; x &lt; 85
μ = 0          jika x ≥ 85</pre>
                                    </div>
                                    <div>
                                        <span class="badge bg-success" style="font-size:0.7rem;">Tinggi</span> (Linear Naik)
                                        <pre style="background:#f8fafc;padding:6px 8px;border-radius:6px;margin:4px 0 0;font-size:0.75rem;border:1px solid #e2e8f0;">μ = 0          jika x ≤ 75
μ = (x−75)/10  jika 75 &lt; x &lt; 85
μ = 1          jika x ≥ 85</pre>
                                    </div>
                                </div>
                            </div>
                            {{-- Rumus Nilai Tugas --}}
                            <div class="col-md-4">
                                <div style="border-radius:12px;padding:16px;background:#fff;border:1px solid var(--border);font-size:0.82rem;">
                                    <h6 style="font-weight:700;color:var(--primary);margin-bottom:10px;">Nilai Tugas (0–100)</h6>
                                    <div style="margin-bottom:8px;">
                                        <span class="badge bg-danger" style="font-size:0.7rem;">Rendah</span> (Linear Turun)
                                        <pre style="background:#f8fafc;padding:6px 8px;border-radius:6px;margin:4px 0 0;font-size:0.75rem;border:1px solid #e2e8f0;">μ = 1          jika x ≤ 50
μ = (60−x)/10  jika 50 &lt; x &lt; 60
μ = 0          jika x ≥ 60</pre>
                                    </div>
                                    <div style="margin-bottom:8px;">
                                        <span class="badge bg-warning text-dark" style="font-size:0.7rem;">Sedang</span> (Trapesium)
                                        <pre style="background:#f8fafc;padding:6px 8px;border-radius:6px;margin:4px 0 0;font-size:0.75rem;border:1px solid #e2e8f0;">μ = 0          jika x ≤ 50
μ = (x−50)/10  jika 50 &lt; x ≤ 60
μ = 1          jika 60 &lt; x ≤ 75
μ = (85−x)/10  jika 75 &lt; x &lt; 85
μ = 0          jika x ≥ 85</pre>
                                    </div>
                                    <div>
                                        <span class="badge bg-success" style="font-size:0.7rem;">Tinggi</span> (Linear Naik)
                                        <pre style="background:#f8fafc;padding:6px 8px;border-radius:6px;margin:4px 0 0;font-size:0.75rem;border:1px solid #e2e8f0;">μ = 0          jika x ≤ 75
μ = (x−75)/10  jika 75 &lt; x &lt; 85
μ = 1          jika x ≥ 85</pre>
                                    </div>
                                </div>
                            </div>
                            {{-- Rumus Keaktifan Diskusi --}}
                            <div class="col-md-4">
                                <div style="border-radius:12px;padding:16px;background:#fff;border:1px solid var(--border);font-size:0.82rem;">
                                    <h6 style="font-weight:700;color:var(--primary);margin-bottom:10px;">Keaktifan Diskusi (0–100)</h6>
                                    <div style="margin-bottom:8px;">
                                        <span class="badge bg-danger" style="font-size:0.7rem;">Rendah</span> (Linear Turun)
                                        <pre style="background:#f8fafc;padding:6px 8px;border-radius:6px;margin:4px 0 0;font-size:0.75rem;border:1px solid #e2e8f0;">μ = 1          jika x ≤ 40
μ = (50−x)/10  jika 40 &lt; x &lt; 50
μ = 0          jika x ≥ 50</pre>
                                    </div>
                                    <div style="margin-bottom:8px;">
                                        <span class="badge bg-warning text-dark" style="font-size:0.7rem;">Sedang</span> (Trapesium)
                                        <pre style="background:#f8fafc;padding:6px 8px;border-radius:6px;margin:4px 0 0;font-size:0.75rem;border:1px solid #e2e8f0;">μ = 0          jika x ≤ 40
μ = (x−40)/10  jika 40 &lt; x ≤ 50
μ = 1          jika 50 &lt; x ≤ 70
μ = (80−x)/10  jika 70 &lt; x &lt; 80
μ = 0          jika x ≥ 80</pre>
                                    </div>
                                    <div>
                                        <span class="badge bg-success" style="font-size:0.7rem;">Tinggi</span> (Linear Naik)
                                        <pre style="background:#f8fafc;padding:6px 8px;border-radius:6px;margin:4px 0 0;font-size:0.75rem;border:1px solid #e2e8f0;">μ = 0          jika x ≤ 70
μ = (x−70)/10  jika 70 &lt; x &lt; 80
μ = 1          jika x ≥ 80</pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Rumus Defuzzifikasi --}}
                        <div class="mt-3" style="border-radius:12px;padding:16px;background:#fff;border:1px solid var(--border);font-size:0.82rem;">
                            <h6 style="font-weight:700;color:var(--primary);margin-bottom:8px;">Defuzzifikasi — Weighted Average</h6>
                            <pre style="background:#f8fafc;padding:8px 10px;border-radius:6px;margin:0;font-size:0.8rem;border:1px solid #e2e8f0;">Skor = Σ(μᵢ × centroidᵢ) / Σ(μᵢ)

Centroid: Tidak Lulus = 20, Cukup = 50, Lulus = 80
Threshold: Skor ≥ 50 → LULUS, Skor &lt; 50 → TIDAK LULUS</pre>
                        </div>
                    </div>
                </div>

                {{-- ═══════════════════════════════════════════════════════ --}}
                {{-- 2. RULES AKTIF --}}
                {{-- ═══════════════════════════════════════════════════════ --}}
                @if(count($rulesAktif) > 0)
                <h6 class="mb-3" style="font-weight:700;color:var(--text);display:flex;align-items:center;gap:8px;">
                    <span style="width:28px;height:28px;border-radius:6px;background:rgba(14,165,233,0.1);display:flex;align-items:center;justify-content:center;color:#0ea5e9;font-size:0.8rem;">2</span>
                    Rules Aktif ({{ count($rulesAktif) }} dari 27 rules)
                </h6>
                <div class="table-responsive mb-4">
                    <table class="table table-sm table-bordered mb-0">
                        <thead class="table-secondary">
                            <tr><th width="60">Rule</th><th>Kondisi (IF)</th><th width="80">μ (min)</th><th width="100">Output (THEN)</th></tr>
                        </thead>
                        <tbody>
                            @foreach($rulesAktif as $rule)
                            @php
                                $parts = explode(' | ', $rule);
                                $ruleName = Str::before($parts[0], ': ');
                                $desc = Str::after($parts[0], ': ');
                                $muOut = $parts[1] ?? '';
                                $mu = Str::before($muOut, ' →');
                                $mu = Str::after($mu, 'μ=');
                                $out = Str::after($muOut, '→ ');
                            @endphp
                            <tr>
                                <td><strong>{{ $ruleName }}</strong></td>
                                <td>{{ $desc }}</td>
                                <td><code>{{ $mu }}</code></td>
                                <td>
                                    <span class="badge {{ $out === 'lulus' ? 'bg-success' : ($out === 'cukup' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                        {{ strtoupper($out) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                {{-- ═══════════════════════════════════════════════════════ --}}
                {{-- 3. OUTPUT: GRAFIK + HASIL --}}
                {{-- ═══════════════════════════════════════════════════════ --}}
                <h6 class="mb-3" style="font-weight:700;color:var(--text);display:flex;align-items:center;gap:8px;">
                    <span style="width:28px;height:28px;border-radius:6px;background:rgba(16,185,129,0.1);display:flex;align-items:center;justify-content:center;color:#10b981;font-size:0.8rem;">3</span>
                    Output: Grafik Fungsi Keanggotaan & Hasil
                </h6>

                {{-- Grafik Output --}}
                <div style="border-radius:14px;padding:20px;background:linear-gradient(135deg,#f8fafc,#f1f5f9);border:1px solid var(--border);margin-bottom:20px;">
                    <h6 style="font-weight:700;color:var(--primary);margin-bottom:8px;text-align:center;">Skor Prediksi = {{ number_format($kelayakan->skor_prediksi, 2) }}</h6>
                    @php $skor = $kelayakan->skor_prediksi; $x = ($skor / 100) * 280 + 10; @endphp
                    <svg viewBox="0 0 300 120" style="width:100%;max-height:110px;">
                        <line x1="10" y1="100" x2="290" y2="100" stroke="#cbd5e1" stroke-width="1"/>
                        <polyline points="10,10 94,10 122,100" fill="none" stroke="#ef4444" stroke-width="2"/>
                        <text x="52" y="8" font-size="8" fill="#ef4444" font-weight="600">Tidak Lulus</text>
                        <polyline points="94,100 122,10 178,10 206,100" fill="none" stroke="#eab308" stroke-width="2"/>
                        <text x="145" y="8" font-size="8" fill="#eab308" font-weight="600">Cukup</text>
                        <polyline points="178,100 206,10 290,10" fill="none" stroke="#22c55e" stroke-width="2"/>
                        <text x="240" y="8" font-size="8" fill="#22c55e" font-weight="600">Lulus</text>
                        <line x1="{{ $x }}" y1="0" x2="{{ $x }}" y2="100" stroke="#0d1b3e" stroke-width="2.5" stroke-dasharray="4,3"/>
                        <circle cx="{{ $x }}" cy="100" r="5" fill="#0d1b3e"/>
                        <text x="{{ $x }}" y="118" text-anchor="middle" font-size="11" fill="#0d1b3e" font-weight="800">{{ number_format($skor, 1) }}</text>
                        <text x="10" y="115" font-size="9" fill="#64748b">0</text>
                        <text x="150" y="115" text-anchor="middle" font-size="9" fill="#64748b">50</text>
                        <text x="290" y="115" text-anchor="end" font-size="9" fill="#64748b">100</text>
                    </svg>
                </div>

                {{-- Hasil Akhir --}}
                @if($kelayakan->hasil_prediksi === 'lulus')
                <div class="text-center p-4 rounded" style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);border:2px solid #10b981;">
                    <div style="width:60px;height:60px;border-radius:50%;background:linear-gradient(135deg,#10b981,#059669);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;box-shadow:0 4px 15px rgba(16,185,129,0.3);">
                        <i class="bi bi-check-lg" style="color:#fff;font-size:1.8rem;"></i>
                    </div>
                    <h5 class="mb-1" style="color:#065f46;">Skor Prediksi: <strong style="color:#059669;font-size:1.3rem;">{{ number_format($kelayakan->skor_prediksi, 2) }}</strong></h5>
                    <h4 class="mb-0" style="color:#065f46;font-weight:800;">
                        DIPREDIKSI <strong>LULUS</strong> mata kuliah {{ $kelayakan->matakuliah->nama_mk ?? '' }}
                    </h4>
                </div>
                @elseif($kelayakan->hasil_prediksi === 'cukup')
                <div class="text-center p-4 rounded" style="background:linear-gradient(135deg,#fffbeb,#fef3c7);border:2px solid #f59e0b;">
                    <div style="width:60px;height:60px;border-radius:50%;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;box-shadow:0 4px 15px rgba(245,158,11,0.3);">
                        <i class="bi bi-dash-lg" style="color:#fff;font-size:1.8rem;"></i>
                    </div>
                    <h5 class="mb-1" style="color:#92400e;">Skor Prediksi: <strong style="color:#d97706;font-size:1.3rem;">{{ number_format($kelayakan->skor_prediksi, 2) }}</strong></h5>
                    <h4 class="mb-0" style="color:#92400e;font-weight:800;">
                        DIPREDIKSI <strong>CUKUP</strong> mata kuliah {{ $kelayakan->matakuliah->nama_mk ?? '' }}
                    </h4>
                </div>
                @else
                <div class="text-center p-4 rounded" style="background:linear-gradient(135deg,#fff1f2,#ffe4e6);border:2px solid #f43f5e;">
                    <div style="width:60px;height:60px;border-radius:50%;background:linear-gradient(135deg,#f43f5e,#e11d48);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;box-shadow:0 4px 15px rgba(244,63,94,0.3);">
                        <i class="bi bi-x-lg" style="color:#fff;font-size:1.8rem;"></i>
                    </div>
                    <h5 class="mb-1" style="color:#9f1239;">Skor Prediksi: <strong style="color:#e11d48;font-size:1.3rem;">{{ number_format($kelayakan->skor_prediksi, 2) }}</strong></h5>
                    <h4 class="mb-0" style="color:#9f1239;font-weight:800;">
                        DIPREDIKSI <strong>TIDAK LULUS</strong> mata kuliah {{ $kelayakan->matakuliah->nama_mk ?? '' }}
                    </h4>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
