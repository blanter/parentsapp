<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('/file/lifebookicon.png')}}" rel='icon' type='image/x-icon' />
    <title>Teacher Dashboard - Lifebook Parents</title>
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
                <span
                    style="background: var(--db-purple); color: #fff; padding: 3px 10px; border-radius: 99px; font-size: 10px; font-weight: 800; text-transform: uppercase;">Teacher
                    Hub</span>
                <h1 style="margin-top: 5px;">Lifebook<br>Teacher</h1>
            </div>
            <div class="db-avatar-section" style="cursor: default;">
                <i data-lucide="user-check" style="color: var(--db-purple)"></i>
            </div>
        </div>

        <div class="db-greeting-wrapper">
            <div class="db-welcome-bubble">
                Hello, Teacher {{ Auth::guard('teacher')->user()->name }}!
            </div>
        </div>

        <div class="db-menu-container">
            <a href="{{ route('children-tracker.index') }}" class="db-menu-item gardening">
                <i data-lucide="book-open"></i>
                <span>Lifebook Children Tracker</span>
            </a>
            <a href="{{ route('teacher.profile') }}" class="db-menu-item journey"
                style="background: var(--db-purple); box-shadow: 0 8px 0px #4A63B3;">
                <i data-lucide="user-cog"></i>
                <span>Halaman Profil</span>
            </a>
        </div>

        <div
            style="text-align: center; margin-top: 50px; opacity: 0.3; font-size: 10px; font-weight: 700; color: var(--db-text-dark); margin-bottom: 100px;">
            Version {{ $appVersion ?? '1.0.0' }} • Teacher Hub
        </div>
    </div>

    <!-- Bottom Navigation -->
    <nav class="db-bottom-nav">
        <a href="{{ route('teacher.dashboard') }}" class="db-nav-item active">
            <div class="db-nav-icon">
                <i data-lucide="home"></i>
            </div>
            <span>Home</span>
        </a>
        <a href="{{ route('teacher.profile') }}" class="db-nav-item">
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