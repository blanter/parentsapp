<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('/file/lifebookicon.png')}}" rel='icon' type='image/x-icon' />
    <title>Children Tracker - Lifebook Parents</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="{{asset('/file/style.css')}}?v=21" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="db-body">
    <!-- Background Elements -->
    <img src="{{ asset('/file/bee.png') }}" class="db-bg-pattern db-bee" alt="">
    <img src="{{ asset('/file/flower.png') }}" class="db-bg-pattern db-flower" alt="">

    <div class="db-container">
        <div class="db-header">
            <div class="db-brand-section">
                <h1 style="font-size: 28px;">Hello<br><span
                        style="color: var(--db-purple)">{{ Auth::user()->name }}</span></h1>
                <p style="font-size: 14px; font-weight: 600; opacity: 0.6; margin-top: 5px;">Have a great day with your
                    children, parents</p>
            </div>
            <a href="{{ route('dashboard') }}" class="db-avatar-section"
                style="width: 50px; height: 50px; text-decoration: none;">
                <i data-lucide="chevron-left" style="font-size: 24px; opacity: 1;"></i>
            </a>
        </div>

        <h2 class="ct-section-title">Lifebook Children Tracker</h2>

        <div class="ct-list">
            <!-- Aspek Orang Tua -->
            <a href="#" class="ct-card">
                <div class="ct-card-icon active">
                    <i data-lucide="user"></i>
                </div>
                <div class="ct-card-info">
                    <h3>Aspek Orang Tua</h3>
                    <p>Monitoring peran orang tua</p>
                </div>
                <i data-lucide="chevron-right" style="margin-left: auto; opacity: 0.3;"></i>
            </a>

            <!-- Aspek Anak -->
            <a href="#" class="ct-card">
                <div class="ct-card-icon active">
                    <i data-lucide="users"></i>
                </div>
                <div class="ct-card-info">
                    <h3>Aspek Anak</h3>
                    <p>Monitoring perkembangan anak</p>
                </div>
                <i data-lucide="chevron-right" style="margin-left: auto; opacity: 0.3;"></i>
            </a>

            @foreach($months as $month)
                @if($loop->first || (!$month['completed'] && !$month['is_current']))
                    <a href="#" class="ct-card">
                        <div class="ct-card-icon {{ $month['is_current'] ? 'active' : '' }}">
                            <i data-lucide="calendar"></i>
                        </div>
                        <div class="ct-card-info">
                            <h3>{{ $month['name'] }}</h3>
                            <p>{{ $month['is_current'] ? 'Bulan ini (Sedang berjalan)' : 'Bulan lalu' }}</p>
                        </div>
                        @if($month['is_current'])
                            <div class="ct-badge" style="background: var(--db-purple); color: #fff;">Sedang Diisi</div>
                        @endif
                        <i data-lucide="chevron-right"
                            style="{{ $month['is_current'] ? '' : 'margin-left: auto;' }} opacity: 0.3;"></i>
                    </a>
                @endif
            @endforeach
        </div>

        <h2 class="ct-section-title">Selesai Diisi</h2>
        <div class="ct-list">
            @foreach($months as $month)
                @if($month['completed'])
                    <a href="#" class="ct-card">
                        <div class="ct-card-icon" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">
                            <i data-lucide="check-circle-2"></i>
                        </div>
                        <div class="ct-card-info">
                            <h3>{{ $month['name'] }}</h3>
                            <p>Data sudah tersimpan</p>
                        </div>
                        <div class="ct-badge completed">Selesai</div>
                    </a>
                @endif
            @endforeach

            @if(collect($months)->where('completed', true)->isEmpty())
                <div style="text-align: center; padding: 40px; opacity: 0.4;">
                    <i data-lucide="inbox" style="width: 40px; height: 40px; margin-bottom: 15px;"></i>
                    <p style="font-weight: 700; font-size: 14px;">Belum ada data yang selesai</p>
                </div>
            @endif
        </div>

        <div
            style="text-align: center; margin-top: 50px; opacity: 0.3; font-size: 10px; font-weight: 700; color: var(--db-text-dark); margin-bottom: 100px;">
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