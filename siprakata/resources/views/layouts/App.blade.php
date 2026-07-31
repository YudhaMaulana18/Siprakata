<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>@yield('title', 'Sistem Akademik')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @php
        $role = Auth::user()->role;
        $isMhs = $role === 'mahasiswa';
        $primary = $isMhs ? '#047857' : '#0d1b3e';
        $primaryLight = $isMhs ? '#065f46' : '#162d6a';
        $primaryLighter = $isMhs ? '#10b981' : '#1e3a8a';
        $sidebarFrom = $isMhs ? '#047857' : '#0d1b3e';
        $sidebarTo = $isMhs ? '#064e3b' : '#0a1628';
        $brandIconColor = $isMhs ? '#064e3b' : '#0d1b3e';
        $subtitle = $isMhs ? 'Portal Mahasiswa' : 'Sistem Akademik';
        $iconBg = $isMhs ? 'rgba(4,120,87,0.1)' : 'rgba(200,169,81,0.1)';
        $iconColor = $isMhs ? '#047857' : 'var(--accent)';
        $avatarFrom = $isMhs ? '#047857' : 'var(--primary)';
        $avatarTo = $isMhs ? '#10b981' : 'var(--primary-lighter)';
        $btnShadow = $isMhs ? '4,120,87' : '13,27,62';
        $focusRgb = $isMhs ? '4,120,87' : '30,58,138';
        $roleLabel = $isMhs ? 'Mahasiswa' : (Auth::user()->role?->display_name ?? 'User');
    @endphp
    <style>
        :root {
            --primary: {{ $primary }};
            --primary-light: {{ $primaryLight }};
            --primary-lighter: {{ $primaryLighter }};
            --sidebar-from: {{ $sidebarFrom }};
            --sidebar-to: {{ $sidebarTo }};
            --icon-bg: {{ $iconBg }};
            --icon-color: {{ $iconColor }};
            --avatar-from: {{ $avatarFrom }};
            --avatar-to: {{ $avatarTo }};
            --btn-shadow: {{ $btnShadow }};
            --focus-rgb: {{ $focusRgb }};
            --accent: #c8a951;
            --accent-light: #e8d48b;
            --sidebar-w: 270px;
            --bg-body: #f4f6fa;
            --bg-card: #ffffff;
            --text-dark: #1a1f36;
            --text-muted: #6b7280;
            --border: #e5e9f0;
            --radius: 8px;
            --radius-lg: 12px;
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 16px rgba(0,0,0,0.06);
            --shadow-lg: 0 12px 40px rgba(0,0,0,0.08);
            --ease: cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
    <link href="{{ asset('css/app.css') }}?v=3" rel="stylesheet">
</head>
<body>

<div id="page-progress"></div>
<div id="toastContainer" class="toast-container"></div>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
        <div class="brand-text">
            <h5>SIPRAKATA</h5>
            <small>{{ $subtitle }}</small>
        </div>
    </div>

    <div class="sidebar-nav">
        @if($isMhs)
            <nav class="nav flex-column">
                <a href="{{ route('mhs.dashboard') }}" class="nav-link {{ request()->routeIs('mhs.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house-door-fill"></i> <span>Dashboard</span>
                </a>
            </nav>
            <div class="sidebar-section">Kegiatan Belajar</div>
            <nav class="nav flex-column">
                <a href="{{ route('mhs.krs') }}" class="nav-link {{ request()->routeIs('mhs.krs*') ? 'active' : '' }}">
                    <i class="bi bi-card-checklist"></i> <span>Transaksi KRS</span>
                </a>
                <a href="{{ route('mhs.jadwal') }}" class="nav-link {{ request()->routeIs('mhs.jadwal*') ? 'active' : '' }}">
                    <i class="bi bi-calendar3-week-fill"></i> <span>Jadwal Kuliah</span>
                </a>
                <a href="{{ route('mhs.presensi') }}" class="nav-link {{ request()->routeIs('mhs.presensi*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard2-check-fill"></i> <span>Presensi</span>
                </a>
                <a href="{{ route('mhs.materi') }}" class="nav-link {{ request()->routeIs('mhs.materi*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text-fill"></i> <span>Materi Kuliah</span>
                </a>
                <a href="{{ route('mhs.nilai') }}" class="nav-link {{ request()->routeIs('mhs.nilai*') ? 'active' : '' }}">
                    <i class="bi bi-graph-up"></i> <span>Nilai</span>
                </a>
                <a href="{{ route('mhs.pengumuman') }}" class="nav-link {{ request()->routeIs('mhs.pengumuman*') ? 'active' : '' }}">
                    <i class="bi bi-megaphone-fill"></i> <span>Pengumuman</span>
                </a>
            </nav>
            <div class="sidebar-section">Analisis</div>
            <nav class="nav flex-column">
                <a href="{{ route('mhs.kelayakan') }}" class="nav-link {{ request()->routeIs('mhs.kelayakan*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard2-check-fill"></i> <span>Kelayakan Mahasiswa</span>
                </a>
            </nav>
        @else
            <nav class="nav flex-column">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                    <i class="bi bi-house-door-fill"></i> <span>Dashboard</span>
                </a>
            </nav>
            <div class="sidebar-section">Master Data</div>
            <nav class="nav flex-column">
                <a href="{{ route('prodi.index') }}" class="nav-link {{ request()->is('prodi*')?'active':'' }}"><i class="bi bi-building"></i> <span>Program Studi</span></a>
                <a href="{{ route('data-mahasiswa') }}" class="nav-link {{ request()->is('data-mahasiswa*','create-mahasiswa*','edit-mahasiswa*')?'active':'' }}"><i class="bi bi-people-fill"></i> <span>Mahasiswa</span></a>
                <a href="{{ route('dosen.index') }}" class="nav-link {{ request()->is('dosen*')?'active':'' }}"><i class="bi bi-person-badge-fill"></i> <span>Dosen</span></a>
                <a href="{{ route('matakuliah.index') }}" class="nav-link {{ request()->is('matakuliah*')?'active':'' }}"><i class="bi bi-journal-bookmark-fill"></i> <span>Mata Kuliah</span></a>
                <a href="{{ route('ruangan.index') }}" class="nav-link {{ request()->is('ruangan*')?'active':'' }}"><i class="bi bi-door-open-fill"></i> <span>Ruangan</span></a>
                <a href="{{ route('tahun_ajaran.index') }}" class="nav-link {{ request()->is('tahun_ajaran*')?'active':'' }}"><i class="bi bi-calendar-range-fill"></i> <span>Tahun Ajaran</span></a>
            </nav>
            <div class="sidebar-section">Kegiatan Belajar</div>
            <nav class="nav flex-column">
                <a href="{{ route('krs.index') }}" class="nav-link {{ request()->is('krs*')?'active':'' }}"><i class="bi bi-card-checklist"></i> <span>Transaksi KRS</span></a>
                <a href="{{ route('jadwal.index') }}" class="nav-link {{ request()->is('jadwal*')?'active':'' }}"><i class="bi bi-calendar3-week-fill"></i> <span>Jadwal Kuliah</span></a>
                <a href="{{ route('presensi.index') }}" class="nav-link {{ request()->is('presensi*')?'active':'' }}"><i class="bi bi-clipboard2-check-fill"></i> <span>Presensi</span></a>
                <a href="{{ route('materi.index') }}" class="nav-link {{ request()->is('materi*')?'active':'' }}"><i class="bi bi-file-earmark-text-fill"></i> <span>Materi Kuliah</span></a>
                <a href="{{ route('nilai.index') }}" class="nav-link {{ request()->is('nilai*')?'active':'' }}"><i class="bi bi-graph-up"></i> <span>Nilai</span></a>
                <a href="{{ route('pengumuman.index') }}" class="nav-link {{ request()->is('pengumuman*')?'active':'' }}"><i class="bi bi-megaphone-fill"></i> <span>Pengumuman</span></a>
            </nav>
            <div class="sidebar-section">Analisis</div>
            <nav class="nav flex-column">
                <a href="{{ route('kelayakan.index') }}" class="nav-link {{ request()->is('kelayakan*')?'active':'' }}"><i class="bi bi-clipboard2-check-fill"></i> <span>Kelayakan Mahasiswa</span></a>
            </nav>
            @if(Auth::user()->isAdmin())
            <div class="sidebar-section">Hak Akses</div>
            <nav class="nav flex-column">
                <a href="{{ route('roles.index') }}" class="nav-link {{ request()->is('roles*')?'active':'' }}"><i class="bi bi-shield-lock-fill"></i> <span>Kelola Role</span></a>
                <a href="{{ route('permissions.index') }}" class="nav-link {{ request()->is('permissions*')?'active':'' }}"><i class="bi bi-key-fill"></i> <span>Daftar Permission</span></a>
                <a href="{{ route('user-roles.index') }}" class="nav-link {{ request()->is('user-roles*')?'active':'' }}"><i class="bi bi-person-gear"></i> <span>User & Role</span></a>
            </nav>
            @endif
        @endif
    </div>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="bi bi-box-arrow-left"></i>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</div>

<div class="main-content">
    <div class="topbar">
        <div class="d-flex align-items-center gap-2">
            <button class="sidebar-toggle" id="sidebarToggle" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <div class="page-title">
                <div class="page-title-icon">
                    @if(View::hasSection('page-title-icon')){!! View::getSection('page-title-icon') !!}@else<i class="bi bi-grid"></i>@endif
                </div>
                @yield('page-title', 'Dashboard')
            </div>
        </div>
        <div class="user-badge">
            <div class="user-info text-end">
                <div class="name">{{ Auth::user()->name }}</div>
                <div class="role">{{ $roleLabel }}</div>
            </div>
            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <form action="{{ route('logout') }}" method="POST" class="mb-0">
                @csrf
                <button type="submit" class="btn-logout-top" title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="page-body">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-check-circle-fill"></i>{{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @yield('content')
    </div>
</div>

<button id="scroll-top" onclick="window.scrollTo({top:0,behavior:'smooth'})">
    <i class="bi bi-arrow-up"></i>
</button>

<div class="bottom-nav d-none d-md-none">
    @if($isMhs)
        <a href="{{ route('mhs.dashboard') }}" class="{{ request()->routeIs('mhs.dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door-fill"></i><span>Dashboard</span>
        </a>
        <a href="{{ route('mhs.krs') }}" class="{{ request()->routeIs('mhs.krs*') ? 'active' : '' }}">
            <i class="bi bi-card-checklist"></i><span>KRS</span>
        </a>
        <a href="{{ route('mhs.jadwal') }}" class="{{ request()->routeIs('mhs.jadwal*') ? 'active' : '' }}">
            <i class="bi bi-calendar3-week-fill"></i><span>Jadwal</span>
        </a>
        <a href="{{ route('mhs.presensi') }}" class="{{ request()->routeIs('mhs.presensi*') ? 'active' : '' }}">
            <i class="bi bi-clipboard2-check-fill"></i><span>Presensi</span>
        </a>
        <a href="#" onclick="toggleSidebar(); return false;" class="{{ request()->routeIs('mhs.materi*') || request()->routeIs('mhs.nilai*') || request()->routeIs('mhs.pengumuman*') || request()->routeIs('mhs.kelayakan*') ? 'active' : '' }}">
            <i class="bi bi-grid-3x3-gap-fill"></i><span>Lainnya</span>
        </a>
    @else
        <a href="{{ route('dashboard') }}" class="{{ request()->is('/') ? 'active' : '' }}">
            <i class="bi bi-house-door-fill"></i><span>Dashboard</span>
        </a>
        <a href="{{ route('krs.index') }}" class="{{ request()->is('krs*') ? 'active' : '' }}">
            <i class="bi bi-card-checklist"></i><span>KRS</span>
        </a>
        <a href="{{ route('jadwal.index') }}" class="{{ request()->is('jadwal*') ? 'active' : '' }}">
            <i class="bi bi-calendar3-week-fill"></i><span>Jadwal</span>
        </a>
        <a href="{{ route('data-mahasiswa') }}" class="{{ request()->is('data-mahasiswa*','create-mahasiswa*','edit-mahasiswa*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i><span>Mahasiswa</span>
        </a>
        <a href="#" onclick="toggleSidebar(); return false;" class="{{ request()->is('prodi*','dosen*','matakuliah*','ruangan*','tahun_ajaran*','presensi*','materi*','nilai*','pengumuman*','kelayakan*','roles*','permissions*','user-roles*') ? 'active' : '' }}">
            <i class="bi bi-grid-3x3-gap-fill"></i><span>Lainnya</span>
        </a>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function(){
    'use strict';

    // ── Sidebar ──
    window.toggleSidebar = function() {
        var s = document.getElementById('sidebar');
        var o = document.getElementById('sidebarOverlay');
        s.classList.toggle('open');
        o.classList.toggle('show');
        document.body.style.overflow = s.classList.contains('open') ? 'hidden' : '';
    };
    document.getElementById('sidebarOverlay')?.addEventListener('click', function() {
        document.getElementById('sidebar').classList.remove('open');
        this.classList.remove('show');
        document.body.style.overflow = '';
    });
    document.querySelectorAll('.sidebar .nav-link').forEach(function(el) {
        el.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                document.getElementById('sidebar').classList.remove('open');
                document.getElementById('sidebarOverlay').classList.remove('show');
                document.body.style.overflow = '';
            }
        });
    });

    // ── Page Load Progress ──
    var bar = document.getElementById('page-progress');
    if (bar) {
        bar.style.width = '20%';
        window.addEventListener('load', function() {
            bar.style.width = '100%';
            setTimeout(function() { bar.style.width = '0'; }, 400);
        });
    }

    // ── Scroll to Top ──
    var scrollBtn = document.getElementById('scroll-top');
    if (scrollBtn) {
        window.addEventListener('scroll', function() {
            scrollBtn.classList.toggle('show', window.scrollY > 300);
        });
    }

    // ── Auto-dismiss alerts ──
    document.querySelectorAll('.alert-dismissible').forEach(function(a) {
        setTimeout(function() {
            a.classList.remove('show');
            setTimeout(function() { a.remove(); }, 300);
        }, 5000);
    });

    // ── Ripple effect on buttons ──
    document.querySelectorAll('.btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            var rect = this.getBoundingClientRect();
            this.style.setProperty('--ripple-x', ((e.clientX - rect.left) / rect.width * 100) + '%');
            this.style.setProperty('--ripple-y', ((e.clientY - rect.top) / rect.height * 100) + '%');
        });
    });

    // ── Toast helper ──
    window.showToast = function(message, type) {
        type = type || 'success';
        var c = document.getElementById('toastContainer');
        if (!c) return;
        var t = document.createElement('div');
        t.className = 'toast-custom ' + type;
        var icon = type === 'success' ? 'bi-check-circle-fill' : (type === 'error' ? 'bi-x-circle-fill' : 'bi-info-circle-fill');
        t.innerHTML = '<i class="bi ' + icon + '"></i> ' + message;
        c.appendChild(t);
        setTimeout(function() {
            t.classList.add('toast-out');
            setTimeout(function() { t.remove(); }, 300);
        }, 3500);
    };
})();
</script>
</body>
</html>
