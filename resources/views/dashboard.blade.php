@extends('layouts.app')

@section('title', 'Dashboard - Lifebook Parents')

@section('content')
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
                    <img src="{{ asset('avatars/' . Auth::user()->avatar) }}" alt="Avatar"
                        style="width: 100%; height: 100%; object-fit: cover;">
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
            <a href="{{ route('volunteer.index') }}" class="db-menu-item mission">
                <i data-lucide="award"></i>
                <span>Volunteer Mission</span>
            </a>
            <a href="{{ route('children-tracker.index') }}" class="db-menu-item children-tracker">
                <i data-lucide="activity"></i>
                <span>Lifebook Children Tracker</span>
            </a>
            <a href="{{ route('coming-soon') }}" class="db-menu-item learning-tracker">
                <i data-lucide="book-open"></i>
                <span>My Kids Learning Tracker</span>
            </a>
            <a href="{{ route('lifebook-journey.index') }}" class="db-menu-item journey">
                <i data-lucide="map"></i>
                <span>Parents Lifebook Journey</span>
            </a>
        </div>

        <div class="profile-footer-version" style="margin-bottom: 90px;">
            Version {{ $appVersion }} • Parents App
        </div>
    </div>
@endsection