@extends('layouts.app')

@section('title', 'Aspek Internal/Eksternal - Lifebook Parents')

@section('content')
    <!-- Background Elements -->
    <img src="{{ asset('/file/bee.png') }}" class="db-bg-pattern db-bee" alt="">
    <img src="{{ asset('/file/flower.png') }}" class="db-bg-pattern db-flower" alt="">

    <div class="db-container pa-container">
        <!-- Reflection Modal -->
        <div id="reflectionModal" class="re-modal-overlay" style="display: none;">
            <div class="re-modal-content">
                <h2 class="re-title">Refleksi Parents</h2>
                
                @php
                    $reflections = [
                        ['id' => 'keterbukaan', 'label' => 'Keterbukaan anak dengan orangtua'],
                        ['id' => 'rutinitas', 'label' => 'Pelaksanaan rutinitas dan mengikuti aturan rumah'],
                        ['id' => 'tauladan', 'label' => 'Menjadi orangtua yang memberikan tauladan, daripada hanya menasehati'],
                        ['id' => 'emosi', 'label' => 'Mendengarkan emosi negatif anak, memahami dan memberikan solusi'],
                        ['id' => 'journaling', 'label' => 'Melakukan journaling orangtua sebagai bentuk perhatian terhadap perkembangan anak'],
                        ['id' => 'bersahabat', 'label' => 'Menjadi orangtua yang menyenangkan dan bersahabat dengan anak'],
                    ];
                    $emojis = ['😁', '😊', '😐', '😟', '😡']; // From Good to Bad
                @endphp

                @foreach($reflections as $ref)
                    <div class="re-item" data-ref="{{ $ref['id'] }}">
                        <span class="re-item-label">{{ $ref['label'] }}</span>
                        <div class="re-rating-group">
                            @foreach($emojis as $index => $emoji)
                                <button class="re-emoji-btn" onclick="setRating('{{ $ref['id'] }}', {{ 5 - $index }}, this)">{{ $emoji }}</button>
                            @endforeach
                        </div>
                        <div class="re-progress-bar"></div>
                        <input type="hidden" id="ref_{{ $ref['id'] }}" value="{{ $journal->{'refleksi_' . $ref['id']} ?? '' }}">
                    </div>
                @endforeach

                <div class="re-actions">
                    <button class="re-skip-btn" onclick="skipReflection()">Lewati</button>
                    <button class="re-submit-btn" onclick="submitReflection()">Simpan Refleksi</button>
                </div>
            </div>
        </div>

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
                <h1 style="font-size: 26px;">Aspek Internal<br>& Eksternal</h1>
            </div>
            <div style="display: flex; gap: 10px; align-items: center;">
                <a href="{{ route('children-tracker.index') }}" class="db-avatar-section"
                    style="width: 45px; height: 45px; text-decoration: none;">
                    <i data-lucide="chevron-left" style="font-size: 20px;"></i>
                </a>
            </div>
        </div>

        <!-- Month Selector -->
        <div class="pa-month-selector" style="margin-top: 5px;">
            @php
                $timeDate = Carbon\Carbon::createFromFormat('F Y', $selectedTime);
            @endphp
            <a href="{{ route('children-tracker.internal-external-aspect', ['month' => $timeDate->copy()->subMonth()->translatedFormat('F Y'), 'child_id' => $selectedChildId]) }}"
                class="pa-month-btn">
                <i data-lucide="chevron-left"></i>
            </a>
            <span>{{ $selectedTime }}</span>
            <a href="{{ route('children-tracker.internal-external-aspect', ['month' => $timeDate->copy()->addMonth()->translatedFormat('F Y'), 'child_id' => $selectedChildId]) }}"
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

        <!-- Aspect Internal Section -->
        <h2 class="pa-response-title" style="margin-top: 0;">Aspek Internal</h2>
        <div class="pa-form-section">
            <p class="pa-question">
                Dari aspek kesehatan, pikiran, emosi, spiritual dan keterampilan, aspek mana yang saat ini sedang menonjol / sedang anak-anak / ayah bunda fokuskan? Bagaimana perkembangannya?
            </p>
            <div class="pa-textarea-wrapper">
                <textarea class="pa-textarea" id="aspek_internal" {{ $isTeacher ? 'readonly' : '' }}
                    placeholder="Tulis jawaban ayah / bunda disini...">{{ $journal->aspek_internal ?? '' }}</textarea>
                @if(!$isTeacher)
                    <button class="pa-save-btn" onclick="saveField('aspek_internal', event)">Simpan</button>
                @endif
            </div>
        </div>

        <div class="pa-form-section">
            <p class="pa-question">
                Tanggapan <b>Guru Wali ({{ $teacherWali }})</b>:
            </p>
            <div class="pa-textarea-wrapper">
                @php
                    $canEditInternalTeacherReply = $isTeacher && !$isLifebookTeacher;
                @endphp

                @if($canEditInternalTeacherReply)
                    <textarea class="pa-textarea" id="internal_teacher_reply"
                        placeholder="Berikan tanggapan untuk aspek internal murid...">{{ $journal->internal_teacher_reply ?? '' }}</textarea>
                    <button class="pa-save-btn" onclick="saveField('internal_teacher_reply', event)">Simpan</button>
                @else
                    <div
                        style="padding: 20px; font-size: 14px; font-weight: 600; color: var(--db-text-dark); opacity: 0.7;">
                        @if($journal && $journal->internal_teacher_reply)
                            <div style="margin-bottom: 5px; color: var(--db-purple); font-weight: 800;">Guru Wali:
                                {{ $journal->teacher_name }}</div>
                            {{ $journal->internal_teacher_reply }}
                            @if($journal->internal_teacher_replied_at)
                                <div style="font-size: 10px; margin-top: 10px; opacity: 0.5;">Dibalas pada:
                                    {{ $journal->internal_teacher_replied_at->format('d M Y H:i') }}</div>
                            @endif
                        @else
                            <i>Menunggu tanggapan Guru Wali...</i>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Aspect External Section -->
        <h2 class="pa-response-title">Aspek Eksternal</h2>
        <div class="pa-form-section">
            <p class="pa-question">
                Dari aspek keluarga, lingkungan sosial, keuangan dan kualitas hidup, aspek mana yang saat ini sedang menonjol / sedang anak-anak fokuskan / ayah bunda fokuskan? Bagaimana perkembangannya?
            </p>
            <div class="pa-textarea-wrapper">
                <textarea class="pa-textarea" id="aspek_external" {{ $isTeacher ? 'readonly' : '' }}
                    placeholder="Tulis jawaban ayah / bunda disini...">{{ $journal->aspek_external ?? '' }}</textarea>
                @if(!$isTeacher)
                    <button class="pa-save-btn" onclick="saveField('aspek_external', event)">Simpan</button>
                @endif
            </div>
        </div>

        <div class="pa-form-section">
            <p class="pa-question">
                Tanggapan <b>Guru Wali ({{ $teacherWali }})</b>:
            </p>
            <div class="pa-textarea-wrapper">
                @php
                    $canEditExternalTeacherReply = $isTeacher && !$isLifebookTeacher;
                @endphp

                @if($canEditExternalTeacherReply)
                    <textarea class="pa-textarea" id="external_teacher_reply"
                        placeholder="Berikan tanggapan untuk aspek eksternal murid...">{{ $journal->external_teacher_reply ?? '' }}</textarea>
                    <button class="pa-save-btn" onclick="saveField('external_teacher_reply', event)">Simpan</button>
                @else
                    <div
                        style="padding: 20px; font-size: 14px; font-weight: 600; color: var(--db-text-dark); opacity: 0.7;">
                        @if($journal && $journal->external_teacher_reply)
                            <div style="margin-bottom: 5px; color: var(--db-purple); font-weight: 800;">Guru Wali:
                                {{ $journal->teacher_name }}</div>
                            {{ $journal->external_teacher_reply }}
                            @if($journal->external_teacher_replied_at)
                                <div style="font-size: 10px; margin-top: 10px; opacity: 0.5;">Dibalas pada:
                                    {{ $journal->external_teacher_replied_at->format('d M Y H:i') }}</div>
                            @endif
                        @else
                            <i>Menunggu tanggapan Guru Wali...</i>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Strategy Discovery Section -->
        <h2 class="pa-response-title">Strategi / Pendekatan Baru</h2>
        <div class="pa-form-section">
            <p class="pa-question">
                Ditulis oleh guru-guru yang membimbing journaling my lifebooknya! (<b>{{ $activeLifebookTeacher->name ?? 'Guru Lifebook' }}</b>)
            </p>
            <div class="pa-textarea-wrapper">
                @if($isLifebookTeacher)
                    <textarea class="pa-textarea" id="strategi_baru"
                        placeholder="Tuliskan penemuan strategi atau pendekatan baru...">{{ $journal->strategi_baru ?? '' }}</textarea>
                    <button class="pa-save-btn" onclick="saveField('strategi_baru', event)">Simpan</button>
                @else
                    <div
                        style="padding: 20px; font-size: 14px; font-weight: 600; color: var(--db-text-dark); opacity: 0.7;">
                        @if($journal && $journal->strategi_baru)
                            <div style="margin-bottom: 5px; color: var(--db-purple); font-weight: 800;">Guru Lifebook:
                                {{ $journal->lifebook_teacher_name }}</div>
                            {{ $journal->strategi_baru }}
                            @if($journal->lifebook_strategy_at)
                                <div style="font-size: 10px; margin-top: 10px; opacity: 0.5;">Ditulis pada:
                                    {{ $journal->lifebook_strategy_at->format('d M Y H:i') }}</div>
                            @endif
                        @else
                            <i>Menunggu penemuan strategi dari Guru Lifebook...</i>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="pa-form-section">
            <p class="pa-question">
                Tanggapan Orang Tua:
            </p>
            <div class="pa-textarea-wrapper">
                <textarea class="pa-textarea" id="strategi_parent_reply" {{ $isTeacher ? 'readonly' : '' }}
                    placeholder="Tulis tanggapan ayah / bunda disini...">{{ $journal->strategi_parent_reply ?? '' }}</textarea>
                @if(!$isTeacher)
                    <button class="pa-save-btn" onclick="saveField('strategi_parent_reply', event)">Simpan</button>
                @endif
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        // Handle child selector change
        $('#childSelector').on('change', function () {
            const childId = $(this).val();
            window.location.href = "{{ route('children-tracker.internal-external-aspect') }}?month={{ $selectedTime }}&child_id=" + childId;
        });

        // Set emoji rating
        function setRating(refId, value, btn) {
            $('[data-ref="' + refId + '"] .re-emoji-btn').removeClass('active');
            $(btn).addClass('active');
            $('#ref_' + refId).val(value);
        }

        // Reflection Modal Logic
        const reflectionKey = "dismissed_reflection_{{ Auth::id() }}_{{ $selectedChildId }}_{{ str_replace(' ', '_', $selectedTime) }}";

        function checkAndShowReflection() {
            @if(!$isTeacher)
                if (localStorage.getItem(reflectionKey)) return;

                const internal = $('#aspek_internal').val();
                const external = $('#aspek_external').val();
                const reflectionFilled = {{ ($journal && $journal->refleksi_filled_at) ? 'true' : 'false' }};

                if (internal && external && !reflectionFilled) {
                    $('#reflectionModal').fadeIn(400);
                    // Pre-select current ratings if any
                    @foreach($reflections as $ref)
                        const val_{{ $ref['id'] }} = $('#ref_{{ $ref['id'] }}').val();
                        if (val_{{ $ref['id'] }}) {
                            const btnIndex = 5 - val_{{ $ref['id'] }};
                            $('[data-ref="{{ $ref['id'] }}"] .re-emoji-btn').eq(btnIndex).addClass('active');
                        }
                    @endforeach
                }
            @endif
        }

        $(document).ready(function() {
            setTimeout(checkAndShowReflection, 800);
        });

        function skipReflection() {
            localStorage.setItem(reflectionKey, 'true');
            $('#reflectionModal').fadeOut(400);
        }

        function submitReflection() {
            const childId = $('#childSelector').val();
            const monthYear = "{{ $selectedTime }}";
            const ratings = {};
            let allSet = true;

            @foreach($reflections as $ref)
                ratings['refleksi_{{ $ref['id'] }}'] = $('#ref_{{ $ref['id'] }}').val();
                if (!ratings['refleksi_{{ $ref['id'] }}']) allSet = false;
            @endforeach

            if (!allSet) {
                alert('Mohon lengkapi seluruh penilaian refleksi.');
                return;
            }

            $('.re-submit-btn').prop('disabled', true).text('Menyimpan...');

            $.ajax({
                url: "{{ route('children-tracker.save-reflection') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    student_id: childId,
                    month_year: monthYear,
                    ratings: ratings
                },
                success: function(response) {
                    if (response.success) {
                        $('#reflectionModal').fadeOut(400);
                        alert('Refleksi berhasil disimpan! Terima kasih Ayah/Bunda.');
                        localStorage.setItem(reflectionKey, 'true'); 
                    }
                },
                error: function() {
                    alert('Gagal menyimpan refleksi.');
                    $('.re-submit-btn').prop('disabled', false).text('Simpan Refleksi');
                }
            });
        }

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
                            checkAndShowReflection(); // Re-check if reflection should be shown
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
