<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('/file/lifebookicon.png')}}" rel='icon' type='image/x-icon' />
    <title>Admin Dashboard - Parents App</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="{{asset('/file/style.css')}}?v=20" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="db-body">
    <!-- Background Elements -->
    <img src="{{ asset('/file/bee.png') }}" class="db-bg-pattern db-bee" alt="">
    <img src="{{ asset('/file/flower.png') }}" class="db-bg-pattern db-flower" alt="">

    <div class="adm-page-container">
        <div class="adm-header">
            <div class="adm-brand">
                <h1>Admin Dashboard</h1>
                <p>Welcome back, Admin!</p>
            </div>
            <a href="{{ route('profile') }}" class="db-avatar-section" style="width: 50px; height: 50px;">
                @if(Auth::user()->avatar)
                    <img src="{{ asset('avatars/' . Auth::user()->avatar) }}" alt="Avatar"
                        style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <i data-lucide="user"></i>
                @endif
            </a>
        </div>

        <div class="adm-menu-wrapper">
            <a href="{{ route('admin.dashboard') }}"
                class="adm-menu-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                <i data-lucide="layout-grid"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('parents.index') }}"
                class="adm-menu-item {{ Route::is('parents.index') || Route::is('score.edit') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard"></i>
                <span>Parents Score</span>
            </a>
            <a href="{{ route('admin.users') }}" class="adm-menu-item {{ Route::is('admin.users') ? 'active' : '' }}">
                <i data-lucide="users"></i>
                <span>Manage Users</span>
            </a>
            <a href="{{ route('admin.settings') }}"
                class="adm-menu-item {{ Route::is('admin.settings') ? 'active' : '' }}">
                <i data-lucide="settings"></i>
                <span>System Settings</span>
            </a>
        </div>

        <div class="adm-dash-grid">
            <!-- Users Card -->
            <div class="adm-dash-card users">
                <div class="adm-dash-icon">
                    <i data-lucide="users"></i>
                </div>
                <div class="adm-dash-info">
                    <h3>Total Users</h3>
                    <div class="value">{{ $totalUsers }}</div>
                </div>
                @if($pendingUsers > 0)
                    <div class="adm-dash-stat pending">
                        <i data-lucide="clock" style="width: 14px; height: 14px;"></i>
                        <span>{{ $pendingUsers }} pending approval</span>
                    </div>
                @else
                    <div class="adm-dash-stat up">
                        <i data-lucide="check-circle" style="width: 14px; height: 14px;"></i>
                        <span>All approved</span>
                    </div>
                @endif
            </div>

            <!-- Gardening Card -->
            <div class="adm-dash-card gardening">
                <div class="adm-dash-icon">
                    <i data-lucide="sprout"></i>
                </div>
                <div class="adm-dash-info">
                    <h3>Home Gardening</h3>
                    <div class="value">{{ $totalGardening }}</div>
                </div>
                <div class="adm-dash-stat up">
                    <i data-lucide="trending-up" style="width: 14px; height: 14px;"></i>
                    <span>+{{ $recentGardening }} new this week</span>
                </div>
            </div>

            <!-- Missions Card -->
            <div class="adm-dash-card missions">
                <div class="adm-dash-icon">
                    <i data-lucide="award"></i>
                </div>
                <div class="adm-dash-info">
                    <h3>Missions Done</h3>
                    <div class="value">{{ $thisWeekCompletions }}</div>
                </div>
                <div class="adm-dash-stat pending">
                    <i data-lucide="calendar" style="width: 14px; height: 14px;"></i>
                    <span>{{ $todayCompletions }} completed today</span>
                </div>
            </div>

            <!-- Coming Soon Card -->
            <div class="adm-dash-card coming">
                <div class="adm-dash-icon">
                    <i data-lucide="activity"></i>
                </div>
                <div class="adm-dash-info">
                    <h3>Coming Soon</h3>
                    <div class="value">-</div>
                </div>
                <div class="adm-dash-stat">
                    <span>Children tracker monitor</span>
                </div>
            </div>
        </div>

        <div class="db-menu-container"
            style="margin-top: 10px; margin-bottom: 40px; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));">
            <a href="{{ route('gardening.index') }}" class="db-menu-item gardening">
                <i data-lucide="sprout"></i>
                <span>Home Gardening</span>
            </a>
            <a href="{{ route('volunteer.index') }}" class="db-menu-item mission">
                <i data-lucide="award"></i>
                <span>Volunteer Mission</span>
            </a>
            <a href="{{ route('coming-soon') }}" class="db-menu-item children-tracker">
                <i data-lucide="activity"></i>
                <span>Lifebook Children Tracker</span>
            </a>
            <a href="{{ route('coming-soon') }}" class="db-menu-item learning-tracker">
                <i data-lucide="book-open"></i>
                <span>My Kids Learning Tracker</span>
            </a>
            <a href="#" class="db-menu-item journey">
                <i data-lucide="map"></i>
                <span>Parents Lifebook Journey</span>
            </a>
        </div>

        <div
            style="text-align: center; margin-top: 40px; opacity: 0.3; font-size: 10px; font-weight: 700; color: var(--db-text-dark);">
            Version {{ $appVersion }} • Parents App
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</body>

</html>