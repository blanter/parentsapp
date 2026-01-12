@extends('layouts.app')

@section('title', 'Volunteer Mission - Lifebook Parents')

@section('content')
    <!-- Background Elements -->
    <img src="{{ asset('/file/bee.png') }}" class="db-bg-pattern db-bee" alt="">
    <img src="{{ asset('/file/flower.png') }}" class="db-bg-pattern db-flower" alt="">

    <div class="vm-container">
        <!-- Success Alert Popup -->
        <div id="successPopup" class="pa-popup-overlay" style="display: none;">
            <div class="pa-popup-card">
                <div class="pa-popup-icon" style="background: rgba(54, 179, 126, 0.1); color: var(--db-secondary);">
                    <i data-lucide="award"></i>
                </div>
                <h3 class="pa-popup-title">Berhasil Disimpan!</h3>
                <p class="pa-popup-message">
                    Selamat Ayah / Bunda! Anda mendapatkan <b id="earnedPointsText">0 Poin</b> untuk misi volunteer hari
                    ini.
                </p>
                <button class="pa-popup-btn" onclick="closePopup()">Siap, Terima Kasih</button>
            </div>
        </div>

        <!-- Header -->
        <div class="db-header" style="margin-bottom: 20px;">
            <div class="db-brand-section">
                <a href="{{ route('dashboard') }}"
                    style="text-decoration: none; color: inherit; display: flex; align-items: center; gap: 10px;">
                    <i data-lucide="chevron-left" style="width: 30px; height: 30px;"></i>
                    <h1 style="font-size: 24px; margin: 0;">Back</h1>
                </a>
            </div>
            <a href="{{ route('profile') }}" class="db-avatar-section" style="width: 45px; height: 45px;">
                @if(Auth::user()->avatar)
                    <img src="{{ asset('avatars/' . Auth::user()->avatar) }}" alt="Avatar"
                        style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <i data-lucide="user"></i>
                @endif
            </a>
        </div>

        <div class="vm-header">
            <h1>Volunteer Mission</h1>
            <p class="vm-subtitle">Pilih misi yang telah Anda selesaikan!</p>
        </div>

        <!-- Tabs -->
        <div class="vm-tabs">
            <button class="vm-tab-btn active" onclick="switchTab('checklist')">
                <i data-lucide="check-square"></i>
                Checklist
            </button>
            <button class="vm-tab-btn" onclick="switchTab('overview')">
                <i data-lucide="bar-chart-3"></i>
                Overview
            </button>
        </div>

        <!-- Checklist View -->
        <div id="checklistView" class="tab-content active">
            <div class="vm-list">
                @foreach($missions as $mission)
                    <div class="vm-card">
                        <div class="vm-card-header">
                            <div class="vm-mission-name">{{ $mission->name }}</div>
                            <div class="vm-mission-icon">
                                <i
                                    data-lucide="{{ str_contains(strtolower($mission->name), 'peternakan') ? 'dog' : (str_contains(strtolower($mission->name), 'perkebunan') ? 'flower' : (str_contains(strtolower($mission->name), 'karya') ? 'palette' : 'star')) }}"></i>
                            </div>
                        </div>

                        <div class="vm-days-grid">
                            @foreach($weekDays as $day)
                                @php
                                    $isCompleted = isset($completions[$mission->id]) && $completions[$mission->id]->contains('completed_at', $day['date']);
                                @endphp
                                <div class="vm-day-item">
                                    <span class="vm-day-label">{{ $day['name'] }}</span>
                                    <div class="vm-checkbox {{ $isCompleted ? 'checked' : '' }}"
                                        onclick="toggleMission({{ $mission->id }}, '{{ $day['date'] }}', this)">
                                        @if($isCompleted)
                                            <i data-lucide="check"></i>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Overview View -->
        <div id="overviewView" class="tab-content" style="display: none;">
            <!-- Summary Stats -->
            <div class="vm-summary-grid">
                <div class="vm-summary-card">
                    <span class="vm-summary-label">Current Streak</span>
                    <span class="vm-summary-value" id="summary-streak">{{ $overviewData['current_streak'] }} days</span>
                </div>
                <div class="vm-summary-card">
                    <span class="vm-summary-label">Success Rate</span>
                    <span class="vm-summary-value" id="summary-rate">{{ $overviewData['success_rate'] }}%</span>
                </div>
                <div class="vm-summary-card">
                    <span class="vm-summary-label">Best Streak</span>
                    <span class="vm-summary-value" id="summary-best">{{ $overviewData['best_streak'] }} days</span>
                </div>
                <div class="vm-summary-card">
                    <span class="vm-summary-label">Total Completed</span>
                    <span class="vm-summary-value" id="summary-total">{{ $overviewData['total_completed'] }}</span>
                </div>
            </div>

            <!-- Heatmap Grids -->
            <h2 style="font-size: 16px; font-weight: 800; color: var(--db-text-dark); margin-bottom: 15px;">Habit
                Progress</h2>

            @foreach($missions as $index => $mission)
                @php
                    $missionIndex = ($index % 4) + 1;
                    $missionCompletions = $overviewData['all_completions'][$mission->id] ?? [];
                @endphp
                <div class="vm-heatmap-card">
                    <div class="vm-heatmap-header">
                        <div class="vm-heatmap-title">
                            <i data-lucide="{{ str_contains(strtolower($mission->name), 'peternakan') ? 'dog' : (str_contains(strtolower($mission->name), 'perkebunan') ? 'flower' : (str_contains(strtolower($mission->name), 'karya') ? 'palette' : 'star')) }}"
                                style="width: 14px; height: 14px; opacity: 0.6;"></i>
                            {{ $mission->name }}
                        </div>
                        <span class="vm-heatmap-subtitle">EVERYDAY</span>
                    </div>

                    <div class="vm-heatmap-scroll">
                        <div class="vm-heatmap-grid">
                            <div class="vm-heatmap-labels" style="justify-content: flex-start;">
                                <span class="vm-heatmap-label">S</span>
                                <span class="vm-heatmap-label">S</span>
                                <span class="vm-heatmap-label">R</span>
                                <span class="vm-heatmap-label">K</span>
                                <span class="vm-heatmap-label">J</span>
                                <span class="vm-heatmap-label">S</span>
                                <span class="vm-heatmap-label">M</span>
                            </div>
                            @for($w = 0; $w < $overviewData['grid_weeks']; $w++)
                                <div class="vm-heatmap-week">
                                    @for($d = 0; $d < 7; $d++)
                                        @php
                                            $currDate = $overviewData['grid_start_date']->copy()->addWeeks($w)->addDays($d);
                                            $dateStr = $currDate->toDateString();
                                            $isDone = in_array($dateStr, $missionCompletions);
                                            $isFuture = $currDate->isFuture();
                                        @endphp
                                        <div class="vm-heatmap-cell {{ $isDone ? "level-$missionIndex" : "" }}"
                                            data-mission="{{ $mission->id }}" data-date="{{ $dateStr }}"
                                            data-level="{{ $missionIndex }}" title="{{ $currDate->translatedFormat('d M Y') }}"
                                            style="{{ $isFuture ? "opacity: 0.1;" : "" }}">
                                        </div>
                                    @endfor
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div
            style="text-align: center; margin-top: 40px; opacity: 0.3; font-size: 10px; font-weight: 700; color: var(--db-text-dark); margin-bottom: 100px;">
            Version {{ $appVersion }} • Parents App
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function switchTab(tab) {
            $('.vm-tab-btn').removeClass('active');
            $('.tab-content').hide();

            if (tab === 'checklist') {
                $('.vm-tab-btn:first-child').addClass('active');
                $('#checklistView').show();
            } else {
                $('.vm-tab-btn:last-child').addClass('active');
                $('#overviewView').show();
                // Scroll heatmaps to the end
                $('.vm-heatmap-scroll').each(function () {
                    $(this).scrollLeft($(this)[0].scrollWidth);
                });
            }
            lucide.createIcons();
        }

        function toggleMission(missionId, date, element) {
            $(element).css('pointer-events', 'none');

            $.ajax({
                url: "{{ route('volunteer.toggle') }}",
                type: "POST",
                data: {
                    mission_id: missionId,
                    date: date,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    if (response.status === 'success') {
                        if (response.action === 'checked') {
                            $(element).addClass('checked').html('<i data-lucide="check"></i>');
                            // Update heatmap
                            const targetCell = $(`.vm-heatmap-cell[data-mission="${missionId}"][data-date="${date}"]`);
                            const level = targetCell.data('level');
                            targetCell.addClass(`level-${level}`);

                            // Show Points Popup
                            if (response.earned_points > 0) {
                                $('#earnedPointsText').text(response.earned_points + ' Poin');
                                $('#successPopup').fadeIn(300).css('display', 'flex');
                            }
                        } else {
                            $(element).removeClass('checked').empty();
                            // Update heatmap
                            const targetCell = $(`.vm-heatmap-cell[data-mission="${missionId}"][data-date="${date}"]`);
                            targetCell.removeClass('level-1 level-2 level-3 level-4');
                        }

                        // Update Summary Stats
                        $('#summary-streak').text(response.stats.current_streak + ' days');
                        $('#summary-rate').text(response.stats.success_rate + '%');
                        $('#summary-best').text(response.stats.best_streak + ' days');
                        $('#summary-total').text(response.stats.total_completed);
                    }
                },
                error: function () {
                    alert('Terjadi kesalahan, silakan coba lagi.');
                },
                complete: function () {
                    $(element).css('pointer-events', 'auto');
                    lucide.createIcons();
                }
            });
        }

        function closePopup() {
            $('#successPopup').fadeOut(300);
        }
    </script>
@endsection