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
            <a href="#" class="db-menu-item mission">
                <i data-lucide="book-open"></i>
                <span>Review Jurnal Murid</span>
            </a>
            <a href="#" class="db-menu-item gardening">
                <i data-lucide="users"></i>
                <span>Data Murid & Orang Tua</span>
            </a>
            <a href="#" class="db-menu-item journey">
                <i data-lucide="settings"></i>
                <span>Pengaturan Akun</span>
            </a>

            <form action="{{ route('teacher.logout') }}" method="POST" style="width: 100%;">
                @csrf
                <button type="submit" class="db-menu-item"
                    style="width: 100%; border: none; background: #EF4444; box-shadow: 0 8px 0px #991B1B; color: #fff; text-align: left;">
                    <i data-lucide="log-out"></i>
                    <span>Keluar Aplikasi</span>
                </button>
            </form>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>