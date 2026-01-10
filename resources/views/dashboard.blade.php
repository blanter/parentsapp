<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('/file/lifebookicon.png')}}" rel='icon' type='image/x-icon' />
    <title>Dashboard - Lifebook Parents</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="{{asset('/file/style.css')}}?v=9" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="db-body">
    <!-- Background Elements -->
    <img src="{{ asset('/file/bee.png') }}" class="db-bg-pattern db-bee" alt="">
    <img src="{{ asset('/file/flower.png') }}" class="db-bg-pattern db-flower" alt="">

    <div class="db-container">
        <div class="db-header">
            <div class="db-brand-section">
                <h1>Lifebook<br>Parents</h1>
            </div>
            <a href="{{ route('profile') }}" class="db-avatar-section">
                @if(Auth::user()->avatar)
                    <img src="{{ asset('avatars/' . Auth::user()->avatar) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <i data-lucide="user"></i>
                @endif
            </a>
        </div>

        <div class="db-greeting-wrapper">
            <div class="db-welcome-bubble">
                Hello! {{ Auth::user()->name }}
            </div>
        </div>

        <a href="{{ route('parents.leaderboard') }}" class="db-promo-button">
            <i data-lucide="trophy"></i>
            <span>Poin & Leaderboard</span>
        </a>

        <div class="db-menu-container">
            <a href="{{ route('gardening.index') }}" class="db-menu-item gardening">
                <i data-lucide="sprout"></i>
                <span>Home Gardening</span>
            </a>
            <a href="#" class="db-menu-item mission">
                <i data-lucide="award"></i>
                <span>Volunteer Mission</span>
            </a>
            <a href="#" class="db-menu-item children-tracker">
                <i data-lucide="activity"></i>
                <span>Lifebook Children Tracker</span>
            </a>
            <a href="#" class="db-menu-item learning-tracker">
                <i data-lucide="book-open"></i>
                <span>My Kids Learning Tracker</span>
            </a>
            <a href="#" class="db-menu-item journey">
                <i data-lucide="map"></i>
                <span>Parents Lifebook Journey</span>
            </a>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <nav class="db-bottom-nav">
        <a href="{{ route('dashboard') }}" class="db-nav-item active">
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
        <a href="{{ route('profile') }}" class="db-nav-item">
            <div class="db-nav-icon">
                <i data-lucide="user"></i>
            </div>
            <span>Profile</span>
        </a>
    </nav>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();
    </script>
</body>

</html>