@extends('layouts.admin')

@section('title', 'Children Tracker Data')
@section('header_title', 'Admin Panel')
@section('header_subtitle', 'Lifebook Children Tracker Analytics')

@section('content')
    <!-- Primary Statistics Cards -->
    <div class="ct-premium-stats-grid">
        <div class="adm-card ct-premium-stat-card">
            <div class="ct-premium-stat-content">
                <div class="ct-premium-stat-icon-box" style="background: rgba(108, 136, 224, 0.1);">
                    <i data-lucide="book-open" class="ct-premium-stat-icon-inner" style="color: var(--db-purple);"></i>
                </div>
                <div class="ct-premium-stat-info">
                    <div class="ct-premium-stat-label">Parent Aspect</div>
                    <div class="ct-premium-stat-value">{{ $parentAspectFilled }}</div>
                </div>
            </div>
        </div>

        <div class="adm-card ct-premium-stat-card">
            <div class="ct-premium-stat-content">
                <div class="ct-premium-stat-icon-box" style="background: rgba(255, 214, 75, 0.1);">
                    <i data-lucide="graduation-cap" class="ct-premium-stat-icon-inner"
                        style="color: var(--db-primary);"></i>
                </div>
                <div class="ct-premium-stat-info">
                    <div class="ct-premium-stat-label">Child Aspect</div>
                    <div class="ct-premium-stat-value">{{ $childAspectFilled }}</div>
                </div>
            </div>
        </div>

        <div class="adm-card ct-premium-stat-card">
            <div class="ct-premium-stat-content">
                <div class="ct-premium-stat-icon-box" style="background: rgba(34, 197, 94, 0.1);">
                    <i data-lucide="activity" class="ct-premium-stat-icon-inner" style="color: #22C55E;"></i>
                </div>
                <div class="ct-premium-stat-info">
                    <div class="ct-premium-stat-label">Int / Ext</div>
                    <div class="ct-premium-stat-value">{{ $internalExternalFilled }}</div>
                </div>
            </div>
        </div>

        <div class="adm-card ct-premium-stat-card">
            <div class="ct-premium-stat-content">
                <div class="ct-premium-stat-icon-box" style="background: rgba(139, 92, 246, 0.1);">
                    <i data-lucide="message-square" class="ct-premium-stat-icon-inner" style="color: #8B5CF6;"></i>
                </div>
                <div class="ct-premium-stat-info">
                    <div class="ct-premium-stat-label">Replied</div>
                    <div class="ct-premium-stat-value">{{ $totalReplied }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Statistics Cards -->
    <div class="ct-premium-users-grid">
        <div class="adm-card ct-premium-user-card" style="background: linear-gradient(135deg, #ffffff 0%, #F8FAFF 100%);">
            <div class="ct-premium-user-content">
                <div>
                    <div class="ct-premium-user-label">Active Parents</div>
                    <div class="ct-premium-user-value" style="color: var(--db-purple);">{{ $activeParents }}</div>
                </div>
                <div class="ct-premium-user-icon-box"
                    style="background: var(--db-purple); box-shadow: 0 4px 12px rgba(108, 136, 224, 0.3);">
                    <i data-lucide="users" class="ct-premium-user-icon-inner"></i>
                </div>
            </div>
        </div>

        <div class="adm-card ct-premium-user-card" style="background: linear-gradient(135deg, #ffffff 0%, #FFF9F8 100%);">
            <div class="ct-premium-user-content">
                <div>
                    <div class="ct-premium-user-label">Active Students</div>
                    <div class="ct-premium-user-value" style="color: var(--db-accent);">{{ $activeStudents }}</div>
                </div>
                <div class="ct-premium-user-icon-box"
                    style="background: var(--db-accent); box-shadow: 0 4px 12px rgba(255, 107, 74, 0.3);">
                    <i data-lucide="user" class="ct-premium-user-icon-inner"></i>
                </div>
            </div>
        </div>

        <div class="adm-card ct-premium-user-card" style="background: linear-gradient(135deg, #ffffff 0%, #FFFDF5 100%);">
            <div class="ct-premium-user-content">
                <div>
                    <div class="ct-premium-user-label">Active Teachers</div>
                    <div class="ct-premium-user-value" style="color: var(--db-primary);">{{ $activeTeachers }}</div>
                </div>
                <div class="ct-premium-user-icon-box"
                    style="background: var(--db-primary); box-shadow: 0 4px 12px rgba(255, 214, 75, 0.3); color: var(--db-text-dark);">
                    <i data-lucide="briefcase" class="ct-premium-user-icon-inner"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Data Table -->
    <div class="adm-card">
        <div class="adm-card-header">
            <div class="ct-premium-table-header">
                <h3 class="ct-premium-table-title">Journal Submissions Overview</h3>
                <div class="ct-premium-table-period-badge">
                    <i data-lucide="calendar" class="ct-premium-table-period-icon"></i>
                    <span>{{ $currentQuarterName }} / {{ $currentMonthName }} {{ $currentYear }}</span>
                </div>
            </div>
        </div>
        <div class="ct-premium-table-body-wrapper">
            <div class="adm-table-wrapper">
                <table class="adm-table">
                    <thead>
                        <tr>
                            <th class="ct-premium-th-left">Student & Parent</th>
                            <th class="ct-premium-th-center">Teacher Wali</th>
                            <th class="ct-premium-th-center">Period</th>
                            <th class="ct-premium-th-center">Parent Aspect</th>
                            <th class="ct-premium-th-center">Child Aspect</th>
                            <th class="ct-premium-th-center">Int / Ext</th>
                            <th class="ct-premium-th-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions as $submission)
                            <tr>
                                <td class="ct-premium-td-left">
                                    <div class="ct-premium-user-details-flex">
                                        <div class="ct-premium-avatar-wrapper">
                                            @if($submission->student_avatar)
                                                <img src="https://lifebook.id/storage/{{ $submission->student_avatar }}"
                                                    class="ct-premium-avatar-img">
                                            @else
                                                <i data-lucide="user" class="ct-premium-avatar-icon"></i>
                                            @endif
                                        </div>
                                        <div class="ct-premium-user-details-box">
                                            <div class="ct-premium-user-name">{{ $submission->student_name }}</div>
                                            <div class="ct-premium-user-sub">{{ $submission->parent_name }} •
                                                {{ $submission->parent_email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="ct-premium-td-center">
                                    <div class="ct-premium-teacher-wali">
                                        <i data-lucide="briefcase" class="ct-premium-teacher-icon"></i>
                                        {{ $submission->teacher_wali ?: '-' }}
                                    </div>
                                </td>
                                <td class="ct-premium-td-center">
                                    <span class="ct-premium-period-label">
                                        {{ $submission->bulan }}
                                    </span>
                                </td>
                                <td class="ct-premium-td-center">
                                    @include('admin.partials.ct-status-badge', ['filled' => $submission->parent_aspect_filled, 'replied' => $submission->parent_aspect_replied])
                                </td>
                                <td class="ct-premium-td-center">
                                    @include('admin.partials.ct-status-badge', ['filled' => $submission->child_aspect_filled, 'replied' => $submission->child_aspect_replied])
                                </td>
                                <td class="ct-premium-td-center">
                                    @include('admin.partials.ct-status-badge', ['filled' => $submission->internal_external_filled, 'replied' => $submission->internal_external_replied])
                                </td>
                                <td class="ct-premium-td-right">
                                    <button class="ct-premium-btn-view" onclick="viewJournalDetail({{ $submission->id }})">
                                        <i data-lucide="eye" class="ct-premium-btn-view-icon"></i>
                                        <span>VIEW</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="ct-premium-empty-wrapper">
                                    <div class="ct-premium-empty-content">
                                        <i data-lucide="inbox" class="ct-premium-empty-icon"></i>
                                        <p class="ct-premium-empty-text">No journal submissions available yet.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function viewJournalDetail(journalId) {
            alert('Journal Detail View - ID: ' + journalId + '\n\nThis feature will show detailed information about the journal submission.');
        }

        // Add hover effect for premium buttons
        document.querySelectorAll('.ct-premium-btn-view').forEach(btn => {
            btn.addEventListener('mousedown', () => {
                btn.style.transform = 'translateY(2px)';
                btn.style.boxShadow = '0 2px 0px #e6a51d';
            });
            btn.addEventListener('mouseup', () => {
                btn.style.transform = 'translateY(0px)';
                btn.style.boxShadow = '0 4px 0px #e6a51d';
            });
            btn.addEventListener('mouseleave', () => {
                btn.style.transform = 'translateY(0px)';
                btn.style.boxShadow = '0 4px 0px #e6a51d';
            });
        });
    </script>
@endsection