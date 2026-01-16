@extends('layouts.app')

@section('title', 'Learning Tracker - Lifebook Parents')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <!-- Background Elements -->
    <img src="{{ asset('/file/bee.png') }}" class="db-bg-pattern db-bee" alt="">
    <img src="{{ asset('/file/flower.png') }}" class="db-bg-pattern db-flower" alt="">

    <!-- Loading Overlay -->
    <div class="lt-loading-overlay" id="loadingOverlay">
        <div class="lt-spinner"></div>
        <div class="lt-loading-text">Sedang memproses...</div>
    </div>

    <!-- Toast Notification -->
    <div id="ltToast" class="lt-toast" style="display: none;">
        <div class="lt-toast-content">
            <i data-lucide="check-circle" id="ltToastIcon"></i>
            <span id="ltToastMessage">Berhasil!</span>
        </div>
    </div>

    <!-- Image Viewer Modal -->
    <div class="lt-modal" id="imageViewerModal">
        <div class="lt-modal-content" style="max-width: 500px; padding: 0; overflow: hidden;">
            <button class="lt-close-modal" id="closeImageViewer" style="position: absolute; top: 15px; right: 15px; z-index: 10;">
                <i data-lucide="x"></i>
            </button>
            <img id="viewerImage" src="" style="width: 100%; height: auto; display: block;">
        </div>
    </div>

    <!-- Edit Comment Modal -->
    <div class="lt-modal" id="editCommentModal">
        <div class="lt-modal-content">
            <div class="lt-modal-header">
                <h3 class="lt-modal-title">Edit Komentar</h3>
                <button class="lt-close-modal" onclick="closeEditCommentModal()">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <form id="editCommentForm" method="POST" enctype="multipart/form-data" class="lt-form">
                @csrf
                @method('PUT')
                <div class="auth-form-group">
                    <label>Isi Komentar</label>
                    <textarea name="content" id="edit_comment_content" class="auth-form-control" style="height: 100px; resize: none; padding-top: 15px;" required></textarea>
                </div>

                <div class="auth-form-row" style="display: flex; gap: 15px; margin-bottom: 20px;">
                    @if($isTeacher)
                        <div class="auth-form-group" style="flex: 1;">
                            <label>Update Progress (%)</label>
                            <input type="number" name="progress_percentage" id="edit_comment_progress" class="auth-form-control" min="0" max="100">
                        </div>
                    @endif
                    <div class="auth-form-group" style="flex: 1;">
                        <label>Ganti Foto (Optional)</label>
                        <input type="file" name="image" class="auth-form-control" style="padding-top: 12px;">
                    </div>
                </div>

                <button type="submit" class="auth-btn-primary">
                    <i data-lucide="save"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </form>
        </div>
    </div>

    @if($isTeacher)
        <!-- Edit Project Modal -->
        <div class="lt-modal" id="editProjectModal">
            <div class="lt-modal-content">
                <div class="lt-modal-header">
                    <h3 class="lt-modal-title">Edit Project</h3>
                    <button class="lt-close-modal" onclick="closeEditModal()">
                        <i data-lucide="x"></i>
                    </button>
                </div>
                <form id="editProjectForm" method="POST" class="lt-form">
                    @csrf
                    @method('PUT')
                    <div class="auth-form-group">
                        <label>Nama Project</label>
                        <input type="text" name="title" id="edit_title" class="auth-form-control" required>
                    </div>

                    <div class="auth-form-group">
                        <label>Jenis Project</label>
                        <select name="type" id="edit_type" class="auth-form-control" style="background: #fff; border: 2px solid #F3F4F6; height: 52px; border-radius: 12px; font-family: 'Poppins', sans-serif;">
                            <option value="weekly">Mingguan</option>
                            <option value="monthly">Bulanan</option>
                            <option value="semester">Semesteran</option>
                        </select>
                    </div>

                    <div class="auth-form-group">
                        <label>Deskripsi Project</label>
                        <textarea name="description" id="edit_description" class="auth-form-control" style="height: 100px; resize: none; padding-top: 15px;" required></textarea>
                    </div>

                    <button type="submit" class="auth-btn-primary">
                        <i data-lucide="save"></i>
                        <span>Update Project</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Create Project Modal -->
        <div class="lt-modal" id="projectModal">
            <div class="lt-modal-content">
                <div class="lt-modal-header">
                    <h3 class="lt-modal-title">Buat Project Baru</h3>
                    <button class="lt-close-modal" id="closeModalBtn">
                        <i data-lucide="x"></i>
                    </button>
                </div>
                <form action="{{ route('learning-tracker.store') }}" method="POST" class="lt-form">
                    @csrf
                    <div class="auth-form-group">
                        <label>Nama Project</label>
                        <input type="text" name="title" class="auth-form-control" placeholder="Contoh: Menanam Sayur Hidroponik" required>
                    </div>

                    <div class="auth-form-group">
                        <label>Pilih Anak Didik</label>
                        <select name="student_ids[]" class="select2-modal" multiple="multiple" required>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="auth-form-group">
                        <label>Jenis Project</label>
                        <select name="type" class="auth-form-control" style="background: #fff; border: 2px solid #F3F4F6; height: 52px; border-radius: 12px; font-family: 'Poppins', sans-serif;">
                            <option value="weekly">Mingguan</option>
                            <option value="monthly">Bulanan</option>
                            <option value="semester">Semesteran</option>
                        </select>
                    </div>

                    <div class="auth-form-group">
                        <label>Deskripsi Project</label>
                        <textarea name="description" class="auth-form-control" style="height: 100px; resize: none; padding-top: 15px;" placeholder="Jelaskan detail project yang akan dipantau..." required></textarea>
                    </div>

                    <button type="submit" class="auth-btn-primary">
                        <i data-lucide="save"></i>
                        <span>Publikasikan Project</span>
                    </button>
                </form>
            </div>
        </div>
    @endif

    <div class="db-container">
        <div class="db-header">
            <div class="db-brand-section">
                <span style="background: var(--db-purple); color: #fff; padding: 3px 10px; border-radius: 99px; font-size: 10px; font-weight: 800; text-transform: uppercase;">Tracker</span>
                <h1 style="margin-top: 5px;">My Kids<br>Learning Tracker</h1>
            </div>
            <a href="{{ $isTeacher ? route('teacher.dashboard') : route('dashboard') }}" class="db-avatar-section">
                <i data-lucide="chevron-left" style="color: var(--db-purple)"></i>
            </a>
        </div>

        @if($isTeacher)
            <!-- Floating Action Button for Teacher -->
            <button class="lt-fab" id="openModalBtn">
                <i data-lucide="plus"></i>
            </button>
        @endif

        @if($studentsWithProjects->isNotEmpty())
            <!-- Student Selector -->
            <div class="lt-student-selector">
                <h3 class="lt-section-title">Anak-anak</h3>
                <div class="lt-student-grid">
                    @foreach($studentsWithProjects as $student)
                        <div class="lt-student-card" onclick="filterByStudent({{ $student->id }}, this)">
                            <div class="lt-student-avatar">
                                <i data-lucide="user"></i>
                            </div>
                            <span class="lt-student-name">{{ explode(' ', $student->name)[0] }}</span>
                        </div>
                    @endforeach
                    <div class="lt-student-card active" onclick="filterByStudent('all', this)">
                        <div class="lt-student-avatar" style="background: var(--db-purple); color: #fff;">
                            <i data-lucide="users"></i>
                        </div>
                        <span class="lt-student-name">Semua</span>
                    </div>
                </div>
            </div>

            <!-- Project Selector (Chips) -->
            <div class="lt-project-selector" id="projectSelector" style="margin-bottom: 30px; display: none;">
                <h3 class="lt-section-title">Project Tersedia</h3>
                <div class="lt-project-chips">
                    <!-- Dynamic chips added via JS -->
                </div>
            </div>
        @endif

        @if($projects->isEmpty())
            <div class="profile-card" style="text-align: center; padding: 50px 20px;">
                <div style="font-size: 40px; margin-bottom: 20px; opacity: 0.3;">
                    <i data-lucide="book-x"></i>
                </div>
                <h3 style="font-weight: 800; color: var(--db-text-dark);">Belum Ada Project</h3>
                <p style="font-size: 13px; color: var(--db-text-dark); opacity: 0.6; margin-top: 10px;">
                    @if($isTeacher)
                        Mulai buat project tracker untuk memantau progress belajar anak didik.
                    @else
                        Halaman ini masih kosong. Silakan hubungi Wali Kelas untuk menanyakan progress belajar anak Ayah / Bunda.
                    @endif
                </p>
            </div>
        @else
            <!-- Social Media Feed Style -->
            <div class="lt-feed" id="projectFeed">
                @foreach($projects as $project)
                    <div class="lt-post-card" data-project-id="{{ $project->id }}" data-project-title="{{ $project->title }}" data-students="{{ json_encode($project->students->pluck('id')) }}">
                        <div class="lt-post-header">
                            <div class="lt-post-info">
                                <h2 class="lt-project-title">{{ $project->title }}</h2>
                                <div class="lt-students-tags" style="margin-bottom: 8px;">
                                    @foreach($project->students as $student)
                                        <span class="lt-student-tag">@ {{ $student->name }}</span>
                                    @endforeach
                                </div>
                                <div class="lt-meta-info">
                                    <span class="lt-posted-by">Posted by: {{ $project->teacher->name }}</span>
                                    <span class="lt-post-date">{{ $project->created_at->diffForHumans() }} • {{ ucfirst($project->type) }}</span>
                                </div>
                            </div>
                            <div class="lt-actions">
                                <div class="lt-badge">{{ $project->progress_percentage }}%</div>
                                @if($isTeacher)
                                    <button class="lt-action-btn lt-edit-btn" onclick="openEditModal({{ $project->id }}, '{{ addslashes($project->title) }}', '{{ $project->type }}', '{{ addslashes($project->description) }}')">
                                        <i data-lucide="edit-2"></i>
                                    </button>
                                    <button class="lt-action-btn lt-delete-btn" onclick="deleteProject({{ $project->id }}, '{{ addslashes($project->title) }}')">
                                        <i data-lucide="trash-2"></i>
                                    </button>
                                @endif
                            </div>
                        </div>

                        <div class="lt-post-content">
                            <p class="lt-project-desc">{{ $project->description }}</p>

                            @if($project->image)
                                <button class="lt-view-image-btn" onclick="viewImage('{{ asset('storage/' . $project->image) }}')">
                                    <i data-lucide="image"></i>
                                    <span>Lihat Gambar Project</span>
                                </button>
                            @endif
                        </div>

                        <div class="lt-progress-tracker">
                            <div class="lt-progress-bar-container">
                                <div class="lt-progress-bar" style="width: {{ $project->progress_percentage }}%"></div>
                            </div>
                        </div>

                        <!-- Logs / Comments -->
                        <div class="lt-comments-section">
                            <div class="lt-comments-title">Komentar & Progress Update</div>

                            @foreach($project->logs as $log)
                                <div class="lt-comment {{ $log->teacher_id ? 'teacher-comment' : 'parent-comment' }}">
                                    <div class="lt-comment-header">
                                        <span class="lt-comment-author">
                                            {{ $log->teacher_id ? $log->teacher->name : $log->user->name }}
                                        </span>
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <span class="lt-comment-time">{{ $log->created_at->format('d M, H:i') }}</span>
                                            
                                            @php
                                                $canEdit = false;
                                                if ($isTeacher && $log->teacher_id == Auth::guard('teacher')->id()) $canEdit = true;
                                                if (!$isTeacher && $log->user_id == Auth::id()) $canEdit = true;
                                            @endphp

                                            @if($canEdit)
                                                <div class="lt-comment-actions">
                                                    <button onclick="openEditCommentModal({{ $log->id }}, '{{ addslashes($log->content) }}', {{ $log->progress_percentage ?? 'null' }})" style="background: none; border: none; padding: 0; color: var(--db-purple); cursor: pointer;">
                                                        <i data-lucide="edit-3" style="width: 14px;"></i>
                                                    </button>
                                                    <form action="{{ route('learning-tracker.log.destroy', $log->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus komentar ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" style="background: none; border: none; padding: 0; color: #EF4444; cursor: pointer;">
                                                            <i data-lucide="trash" style="width: 14px;"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="lt-comment-content">
                                        @if($log->progress_percentage !== null)
                                            <div style="font-size: 11px; font-weight: 800; color: var(--db-purple); margin-bottom: 5px;">
                                                Update Progress: {{ $log->progress_percentage }}%
                                            </div>
                                        @endif
                                        <p>{{ $log->content }}</p>
                                        @if($log->image)
                                            <button class="lt-view-image-btn" style="margin-top: 10px;" onclick="viewImage('{{ asset('storage/' . $log->image) }}')">
                                                <i data-lucide="image"></i>
                                                <span>Lihat Gambar</span>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            <!-- Reply Form -->
                            <div class="lt-reply-form">
                                <form action="{{ route('learning-tracker.reply', $project->id) }}" method="POST" enctype="multipart/form-data" class="lt-form">
                                    @csrf
                                    <div class="lt-reply-input-wrapper">
                                        <textarea name="content" placeholder="Tulis balasan atau update..." required></textarea>
                                        <div class="lt-reply-actions">
                                            @if($isTeacher)
                                                <div class="lt-inline-input">
                                                    <i data-lucide="percent" style="width: 14px;"></i>
                                                    <input type="number" name="progress_percentage" min="0" max="100" value="{{ $project->progress_percentage }}" placeholder="Progress">
                                                </div>
                                            @endif
                                            <label class="lt-file-input">
                                                <i data-lucide="image"></i>
                                                <input type="file" name="image" style="display: none;">
                                            </label>
                                            <button type="submit">
                                                <i data-lucide="send"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div style="margin-bottom: 20px;"></div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            // Modal Logic
            $('#openModalBtn').on('click', function() {
                $('#projectModal').addClass('active');
                $('.select2-modal').select2({
                    placeholder: "Pilih murid...",
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#projectModal .lt-modal-content')
                });
                lucide.createIcons();
            });

            $('#closeModalBtn').on('click', function() {
                $('#projectModal').removeClass('active');
            });

            $('#projectModal').on('click', function(e) {
                if ($(e.target).hasClass('lt-modal')) {
                    $(this).removeClass('active');
                }
            });

            // Image Viewer
            $('#closeImageViewer').on('click', function() {
                $('#imageViewerModal').removeClass('active');
            });

            $('#imageViewerModal').on('click', function(e) {
                if ($(e.target).hasClass('lt-modal')) {
                    $(this).removeClass('active');
                }
            });

            // Edit Comment Modal Backdrop
            $('#editCommentModal').on('click', function(e) {
                if ($(e.target).hasClass('lt-modal')) {
                    $(this).removeClass('active');
                }
            });

            // Loading Logic on Form Submit
            $('.lt-form').on('submit', function() {
                $('#loadingOverlay').addClass('active');
            });

            // Reinitialize icons after any dynamic content
            lucide.createIcons();

            // Edit Project Form AJAX
            $('#editProjectForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const url = form.attr('action');
                const formData = new FormData(form[0]);
                
                console.log('=== EDIT PROJECT ===');
                console.log('URL:', url);
                console.log('Form Data:', Object.fromEntries(formData));
                
                $('#loadingOverlay').addClass('active');
                
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    success: function(response) {
                        console.log('Edit Success:', response);
                        closeEditModal();
                        showToast('Project berhasil diupdate!', 'success');
                        setTimeout(() => location.reload(), 1500);
                    },
                    error: function(xhr, status, error) {
                        console.error('=== EDIT PROJECT ERROR ===');
                        console.error('Status:', xhr.status);
                        console.error('Status Text:', xhr.statusText);
                        console.error('Response:', xhr.responseText);
                        console.error('Error:', error);
                        
                        let errorMsg = 'Gagal mengupdate project.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        } else if (xhr.status === 403) {
                            errorMsg = 'Unauthorized: Anda tidak memiliki akses.';
                        } else if (xhr.status === 404) {
                            errorMsg = 'Project tidak ditemukan.';
                        } else if (xhr.status === 422) {
                            errorMsg = 'Validasi gagal. Periksa input Anda.';
                        } else if (xhr.status === 500) {
                            errorMsg = 'Server error. Cek log server.';
                        }
                        
                        showToast(errorMsg, 'error');
                    },
                    complete: function() {
                        $('#loadingOverlay').removeClass('active');
                    }
                });
            });

            // Edit Comment Form AJAX
            $('#editCommentForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const url = form.attr('action');
                const formData = new FormData(form[0]);
                
                console.log('=== EDIT COMMENT ===');
                console.log('URL:', url);
                console.log('Form Data:', Object.fromEntries(formData));
                
                $('#loadingOverlay').addClass('active');
                
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    success: function(response) {
                        console.log('Edit Comment Success:', response);
                        closeEditCommentModal();
                        showToast('Komentar berhasil diupdate!', 'success');
                        setTimeout(() => location.reload(), 1500);
                    },
                    error: function(xhr, status, error) {
                        console.error('=== EDIT COMMENT ERROR ===');
                        console.error('Status:', xhr.status);
                        console.error('Status Text:', xhr.statusText);
                        console.error('Response:', xhr.responseText);
                        console.error('Error:', error);
                        
                        let errorMsg = 'Gagal mengupdate komentar.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        } else if (xhr.status === 403) {
                            errorMsg = 'Unauthorized: Anda tidak memiliki akses.';
                        } else if (xhr.status === 404) {
                            errorMsg = 'Komentar tidak ditemukan.';
                        } else if (xhr.status === 422) {
                            errorMsg = 'Validasi gagal. Periksa input Anda.';
                        } else if (xhr.status === 500) {
                            errorMsg = 'Server error. Cek log server.';
                        }
                        
                        showToast(errorMsg, 'error');
                    },
                    complete: function() {
                        $('#loadingOverlay').removeClass('active');
                    }
                });
            });
        });

        // Toast Notification Function
        function showToast(message, type = 'success') {
            const toast = $('#ltToast');
            const icon = $('#ltToastIcon');
            const msg = $('#ltToastMessage');
            
            msg.text(message);
            
            if (type === 'success') {
                toast.css('background', 'linear-gradient(135deg, #36B37E, #2E9968)');
                icon.attr('data-lucide', 'check-circle');
            } else {
                toast.css('background', 'linear-gradient(135deg, #EF4444, #DC2626)');
                icon.attr('data-lucide', 'x-circle');
            }
            
            lucide.createIcons();
            toast.fadeIn(300);
            
            setTimeout(() => {
                toast.fadeOut(300);
            }, 3000);
        }

        // Delete Project Function
        function deleteProject(id, title) {
            if (!confirm(`Yakin ingin menghapus project "${title}"?`)) return;
            
            const deleteUrl = "{{ route('learning-tracker.destroy', ':id') }}".replace(':id', id);
            
            console.log('=== DELETE PROJECT ===');
            console.log('Project ID:', id);
            console.log('URL:', deleteUrl);
            
            $('#loadingOverlay').addClass('active');
            
            $.ajax({
                url: deleteUrl,
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: 'DELETE'
                },
                success: function(response) {
                    console.log('Delete Success:', response);
                    showToast('Project berhasil dihapus!', 'success');
                    setTimeout(() => location.reload(), 1500);
                },
                error: function(xhr, status, error) {
                    console.error('=== DELETE PROJECT ERROR ===');
                    console.error('Status:', xhr.status);
                    console.error('Status Text:', xhr.statusText);
                    console.error('Response:', xhr.responseText);
                    console.error('Error:', error);
                    
                    let errorMsg = 'Gagal menghapus project.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else if (xhr.status === 403) {
                        errorMsg = 'Unauthorized: Anda tidak memiliki akses.';
                    } else if (xhr.status === 404) {
                        errorMsg = 'Project tidak ditemukan.';
                    } else if (xhr.status === 500) {
                        errorMsg = 'Server error. Cek log server.';
                    }
                    
                    showToast(errorMsg, 'error');
                    $('#loadingOverlay').removeClass('active');
                }
            });
        }

        let currentEditProjectId = null;

        function openEditModal(id, title, type, description) {
            currentEditProjectId = id;
            const updateUrl = "{{ route('learning-tracker.update', ':id') }}".replace(':id', id);
            $('#editProjectForm').attr('action', updateUrl);
            $('#edit_title').val(title);
            $('#edit_type').val(type);
            $('#edit_description').val(description);
            $('#editProjectModal').addClass('active');
            lucide.createIcons();
        }

        function closeEditModal() {
            $('#editProjectModal').removeClass('active');
        }

        function openEditCommentModal(id, content, progress) {
            const updateUrl = "{{ route('learning-tracker.log.update', ':id') }}".replace(':id', id);
            $('#editCommentForm').attr('action', updateUrl);
            $('#edit_comment_content').val(content);
            if ($('#edit_comment_progress').length) {
                $('#edit_comment_progress').val(progress);
            }
            $('#editCommentModal').addClass('active');
            lucide.createIcons();
        }

        function closeEditCommentModal() {
            $('#editCommentModal').removeClass('active');
        }

        function viewImage(imageUrl) {
            $('#viewerImage').attr('src', imageUrl);
            $('#imageViewerModal').addClass('active');
            lucide.createIcons();
        }

        function filterByStudent(studentId, element) {
            $('.lt-student-card').removeClass('active');
            $(element).addClass('active');

            // Reset project selector
            $('.lt-project-chips').empty();
            let visibleProjects = new Set();
            let projectMap = new Map(); // id -> title

            if (studentId === 'all') {
                $('#projectSelector').fadeOut();
                $('.lt-post-card').fadeIn();
            } else {
                $('.lt-post-card').each(function() {
                    const studentIds = $(this).data('students');
                    const pId = $(this).data('project-id');
                    const pTitle = $(this).data('project-title');
                    
                    if (studentIds.includes(studentId)) {
                        $(this).fadeIn();
                        projectMap.set(pId, pTitle);
                    } else {
                        $(this).fadeOut();
                    }
                });

                // Populate Project Chips
                if (projectMap.size > 0) {
                    $('.lt-project-chips').append(`
                        <div class="lt-project-chip active" onclick="filterByProject('all', this)">
                            Semua Project
                        </div>
                    `);
                    
                    projectMap.forEach((title, id) => {
                        $('.lt-project-chips').append(`
                            <div class="lt-project-chip" onclick="filterByProject(${id}, this)">
                                ${title}
                            </div>
                        `);
                    });
                    $('#projectSelector').fadeIn();
                } else {
                    $('#projectSelector').fadeOut();
                }
            }
            
            setTimeout(() => lucide.createIcons(), 500);
        }

        function filterByProject(projectId, element) {
            $('.lt-project-chip').removeClass('active');
            $(element).addClass('active');

            const activeStudentId = $('.lt-student-card.active').attr('onclick').match(/\d+|all/)[0];

            $('.lt-post-card').each(function() {
                const studentIds = $(this).data('students');
                const pId = $(this).data('project-id');
                
                const matchesStudent = activeStudentId === 'all' || studentIds.includes(parseInt(activeStudentId));
                const matchesProject = projectId === 'all' || pId === projectId;

                if (matchesStudent && matchesProject) {
                    $(this).fadeIn();
                } else {
                    $(this).fadeOut();
                }
            });

            setTimeout(() => lucide.createIcons(), 500);
        }
    </script>
@endsection