@extends('layouts.app')

@section('title', 'Leaderboard Parents - Lifebook Parents')

@section('body-class', 'db-body lb-body')

@section('content')
    <!-- Background Elements -->
    <img src="{{ asset('/file/bee.png') }}" class="db-bg-pattern db-bee" alt="">
    <img src="{{ asset('/file/flower.png') }}" class="db-bg-pattern db-flower" alt="">

    <div class="lb-container">
        <div class="lb-header">
            <h1>Leaderboard<br>Parents</h1>
            <p>Lifebook Academy Parents Management</p>
        </div>

        <!-- Filter Buttons -->
        <div class="lb-filter-buttons">
            <a href="{{ route('parents.leaderboard', ['activity' => 'all']) }}"
                class="lb-filter-btn {{ $activityFilter === 'all' ? 'active' : '' }}">
                All Activities
            </a>
            @foreach(['Journaling Parents', 'Support/Kerjasama', 'Home Gardening', 'Administrasi', 'Lifebook Journey'] as $act)
                <a href="{{ route('parents.leaderboard', ['activity' => $act]) }}"
                    class="lb-filter-btn {{ $activityFilter === $act ? 'active' : '' }}">
                    {{ $act }}
                </a>
            @endforeach
        </div>

        <!-- Podium Section -->
        <h3 class="lb-section-title">
            <i data-lucide="trophy" style="color: var(--db-purple);"></i>
            <span>Top 3 Parents</span>
        </h3>
        <div class="lb-card">
            <div class="lb-podium">
                @php
                    $top3 = $leaderboard->take(3);
                    $podiumOrder = [1, 0, 2]; // Second, First, Third
                @endphp
                @foreach($podiumOrder as $index)
                    @if(isset($top3[$index]))
                        @php $lb = $top3[$index]; @endphp
                        <div class="lb-podium-item {{ $index == 0 ? 'first' : ($index == 1 ? 'second' : 'third') }}">
                            <div class="lb-podium-score">{{ $lb->total_score }}</div>
                            <div class="lb-podium-avatar">
                                <i data-lucide="user" style="opacity: 0.3;"></i>
                            </div>
                            <div class="lb-podium-name">{{ $lb->parent?->name ?? 'Unknown' }}</div>
                            <div class="lb-podium-base">
                                {{ $index + 1 }}
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Ranking List -->
            <table class="lb-ranking-table">
                <tbody>
                    @foreach($leaderboard->skip(3)->take(10) as $i => $lb)
                        <tr class="lb-ranking-row">
                            <td>
                                <div class="lb-rank-badge">{{ $i + 4 }}</div>
                            </td>
                            <td>{{ $lb->parent?->name ?? 'Unknown' }}</td>
                            <td>{{ $lb->total_score }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Best Section -->
        <h3 class="lb-section-title">
            <i data-lucide="award" style="color: var(--db-primary);"></i>
            <span>The Best Performers</span>
        </h3>
        <div class="lb-card">
            @forelse($bestScores as $activity => $best)
                <div class="lb-best-item">
                    <span class="lb-best-activity">{{ $activity }}</span>
                    <span class="lb-best-parent">{{ $best->parent?->name ?? 'Unknown' }}</span>
                    <span class="lb-best-score">Score: {{ $best->score }}</span>
                </div>
            @empty
                <div style="text-align: center; opacity: 0.5; padding: 10px;">Belum ada data</div>
            @endforelse
        </div>

        <div
            style="text-align: center; margin-top: 50px; opacity: 0.3; font-size: 10px; font-weight: 700; color: var(--db-text-dark); margin-bottom: 120px;">
            Version {{ $appVersion }} • Parents App
        </div>
    </div>
@endsection