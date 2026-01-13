@extends('layouts.admin')

@section('title', 'Learning Tracker Analytics')
@section('header_title', 'Admin Panel')
@section('header_subtitle', 'Lifebook Learning Tracker Analytics')

@section('content')
    <!-- Primary Statistics Cards -->
    <div class="ct-premium-stats-grid">
        <div class="adm-card ct-premium-stat-card">
            <div class="ct-premium-stat-content">
                <div class="ct-premium-stat-icon-box" style="background: rgba(108, 136, 224, 0.1);">
                    <i data-lucide="folder-kanban" class="ct-premium-stat-icon-inner" style="color: var(--db-purple);"></i>
                </div>
                <div class="ct-premium-stat-info">
                    <div class="ct-premium-stat-label">Total Projects</div>
                    <div class="ct-premium-stat-value">{{ $totalProjects }}</div>
                </div>
            </div>
        </div>

        <div class="adm-card ct-premium-stat-card">
            <div class="ct-premium-stat-content">
                <div class="ct-premium-stat-icon-box" style="background: rgba(34, 197, 94, 0.1);">
                    <i data-lucide="message-square" class="ct-premium-stat-icon-inner" style="color: #22C55E;"></i>
                </div>
                <div class="ct-premium-stat-info">
                    <div class="ct-premium-stat-label">Total Logs</div>
                    <div class="ct-premium-stat-value">{{ $totalLogs }}</div>
                </div>
            </div>
        </div>

        <div class="adm-card ct-premium-stat-card">
            <div class="ct-premium-stat-content">
                <div class="ct-premium-stat-icon-box" style="background: rgba(255, 214, 75, 0.1);">
                    <i data-lucide="trending-up" class="ct-premium-stat-icon-inner" style="color: var(--db-primary);"></i>
                </div>
                <div class="ct-premium-stat-info">
                    <div class="ct-premium-stat-label">Avg. Progress</div>
                    <div class="ct-premium-stat-value">{{ round($avgProgress, 1) }}%</div>
                </div>
            </div>
        </div>

        <div class="adm-card ct-premium-stat-card">
            <div class="ct-premium-stat-content">
                <div class="ct-premium-stat-icon-box" style="background: rgba(139, 92, 246, 0.1);">
                    <i data-lucide="award" class="ct-premium-stat-icon-inner" style="color: #8B5CF6;"></i>
                </div>
                <div class="ct-premium-stat-info">
                    <div class="ct-premium-stat-label">Active Students</div>
                    <div class="ct-premium-stat-value">{{ $activeStudents }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Statistics Cards -->
    <div class="ct-premium-users-grid" style="grid-template-columns: 1fr 1fr;">
        <div class="adm-card ct-premium-user-card" style="background: linear-gradient(135deg, #ffffff 0%, #F8FAFF 100%);">
            <div class="ct-premium-user-content">
                <div>
                    <div class="ct-premium-user-label">Active Teachers</div>
                    <div class="ct-premium-user-value" style="color: var(--db-purple);">{{ $activeTeachers }}</div>
                </div>
                <div class="ct-premium-user-icon-box"
                    style="background: var(--db-purple); box-shadow: 0 4px 12px rgba(108, 136, 224, 0.3);">
                    <i data-lucide="briefcase" class="ct-premium-user-icon-inner"></i>
                </div>
            </div>
        </div>

        <div class="adm-card ct-premium-user-card" style="background: linear-gradient(135deg, #ffffff 0%, #FFF9F8 100%);">
            <div class="ct-premium-user-content">
                <div>
                    <div class="ct-premium-user-label">Participating Parents</div>
                    <div class="ct-premium-user-value" style="color: var(--db-accent);">{{ $activeParents }}</div>
                </div>
                <div class="ct-premium-user-icon-box"
                    style="background: var(--db-accent); box-shadow: 0 4px 12px rgba(255, 107, 74, 0.3);">
                    <i data-lucide="users" class="ct-premium-user-icon-inner"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Data Table -->
    <div class="adm-card">
        <div class="adm-card-header">
            <div class="ct-premium-table-header">
                <h3 class="ct-premium-table-title">Learning Projects Overview</h3>
            </div>
        </div>
        <div class="ct-premium-table-body-wrapper">
            <div class="adm-table-wrapper">
                <table class="adm-table">
                    <thead>
                        <tr>
                            <th class="ct-premium-th-left">Project Title</th>
                            <th class="ct-premium-th-center">Teacher</th>
                            <th class="ct-premium-th-center">Type</th>
                            <th class="ct-premium-th-left">Students & Parents</th>
                            <th class="ct-premium-th-center">Progress & Activity</th>
                            <th class="ct-premium-th-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $project)
                            <tr>
                                <td class="ct-premium-td-left">
                                    <div class="ct-premium-user-name">{{ $project->title }}</div>
                                </td>
                                <td class="ct-premium-td-center">
                                    <div class="ct-premium-teacher-wali">
                                        <i data-lucide="user" class="ct-premium-teacher-icon"></i>
                                        {{ $project->teacher->name }}
                                    </div>
                                </td>
                                <td class="ct-premium-td-center">
                                    <span class="ct-premium-period-label"
                                        style="background: rgba(108, 136, 224, 0.1); color: var(--db-purple);">
                                        {{ ucfirst($project->type) }}
                                    </span>
                                </td>
                                <td class="ct-premium-td-left">
                                    @foreach($project->student_details as $detail)
                                        <div style="margin-bottom: 5px;">
                                            <div class="ct-premium-user-name" style="font-size: 12px;">{{ $detail->student_name }}
                                            </div>
                                            <div class="ct-premium-user-sub" style="font-size: 10px;">Ortu:
                                                {{ $detail->parent_name ?: '-' }}</div>
                                        </div>
                                    @endforeach
                                </td>
                                <td class="ct-premium-td-center" style="min-width: 150px;">
                                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 5px;">
                                        <div
                                            style="flex: 1; height: 6px; background: #F3F4F6; border-radius: 99px; overflow: hidden;">
                                            <div
                                                style="width: {{ $project->progress_percentage }}%; height: 100%; background: var(--db-purple);">
                                            </div>
                                        </div>
                                        <span
                                            style="font-size: 11px; font-weight: 800; color: var(--db-purple);">{{ $project->progress_percentage }}%</span>
                                    </div>
                                    <div class="ct-premium-user-sub" style="font-size: 10px;">
                                        <i data-lucide="clock"
                                            style="width: 10px; height: 10px; display: inline-block; vertical-align: middle;"></i>
                                        {{ $project->last_activity ? $project->last_activity->diffForHumans() : 'No activity' }}
                                    </div>
                                </td>
                                <td class="ct-premium-td-right">
                                    <button class="ct-premium-btn-view" onclick="viewProjectDetail({{ $project->id }})">
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
                                        <p class="ct-premium-empty-text">No learning projects available yet.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="projectDetailModal" class="ct-modal-overlay">
        <div class="ct-modal-card" style="max-width: 900px;">
            <div class="ct-modal-header">
                <div class="ct-modal-title-group">
                    <div class="ct-modal-header-icon">
                        <i data-lucide="folder-kanban"></i>
                    </div>
                    <div>
                        <h3 class="ct-modal-title" id="modalProjectTitle">Project Detail</h3>
                        <p class="ct-modal-subtitle" id="modalProjectSubtitle">Teacher & Type</p>
                    </div>
                </div>
                <button class="ct-modal-close" onclick="closeProjectModal()">
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
        function viewProjectDetail(projectId) {
            const modal = $('#projectDetailModal');
            const body = $('#modalBody');

            modal.css('display', 'flex').hide().fadeIn(300);
            body.html(`
                                <div class="ct-modal-loading">
                                    <div class="ct-modal-spinner"></div>
                                    <p style="font-weight: 700; color: var(--db-text-dark); opacity: 0.6;">Fetching details...</p>
                                </div>
                            `);

            $.ajax({
                url: `/admin/learning-tracker/${projectId}`,
                method: 'GET',
                success: function (response) {
                    if (response.success) {
                        const data = response.data;
                        $('#modalProjectTitle').text(data.title);
                        $('#modalProjectSubtitle').text(`Posted by ${data.teacher.name} • ${data.type.toUpperCase()}`);

                        let html = `<div class="ct-modal-section">
                                                <div class="ct-modal-section-title">
                                                    <i data-lucide="info"></i>
                                                    <h4>Project Description</h4>
                                                </div>
                                                <div class="ct-modal-answer" style="padding: 15px; background: #F9FAFF; border-radius: 12px; font-size: 14px; line-height: 1.6;">${data.description}</div>
                                            </div>

                                            <div class="ct-modal-section">
                                                <div class="ct-modal-section-title">
                                                    <i data-lucide="users"></i>
                                                    <h4>Included Students</h4>
                                                </div>
                                                <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px;">
                                                    ${data.students.map(s => `
                                                        <span style="background: rgba(108, 136, 224, 0.1); color: var(--db-purple); padding: 5px 12px; border-radius: 99px; font-size: 11px; font-weight: 700;">
                                                            @${s.name}
                                                        </span>
                                                    `).join('')}
                                                </div>
                                            </div>

                                            <div class="ct-modal-section">
                                                <div class="ct-modal-section-title">
                                                    <i data-lucide="message-circle"></i>
                                                    <h4>Activity Logs (${data.logs.length})</h4>
                                                </div>
                                                <div style="display: flex; flex-direction: column; gap: 15px; margin-top: 15px;">
                                                    ${data.logs.length > 0 ? data.logs.map(log => {
                            const authorName = log.teacher_id ? log.teacher.name : (log.user ? log.user.name : 'Unknown');
                            const roleClass = log.teacher_id ? 'background: #F0F4FF; border-left: 3px solid var(--db-purple);' : 'background: #FFF9F8; border-left: 3px solid var(--db-accent);';
                            const roleLabel = log.teacher_id ? 'Guru' : 'Orang Tua';

                            return `
                                                            <div style="padding: 15px; border-radius: 12px; ${roleClass}">
                                                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                                                    <span style="font-weight: 800; font-size: 13px; color: var(--db-text-dark);">${authorName} (${roleLabel})</span>
                                                                    <span style="font-size: 11px; color: #9CA3AF;">${new Date(log.created_at).toLocaleString()}</span>
                                                                </div>
                                                                ${log.progress_percentage !== null ? `
                                                                    <div style="font-weight: 800; font-size: 11px; color: var(--db-purple); margin-bottom: 5px;">Update Progress: ${log.progress_percentage}%</div>
                                                                ` : ''}
                                                                <div style="font-size: 13px; color: var(--db-text-dark); line-height: 1.5;">${log.content}</div>
                                                                ${log.image ? `
                                                                    <div style="margin-top: 10px;">
                                                                        <a href="/storage/${log.image}" target="_blank" style="font-size: 11px; color: var(--db-purple); font-weight: 700; display: flex; align-items: center; gap: 5px;">
                                                                            <i data-lucide="image" style="width: 12px;"></i> Lihat Lampiran Gambar
                                                                        </a>
                                                                    </div>
                                                                ` : ''}
                                                            </div>
                                                        `;
                        }).join('') : `
                                                        <div style="text-align: center; padding: 20px; opacity: 0.5;">No activity logs yet.</div>
                                                    `}
                                                </div>
                                            </div>
                                        `;

                        body.html(html);
                        lucide.createIcons();
                    }
                },
                error: function () {
                    body.html('<p style="text-align:center; color: red;">Failed to load data. Please try again.</p>');
                }
            });
        }

        function closeProjectModal() {
            $('#projectDetailModal').fadeOut(300);
        }

        $('#projectDetailModal').on('click', function (e) {
            if ($(e.target).hasClass('ct-modal-overlay')) {
                closeProjectModal();
            }
        });
    </script>
@endsection