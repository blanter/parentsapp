<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('/file/lifebookicon.png')}}" rel='icon' type='image/x-icon'/>
    <title>Leaderboard Parents - Lifebook Academy</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{asset('/file/style.css')}}?v=6" rel="stylesheet"/>
    <script src="{{asset('/file/jquery.min.js')}}"></script>
</head>
<body>
    <div class="header">
        <a href="/leaderboard-parents" title="Parents Score App">
            <h1>Leaderboard Parents</h1>
        </a>
        <p>Lifebook Academy Parents Management App</p>
    </div>
        
    <div class="container">
        <div class="content">
            <!-- Filter Buttons -->
            <div class="filter-buttons">
                <a href="{{ route('parents.leaderboard', ['activity' => 'all']) }}"
                class="filter-btn {{ $activityFilter === 'all' ? 'active' : '' }}">
                All Activities
                </a>
                <a href="{{ route('parents.leaderboard', ['activity' => 'Journaling Parents']) }}"
                class="filter-btn {{ $activityFilter === 'Journaling Parents' ? 'active' : '' }}">
                Journaling Parents
                </a>
                <a href="{{ route('parents.leaderboard', ['activity' => 'Support/Kerjasama']) }}"
                class="filter-btn {{ $activityFilter === 'Support/Kerjasama' ? 'active' : '' }}">
                Support/Kerjasama
                </a>
                <a href="{{ route('parents.leaderboard', ['activity' => 'Home Gardening']) }}"
                class="filter-btn {{ $activityFilter === 'Home Gardening' ? 'active' : '' }}">
                Home Gardening
                </a>
                <a href="{{ route('parents.leaderboard', ['activity' => 'Administrasi']) }}"
                class="filter-btn {{ $activityFilter === 'Administrasi' ? 'active' : '' }}">
                Administrasi
                </a>
                <a href="{{ route('parents.leaderboard', ['activity' => 'Lifebook Journey']) }}"
                class="filter-btn {{ $activityFilter === 'Lifebook Journey' ? 'active' : '' }}">
                Lifebook Journey
                </a>
            </div>
            
            <!-- Leaderboard Section -->
            <div class="leaderboard">
                <div class="top-performers mt-5">
                    <div class="asset-new">
                        <img class="mybee" src="{{asset('/file/bee.png')}}" alt="Bee"/>
                        <img class="myflo" src="{{asset('/file/flower.png')}}" alt="Flower"/>
                    </div>
                    <h3>THE BEST</h3>
                    <div class="activities-list" id="bestActivities">
                        @forelse($bestScores as $activity => $best)
                            <div class="activity-item">
                                <div class="activity-header">{{ $activity }}</div>
                                <div class="activity-parent">{{ $best->parent?->name ?? 'Unknown' }} (Score: {{ $best->score }})</div>
                            </div>
                        @empty
                            <div class="activity-item">Belum ada data</div>
                        @endforelse
                    </div>
                </div>

                <div>
                    <div class="top-performers no-bgya">
                        <div class="podium" id="podium">
                            @foreach($leaderboard->take(3) as $i => $lb)
                                <div class="podium-item {{ $i == 0 ? 'first' : ($i == 1 ? 'second' : 'third') }}" data-activity="{{ $lb->parent?->scores->first()->activity ?? 'All' }}">
                                    <div class="avatar"></div>
                                    <div class="podium-name">{{ $lb->parent?->name ?? 'Unknown' }}</div>
                                    <div class="score-badge">{{ $lb->total_score }}</div>
                                    <div class="podium-base">
                                        <div class="podium-rank">{{ $i+1 }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="ranking-section">
                        <table class="ranking-table">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Name</th>
                                    <th>Score</th>
                                </tr>
                            </thead>
                            <tbody id="rankingBody">
                                @foreach($leaderboard->skip(3)->take(5) as $i => $lb)
                                    <tr data-activity="{{ $lb->parent?->scores->first()->activity ?? 'All' }}">
                                        <td class="rank-cell">{{ $i+1 }}</td>
                                        <td class="name-cell">
                                            <div class="avatar"></div>
                                            {{ $lb->parent?->name ?? 'Unknown' }}
                                        </td>
                                        <td>{{ $lb->total_score }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- App Version -->
            <div class="app-version">App v1.10</div>
        </div>
    </div>
</body>
</html>