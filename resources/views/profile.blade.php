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
    <title>Profile - Lifebook Parents</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="{{asset('/file/style.css')}}?v=11" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="db-body">
    <!-- Background Elements -->
    <img src="{{ asset('/file/bee.png') }}" class="db-bg-pattern db-bee" alt="">
    <img src="{{ asset('/file/flower.png') }}" class="db-bg-pattern db-flower" alt="">

    <div class="db-container">
        <div class="db-header">
            <div class="db-brand-section">
                <h1 style="font-size: 28px;">My Profile</h1>
            </div>
            <a href="{{ route('dashboard') }}" class="db-avatar-section"
                style="width: 50px; height: 50px; text-decoration: none;">
                <i data-lucide="chevron-left" style="font-size: 24px; opacity: 1;"></i>
            </a>
        </div>

        <div class="profile-card">
            <div class="profile-header-info">
                <div class="profile-avatar-large">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('avatars/' . Auth::user()->avatar) }}" alt="Avatar"
                            style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <i data-lucide="user"></i>
                    @endif
                </div>
                <h2 class="profile-name">{{ Auth::user()->name }}</h2>
                <p class="profile-email">{{ Auth::user()->email }}</p>
            </div>

            <div class="profile-info-grid">
                <a href="{{ route('profile.settings') }}" class="profile-info-item"
                    style="text-decoration: none; border-color: var(--db-purple); background: rgba(108, 136, 224, 0.05);">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <i data-lucide="settings" style="color: var(--db-purple); width: 20px; height: 20px;"></i>
                        <span class="profile-info-label" style="opacity: 1; font-size: 14px;">Pengaturan Akun</span>
                    </div>
                    <i data-lucide="chevron-right" style="color: var(--db-purple); width: 20px; height: 20px;"></i>
                </a>

                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.settings') }}" class="profile-info-item"
                        style="text-decoration: none; border-color: var(--db-accent); background: rgba(255, 107, 74, 0.05);">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <i data-lucide="shield" style="color: var(--db-accent); width: 20px; height: 20px;"></i>
                            <span class="profile-info-label" style="opacity: 1; font-size: 14px;">Sistem Settings</span>
                        </div>
                        <i data-lucide="chevron-right" style="color: var(--db-accent); width: 20px; height: 20px;"></i>
                    </a>
                @endif
                <div class="profile-info-item">
                    <span class="profile-info-label">Status Akun</span>
                    <span class="profile-info-value" style="color: var(--db-secondary);">Terverifikasi</span>
                </div>
                <div class="profile-info-item">
                    <span class="profile-info-label">Member Sejak</span>
                    <span class="profile-info-value">{{ Auth::user()->created_at->format('d M Y') }}</span>
                </div>
                <div class="profile-info-item">
                    <span class="profile-info-label">Role</span>
                    <span class="profile-info-value" style="text-transform: capitalize;">{{ Auth::user()->role }}</span>
                </div>
            </div>
        </div>

        <div class="profile-stat-container">
            <div class="profile-stat-box points">
                <div class="profile-stat-value">0</div>
                <div class="profile-stat-label">Total Poin</div>
            </div>
            <div class="profile-stat-box rank">
                <div class="profile-stat-value">#--</div>
                <div class="profile-stat-label">Peringkat</div>
            </div>
        </div>

        <!-- PWA Download Item -->
        <a href="{{ route('app.download') }}" class="profile-info-item"
            style="text-decoration: none; border-color: var(--db-primary); background: #ffffff; margin-bottom: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
            <div style="display: flex; align-items: center; gap: 15px;">
                <div
                    style="background: var(--db-primary); width: 44px; height: 44px; border-radius: 14px; display: flex; align-items: center; justify-content: center; color: var(--db-text-dark); box-shadow: 0 4px 10px rgba(255, 214, 75, 0.3);">
                    <i data-lucide="download-cloud" style="width: 22px; height: 22px;"></i>
                </div>
                <div>
                    <span class="profile-info-label"
                        style="opacity: 1; font-size: 14px; font-weight: 800; display: block; color: var(--db-text-dark);">Pasang
                        Aplikasi HP</span>
                    <span style="font-size: 11px; opacity: 0.6; font-weight: 600; color: var(--db-text-dark);">Panduan
                        instalasi resmi PWA</span>
                </div>
            </div>
            <i data-lucide="chevron-right" style="color: var(--db-primary); width: 22px; height: 22px;"></i>
        </a>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">
                <i data-lucide="log-out"></i>
                <span>Keluar dari Aplikasi</span>
            </button>
        </form>

        <div class="profile-footer-version">
            Version {{ $appVersion }} • Parents App
        </div>
    </div>

    <!-- Bottom Navigation -->
    <nav class="db-bottom-nav">
        <a href="{{ route('dashboard') }}" class="db-nav-item">
            <div class="db-nav-icon">
                <i data-lucide="home"></i>
            </div>
            <span>Home</span>
        </a>
        <a href="{{ route('parents.leaderboard') }}" class="db-nav-item">
            <div class="db-nav-icon">
                <i data-lucide="trophy"></i>
            </div>
            <span>Scores</span>
        </a>
        <a href="{{ route('profile') }}" class="db-nav-item active">
            <div class="db-nav-icon">
                <i data-lucide="user"></i>
            </div>
            <span>Profile</span>
        </a>
    </nav>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>