<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – BMNTrack</title>
    <meta name="description" content="Login ke sistem inventaris BMN kampus.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: #1a4fba;
            --primary-dark: #0f3591;
            --primary-light: #2563eb;
            --accent: #f59e0b;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #f0f4f8;
        }

        /* Left panel */
        .login-panel {
            flex: 1;
            background: linear-gradient(150deg, #0c2461 0%, #1a4fba 50%, #2563eb 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 40px;
            position: relative;
            overflow: hidden;
        }
        .login-panel::before {
            content: '';
            position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .panel-circles {
            position: absolute;
            top: -80px; right: -80px;
            width: 400px; height: 400px;
            border-radius: 50%;
            border: 80px solid rgba(255,255,255,.04);
        }
        .panel-circles::after {
            content: '';
            position: absolute;
            top: 80px; left: 80px;
            right: -160px; bottom: -160px;
            border-radius: 50%;
            border: 60px solid rgba(255,255,255,.04);
        }

        .panel-content { position: relative; z-index: 1; max-width: 400px; text-align: center; }
        .panel-logo {
            width: 80px; height: 80px;
            background: rgba(255,255,255,.1);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255,255,255,.2);
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            font-size: 38px; color: #fff; margin: 0 auto 28px;
        }
        .panel-title {
            font-size: 36px; font-weight: 800;
            color: #fff; margin-bottom: 12px;
            letter-spacing: -1px;
        }
        .panel-subtitle { font-size: 15px; color: rgba(255,255,255,.7); line-height: 1.6; margin-bottom: 36px; }

        .panel-features { display: flex; flex-direction: column; gap: 14px; }
        .panel-feature {
            display: flex; align-items: center; gap: 14px;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 12px; padding: 14px 16px;
            text-align: left;
        }
        .panel-feature-icon {
            width: 40px; height: 40px; flex-shrink: 0;
            background: rgba(255,255,255,.12);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 17px;
        }
        .panel-feature-text strong { display: block; color: #fff; font-size: 13px; font-weight: 600; }
        .panel-feature-text span { color: rgba(255,255,255,.6); font-size: 12px; }

        /* Right panel (form) */
        .login-form-wrapper {
            width: 480px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px 48px;
            background: #fff;
        }
        .form-header { margin-bottom: 36px; }
        .form-header h2 {
            font-size: 26px; font-weight: 800;
            color: #0f172a; margin-bottom: 6px;
        }
        .form-header p { font-size: 14px; color: #64748b; }

        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block; font-size: 13px; font-weight: 600;
            color: #0f172a; margin-bottom: 7px;
        }
        .input-wrapper {
            position: relative;
        }
        .input-icon {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%);
            color: #94a3b8; font-size: 15px; pointer-events: none;
        }
        .form-control {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px; color: #0f172a;
            font-family: 'Inter', sans-serif;
            outline: none; transition: all .2s;
        }
        .form-control:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(37,99,235,.1);
        }
        .form-control.is-invalid { border-color: #ef4444; }
        .form-error { font-size: 12px; color: #ef4444; margin-top: 5px; display: flex; align-items: center; gap: 4px; }
        .btn-eye {
            position: absolute; right: 14px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            color: #94a3b8; cursor: pointer; font-size: 15px;
            transition: color .2s;
        }
        .btn-eye:hover { color: var(--primary); }

        .form-row-flex { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
        .remember-label { display: flex; align-items: center; gap: 7px; font-size: 13px; color: #475569; cursor: pointer; }
        .remember-label input[type="checkbox"] { accent-color: var(--primary-light); width: 15px; height: 15px; }

        .btn-login {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            color: #fff; border: none; border-radius: 10px;
            font-size: 15px; font-weight: 700;
            cursor: pointer; transition: all .2s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            box-shadow: 0 4px 16px rgba(37,99,235,.4);
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(37,99,235,.5);
        }
        .btn-login:active { transform: translateY(0); }

        .alert {
            padding: 12px 16px;
            border-radius: 10px; font-size: 13px; font-weight: 500;
            display: flex; align-items: center; gap: 8px;
            margin-bottom: 20px;
            background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5;
        }

        .form-footer {
            margin-top: 28px; padding-top: 24px;
            border-top: 1px solid #f1f5f9;
            text-align: center;
        }
        .form-footer p { font-size: 12px; color: #94a3b8; }
        .form-footer strong { color: #64748b; }

        @media (max-width: 900px) {
            .login-panel { display: none; }
            .login-form-wrapper { width: 100%; padding: 40px 28px; }
        }
        @media (max-width: 480px) {
            .login-form-wrapper { padding: 32px 20px; }
        }
    </style>
</head>
<body>
<div class="login-panel">
    <div class="panel-circles"></div>
    <div class="panel-content">
        <div class="panel-logo">
            <i class="fas fa-boxes-stacked"></i>
        </div>
        <h1 class="panel-title">BMNTrack</h1>
        <p class="panel-subtitle">
            Sistem Inventarisasi Barang Milik Negara<br>
            untuk pengelolaan aset kampus secara modern, akurat, dan efisien.
        </p>
        <div class="panel-features">
            <div class="panel-feature">
                <div class="panel-feature-icon"><i class="fas fa-building"></i></div>
                <div class="panel-feature-text">
                    <strong>Manajemen Gedung & Ruangan</strong>
                    <span>Kelola data gedung dan ruangan dengan mudah</span>
                </div>
            </div>
            <div class="panel-feature">
                <div class="panel-feature-icon"><i class="fas fa-camera"></i></div>
                <div class="panel-feature-text">
                    <strong>Foto via Kamera / Galeri</strong>
                    <span>Dokumentasikan aset langsung dari perangkat</span>
                </div>
            </div>
            <div class="panel-feature">
                <div class="panel-feature-icon"><i class="fas fa-chart-pie"></i></div>
                <div class="panel-feature-text">
                    <strong>Dashboard Statistik Real-time</strong>
                    <span>Pantau kondisi seluruh aset dalam satu tampilan</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="login-form-wrapper">
    <div class="form-header">
        <h2>Selamat Datang 👋</h2>
        <p>Masuk ke sistem untuk mengelola inventaris BMN kampus.</p>
    </div>

    @if($errors->any())
    <div class="alert">
        <i class="fas fa-circle-exclamation"></i>
        {{ $errors->first() }}
    </div>
    @endif

    @if(session('success'))
    <div class="alert" style="background:#d1fae5;color:#065f46;border-color:#a7f3d0;">
        <i class="fas fa-circle-check"></i>
        {{ session('success') }}
    </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}" id="loginForm">
        @csrf
        <div class="form-group">
            <label class="form-label" for="username">Username</label>
            <div class="input-wrapper">
                <i class="fas fa-user input-icon"></i>
                <input
                    type="text"
                    id="username"
                    name="username"
                    class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                    value="{{ old('username') }}"
                    placeholder="Masukkan username"
                    autocomplete="username"
                    autofocus
                >
            </div>
            @error('username')
            <div class="form-error"><i class="fas fa-triangle-exclamation"></i> {{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <div class="input-wrapper">
                <i class="fas fa-lock input-icon"></i>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                    placeholder="Masukkan password"
                    autocomplete="current-password"
                >
                <button type="button" class="btn-eye" id="togglePass">
                    <i class="fas fa-eye" id="eyeIcon"></i>
                </button>
            </div>
            @error('password')
            <div class="form-error"><i class="fas fa-triangle-exclamation"></i> {{ $message }}</div>
            @enderror
        </div>

        <div class="form-row-flex">
            <label class="remember-label">
                <input type="checkbox" name="remember"> Ingat saya
            </label>
        </div>

        <button type="submit" class="btn-login" id="loginBtn">
            <i class="fas fa-right-to-bracket"></i>
            <span>Masuk ke Sistem</span>
        </button>
    </form>

    <div class="form-footer">
        <p>Akun Demo: <strong>admin</strong> / <strong>admin123</strong></p>
        <p style="margin-top:4px;">Atau: <strong>petugas</strong> / <strong>petugas123</strong></p>
    </div>
</div>

<script>
    // Toggle password visibility
    document.getElementById('togglePass').addEventListener('click', function () {
        const input = document.getElementById('password');
        const icon  = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'fas fa-eye';
        }
    });

    // Loading state on submit
    document.getElementById('loginForm').addEventListener('submit', function () {
        const btn = document.getElementById('loginBtn');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Memproses...</span>';
        btn.disabled = true;
    });
</script>
</body>
</html>
