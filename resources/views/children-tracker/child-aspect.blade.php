@extends('layouts.app')

@section('title', 'Aspek Anak - Lifebook Parents')

@section('content')
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
                        <span id="popupPointsMsg">Data jurnal Anda telah aman disimpan ke sistem. Terima kasih Ayah / Bunda!</span>
                    @endif
                </p>
                <button class="pa-popup-btn" onclick="closePopup()">Siap, Terima Kasih</button>
            </div>
        </div>
        <div class="db-header">
            <div class="db-brand-section">
                <h1 style="font-size: 28px;">Aspek<br>Anak</h1>
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
                $parts = explode(' ', $selectedTime);
                $qNum = (int)($parts[1] ?? 1);
                $year = (int)($parts[2] ?? date('Y'));
                
                $prevQ = $qNum - 1;
                $prevYear = $year;
                if($prevQ < 1) { $prevQ = 4; $prevYear--; }
                
                $nextQ = $qNum + 1;
                $nextYear = $year;
                if($nextQ > 4) { $nextQ = 1; $nextYear++; }
                
                $prevTime = "Kuartal $prevQ $prevYear";
                $nextTime = "Kuartal $nextQ $nextYear";
            @endphp
            <a href="{{ route('children-tracker.child-aspect', ['time' => $prevTime, 'child_id' => $selectedChildId]) }}"
                class="pa-month-btn">
                <i data-lucide="chevron-left"></i>
            </a>
            <span>{{ $selectedTime }}</span>
            <a href="{{ route('children-tracker.child-aspect', ['time' => $nextTime, 'child_id' => $selectedChildId]) }}"
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
            <div style="background: white; border-radius: 20px; padding: 15px 20px; margin-bottom: 25px; border: 2px solid #F3F4F6;">
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 32px; height: 32px; background: rgba(108, 136, 224, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--db-purple);">
                            <i data-lucide="users" style="width: 18px; height: 18px;"></i>
                        </div>
                        <div>
                            <p style="font-size: 10px; font-weight: 700; opacity: 0.5; text-transform: uppercase;">Orang Tua</p>
                            <p style="font-size: 14px; font-weight: 800; color: var(--db-text-dark);">{{ $parentName }}</p>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 32px; height: 32px; background: rgba(54, 179, 126, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--db-secondary);">
                            <i data-lucide="user-check" style="width: 18px; height: 18px;"></i>
                        </div>
                        <div>
                            <p style="font-size: 10px; font-weight: 700; opacity: 0.5; text-transform: uppercase;">Guru Wali</p>
                            <p style="font-size: 14px; font-weight: 800; color: var(--db-text-dark);">{{ $teacherWali }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form Section -->
        <div class="pa-form-section">
            <p class="pa-question">
                Rutinitas: Bagaimana rutinitas pagi dan sore / malamnya berjalan? Baik / cukup / kurang baik / tidak baik? Jelaskan!
            </p>
            <div class="pa-textarea-wrapper">
                <textarea class="pa-textarea" id="rutinitas" {{ $isTeacher ? 'readonly' : '' }}
                    placeholder="Tulis jawaban ayah / bunda disini...">{{ $journal->rutinitas ?? '' }}</textarea>
                @if(!$isTeacher)
                    <button class="pa-save-btn" onclick="saveField('rutinitas', event)">Simpan</button>
                @endif
            </div>
        </div>

        <div class="pa-form-section">
            <p class="pa-question">
                Hubungan keluarga: Bagaimana hubungan dengan keluarga di rumah? Berbaur / interaksi, menyendiri? Jelaskan!
            </p>
            <div class="pa-textarea-wrapper">
                <textarea class="pa-textarea" id="hubungan_keluarga" {{ $isTeacher ? 'readonly' : '' }}
                    placeholder="Tulis jawaban ayah / bunda disini...">{{ $journal->hubungan_keluarga ?? '' }}</textarea>
                @if(!$isTeacher)
                    <button class="pa-save-btn" onclick="saveField('hubungan_keluarga', event)">Simpan</button>
                @endif
            </div>
        </div>

        <div class="pa-form-section">
            <p class="pa-question">
                Hubungan dengan teman: Bagaimana hubungan dengan teman di sekitar rumah / sekolah? Baik / bermasalah? Ada teman dekat? Jelaskan!
            </p>
            <div class="pa-textarea-wrapper">
                <textarea class="pa-textarea" id="hubungan_teman" {{ $isTeacher ? 'readonly' : '' }}
                    placeholder="Tulis jawaban ayah / bunda disini...">{{ $journal->hubungan_teman ?? '' }}</textarea>
                @if(!$isTeacher)
                    <button class="pa-save-btn" onclick="saveField('hubungan_teman', event)">Simpan</button>
                @endif
            </div>
        </div>

        <div class="pa-form-section">
            <p class="pa-question">
                Aspek sosial: Bagaimana perkembangan aspek sosialnya? Seperti kemampuan komunikasi dengan orang lain, sikap saat di tempat umum dan sebagainya? Jelaskan!
            </p>
            <div class="pa-textarea-wrapper">
                <textarea class="pa-textarea" id="aspek_sosial" {{ $isTeacher ? 'readonly' : '' }}
                    placeholder="Tulis jawaban ayah / bunda disini...">{{ $journal->aspek_sosial ?? '' }}</textarea>
                @if(!$isTeacher)
                    <button class="pa-save-btn" onclick="saveField('aspek_sosial', event)">Simpan</button>
                @endif
            </div>
        </div>

        <!-- Respon Guru -->
        <h2 class="pa-response-title">Respon Guru</h2>

        <div class="pa-form-section">
            <p class="pa-question">
                Laporan dari <b>Guru Wali ({{ $teacherWali }})</b> tentang kemampuan disiplin, komitmen, ketertiban selama proses belajar di sekolah. <i>*jika ada masalah, guru sertakan solusi yang sudah diterapkan di sekolah.</i>
            </p>
            <div class="pa-textarea-wrapper">
                @php
                    $canEditTeacherReport = $isTeacher && !$isLifebookTeacher;
                @endphp

                @if($canEditTeacherReport)
                    <textarea class="pa-textarea" id="teacher_report"
                        placeholder="Berikan laporan atau feedback untuk orang tua murid...">{{ $journal->teacher_report ?? '' }}</textarea>
                    <button class="pa-save-btn" onclick="saveField('teacher_report', event)">Simpan</button>
                @else
                    <div
                        style="padding: 20px; font-size: 14px; font-weight: 600; color: var(--db-text-dark); opacity: 0.7;">
                        @if($journal && $journal->teacher_report)
                            <div style="margin-bottom: 5px; color: var(--db-purple); font-weight: 800;">Guru:
                                {{ $journal->teacher_name }}</div>
                            {{ $journal->teacher_report }}
                            @if($journal->teacher_report_at)
                                <div style="font-size: 10px; margin-top: 10px; opacity: 0.5;">Dilaporkan pada:
                                    {{ $journal->teacher_report_at->format('d M Y H:i') }}</div>
                            @endif
                        @else
                            <i>Menunggu laporan dari Guru Wali...</i>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="pa-form-section">
            <p class="pa-question">
                Konfirmasi dari <b>Guru Lifebook ({{ $activeLifebookTeacher->name ?? '-' }})</b> journaling my lifebooknya!
            </p>
            <div class="pa-textarea-wrapper">
                @if($isLifebookTeacher)
                    <textarea class="pa-textarea" id="lifebook_child_reply"
                        placeholder="Konfirmasi jurnal ini sebagai Guru Lifebook...">{{ $journal->lifebook_child_reply ?? '' }}</textarea>
                    <button class="pa-save-btn" onclick="saveField('lifebook_child_reply', event)">Simpan</button>
                @else
                    <div
                        style="padding: 20px; font-size: 14px; font-weight: 600; color: var(--db-text-dark); opacity: 0.7;">
                        @if($journal && $journal->lifebook_child_reply)
                            <div style="margin-bottom: 5px; color: var(--db-purple); font-weight: 800;">Guru Lifebook:
                                {{ $journal->lifebook_teacher_name }}</div>
                            {{ $journal->lifebook_child_reply }}
                            @if($journal->lifebook_child_replied_at)
                                <div style="font-size: 10px; margin-top: 10px; opacity: 0.5;">Dikonfirmasi pada:
                                    {{ $journal->lifebook_child_replied_at->format('d M Y H:i') }}</div>
                            @endif
                        @else
                            <i>Menunggu konfirmasi Guru Lifebook...</i>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('#childSelector').on('change', function () {
            const childId = $(this).val();
            window.location.href = "{{ route('children-tracker.child-aspect') }}?time={{ $selectedTime }}&child_id=" + childId;
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
            $('.pa-textarea').each(function() {
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
                        
                        if (response.earned_points > 0) {
                            $('#popupPointsMsg').html('Selamat Ayah / Bunda! Anda mendapatkan <b>' + response.earned_points + ' Poin</b> dari pengisian journaling parents.');
                        } else {
                            $('#popupPointsMsg').text('Data jurnal Anda telah aman disimpan ke sistem. Terima kasih Ayah / Bunda!');
                        }
                        
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
@endsection
