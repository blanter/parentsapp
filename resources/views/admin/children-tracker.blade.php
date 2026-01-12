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
                                                <img src="https://elearning.lifebookacademy.sch.id/files/{{ $submission->student_avatar }}"
                                                    class="ct-premium-avatar-img">
                                            @else
                                                <i data-lucide="user" class="ct-premium-avatar-icon"></i>
                                            @endif
                                        </div>
                                        <div class="ct-premium-user-details-box">
                                            <div class="ct-premium-user-name">{{ $submission->student_name }}</div>
                                            <div class="ct-premium-user-sub">{{ $submission->parent_name }} •
                                                {{ $submission->parent_email }}
                                            </div>
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

    <!-- Detail Modal -->
        <div id="journalDetailModal" class="ct-modal-overlay">
            <div class="ct-modal-card">
                <div class="ct-modal-header">
                    <div class="ct-modal-title-group">
                        <div class="ct-modal-header-icon">
                            <i data-lucide="layout"></i>
                        </div>
                        <div>
                            <h3 class="ct-modal-title" id="modalStudentName">Journal Detail</h3>
                            <p class="ct-modal-subtitle" id="modalPeriod">Quarterly Report</p>
                        </div>
                    </div>
                    <button class="ct-modal-close" onclick="closeJournalModal()">
                        <i data-lucide="x"></i>
                    </button>
                </div>
                <div class="ct-modal-body" id="modalBody">
                    <div class="ct-modal-loading">
                        <div class="ct-modal-spinner"></div>
                        <p style="font-weight: 700; color: var(--db-text-dark); opacity: 0.6;">Fetching details...</p>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script>
            function viewJournalDetail(journalId) {
                const modal = $('#journalDetailModal');
                const body = $('#modalBody');

                // Open modal and show loader
                modal.css('display', 'flex').hide().fadeIn(300);
                body.html(`
                    <div class="ct-modal-loading">
                        <div class="ct-modal-spinner"></div>
                        <p style="font-weight: 700; color: var(--db-text-dark); opacity: 0.6;">Fetching details...</p>
                    </div>
                `);

                // Fetch Data
                $.ajax({
                    url: `/admin/children-tracker/${journalId}`,
                    method: 'GET',
                    success: function(response) {
                        if(response.success) {
                            const data = response.data;
                            $('#modalStudentName').text(data.student_name);
                            $('#modalPeriod').text(`${data.bulan} ${data.tahun} • Parent: ${data.parent_name}`);

                            let html = '';

                            // Parent Aspect Section
                            if (data.parent_filled_at) {
                                html += `
                                    <div class="ct-modal-section">
                                        <div class="ct-modal-section-title">
                                            <i data-lucide="users"></i>
                                            <h4>Aspek Orang Tua</h4>
                                        </div>
                                        <div class="ct-modal-qa-item">
                                            <div class="ct-modal-question">Pendekatan orangtua kepada anak: Saat ini adakah pendekatan tertentu yang sedang diusahakan?</div>
                                            <div class="ct-modal-answer">${data.pendekatan || ''}</div>
                                        </div>
                                        <div class="ct-modal-qa-item">
                                            <div class="ct-modal-question">Interaksi orangtua dan anak: Bagaimana interaksi ayah / bunda berjalan?</div>
                                            <div class="ct-modal-answer">${data.interaksi || ''}</div>
                                        </div>
                                        <div class="ct-modal-response-box">
                                            <span class="ct-modal-response-label">Respon Guru Wali</span>
                                            <div class="ct-modal-answer">${data.teacher_reply || ''}</div>
                                        </div>
                                        <div class="ct-modal-response-box">
                                            <span class="ct-modal-response-label">Konfirmasi Guru Lifebook</span>
                                            <div class="ct-modal-answer">${data.lifebook_teacher_reply || ''}</div>
                                        </div>
                                    </div>
                                `;
                            }

                            // Child Aspect Section
                            if (data.child_filled_at) {
                                html += `
                                    <div class="ct-modal-section">
                                        <div class="ct-modal-section-title">
                                            <i data-lucide="baby"></i>
                                            <h4>Aspek Anak</h4>
                                        </div>
                                        <div class="ct-modal-qa-item">
                                            <div class="ct-modal-question">Rutinitas: Bagaimana rutinitas pagi dan sore / malamnya berjalan?</div>
                                            <div class="ct-modal-answer">${data.rutinitas || ''}</div>
                                        </div>
                                        <div class="ct-modal-qa-item">
                                            <div class="ct-modal-question">Hubungan keluarga: Bagaimana hubungan dengan keluarga di rumah?</div>
                                            <div class="ct-modal-answer">${data.hubungan_keluarga || ''}</div>
                                        </div>
                                        <div class="ct-modal-qa-item">
                                            <div class="ct-modal-question">Hubungan dengan teman: Bagaimana hubungan dengan teman di sekitar rumah / sekolah?</div>
                                            <div class="ct-modal-answer">${data.hubungan_teman || ''}</div>
                                        </div>
                                        <div class="ct-modal-qa-item">
                                            <div class="ct-modal-question">Aspek sosial: Bagaimana perkembangan aspek sosialnya?</div>
                                            <div class="ct-modal-answer">${data.aspek_sosial || ''}</div>
                                        </div>
                                        <div class="ct-modal-response-box">
                                            <span class="ct-modal-response-label">Respon Guru Wali</span>
                                            <div class="ct-modal-answer">${data.teacher_report || ''}</div>
                                        </div>
                                        <div class="ct-modal-response-box">
                                            <span class="ct-modal-response-label">Konfirmasi Guru Lifebook</span>
                                            <div class="ct-modal-answer">${data.lifebook_child_reply || ''}</div>
                                        </div>
                                    </div>
                                `;
                            }

                            // Internal/External Aspect Section
                            if (data.internal_external_filled_at) {
                                html += `
                                    <div class="ct-modal-section">
                                        <div class="ct-modal-section-title">
                                            <i data-lucide="activity"></i>
                                            <h4>Aspek Internal & Eksternal</h4>
                                        </div>
                                        <div class="ct-modal-qa-item">
                                            <div class="ct-modal-question">Aspek Internal (Kesehatan, Emosi, Spiritual, Keterampilan):</div>
                                            <div class="ct-modal-answer">${data.aspek_internal || ''}</div>
                                        </div>
                                        <div class="ct-modal-response-box" style="margin-bottom: 20px;">
                                            <span class="ct-modal-response-label">Tanggapan Guru Wali (Internal)</span>
                                            <div class="ct-modal-answer">${data.internal_teacher_reply || ''}</div>
                                        </div>
                                        <div class="ct-modal-qa-item">
                                            <div class="ct-modal-question">Aspek Eksternal (Keluarga, Sosial, Keuangan, Kualitas Hidup):</div>
                                            <div class="ct-modal-answer">${data.aspek_external || ''}</div>
                                        </div>
                                        <div class="ct-modal-response-box" style="margin-bottom: 20px;">
                                            <span class="ct-modal-response-label">Tanggapan Guru Wali (Eksternal)</span>
                                            <div class="ct-modal-answer">${data.external_teacher_reply || ''}</div>
                                        </div>
                                        <div class="ct-modal-qa-item" style="background: #FFFBEB; border-left-color: #FBBF24;">
                                            <div class="ct-modal-question" style="color: #92400E;">Strategi / Pendekatan Baru (Ditulis Guru Lifebook):</div>
                                            <div class="ct-modal-answer">${data.strategi_baru || ''}</div>
                                        </div>
                                        <div class="ct-modal-response-box">
                                            <span class="ct-modal-response-label">Tanggapan Orang Tua (Strategi)</span>
                                            <div class="ct-modal-answer">${data.strategi_parent_reply || ''}</div>
                                        </div>
                                    </div>
                                `;
                            }

                            // Reflection Section
                            if (data.refleksi_filled_at) {
                                const emojis = { 5: '😁', 4: '😊', 3: '😐', 2: '😟', 1: '😡' };
                                html += `
                                    <div class="ct-modal-section">
                                        <div class="ct-modal-section-title">
                                            <i data-lucide="smile"></i>
                                            <h4>Refleksi Orang Tua</h4>
                                        </div>
                                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                            <div class="ct-modal-qa-item" style="margin-bottom: 0;">
                                                <div class="ct-modal-question" style="font-size: 11px;">Keterbukaan anak</div>
                                                <div style="font-size: 20px;">${emojis[data.refleksi_keterbukaan] || '-'}</div>
                                            </div>
                                            <div class="ct-modal-qa-item" style="margin-bottom: 0;">
                                                <div class="ct-modal-question" style="font-size: 11px;">Rutinitas rumah</div>
                                                <div style="font-size: 20px;">${emojis[data.refleksi_rutinitas] || '-'}</div>
                                            </div>
                                            <div class="ct-modal-qa-item" style="margin-bottom: 0;">
                                                <div class="ct-modal-question" style="font-size: 11px;">Tauladan ortu</div>
                                                <div style="font-size: 20px;">${emojis[data.refleksi_tauladan] || '-'}</div>
                                            </div>
                                            <div class="ct-modal-qa-item" style="margin-bottom: 0;">
                                                <div class="ct-modal-question" style="font-size: 11px;">Memahami emosi</div>
                                                <div style="font-size: 20px;">${emojis[data.refleksi_emosi] || '-'}</div>
                                            </div>
                                            <div class="ct-modal-qa-item" style="margin-bottom: 0;">
                                                <div class="ct-modal-question" style="font-size: 11px;">Journaling ortu</div>
                                                <div style="font-size: 20px;">${emojis[data.refleksi_journaling] || '-'}</div>
                                            </div>
                                            <div class="ct-modal-qa-item" style="margin-bottom: 0;">
                                                <div class="ct-modal-question" style="font-size: 11px;">Ortu bersahabat</div>
                                                <div style="font-size: 20px;">${emojis[data.refleksi_bersahabat] || '-'}</div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            }

                            if (html === '') {
                                html = `
                                    <div class="ct-premium-empty-wrapper">
                                        <div class="ct-premium-empty-content">
                                            <i data-lucide="alert-circle" class="ct-premium-empty-icon"></i>
                                            <p class="ct-premium-empty-text">This journal is currently empty.</p>
                                        </div>
                                    </div>
                                `;
                            }

                            body.html(html);
                            lucide.createIcons();
                        }
                    },
                    error: function() {
                        body.html('<p style="text-align:center; color: red;">Failed to load data. Please try again.</p>');
                    }
                });
            }

            function closeJournalModal() {
                $('#journalDetailModal').fadeOut(300);
            }

            // Close on overlay click
            $('#journalDetailModal').on('click', function(e) {
                if ($(e.target).hasClass('ct-modal-overlay')) {
                    closeJournalModal();
                }
            });

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