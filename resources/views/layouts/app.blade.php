<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('/file/lifebookicon.png') }}" rel='icon' type='image/x-icon' />
    <link rel="manifest" href="{{ asset('/manifest.json') }}">
    <meta name="theme-color" content="#FFD64B">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Parents App">
    <link rel="apple-touch-icon" href="{{ asset('/file/lifebookicon.png') }}">
    <title>@yield('title', 'Lifebook Parents')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="{{ asset('/file/style.css') }}?v=17" rel="stylesheet" />

    @yield('styles')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="@yield('body-class', 'db-body')">
    @yield('content')

    <!-- Bottom Navigation -->
    <nav class="db-bottom-nav">
        @if(Auth::guard('teacher')->check())
            <a href="{{ route('teacher.dashboard') }}" class="db-nav-item {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
                <div class="db-nav-icon">
                    <i data-lucide="home"></i>
                </div>
                <span>Home</span>
            </a>
            <a href="{{ route('teacher.profile') }}" class="db-nav-item {{ request()->routeIs('teacher.profile') ? 'active' : '' }}">
                <div class="db-nav-icon">
                    <i data-lucide="user"></i>
                </div>
                <span>Profile</span>
            </a>
        @else
            <a href="{{ route('dashboard') }}" class="db-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <div class="db-nav-icon">
                    <i data-lucide="home"></i>
                </div>
                <span>Home</span>
            </a>
            <a href="{{ route('parents.leaderboard') }}" class="db-nav-item {{ request()->routeIs('parents.leaderboard') ? 'active' : '' }}">
                <div class="db-nav-icon">
                    <i data-lucide="trophy"></i>
                </div>
                <span>Scores</span>
            </a>
            <a href="{{ route('profile') }}" class="db-nav-item {{ (request()->routeIs('profile') || request()->routeIs('profile.settings')) ? 'active' : '' }}">
                <div class="db-nav-icon">
                    <i data-lucide="user"></i>
                </div>
                <span>Profile</span>
            </a>
        @endif
    </nav>

    @yield('scripts')

    <script>
        // Initialize Lucide icons
        lucide.createIcons();
    </script>
</body>

</html>