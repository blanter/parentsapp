<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('/file/lifebookicon.png')}}" rel='icon' type='image/x-icon' />
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
                    <i data-lucide="user"></i>
                </div>
                <h2 class="profile-name">{{ Auth::user()->name }}</h2>
                <p class="profile-email">{{ Auth::user()->email }}</p>
            </div>

            <div class="profile-info-grid">
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

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">
                <i data-lucide="log-out"></i>
                <span>Keluar dari Aplikasi</span>
            </button>
        </form>
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