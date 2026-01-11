@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('header_title', 'Admin Dashboard')
@section('header_subtitle', 'Welcome back, Admin!')

@section('content')
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
        <a href="{{ route('admin.gardening.index') }}" class="db-menu-item gardening">
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
@endsection