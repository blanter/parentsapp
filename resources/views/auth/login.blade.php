<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('/file/lifebookicon.png')}}" rel='icon' type='image/x-icon' />
    <link rel="manifest" href="{{ asset('/manifest.json') }}">
    <meta name="theme-color" content="#FFD64B">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Parents App">
    <link rel="apple-touch-icon" href="{{ asset('/file/lifebookicon.png') }}">
    <title>Login - Lifebook Parents</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="{{asset('/file/style.css')}}?v=10" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="auth-body">
    <!-- Background Elements -->
    <img src="{{ asset('/file/bee.png') }}" class="db-bg-pattern db-bee" alt="">
    <img src="{{ asset('/file/flower.png') }}" class="db-bg-pattern db-flower" alt="">

    <div class="auth-container">
        <div class="auth-card">
            <div style="text-align: center; margin-bottom: 20px;">
                <span
                    style="background: var(--db-purple); color: #fff; padding: 5px 15px; border-radius: 99px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">Akses
                    Orang Tua / Admin</span>
            </div>

            <h1 class="auth-title">Welcome Back!</h1>
            <p class="auth-subtitle">Masuk untuk mengelola misi dan jurnal.</p>

            <!-- Teacher Login Notification -->
            <div
                style="background: rgba(108, 136, 224, 0.08); border: 1px dashed var(--db-purple); padding: 12px; border-radius: 15px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px;">
                <div
                    style="background: var(--db-purple); width: 35px; height: 35px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; flex-shrink: 0;">
                    <i data-lucide="graduation-cap" style="width: 20px; height: 20px;"></i>
                </div>
                <div style="font-size: 11px; font-weight: 600; color: var(--db-text-dark); flex-grow: 1;">
                    Anda seorang guru?
                    <a href="{{ route('teacher.login') }}"
                        style="color: var(--db-purple); font-weight: 800; text-decoration: underline;">Masuk di Halaman
                        Guru</a>
                </div>
            </div>

            <div class="auth-messages">
                @if(session('error'))
                    <div class="msg error">{{ session('error') }}</div>
                @endif
                @if(session('success'))
                    <div class="msg success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="msg error">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
            </div>

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="auth-form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="auth-form-control" placeholder="akun@email.com"
                        required value="{{ old('email') }}">
                </div>
                <div class="auth-form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="auth-form-control"
                        placeholder="••••••••" required>
                </div>

                <button type="submit" class="auth-btn-primary">
                    <i data-lucide="log-in"></i>
                    <span>Masuk Dashboard</span>
                </button>
            </form>

            <div class="auth-footer" style="margin-top: 30px; padding-top: 20px; border-top: 2px dashed #F3F4F6;">
                <p style="font-size: 13px; color: #6B7280; font-weight: 600; margin-bottom: 12px;">Belum memiliki akun
                    orang tua?</p>
                <a href="{{ route('register') }}" class="auth-btn-primary"
                    style="background: #fff; color: var(--db-purple); border: 2px solid var(--db-purple); box-shadow: 0 6px 0px rgba(108, 136, 224, 0.2); text-decoration: none; width: auto; display: inline-flex; padding: 10px 25px;">
                    <i data-lucide="user-plus" style="width: 18px; height: 18px;"></i>
                    <span>Daftar Sekarang</span>
                </a>
                
                <div style="margin-top: 20px;">
                    <a href="{{ route('app.download') }}" style="color: #6B7280; font-size: 11px; font-weight: 700; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 5px;">
                        <i data-lucide="download" style="width: 12px; height: 12px;"></i>
                        Cara Pasang Aplikasi di HP (PWA)
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>