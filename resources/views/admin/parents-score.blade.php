@extends('layouts.admin')

@section('title', 'Parents Score App')
@section('header_title', 'Admin Panel')
@section('header_subtitle', 'Parents Score App')

@section('styles')
    <style>
        .container {
            max-width: 100%;
        }

        .content {
            padding: 0;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="content">
            <!-- Form Section -->
            <div class="form-section">
                <form id="scoreForm" method="POST" action="{{ route('parents.store') }}">
                    @csrf
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label class="form-label">Parents Name</label>
                            <select id="parentsDropdown" name="parent_ids[]" multiple="multiple" style="width:100%">
                                @foreach($parents as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }} ({{ $parent->email }})</option>
                                @endforeach
                            </select>
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
                            <input type="number" class="form-control" id="scoreInput" name="score" min="1" max="100"
                                placeholder="1-100" required>
                        </div>

                        <div class="form-group full-width">
                            <label class="form-label">Description</label>
                            <input type="text" class="form-control" id="deskripsi" name="deskripsi"
                                placeholder="Activity Description">
                        </div>
                    </div>

                    <button type="submit" class="auth-btn-primary" style="margin-top: 10px; width: 100%;">
                        <i data-lucide="save"></i>
                        <span>Save Score</span>
                    </button>
                </form>
            </div>

            <!-- Filter Buttons -->
            <div class="filter-buttons">
                <a href="{{ route('parents.index', ['activity' => 'all']) }}"
                    class="filter-btn {{ !$activityFilter || $activityFilter === 'all' ? 'active' : '' }}">
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
                        <img class="mybee" src="{{asset('/file/bee.png')}}" alt="Bee" />
                        <img class="myflo" src="{{asset('/file/flower.png')}}" alt="Flower" />
                    </div>
                    <h3>THE BEST</h3>
                    <div class="activities-list" id="bestActivities">
                        @forelse($bestScores as $activity => $best)
                            <div class="activity-item">
                                <div class="activity-header">{{ $activity }}</div>
                                <div class="activity-parent">{{ $best->parent?->name ?? 'Unknown' }} (Score:
                                    {{ $best->score }})
                                </div>
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
                                <div class="podium-item {{ $i == 0 ? 'first' : ($i == 1 ? 'second' : 'third') }}">
                                    <div class="avatar">
                                        @if($lb->user?->avatar)
                                            <img src="{{ asset('avatars/' . $lb->user->avatar) }}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                        @endif
                                    </div>
                                    <div class="podium-name">{{ $lb->user?->name ?? 'Unknown' }}</div>
                                    <div class="score-badge">{{ $lb->total_score }}</div>
                                    <div class="podium-base">
                                        <div class="podium-rank">{{ $i + 1 }}</div>
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
                                @foreach($leaderboard->skip(3)->take(10) as $i => $lb)
                                    <tr>
                                        <td class="rank-cell">{{ $i + 4 }}</td>
                                        <td class="name-cell">
                                            <div class="avatar">
                                                @if($lb->user?->avatar)
                                                    <img src="{{ asset('avatars/' . $lb->user->avatar) }}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                                @endif
                                            </div>
                                            {{ $lb->user?->name ?? 'Unknown' }}
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
                <div class="filter-section mb-5" style="background: #fff; padding: 25px; border-radius: 20px; border: 1px solid #F3F4F6; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
                    <form method="GET" action="{{ route('parents.index') }}" class="filter-form">
                        <div style="display: flex; gap: 20px; flex-wrap: wrap; align-items: flex-end;">
                            <!-- Filter Activity -->
                            <div style="flex: 1; min-width: 240px;">
                                <label class="form-label" style="font-size: 11px; font-weight: 800; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px; display: block;">Filter Activity Category</label>
                                <select name="activity" class="form-control" style="background: #fff; border: 2px solid #F3F4F6; height: 52px; border-radius: 12px; font-weight: 600; font-size: 14px; width: 100%;">
                                    <option value="">All Activity Categories</option>
                                    <option value="Journaling Parents" {{ request('activity') == 'Journaling Parents' ? 'selected' : '' }}>Journaling Parents</option>
                                    <option value="Support/Kerjasama" {{ request('activity') == 'Support/Kerjasama' ? 'selected' : '' }}>Support/Kerjasama</option>
                                    <option value="Home Gardening" {{ request('activity') == 'Home Gardening' ? 'selected' : '' }}>Home Gardening</option>
                                    <option value="Administrasi" {{ request('activity') == 'Administrasi' ? 'selected' : '' }}>Administrasi</option>
                                    <option value="Lifebook Journey" {{ request('activity') == 'Lifebook Journey' ? 'selected' : '' }}>Lifebook Journey</option>
                                </select>
                            </div>

                            <!-- Filter Parents Name -->
                            <div style="flex: 1; min-width: 240px;">
                                <label class="form-label" style="font-size: 11px; font-weight: 800; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px; display: block;">Filter Specific Parent</label>
                                <select name="user_id" class="form-control" style="background: #fff; border: 2px solid #F3F4F6; height: 52px; border-radius: 12px; font-weight: 600; font-size: 14px; width: 100%;">
                                    <option value="">All Registered Parents</option>
                                    @foreach($parents as $parent)
                                        <option value="{{ $parent->id }}" {{ request('user_id') == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Action Buttons -->
                            <div style="display: flex; gap: 12px;">
                                <button type="submit" class="auth-btn-primary" style="height: 52px; padding: 0 30px; border-radius: 12px; width: auto; display: flex; align-items: center; justify-content: center; gap: 10px; margin: 0;">
                                    <i data-lucide="filter" style="width: 18px; height: 18px;"></i>
                                    <span style="font-weight: 800;">Apply</span>
                                </button>

                                @if(request('activity') || request('user_id'))
                                    <a href="{{ route('parents.index') }}" class="btn" style="height: 52px; display: flex; align-items: center; justify-content: center; background: #fff; border: 2px solid #F3F4F6; color: #6B7280; border-radius: 12px; font-weight: 800; padding: 0 20px; transition: all 0.2s; text-decoration: none;">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
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
                                    <td data-label="Parents Name">
                                        @if($score->user)
                                            <b>{{ $score->user->name }}</b>
                                            <div style="font-size: 11px; color: #9CA3AF;">{{ $score->user->email }}</div>
                                        @else
                                            <b>Deleted User</b>
                                        @endif
                                    </td>
                                    <td data-label="Activity">{{ $score->activity }}
                                        @if($score->deskripsi != NULL)
                                            - {{$score->deskripsi}}
                                        @endif
                                    </td>
                                    <td data-label="Score">{{ $score->score }}</td>
                                    <td data-label="Aksi">
                                        <div class="myflex">
                                            {{-- Tombol Edit --}}
                                            <a href="{{ route('score.edit', $score->id) }}" class="btn btn-warning mb-1">
                                                Edit
                                            </a>
                                            {{-- Tombol Delete --}}
                                            <form action="{{ route('scores.destroy', $score->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus data ini?')" style="margin:0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-block">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- Manual Pagination --}}
                @if ($scores->hasPages())
                    <div class="custom-pagination">
                        @if ($scores->onFirstPage())
                            <span class="page-btn disabled">Prev</span>
                        @else
                            <a href="{{ $scores->previousPageUrl() }}" class="page-btn">Prev</a>
                        @endif

                        @php
                            $current = $scores->currentPage();
                            $last = $scores->lastPage();
                            $start = max(1, $current - 2);
                            $end = min($last, $current + 2);
                            if ($end - $start < 4) {
                                if ($start == 1) { $end = min($last, $start + 4); } 
                                else { $start = max(1, $end - 4); }
                            }
                        @endphp

                        @if ($start > 1)
                            <a href="{{ $scores->url(1) }}" class="page-btn">1</a>
                            @if ($start > 2)
                                <span class="page-btn dots">...</span>
                            @endif
                        @endif

                        @foreach (range($start, $end) as $page)
                            @if ($page == $current)
                                <span class="page-btn active">{{ $page }}</span>
                            @else
                                <a href="{{ $scores->url($page) }}" class="page-btn">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if ($end < $last)
                            @if ($end < $last - 1)
                                <span class="page-btn dots">...</span>
                            @endif
                            <a href="{{ $scores->url($last) }}" class="page-btn">{{ $last }}</a>
                        @endif

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
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#parentsDropdown').select2({
                placeholder: "Search and select parents...",
                allowClear: true
            });
        });
    </script>
@endsection