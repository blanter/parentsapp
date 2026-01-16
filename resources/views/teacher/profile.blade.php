@extends('layouts.app')

@section('title', 'Teacher Profile - Lifebook Parents')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <!-- Background Elements -->
    <img src="{{ asset('/file/bee.png') }}" class="db-bg-pattern db-bee" alt="">
    <img src="{{ asset('/file/flower.png') }}" class="db-bg-pattern db-flower" alt="">

    <div class="db-container">
        <div class="db-header">
            <div class="db-brand-section">
                <h1 style="font-size: 28px;">Teacher Profile</h1>
            </div>
            <a href="{{ route('teacher.dashboard') }}" class="db-avatar-section"
                style="width: 50px; height: 50px; text-decoration: none;">
                <i data-lucide="chevron-left" style="font-size: 24px; opacity: 1;"></i>
            </a>
        </div>

        <div class="profile-card">
            <div class="profile-header-info">
                <div class="profile-avatar-large">
                    <i data-lucide="user-check"></i>
                </div>
                <h2 class="profile-name">{{ $teacher->name }}</h2>
                <p class="profile-email">{{ $teacher->email }}</p>
                <div style="margin-top: 10px;">
                    <span
                        style="background: var(--db-purple); color: #fff; padding: 4px 12px; border-radius: 99px; font-size: 11px; font-weight: 800; text-transform: uppercase;">Teacher
                        Role</span>
                </div>
            </div>

            <div class="profile-info-grid">
                <div class="profile-info-item">
                    <span class="profile-info-label">Status Guru</span>
                    <span class="profile-info-value" style="color: var(--db-secondary);">Aktif</span>
                </div>
                <div class="profile-info-item">
                    <span class="profile-info-label">Total Anak Didik</span>
                    <span class="profile-info-value">{{ count($assignedStudents) }} Murid</span>
                </div>
            </div>
        </div>

        <div class="profile-card" style="margin-top: 20px;">
            <h3 style="font-weight: 800; font-size: 16px; margin-bottom: 20px; color: var(--db-text-dark);">Kelola Anak
                Didik</h3>
            <p style="font-size: 12px; color: var(--db-text-dark); opacity: 0.6; margin-bottom: 20px;">Pilih murid-murid
                yang berada di bawah bimbingan Anda untuk memantau jurnal mereka.</p>

            <form action="{{ route('teacher.profile.students') }}" method="POST">
                @csrf
                <div class="auth-form-group">
                    <label for="student_ids">Pilih Murid</label>
                    <select name="student_ids[]" id="student_ids" class="select2" multiple="multiple">
                        @foreach($allStudents as $student)
                            <option value="{{ $student->id }}" {{ in_array($student->id, $assignedStudents->pluck('id')->toArray()) ? 'selected' : '' }}>
                                {{ $student->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="auth-btn-primary" style="margin-top: 20px;">
                    <i data-lucide="save"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </form>
        </div>

        <!-- Lifebook Teacher Management -->
        @if(count($assignedStudents) > 0)
            <div class="profile-card" style="margin-top: 20px;">
                <h3 style="font-weight: 800; font-size: 16px; margin-bottom: 10px; color: var(--db-text-dark);">Kelola Guru
                    Lifebook</h3>
                <p style="font-size: 12px; color: var(--db-text-dark); opacity: 0.6; margin-bottom: 20px;">
                    Klaim murid sebagai tanggung jawab lifebook Anda. Anda akan menjadi <strong>Guru Wali & Guru
                        Lifebook</strong> dengan akses penuh untuk merespon jurnal mereka.
                </p>

                <div style="display: flex; flex-direction: column; gap: 10px;">
                    @foreach($assignedStudents as $student)
                        <div
                            style="background: #F9FAFF; border-radius: 15px; padding: 12px; display: flex; align-items: center; gap: 10px;">
                            <div
                                style="width: 45px; height: 45px; background: rgba(108, 136, 224, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--db-purple); flex-shrink: 0;">
                                <i data-lucide="user" style="width: 22px; height: 22px;"></i>
                            </div>

                            <div style="flex: 1; min-width: 0;">
                                <div
                                    style="font-weight: 700; font-size: 13px; color: var(--db-text-dark); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $student->name }}
                                </div>
                                @if(in_array($student->id, $lifebookStudentIds))
                                    <div
                                        style="font-size: 10px; color: var(--db-secondary); font-weight: 600; margin-top: 3px; display: flex; align-items: center; gap: 4px;">
                                        <i data-lucide="check-circle" style="width: 11px; height: 11px;"></i>
                                        <span>Guru Wali & Lifebook</span>
                                    </div>
                                @else
                                @endif
                            </div>

                            @if(in_array($student->id, $lifebookStudentIds))
                                <button class="btn-unclaim-lifebook" data-student-id="{{ $student->id }}"
                                    data-student-name="{{ $student->name }}" title="Batalkan"
                                    style="background: #FEE2E2; color: #EF4444; border: none; width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; flex-shrink: 0;">
                                    <i data-lucide="x-circle" style="width: 18px; height: 18px;"></i>
                                </button>
                            @else
                                <button class="btn-claim-lifebook" data-student-id="{{ $student->id }}"
                                    data-student-name="{{ $student->name }}" title="Saya Guru Lifebook"
                                    style="background: rgba(108, 136, 224, 0.1); color: var(--db-purple); border: none; width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; flex-shrink: 0;">
                                    <i data-lucide="book-heart" style="width: 18px; height: 18px;"></i>
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Info Box -->
                <div
                    style="background: rgba(108, 136, 224, 0.05); border-left: 3px solid var(--db-purple); border-radius: 10px; padding: 12px; margin-top: 15px;">
                    <div style="display: flex; gap: 8px;">
                        <i data-lucide="info"
                            style="width: 16px; height: 16px; color: var(--db-purple); flex-shrink: 0; margin-top: 2px;"></i>
                        <div style="font-size: 11px; color: var(--db-text-dark); opacity: 0.8; line-height: 1.5;">
                            <strong>Klik ikon</strong> <i data-lucide="book-heart"
                                style="width: 12px; height: 12px; display: inline;"></i> untuk menjadi Guru Wali & Lifebook.
                            Klik <i data-lucide="x-circle" style="width: 12px; height: 12px; display: inline;"></i> untuk
                            membatalkan.
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Confirmation Modal -->
        <div id="lifebookModal"
            style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(8px); z-index: 2000; align-items: center; justify-content: center;">
            <div
                style="background: #fff; width: 90%; max-width: 400px; border-radius: 25px; padding: 30px; box-sizing: border-box; animation: slideUp 0.3s ease;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <div
                        style="width: 60px; height: 60px; background: rgba(108, 136, 224, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: var(--db-purple);">
                        <i data-lucide="book-heart" style="width: 28px; height: 28px;"></i>
                    </div>
                    <h3 id="modalTitle"
                        style="font-weight: 800; font-size: 18px; color: var(--db-text-dark); margin-bottom: 10px;"></h3>
                    <p id="modalMessage"
                        style="font-size: 13px; color: var(--db-text-dark); opacity: 0.7; line-height: 1.5;"></p>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button id="modalCancel"
                        style="flex: 1; background: #F3F4F6; color: var(--db-text-dark); border: none; padding: 14px; border-radius: 12px; font-size: 14px; font-weight: 700; cursor: pointer; font-family: 'Poppins', sans-serif;">
                        Batal
                    </button>
                    <button id="modalConfirm"
                        style="flex: 1; background: var(--db-purple); color: #fff; border: none; padding: 14px; border-radius: 12px; font-size: 14px; font-weight: 700; cursor: pointer; font-family: 'Poppins', sans-serif;">
                        Ya, Lanjutkan
                    </button>
                </div>
            </div>
        </div>

        <!-- Toast Notification -->
        <div id="toast"
            style="display: none; position: fixed; bottom: 100px; left: 50%; transform: translateX(-50%); background: #fff; padding: 15px 25px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); z-index: 3000; min-width: 250px; text-align: center; animation: slideUp 0.3s ease;">
            <div id="toastMessage" style="font-size: 13px; font-weight: 600; color: var(--db-text-dark);"></div>
        </div>


        <form action="{{ route('teacher.logout') }}" method="POST" style="margin-top: 30px;">
            @csrf
            <button type="submit" class="btn-logout" style="background: #EF4444; box-shadow: 0 8px 0px #991B1B;">
                <i data-lucide="log-out"></i>
                <span>Keluar Aplikasi Guru</span>
            </button>
        </form>

        <div
            style="text-align: center; margin-top: 30px; opacity: 0.3; font-size: 10px; font-weight: 700; color: var(--db-text-dark); margin-bottom: 100px;">
            Version {{ $appVersion ?? '1.0.0' }} • Teacher Hub
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                placeholder: "Cari nama murid...",
                allowClear: true
            });

            let currentAction = null;
            let currentStudentId = null;
            let currentStudentName = null;

            // Claim Lifebook Button
            $('.btn-claim-lifebook').on('click', function () {
                currentAction = 'claim';
                currentStudentId = $(this).data('student-id');
                currentStudentName = $(this).data('student-name');

                $('#modalTitle').text('Jadi Guru Wali & Lifebook?');
                $('#modalMessage').text(`Anda akan menjadi Guru Wali & Guru Lifebook untuk ${currentStudentName}. Anda akan mendapat akses penuh untuk merespon jurnal lifebook mereka.`);
                $('#lifebookModal').css('display', 'flex');
                lucide.createIcons();
            });

            // Unclaim Lifebook Button
            $('.btn-unclaim-lifebook').on('click', function () {
                currentAction = 'unclaim';
                currentStudentId = $(this).data('student-id');
                currentStudentName = $(this).data('student-name');

                $('#modalTitle').text('Batalkan Guru Wali & Lifebook?');
                $('#modalMessage').text(`Anda tidak akan lagi menjadi Guru Wali & Guru Lifebook untuk ${currentStudentName}. Akses Anda untuk merespon jurnal lifebook mereka akan dicabut.`);
                $('#lifebookModal').css('display', 'flex');
                lucide.createIcons();
            });

            // Modal Cancel
            $('#modalCancel').on('click', function () {
                $('#lifebookModal').css('display', 'none');
                currentAction = null;
                currentStudentId = null;
                currentStudentName = null;
            });

            // Modal Confirm
            $('#modalConfirm').on('click', function () {
                if (!currentAction || !currentStudentId) return;

                const url = currentAction === 'claim'
                    ? '{{ route("teacher.profile.claim-lifebook") }}'
                    : '{{ route("teacher.profile.unclaim-lifebook") }}';

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        student_id: currentStudentId
                    },
                    success: function (response) {
                        $('#lifebookModal').css('display', 'none');
                        showToast(response.message, 'success');

                        // Reload page after 1.5 seconds
                        setTimeout(function () {
                            window.location.reload();
                        }, 1500);
                    },
                    error: function (xhr) {
                        $('#lifebookModal').css('display', 'none');
                        const message = xhr.responseJSON?.message || 'Terjadi kesalahan. Silakan coba lagi.';
                        showToast(message, 'error');
                    }
                });
            });

            // Close modal on backdrop click
            $('#lifebookModal').on('click', function (e) {
                if (e.target.id === 'lifebookModal') {
                    $(this).css('display', 'none');
                }
            });

            function showToast(message, type = 'success') {
                const toast = $('#toast');
                const toastMessage = $('#toastMessage');

                toastMessage.text(message);

                if (type === 'success') {
                    toast.css('border-left', '4px solid var(--db-secondary)');
                } else {
                    toast.css('border-left', '4px solid #EF4444');
                }

                toast.css('display', 'block');

                setTimeout(function () {
                    toast.css('display', 'none');
                }, 3000);
            }

            lucide.createIcons();
        });
    </script>
@endsection