<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parents Score App - Lifebook Academy</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{asset('/file/style.css')}}" rel="stylesheet"/>
    <script src="{{asset('/file/jquery.min.js')}}"></script>
    <link href="{{asset('/file/select2.min.css')}}" rel="stylesheet"/>
    <script src="{{asset('/file/select2.min.js')}}"></script>
</head>
<body>
    <div class="header">
        <a href="/parents-score" title="Parents Score App">
            <h1>Parents Score App</h1>
        </a>
        <p>Lifebook Academy Parents Management App</p>
    </div>
        
    <div class="container">
        <div class="content">
            <!-- Form Section -->
            <div class="form-section">
                <form id="scoreForm" method="POST" action="{{ route('parents.store') }}">
                    @csrf
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label class="form-label">Parents Name</label>
                            <div class="multiselect-container smaller-input" id="parentsSelect">
                                <select id="parentsDropdown" name="parent_ids[]" multiple="multiple" style="width:100%">
                                    @foreach($parents as $parent)
                                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Insert Activity</label>
                            <select class="form-control" id="activitySelect" name="activity" required>
                                <option value="">Select Activity</option>
                                <option value="Journaling Parents">Journaling Parents</option>
                                <option value="Support/Kerjasama">Support/Kerjasama</option>
                                <option value="Home Gardening">Home Gardening</option>
                                <option value="Administrasi">Administrasi</option>
                                <option value="Lifebook Journey">Lifebook Journey</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Insert Score</label>
                            <input type="number" class="form-control" id="scoreInput" name="score" min="1" max="100" placeholder="1-100" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
                {{-- Pesan sukses --}}
                @if(session('success'))
                    <div class="success-message mb-4">
                        {{ session('success') }}
                    </div>
                @endif
            </div>

            <!-- Filter Buttons -->
            <div class="filter-buttons">
                <a href="{{ route('parents.index', ['activity' => 'all']) }}"
                class="filter-btn {{ $activityFilter === 'all' ? 'active' : '' }}">
                All Activities
                </a>
                <a href="{{ route('parents.index', ['activity' => 'Journaling Parents']) }}"
                class="filter-btn {{ $activityFilter === 'Journaling Parents' ? 'active' : '' }}">
                Journaling Parents
                </a>
                <a href="{{ route('parents.index', ['activity' => 'Support/Kerjasama']) }}"
                class="filter-btn {{ $activityFilter === 'Support/Kerjasama' ? 'active' : '' }}">
                Support/Kerjasama
                </a>
                <a href="{{ route('parents.index', ['activity' => 'Home Gardening']) }}"
                class="filter-btn {{ $activityFilter === 'Home Gardening' ? 'active' : '' }}">
                Home Gardening
                </a>
                <a href="{{ route('parents.index', ['activity' => 'Administrasi']) }}"
                class="filter-btn {{ $activityFilter === 'Administrasi' ? 'active' : '' }}">
                Administrasi
                </a>
                <a href="{{ route('parents.index', ['activity' => 'Lifebook Journey']) }}"
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
                                <strong>{{ $activity }}</strong><br>
                                {{ $best->parent?->name ?? 'Unknown' }} (Score: {{ $best->score }})
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

            <!-- History Section -->
            <div class="history-section">
                <h3>History Insert</h3>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Parents Name</th>
                            <th>Activity</th>
                            <th>Score</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="historyBody">
                        @foreach($scores as $score)
                            <tr data-activity="{{ $score->activity }}">
                                <td data-label="Tanggal">{{ $score->created_at->format('d M Y H:i') }}</td>
                                <td data-label="Parents Name">{{ $score->parent?->name ?? '-' }}</td>
                                <td data-label="Activity">{{ $score->activity }}</td>
                                <td data-label="Score">{{ $score->score }}</td>
                                <td data-label="Aksi">
                                    <form action="{{ route('scores.destroy', $score->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Manual Pagination -->
                @if ($scores->hasPages())
                    <div class="custom-pagination">
                        {{-- Tombol Previous --}}
                        @if ($scores->onFirstPage())
                            <span class="page-btn disabled">Prev</span>
                        @else
                            <a href="{{ $scores->previousPageUrl() }}" class="page-btn">Prev</a>
                        @endif

                        {{-- Nomor Halaman --}}
                        @foreach ($scores->getUrlRange(1, $scores->lastPage()) as $page => $url)
                            @if ($page == $scores->currentPage())
                                <span class="page-btn active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Tombol Next --}}
                        @if ($scores->hasMorePages())
                            <a href="{{ $scores->nextPageUrl() }}" class="page-btn">Next</a>
                        @else
                            <span class="page-btn disabled">Next</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // DROPDOWN
        $(document).ready(function() {
            $('#parentsDropdown').select2({
                tags: true,
                tokenSeparators: [','],
                placeholder: "Search and select parents...",
                allowClear: true
            });
        });
    </script>
</body>
</html>