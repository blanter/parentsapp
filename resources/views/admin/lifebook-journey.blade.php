@extends('layouts.admin')

@section('title', 'Parents Lifebook Journey Data')
@section('header_title', 'Admin Panel')
@section('header_subtitle', 'Parents Lifebook Journey & Habit Analytics')

@section('content')
    <!-- Statistics Cards -->
    <div class="ct-premium-stats-grid">
        <div class="adm-card ct-premium-stat-card">
            <div class="ct-premium-stat-content">
                <div class="ct-premium-stat-icon-box" style="background: rgba(108, 136, 224, 0.1);">
                    <i data-lucide="users" class="ct-premium-stat-icon-inner" style="color: var(--db-purple);"></i>
                </div>
                <div class="ct-premium-stat-info">
                    <div class="ct-premium-stat-label">Total Parents</div>
                    <div class="ct-premium-stat-value">{{ $totalParents }}</div>
                </div>
            </div>
        </div>

        <div class="adm-card ct-premium-stat-card">
            <div class="ct-premium-stat-content">
                <div class="ct-premium-stat-icon-box" style="background: rgba(34, 197, 94, 0.1);">
                    <i data-lucide="edit-3" class="ct-premium-stat-icon-inner" style="color: #22C55E;"></i>
                </div>
                <div class="ct-premium-stat-info">
                    <div class="ct-premium-stat-label">Active Users</div>
                    <div class="ct-premium-stat-value">{{ $activeParents }}</div>
                </div>
            </div>
        </div>

        <div class="adm-card ct-premium-stat-card">
            <div class="ct-premium-stat-content">
                <div class="ct-premium-stat-icon-box" style="background: rgba(255, 214, 75, 0.1);">
                    <i data-lucide="milestone" class="ct-premium-stat-icon-inner" style="color: var(--db-primary);"></i>
                </div>
                <div class="ct-premium-stat-info">
                    <div class="ct-premium-stat-label">Journey Done</div>
                    <div class="ct-premium-stat-value">{{ $completedJourneys }}</div>
                </div>
            </div>
        </div>

        <div class="adm-card ct-premium-stat-card">
            <div class="ct-premium-stat-content">
                <div class="ct-premium-stat-icon-box" style="background: rgba(255, 107, 74, 0.1);">
                    <i data-lucide="calendar-check" class="ct-premium-stat-icon-inner" style="color: var(--db-accent);"></i>
                </div>
                <div class="ct-premium-stat-info">
                    <div class="ct-premium-stat-label">Avg. Progress</div>
                    <div class="ct-premium-stat-value">{{ round($avgProgress) }}%</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Section -->
    <div class="vm-tabs" style="margin-bottom: 25px; justify-content: flex-start;">
        <button class="vm-tab-btn active" onclick="switchTab('journey')">
            <i data-lucide="milestone"></i>
            <span>Journey Data</span>
        </button>
        <button class="vm-tab-btn" onclick="switchTab('habits')">
            <i data-lucide="calendar-check"></i>
            <span>Habit Tracker</span>
        </button>
    </div>

    <!-- Journey Data Content -->
    <div id="journeyTab" class="tab-content active">
        <div class="adm-card">
            <div class="adm-card-header">
                <div class="ct-premium-table-header">
                    <h3 class="ct-premium-table-title">Parents Lifebook Journey Overview</h3>
                    <div class="ct-premium-table-period-badge">
                        <i data-lucide="bar-chart-3" class="ct-premium-table-period-icon"></i>
                        <span>Journey Insights</span>
                    </div>
                </div>
            </div>
            <div class="ct-premium-table-body-wrapper">
                <div class="adm-table-wrapper">
                    <table class="adm-table">
                        <thead>
                            <tr>
                                <th class="ct-premium-th-left">Parent Details</th>
                                <th class="ct-premium-th-center">Categories Filled</th>
                                <th class="ct-premium-th-center">Progress Percentage</th>
                                <th class="ct-premium-th-center">Last Activity</th>
                                <th class="ct-premium-th-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($journeyStats as $stat)
                                <tr>
                                    <td class="ct-premium-td-left">
                                        <div class="ct-premium-user-details-flex">
                                            <div class="ct-premium-avatar-wrapper">
                                                @if($stat['user']->avatar)
                                                    <img src="{{ asset('avatars/' . $stat['user']->avatar) }}" class="ct-premium-avatar-img">
                                                @else
                                                    <div class="ct-premium-avatar-icon" style="background: var(--db-purple); width: 42px; height: 42px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff;">
                                                        <i data-lucide="user" style="width: 20px;"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ct-premium-user-details-box">
                                                <div class="ct-premium-user-name">{{ $stat['user']->name }}</div>
                                                <div class="ct-premium-user-sub">{{ $stat['user']->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="ct-premium-td-center">
                                        <div style="font-weight: 800; color: var(--db-text-dark);">
                                            {{ $stat['filled_fields'] }} / {{ $stat['total_fields'] }} <span style="font-size: 11px; color: #9CA3AF;">Fields</span>
                                        </div>
                                    </td>
                                    <td class="ct-premium-td-center" style="width: 200px;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <div style="flex: 1; height: 8px; background: #F3F4F6; border-radius: 4px; overflow: hidden;">
                                                <div style="width: {{ $stat['percentage'] }}%; height: 100%; background: {{ $stat['percentage'] == 100 ? '#22C55E' : 'var(--db-purple)' }};"></div>
                                            </div>
                                            <span style="font-weight: 800; font-size: 13px; color: var(--db-text-dark);">{{ $stat['percentage'] }}%</span>
                                        </div>
                                    </td>
                                    <td class="ct-premium-td-center">
                                        <span class="ct-premium-period-label">
                                            {{ $stat['last_update'] ? $stat['last_update']->diffForHumans() : 'No activity' }}
                                        </span>
                                    </td>
                                    <td class="ct-premium-td-right">
                                        <button class="ct-premium-btn-view" onclick="viewJourneyDetail({{ $stat['user']->id }})">
                                            <i data-lucide="eye" class="ct-premium-btn-view-icon"></i>
                                            <span>VIEW DETAIL</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="ct-premium-empty-wrapper">
                                        <div class="ct-premium-empty-content">
                                            <i data-lucide="inbox" class="ct-premium-empty-icon"></i>
                                            <p class="ct-premium-empty-text">No journey data available yet.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Habit Tracker Content -->
    <div id="habitsTab" class="tab-content" style="display: none;">
        <div class="adm-card">
            <div class="adm-card-header">
                <div class="ct-premium-table-header">
                    <h3 class="ct-premium-table-title">Parents Habit Tracker Overview</h3>
                    <div class="ct-premium-table-period-badge" style="background: rgba(255, 107, 74, 0.1); color: var(--db-accent);">
                        <i data-lucide="flame" class="ct-premium-table-period-icon"></i>
                        <span>Consistency Tracking</span>
                    </div>
                </div>
            </div>
            <div class="ct-premium-table-body-wrapper">
                <div class="adm-table-wrapper">
                    <table class="adm-table">
                        <thead>
                            <tr>
                                <th class="ct-premium-th-left">Parent Details</th>
                                <th class="ct-premium-th-center">Active Habits</th>
                                <th class="ct-premium-th-center">Done Today</th>
                                <th class="ct-premium-th-center">Weekly Tasks Progress</th>
                                <th class="ct-premium-th-right">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($habitStats as $stat)
                                <tr>
                                    <td class="ct-premium-td-left">
                                        <div class="ct-premium-user-details-flex">
                                            <div class="ct-premium-avatar-wrapper">
                                                @if($stat['user']->avatar)
                                                    <img src="{{ asset('avatars/' . $stat['user']->avatar) }}" class="ct-premium-avatar-img">
                                                @else
                                                    <div class="ct-premium-avatar-icon" style="background: var(--db-accent); width: 42px; height: 42px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff;">
                                                        <i data-lucide="user" style="width: 20px;"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ct-premium-user-details-box">
                                                <div class="ct-premium-user-name">{{ $stat['user']->name }}</div>
                                                <div class="ct-premium-user-sub">{{ $stat['user']->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="ct-premium-td-center">
                                        <div class="ct-premium-teacher-wali">
                                            <i data-lucide="activity" class="ct-premium-teacher-icon"></i>
                                            {{ $stat['total_habits'] }} Habits
                                        </div>
                                    </td>
                                    <td class="ct-premium-td-center">
                                        <span class="ct-premium-period-label" style="background: rgba(34, 197, 94, 0.1); color: #22C55E;">
                                            {{ $stat['completed_today'] }} Completed
                                        </span>
                                    </td>
                                    <td class="ct-premium-td-center" style="width: 200px;">
                                        <div style="display: flex; flex-direction: column; gap: 5px;">
                                            <div style="display: flex; justify-content: space-between; font-size: 11px; font-weight: 700;">
                                                <span>{{ $stat['weekly_tasks_completed'] }}/{{ $stat['weekly_tasks_total'] }} Tasks</span>
                                                <span>{{ $stat['weekly_progress'] }}%</span>
                                            </div>
                                            <div style="height: 6px; background: #F3F4F6; border-radius: 3px; overflow: hidden;">
                                                <div style="width: {{ $stat['weekly_progress'] }}%; height: 100%; background: var(--db-accent);"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="ct-premium-td-right">
                                        @if($stat['completed_today'] > 0 || $stat['weekly_tasks_completed'] > 0)
                                            <span style="background: #ecfdf5; color: #059669; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; text-transform: uppercase;">Active</span>
                                        @else
                                            <span style="background: #fdf2f2; color: #e11d48; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; text-transform: uppercase;">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="ct-premium-empty-wrapper">
                                        <div class="ct-premium-empty-content">
                                            <i data-lucide="inbox" class="ct-premium-empty-icon"></i>
                                            <p class="ct-premium-empty-text">No habit tracker data available yet.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="journeyDetailModal" class="ct-modal-overlay">
        <div class="ct-modal-card" style="max-width: 800px;">
            <div class="ct-modal-header">
                <div class="ct-modal-title-group">
                    <div class="ct-modal-header-icon">
                        <i data-lucide="milestone"></i>
                    </div>
                    <div>
                        <h3 class="ct-modal-title" id="modalParentName">Lifebook Journey Detail</h3>
                        <p class="ct-modal-subtitle" id="modalParentEmail">Parent Data</p>
                    </div>
                </div>
                <button class="ct-modal-close" onclick="closeJourneyModal()">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <div class="ct-modal-body" id="modalBody" style="max-height: 70vh; overflow-y: auto;">
                <div class="ct-modal-loading">
                    <div class="ct-modal-spinner"></div>
                    <p style="font-weight: 700; color: var(--db-text-dark); opacity: 0.6;">Fetching details...</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        function switchTab(tab) {
            $('.tab-content').hide().removeClass('active');
            $('.vm-tab-btn').removeClass('active');
            
            if (tab === 'journey') {
                $('#journeyTab').show().addClass('active');
                $('.vm-tab-btn').first().addClass('active');
            } else {
                $('#habitsTab').show().addClass('active');
                $('.vm-tab-btn').last().addClass('active');
            }
            lucide.createIcons();
        }

        function viewJourneyDetail(userId) {
            const modal = $('#journeyDetailModal');
            const body = $('#modalBody');

            modal.css('display', 'flex').hide().fadeIn(300);
            body.html(`
                <div class="ct-modal-loading">
                    <div class="ct-modal-spinner"></div>
                    <p style="font-weight: 700; color: var(--db-text-dark); opacity: 0.6;">Fetching details...</p>
                </div>
            `);

            $.ajax({
                url: `/admin/lifebook-journey/${userId}`,
                method: 'GET',
                success: function(response) {
                    if(response.success) {
                        const journeys = response.journeys;
                        const categories = response.categories;
                        $('#modalParentName').text(response.user.name);
                        $('#modalParentEmail').text(response.user.email);

                        let html = '';
                        
                        categories.forEach(cat => {
                            const j = journeys[cat.id] || {};
                            const isFilled = j.premise || j.vision || j.purpose || j.strategy;
                            
                            html += `
                                <div class="ct-modal-section" style="margin-bottom: 30px;">
                                    <div class="ct-modal-section-title" style="background: ${isFilled ? 'rgba(108, 136, 224, 0.05)' : '#F9FAFB'}; padding: 15px; border-radius: 12px; margin-bottom: 15px; border-left: 4px solid ${isFilled ? 'var(--db-purple)' : '#D1D5DB'};">
                                        <i data-lucide="${cat.icon}" style="width: 18px; color: ${isFilled ? 'var(--db-purple)' : '#9CA3AF'};"></i>
                                        <h4 style="margin: 0; font-weight: 800; font-size: 15px;">${cat.name}</h4>
                                    </div>
                                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 15px;">
                                        <div class="ct-modal-qa-item" style="background: #fff; border: 1px solid #F3F4F6; padding: 12px; border-radius: 10px;">
                                            <div class="ct-modal-question" style="font-size: 10px; opacity: 0.5; text-transform: uppercase; font-weight: 800; margin-bottom: 6px;">Premise</div>
                                            <div class="ct-modal-answer" style="font-size: 12px; font-weight: 600; line-height: 1.5; color: var(--db-text-dark);">${j.premise || '<span style="color: #D1D5DB; font-style: italic;">Belum diisi</span>'}</div>
                                        </div>
                                        <div class="ct-modal-qa-item" style="background: #fff; border: 1px solid #F3F4F6; padding: 12px; border-radius: 10px;">
                                            <div class="ct-modal-question" style="font-size: 10px; opacity: 0.5; text-transform: uppercase; font-weight: 800; margin-bottom: 6px;">Vision</div>
                                            <div class="ct-modal-answer" style="font-size: 12px; font-weight: 600; line-height: 1.5; color: var(--db-text-dark);">${j.vision || '<span style="color: #D1D5DB; font-style: italic;">Belum diisi</span>'}</div>
                                        </div>
                                        <div class="ct-modal-qa-item" style="background: #fff; border: 1px solid #F3F4F6; padding: 12px; border-radius: 10px;">
                                            <div class="ct-modal-question" style="font-size: 10px; opacity: 0.5; text-transform: uppercase; font-weight: 800; margin-bottom: 6px;">Purpose</div>
                                            <div class="ct-modal-answer" style="font-size: 12px; font-weight: 600; line-height: 1.5; color: var(--db-text-dark);">${j.purpose || '<span style="color: #D1D5DB; font-style: italic;">Belum diisi</span>'}</div>
                                        </div>
                                        <div class="ct-modal-qa-item" style="background: #fff; border: 1px solid #F3F4F6; padding: 12px; border-radius: 10px;">
                                            <div class="ct-modal-question" style="font-size: 10px; opacity: 0.5; text-transform: uppercase; font-weight: 800; margin-bottom: 6px;">Strategy</div>
                                            <div class="ct-modal-answer" style="font-size: 12px; font-weight: 600; line-height: 1.5; color: var(--db-text-dark);">${j.strategy || '<span style="color: #D1D5DB; font-style: italic;">Belum diisi</span>'}</div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });

                        body.html(html);
                        lucide.createIcons();
                    }
                },
                error: function() {
                    body.html('<p style="text-align:center; color: red;">Failed to load data. Please try again.</p>');
                }
            });
        }

        function closeJourneyModal() {
            $('#journeyDetailModal').fadeOut(300);
        }

        $('#journeyDetailModal').on('click', function(e) {
            if ($(e.target).hasClass('ct-modal-overlay')) {
                closeJourneyModal();
            }
        });
    </script>
@endsection