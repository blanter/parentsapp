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

    @php
        $isTeacher = Auth::guard('teacher')->check();
        $displayUser = $isTeacher ? Auth::guard('teacher')->user() : Auth::user();
        $backRoute = $isTeacher ? route('teacher.dashboard') : route('dashboard');
    @endphp
    <div class="db-container">
        <div class="db-header">
            <div class="db-brand-section">
                <h1 style="font-size: 28px;">Hello<br><span
                        style="color: var(--db-purple)">{{ $displayUser->name }}</span></h1>
                <p style="font-size: 14px; font-weight: 600; opacity: 0.6; margin-top: 5px;">
                    {{ $isTeacher ? 'Pantau perkembangan anak didik Anda' : 'Have a great day with your children, parents' }}
                </p>
            </div>
            <a href="{{ $backRoute }}" class="db-avatar-section"
                style="width: 50px; height: 50px; text-decoration: none;">
                <i data-lucide="chevron-left" style="font-size: 24px; opacity: 1;"></i>
            </a>
        </div>

        <!-- Month Selector -->
        <div class="pa-month-selector" style="margin-top: 5px;">
            <a href="{{ route('children-tracker.index', ['date' => $selectedDate->copy()->subMonth()->format('Y-m-d')]) }}"
                class="pa-month-btn">
                <i data-lucide="chevron-left"></i>
            </a>
            <span>{{ $selectedMonthName }}</span>
            <a href="{{ route('children-tracker.index', ['date' => $selectedDate->copy()->addMonth()->format('Y-m-d')]) }}"
                class="pa-month-btn">
                <i data-lucide="chevron-right"></i>
            </a>
        </div>

        @if(!empty($alerts))
            <div style="margin: 20px 0;">
                @foreach($alerts as $alert)
                    <div class="ct-alert {{ $alert['type'] }}">
                        <i data-lucide="{{ $alert['icon'] }}"></i>
                        <span>{{ $alert['message'] }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        <h2 class="ct-section-title">Lifebook Children Tracker</h2>

        <div class="ct-list">
            @foreach($aspects as $key => $aspect)
                <a href="{{ $aspect['route'] != '#' ? route($aspect['route'], ['month' => $selectedMonthName]) : '#' }}"
                    class="ct-card">
                    <div class="ct-card-icon {{ $aspect['color'] }}">
                        <i data-lucide="{{ $aspect['icon'] }}"></i>
                    </div>
                    <div class="ct-card-info">
                        <h3>{{ $aspect['name'] }}</h3>
                        <div class="ct-indicator-wrapper">
                            @if($aspect['status'] === 'unfilled')
                                <span class="ct-indicator-pill warning">
                                    <i data-lucide="alert-circle" style="width: 10px; height: 10px;"></i>
                                    Belum Diisi
                                </span>
                            @elseif($aspect['status'] === 'filled')
                                <span class="ct-indicator-pill success">
                                    <i data-lucide="check-circle" style="width: 10px; height: 10px;"></i>
                                    Sudah Diisi
                                </span>
                            @elseif($aspect['status'] === 'replied')
                                <span class="ct-indicator-pill info">
                                    <i data-lucide="message-square" style="width: 10px; height: 10px;"></i>
                                    Ada Balasan Guru
                                </span>
                            @endif
                        </div>
                    </div>
                    <i data-lucide="chevron-right" style="opacity: 0.3;"></i>
                </a>
            @endforeach
        </div>

        <div
            style="text-align: center; margin-top: 50px; opacity: 0.3; font-size: 10px; font-weight: 700; color: var(--db-text-dark); margin-bottom: 100px;">
            Version {{ $appVersion }} • Parents App
        </div>
    </div>

    <!-- Bottom Navigation -->
    <nav class="db-bottom-nav">
        @if($isTeacher)
            <a href="{{ route('teacher.dashboard') }}" class="db-nav-item active">
                <div class="db-nav-icon"><i data-lucide="home"></i></div>
                <span>Home</span>
            </a>
            <a href="{{ route('teacher.profile') }}" class="db-nav-item">
                <div class="db-nav-icon"><i data-lucide="user"></i></div>
                <span>Profile</span>
            </a>
        @else
            <a href="{{ route('dashboard') }}" class="db-nav-item active">
                <div class="db-nav-icon"><i data-lucide="home"></i></div>
                <span>Home</span>
            </a>
            <a href="{{ route('parents.leaderboard') }}" class="db-nav-item">
                <div class="db-nav-icon"><i data-lucide="trophy"></i></div>
                <span>Scores</span>
            </a>
            <a href="{{ route('profile') }}" class="db-nav-item">
                <div class="db-nav-icon"><i data-lucide="user"></i></div>
                <span>Profile</span>
            </a>
        @endif
    </nav>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>