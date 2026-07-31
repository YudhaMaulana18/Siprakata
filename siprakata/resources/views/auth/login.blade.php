<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Login — SIPRAKATA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        :root {
            --primary: #18181b;
            --accent: #f59e0b;
            --text-dark: #18181b;
            --text-muted: #71717a;
            --border: #e4e4e7;
            --ease: cubic-bezier(0.4, 0, 0.2, 1);
        }
        body {
            margin: 0; min-height: 100vh;
            display: flex;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #0f0f13;
        }

        .left-panel {
            flex: 1;
            background: linear-gradient(145deg, #0f0f13 0%, #1a1a2e 40%, #16213e 70%, #0f3460 100%);
            position: relative; overflow: hidden;
            display: flex; align-items: center; justify-content: center;
        }
        .left-panel::before {
            content: '';
            position: absolute; inset: 0;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(245,158,11,0.08) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(99,102,241,0.06) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 80%, rgba(245,158,11,0.04) 0%, transparent 50%);
        }

        /* Animated floating shapes */
        .shape { position: absolute; border-radius: 50%; }
        .shape-1 { width: 500px; height: 500px; top: -200px; right: -150px; background: radial-gradient(circle, rgba(245,158,11,0.04), transparent 70%); }
        .shape-2 { width: 350px; height: 350px; bottom: -120px; left: -100px; background: radial-gradient(circle, rgba(99,102,241,0.05), transparent 70%); }
        .shape-3 { width: 150px; height: 150px; top: 30%; left: 10%; background: radial-gradient(circle, rgba(245,158,11,0.06), transparent); }
        .shape-4 { width: 80px; height: 80px; top: 15%; right: 20%; background: radial-gradient(circle, rgba(255,255,255,0.02), transparent); }

        @keyframes float1 { 0%,100%{transform:translate(0,0)rotate(0)} 33%{transform:translate(30px,-30px)rotate(5deg)} 66%{transform:translate(-20px,20px)rotate(-3deg)} }
        @keyframes float2 { 0%,100%{transform:translate(0,0)rotate(0)} 33%{transform:translate(-30px,20px)rotate(-5deg)} 66%{transform:translate(20px,-30px)rotate(3deg)} }
        @keyframes float3 { 0%,100%{transform:translate(0,0)scale(1)} 50%{transform:translate(10px,15px)scale(1.1)} }
        .shape-1 { animation: float1 20s ease-in-out infinite; }
        .shape-2 { animation: float2 25s ease-in-out infinite; }
        .shape-3 { animation: float3 15s ease-in-out infinite; }

        .left-content {
            position: relative; z-index: 2; text-align: center;
            padding: 40px; max-width: 420px;
            animation: fadeIn 0.8s var(--ease);
        }
        @keyframes fadeIn { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }

        .left-content .icon-main {
            width: 80px; height: 80px;
            background: linear-gradient(135deg, var(--accent), #d97706);
            border-radius: 20px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 2.2rem; color: #fff; margin-bottom: 24px;
            box-shadow: 0 12px 40px rgba(245,158,11,0.3);
            transition: transform 0.3s var(--ease);
        }
        .left-content .icon-main:hover { transform: scale(1.05) rotate(-5deg); }
        .left-content h2 { color: #fff; font-size: 2rem; font-weight: 800; margin: 0 0 8px; letter-spacing: -0.5px; }
        .left-content p { color: rgba(255,255,255,0.45); font-size: 0.9rem; line-height: 1.7; margin: 0; }
        .features {
            margin-top: 36px; display: flex; gap: 16px;
            justify-content: center; flex-wrap: wrap;
        }
        .feature-item {
            color: rgba(255,255,255,0.4);
            font-size: 0.68rem; font-weight: 500;
            display: flex; flex-direction: column; align-items: center; gap: 8px;
            transition: all 0.3s var(--ease);
            padding: 12px 16px; border-radius: 12px;
        }
        .feature-item:hover {
            color: rgba(255,255,255,0.8);
            background: rgba(255,255,255,0.03);
            transform: translateY(-4px);
        }
        .feature-item .feat-icon {
            width: 48px; height: 48px;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.15rem; color: var(--accent);
            background: rgba(255,255,255,0.02);
            transition: all 0.3s var(--ease);
        }
        .feature-item:hover .feat-icon {
            background: rgba(245,158,11,0.1);
            border-color: rgba(245,158,11,0.2);
            transform: scale(1.1);
        }

        /* ── Right Panel ── */
        .right-panel {
            width: 440px; min-height: 100vh; background: #fff;
            display: flex; flex-direction: column; justify-content: center;
            padding: 48px;
            animation: slideIn 0.5s var(--ease) 0.2s both;
        }
        @keyframes slideIn { from{opacity:0;transform:translateX(20px)} to{opacity:1;transform:translateX(0)} }

        .form-header { margin-bottom: 32px; }
        .form-header .brand {
            display: flex; align-items: center; gap: 10px; margin-bottom: 32px;
        }
        .form-header .brand-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--accent), #d97706);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: 0.9rem;
        }
        .form-header .brand-text { font-weight: 700; font-size: 0.95rem; color: var(--text-dark); }
        .form-header h3 {
            font-size: 1.4rem; font-weight: 800; color: var(--text-dark);
            margin: 0 0 6px; letter-spacing: -0.3px;
        }
        .form-header p { color: var(--text-muted); font-size: 0.85rem; margin: 0; line-height: 1.5; }
        .form-group { margin-bottom: 18px; }
        .form-group label {
            font-weight: 500; font-size: 0.8rem; color: var(--text-dark);
            margin-bottom: 6px; display: block;
        }
        .input-wrapper { position: relative; }
        .input-wrapper .input-icon {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            color: #a1a1aa; font-size: 0.95rem; transition: color 0.2s;
        }
        .input-wrapper .form-control {
            width: 100%; padding: 11px 14px 11px 40px;
            border: 1.5px solid var(--border);
            border-radius: 10px; font-size: 0.86rem;
            font-family: 'Inter', sans-serif; color: var(--text-dark);
            transition: all 0.2s; background: #fafafa;
        }
        .input-wrapper .form-control:focus {
            outline: none; border-color: var(--accent);
            background: #fff; box-shadow: 0 0 0 4px rgba(245,158,11,0.08);
        }
        .input-wrapper:focus-within .input-icon { color: var(--accent); }
        .input-wrapper .form-control::placeholder { color: #c0c0c8; }

        .btn-login {
            width: 100%; padding: 12px;
            background: linear-gradient(135deg, #18181b, #27272a);
            border: none; border-radius: 10px;
            color: #fff; font-size: 0.9rem; font-weight: 600;
            font-family: 'Inter', sans-serif; cursor: pointer;
            transition: all 0.25s var(--ease);
            display: flex; align-items: center; justify-content: center; gap: 8px;
            margin-top: 8px; position: relative; overflow: hidden;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(24,24,27,0.3);
        }
        .btn-login:active { transform: translateY(0); }
        .btn-login.loading { pointer-events: none; opacity: 0.8; }
        .btn-login.loading .btn-text { visibility: hidden; }
        .btn-login.loading::after {
            content: '';
            position: absolute; width: 20px; height: 20px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: #fff; border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .alert {
            border-radius: 10px; font-size: 0.82rem; border: none;
            padding: 12px 16px; display: flex; align-items: center; gap: 8px;
            margin-bottom: 18px; animation: slideDown 0.3s var(--ease);
        }
        @keyframes slideDown { from{opacity:0;transform:translateY(-8px)} to{opacity:1;transform:translateY(0)} }
        .alert-danger { background: #fef2f2; color: #991b1b; }
        .alert-success { background: #ecfdf5; color: #065f46; }

        .remember-label {
            display: flex; align-items: center; gap: 8px; cursor: pointer;
            font-size: 0.82rem; color: #64748b; margin-top: -4px;
            transition: color 0.2s;
        }
        .remember-label:hover { color: var(--text-dark); }
        .remember-label input[type="checkbox"] {
            width: 16px; height: 16px; margin: 0;
            accent-color: var(--accent); border-radius: 4px;
        }

        @media (max-width: 900px) {
            .left-panel { display: none; }
            .right-panel {
                width: 100%; min-height: 100vh;
                padding: 32px 24px;
            }
            .form-header h3 { font-size: 1.25rem; }
        }
    </style>
</head>
<body>

<div class="left-panel">
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>
    <div class="shape shape-4"></div>
    <div class="left-content">
        <div class="icon-main"><i class="bi bi-mortarboard-fill"></i></div>
        <h2>SIPRAKATA</h2>
        <p>Sistem Informasi Manajemen Kegiatan Akademik<br>Terintegrasi, Cepat, dan Andal.</p>
        <div class="features">
            <div class="feature-item">
                <div class="feat-icon"><i class="bi bi-card-list"></i></div>
                KRS
            </div>
            <div class="feature-item">
                <div class="feat-icon"><i class="bi bi-calendar-week"></i></div>
                Jadwal
            </div>
            <div class="feature-item">
                <div class="feat-icon"><i class="bi bi-clipboard-check"></i></div>
                Presensi
            </div>
            <div class="feature-item">
                <div class="feat-icon"><i class="bi bi-graph-up-arrow"></i></div>
                Nilai
            </div>
        </div>
    </div>
</div>

<div class="right-panel">
    <div class="form-header">
        <div class="brand">
            <div class="brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
            <span class="brand-text">SIPRAKATA</span>
        </div>
        <h3>Masuk ke Akun</h3>
        <p>Silakan masukkan kredensial Anda untuk melanjutkan</p>
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-circle-fill"></i>
        {{ $errors->first() }}
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success">
        <i class="bi bi-check-circle-fill"></i>
        {{ session('success') }}
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf
        <div class="form-group">
            <label for="email">Email</label>
            <div class="input-wrapper">
                <i class="bi bi-envelope input-icon"></i>
                <input type="email" id="email" name="email" class="form-control"
                    placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
            </div>
        </div>
        <div class="form-group">
            <label for="password">Kata Sandi</label>
            <div class="input-wrapper">
                <i class="bi bi-lock input-icon"></i>
                <input type="password" id="password" name="password" class="form-control"
                    placeholder="Masukkan kata sandi" required>
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <label class="remember-label">
                <input type="checkbox" name="remember">
                Ingat saya
            </label>
        </div>
        <button type="submit" class="btn-login" id="loginBtn">
            <i class="bi bi-box-arrow-in-right"></i>
            <span class="btn-text">Masuk</span>
        </button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function() {
    var form = document.getElementById('loginForm');
    var btn = document.getElementById('loginBtn');
    if (form) {
        form.addEventListener('submit', function() {
            btn.classList.add('loading');
        });
    }
})();
</script>
</body>
</html>
