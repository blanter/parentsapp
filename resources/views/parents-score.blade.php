<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('/file/lifebookicon.png')}}" rel='icon' type='image/x-icon'/>
    <title>Parents Score App - Lifebook Academy</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{asset('/file/style.css')}}?v=6" rel="stylesheet"/>
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
                        
                        <div class="form-group full-width">
                            <label class="form-label">Description</label>
                            <input type="text" class="form-control" id="deskripsi" name="deskripsi" placeholder="Activity Description">
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

            <!-- History Section -->
            <div class="history-section">
                <h3>History Insert</h3>
                <!-- Filter Section -->
                <div class="filter-section mb-4">
                    <form method="GET" action="{{ route('parents.index') }}" class="filter-form">
                
                        <div class="form-row">
                
                            <!-- Filter Activity -->
                            <div class="form-group mr-2">
                                <label class="form-label">Filter Activity</label>
                                <select name="activity" class="form-control">
                                    <option value="">All Activity</option>
                                    <option value="Journaling Parents" {{ request('activity') == 'Journaling Parents' ? 'selected' : '' }}>Journaling Parents</option>
                                    <option value="Support/Kerjasama" {{ request('activity') == 'Support/Kerjasama' ? 'selected' : '' }}>Support/Kerjasama</option>
                                    <option value="Home Gardening" {{ request('activity') == 'Home Gardening' ? 'selected' : '' }}>Home Gardening</option>
                                    <option value="Administrasi" {{ request('activity') == 'Administrasi' ? 'selected' : '' }}>Administrasi</option>
                                    <option value="Lifebook Journey" {{ request('activity') == 'Lifebook Journey' ? 'selected' : '' }}>Lifebook Journey</option>
                                </select>
                            </div>
                
                            <!-- Filter Parents Name -->
                            <div class="form-group mr-2">
                                <label class="form-label">Filter Parent</label>
                                <select name="parent_id" class="form-control">
                                    <option value="">All Parents</option>
                                    @foreach($parents as $parent)
                                        <option value="{{ $parent->id }}" {{ request('parent_id') == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                
                            <!-- Button -->
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Filter</button>
                
                                @if(request('activity') || request('parent_id'))
                                    <a href="{{ route('parents.index') }}" class="btn btn-secondary">Reset</a>
                                @endif
                            </div>
                
                        </div>
                    </form>
                </div>

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
                                <td data-label="Parents Name"><b>{{ $score->parent?->name ?? '-' }}</b></td>
                                <td data-label="Activity">{{ $score->activity }}
                                @if($score->deskripsi != NULL)
                                - {{$score->deskripsi}}
                                @endif</td>
                                <td data-label="Score">{{ $score->score }}</td>
                                <td data-label="Aksi">
                                    <div class="myflex">
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('score.edit', $score->id) }}" class="btn btn-warning mb-1">
                                        Edit
                                    </a>
                                    {{-- Tombol Delete --}}
                                    <form action="{{ route('scores.destroy', $score->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- Manual Pagination --}}
                @if ($scores->hasPages())
                    <div class="custom-pagination">
                        {{-- Tombol Previous --}}
                        @if ($scores->onFirstPage())
                            <span class="page-btn disabled">Prev</span>
                        @else
                            <a href="{{ $scores->previousPageUrl() }}" class="page-btn">Prev</a>
                        @endif
                
                        {{-- Nomor Halaman (maks 5 + ... + last page) --}}
                        @php
                            $current = $scores->currentPage();
                            $last = $scores->lastPage();
                            $start = max(1, $current - 2);
                            $end = min($last, $current + 2);
                
                            // pastikan total hanya 5 halaman
                            if ($end - $start < 4) {
                                if ($start == 1) {
                                    $end = min($last, $start + 4);
                                } else {
                                    $start = max(1, $end - 4);
                                }
                            }
                        @endphp
                
                        {{-- Tampilkan halaman pertama dan ellipsis jika perlu --}}
                        @if ($start > 1)
                            <a href="{{ $scores->url(1) }}" class="page-btn">1</a>
                            @if ($start > 2)
                                <span class="page-btn dots">...</span>
                            @endif
                        @endif
                
                        {{-- Tampilkan rentang halaman --}}
                        @foreach (range($start, $end) as $page)
                            @if ($page == $current)
                                <span class="page-btn active">{{ $page }}</span>
                            @else
                                <a href="{{ $scores->url($page) }}" class="page-btn">{{ $page }}</a>
                            @endif
                        @endforeach
                
                        {{-- Tampilkan ellipsis dan halaman terakhir jika perlu --}}
                        @if ($end < $last)
                            @if ($end < $last - 1)
                                <span class="page-btn dots">...</span>
                            @endif
                            <a href="{{ $scores->url($last) }}" class="page-btn">{{ $last }}</a>
                        @endif
                
                        {{-- Tombol Next --}}
                        @if ($scores->hasMorePages())
                            <a href="{{ $scores->nextPageUrl() }}" class="page-btn">Next</a>
                        @else
                            <span class="page-btn disabled">Next</span>
                        @endif
                    </div>
                @endif
            </div>
            
            <!-- App Version -->
            <div class="app-version">App v1.10</div>
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