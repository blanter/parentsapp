<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('/file/lifebookicon.png')}}" rel='icon' type='image/x-icon' />
    <title>Coming Soon - Lifebook Parents</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="{{asset('/file/style.css')}}?v=19" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="db-body">
    <!-- Background Elements -->
    <img src="{{ asset('/file/bee.png') }}" class="db-bg-pattern db-bee" alt="">
    <img src="{{ asset('/file/flower.png') }}" class="db-bg-pattern db-flower" alt="">

    <div class="db-container cs-container">
        <div class="cs-box">
            <div class="cs-icon-wrapper">
                <i data-lucide="construction"></i>
            </div>

            <h1 class="cs-title">Fitur Belum Tersedia</h1>
            <p class="cs-subtitle">
                Halaman ini sedang dalam tahap pengembangan. <br>Nantikan pembaruan selanjutnya ya!
            </p>

            <a href="{{ route('dashboard') }}" class="auth-btn-primary"
                style="text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 10px;">
                <i data-lucide="arrow-left"></i>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>

        <div class="cs-footer">
            Version {{ $appVersion }} • Parents App
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
        lucide.createIcons();
    </script>
</body>

</html>