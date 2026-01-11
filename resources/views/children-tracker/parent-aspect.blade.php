<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('/file/lifebookicon.png')}}" rel='icon' type='image/x-icon' />
    <title>Aspek Orang Tua - Lifebook Parents</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="{{asset('/file/style.css')}}?v=22" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="db-body">
    <!-- Background Elements -->
    <img src="{{ asset('/file/bee.png') }}" class="db-bg-pattern db-bee" alt="">
    <img src="{{ asset('/file/flower.png') }}" class="db-bg-pattern db-flower" alt="">

    <div class="db-container pa-container">
        <!-- Success Alert Popup -->
        <div id="successPopup" class="pa-popup-overlay" style="display: none;">
            <div class="pa-popup-card">
                <div class="pa-popup-icon">
                    <i data-lucide="check-circle-2"></i>
                </div>
                <h3 class="pa-popup-title">Berhasil Disimpan!</h3>
                <p class="pa-popup-message">
                    @if($isTeacher)
                        Data respon Anda telah aman disimpan ke sistem. Tetap semangat membimbing murid!
                    @else
                        Data jurnal Anda telah aman disimpan ke sistem. Terima kasih Ayah / Bunda!
                    @endif
                </p>
                <button class="pa-popup-btn" onclick="closePopup()">Siap, Terima Kasih</button>
            </div>
        </div>
        <div class="db-header">
            <div class="db-brand-section">
                <h1 style="font-size: 28px;">Aspek<br>Orang Tua</h1>
            </div>
            <div style="display: flex; gap: 10px; align-items: center;">
                <a href="{{ route('children-tracker.index') }}" class="db-avatar-section"
                    style="width: 45px; height: 45px; text-decoration: none;">
                    <i data-lucide="chevron-left" style="font-size: 20px;"></i>
                </a>
            </div>
        </div>

        <!-- Quarter Selector -->
        <div class="pa-month-selector" style="margin-top: 5px;">
            @php
                // Expecting "Kuartal X 2026"
                $parts = explode(' ', $selectedTime);
                $qNum = (int) ($parts[1] ?? 1);
                $year = (int) ($parts[2] ?? date('Y'));

                $prevQ = $qNum - 1;
                $prevYear = $year;
                if ($prevQ < 1) {
                    $prevQ = 4;
                    $prevYear--;
                }

                $nextQ = $qNum + 1;
                $nextYear = $year;
                if ($nextQ > 4) {
                    $nextQ = 1;
                    $nextYear++;
                }

                $prevTime = "Kuartal $prevQ $prevYear";
                $nextTime = "Kuartal $nextQ $nextYear";
            @endphp
            <a href="{{ route('children-tracker.parent-aspect', ['time' => $prevTime, 'child_id' => $selectedChildId]) }}"
                class="pa-month-btn">
                <i data-lucide="chevron-left"></i>
            </a>
            <span>{{ $selectedTime }}</span>
            <a href="{{ route('children-tracker.parent-aspect', ['time' => $nextTime, 'child_id' => $selectedChildId]) }}"
                class="pa-month-btn">
                <i data-lucide="chevron-right"></i>
            </a>
        </div>

        <!-- Child Selector -->
        <div class="pa-child-selector">
            <label class="pa-child-label">Pilih Nama Anak</label>
            <div class="pa-select-wrapper">
                <select class="pa-select" id="childSelector">
                    @foreach($children as $child)
                        <option value="{{ $child->id }}" {{ $selectedChildId == $child->id ? 'selected' : '' }}>
                            {{ $child->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        @if($isTeacher || (Auth::check() && Auth::user()->role === 'admin'))
            <div
                style="background: white; border-radius: 20px; padding: 15px 20px; margin-bottom: 25px; border: 2px solid #F3F4F6;">
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div
                            style="width: 32px; height: 32px; background: rgba(108, 136, 224, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--db-purple);">
                            <i data-lucide="users" style="width: 18px; height: 18px;"></i>
                        </div>
                        <div>
                            <p style="font-size: 10px; font-weight: 700; opacity: 0.5; text-transform: uppercase;">Orang Tua
                            </p>
                            <p style="font-size: 14px; font-weight: 800; color: var(--db-text-dark);">{{ $parentName }}</p>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div
                            style="width: 32px; height: 32px; background: rgba(54, 179, 126, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--db-secondary);">
                            <i data-lucide="user-check" style="width: 18px; height: 18px;"></i>
                        </div>
                        <div>
                            <p style="font-size: 10px; font-weight: 700; opacity: 0.5; text-transform: uppercase;">Guru Wali
                            </p>
                            <p style="font-size: 14px; font-weight: 800; color: var(--db-text-dark);">{{ $teacherWali }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif



        <!-- Parent Section -->
        <div class="pa-form-section">
            <p class="pa-question">
                Pendekatan orangtua kepada anak: Saat ini adakah pendekatan tertentu yang sedang diusahakan oleh
                orangtua, untuk mewujudkan suatu karakter yang diharapkan?
            </p>
            <div class="pa-textarea-wrapper">
                <textarea class="pa-textarea" id="pendekatan" {{ $isTeacher ? 'readonly' : '' }}
                    placeholder="Tulis jawaban ayah / bunda disini...">{{ $journal->pendekatan ?? '' }}</textarea>
                @if(!$isTeacher)
                    <button class="pa-save-btn" onclick="saveField('pendekatan', event)">Simpan</button>
                @endif
            </div>
        </div>

        <div class="pa-form-section">
            <p class="pa-question">
                Interaksi orangtua dan anak: Bagaimana interaksi ayah / bunda berjalan? Apakah sering? Apakah jarang?
                Dan bagaimana bentuk interaksinya?
            </p>
            <div class="pa-textarea-wrapper">
                <textarea class="pa-textarea" id="interaksi" {{ $isTeacher ? 'readonly' : '' }}
                    placeholder="Tulis jawaban ayah / bunda disini...">{{ $journal->interaksi ?? '' }}</textarea>
                @if(!$isTeacher)
                    <button class="pa-save-btn" onclick="saveField('interaksi', event)">Simpan</button>
                @endif
            </div>
        </div>

        <!-- Respon Guru -->
        <h2 class="pa-response-title">Respon Guru</h2>

        <div class="pa-form-section">
            <p class="pa-question">
                Saran dari <b>Guru Wali ({{ $teacherWali }})</b> antara harapan orangtua dengan apa yang terjadi di
                sekolah dan strategi yang bisa
                digunakan dari pihak rumah maupun pihak sekolah!
            </p>
            <div class="pa-textarea-wrapper">
                @php
                    $canEditTeacherReply = $isTeacher && !$isLifebookTeacher;
                @endphp

                @if($canEditTeacherReply)
                    <textarea class="pa-textarea" id="teacher_reply"
                        placeholder="Berikan saran atau feedback untuk orang tua murid...">{{ $journal->teacher_reply ?? '' }}</textarea>
                    <button class="pa-save-btn" onclick="saveField('teacher_reply', event)">Simpan</button>
                @else
                    <div
                        style="padding: 20px; font-size: 14px; font-weight: 600; color: var(--db-text-dark); opacity: 0.7;">
                        @if($journal && $journal->teacher_reply)
                            <div style="margin-bottom: 5px; color: var(--db-purple); font-weight: 800;">Guru:
                                {{ $journal->teacher_name }}
                            </div>
                            {{ $journal->teacher_reply }}
                            <div style="font-size: 10px; margin-top: 10px; opacity: 0.5;">Dibalas pada:
                                {{ $journal->teacher_replied_at->format('d M Y H:i') }}
                            </div>
                        @else
                            <i>Menunggu saran dari Guru Wali...</i>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="pa-form-section">
            <p class="pa-question">
                Konfirmasi dari <b>Guru Lifebook ({{ $activeLifebookTeacher->name ?? '-' }})</b> journaling my lifebook!
            </p>
            <div class="pa-textarea-wrapper">
                @if($isLifebookTeacher)
                    <textarea class="pa-textarea" id="lifebook_teacher_reply"
                        placeholder="Konfirmasi jurnal ini sebagai Guru Lifebook...">{{ $journal->lifebook_teacher_reply ?? '' }}</textarea>
                    <button class="pa-save-btn" onclick="saveField('lifebook_teacher_reply', event)">Simpan</button>
                @else
                    <div
                        style="padding: 20px; font-size: 14px; font-weight: 600; color: var(--db-text-dark); opacity: 0.7;">
                        @if($journal && $journal->lifebook_teacher_reply)
                            <div style="margin-bottom: 5px; color: var(--db-purple); font-weight: 800;">Guru Lifebook:
                                {{ $journal->lifebook_teacher_name }}
                            </div>
                            {{ $journal->lifebook_teacher_reply }}
                            <div style="font-size: 10px; margin-top: 10px; opacity: 0.5;">Dibalas pada:
                                {{ $journal->lifebook_teacher_replied_at->format('d M Y H:i') }}
                            </div>
                        @else
                            <i>Menunggu konfirmasi Guru Lifebook...</i>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <nav class="db-bottom-nav">
        @if($isTeacher)
            <a href="{{ route('teacher.dashboard') }}" class="db-nav-item active">
                <div class="db-nav-icon"><i data-lucide="home"></i></div>
                <span>Home</span>
            </a>
            <a href="{{ route('teacher.profile') }}" class="db-nav-item">
                <div class="db-nav-icon"><i data-lucide="user"></i></div>
                <span>Profile</span>
            </a>
        @else
            <a href="{{ route('dashboard') }}" class="db-nav-item active">
                <div class="db-nav-icon"><i data-lucide="home"></i></div>
                <span>Home</span>
            </a>
            <a href="{{ route('parents.leaderboard') }}" class="db-nav-item">
                <div class="db-nav-icon"><i data-lucide="trophy"></i></div>
                <span>Scores</span>
            </a>
            <a href="{{ route('profile') }}" class="db-nav-item">
                <div class="db-nav-icon"><i data-lucide="user"></i></div>
                <span>Profile</span>
            </a>
        @endif
    </nav>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        lucide.createIcons();

        $('#childSelector').on('change', function () {
            const childId = $(this).val();
            window.location.href = "{{ route('children-tracker.parent-aspect') }}?time={{ $selectedTime }}&child_id=" + childId;
        });

        // AJAX Save Fields (Batch)
        function saveField(field, event) {
            const childId = $('#childSelector').val();
            const monthYear = "{{ $selectedTime }}";
            const btn = event.currentTarget;
            const originalText = $(btn).text();

            if (!childId) {
                alert('Silakan pilih murid terlebih dahulu.');
                return;
            }

            // Collect all editable fields
            const data = {};
            $('.pa-textarea').each(function () {
                if (!$(this).prop('readonly')) {
                    const id = $(this).attr('id');
                    const val = $(this).val();
                    data[id] = val;
                }
            });

            $(btn).prop('disabled', true).text('...');

            $.ajax({
                url: "{{ route('children-tracker.save-journal') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    student_id: childId,
                    month_year: monthYear,
                    data: data
                },
                success: function (response) {
                    if (response.success) {
                        $(btn).text('Saved!').css('background', '#10B981');
                        showSuccessPopup();
                        setTimeout(() => {
                            $(btn).text(originalText).prop('disabled', false).css('background', '');
                        }, 2000);
                    }
                },
                error: function (xhr) {
                    const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Gagal menyimpan data.';
                    alert(msg);
                    $(btn).text(originalText).prop('disabled', false);
                }
            });
        }
        function showSuccessPopup() {
            $('#successPopup').fadeIn(300).css('display', 'flex');
        }

        function closePopup() {
            $('#successPopup').fadeOut(300);
        }
    </script>
</body>

</html>