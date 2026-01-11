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

        <!-- Month Label -->
        <div style="text-align: center; margin-bottom: 20px;">
            <span
                style="font-weight: 800; font-size: 16px; color: var(--db-text-dark); background: #F3F4F6; padding: 8px 20px; border-radius: 99px;">
                {{ $selectedMonth }}
            </span>
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

        <!-- Parent Section -->
        <div class="pa-form-section">
            <p class="pa-question">
                Pendekatan orangtua kepada anak: Saat ini adakah pendekatan tertentu yang sedang diusahakan oleh
                orangtua, untuk mewujudkan suatu karakter yang diharapkan?
            </p>
            <div class="pa-textarea-wrapper">
                <textarea class="pa-textarea" id="pendekatan"
                    placeholder="Tulis jawaban ayah / bunda disini...">{{ $journal->pendekatan ?? '' }}</textarea>
                <button class="pa-save-btn" onclick="saveField('pendekatan')">Simpan</button>
            </div>
        </div>

        <div class="pa-form-section">
            <p class="pa-question">
                Interaksi orangtua dan anak: Bagaimana interaksi ayah / bunda berjalan? Apakah sering? Apakah jarang?
                Dan bagaimana bentuk interaksinya?
            </p>
            <div class="pa-textarea-wrapper">
                <textarea class="pa-textarea" id="interaksi"
                    placeholder="Tulis jawaban ayah / bunda disini...">{{ $journal->interaksi ?? '' }}</textarea>
                <button class="pa-save-btn" onclick="saveField('interaksi')">Simpan</button>
            </div>
        </div>

        <!-- Respon Guru -->
        <h2 class="pa-response-title">Respon Guru</h2>

        <div class="pa-form-section">
            <p class="pa-question">
                Saran dari guru antara harapan orangtua dengan apa yang terjadi di sekolah dan strategi yang bisa
                digunakan dari pihak rumah maupun pihak sekolah!
            </p>
            <div class="pa-textarea-wrapper" style="background: #F9FAFB; padding-bottom: 20px;">
                <div
                    style="padding: 20px; font-size: 14px; font-weight: 600; color: var(--db-text-dark); opacity: 0.7;">
                    @if($journal && $journal->teacher_reply)
                        <div style="margin-bottom: 5px; color: var(--db-purple); font-weight: 800;">Guru:
                            {{ $journal->teacher_name }}</div>
                        {{ $journal->teacher_reply }}
                        <div style="font-size: 10px; margin-top: 10px; opacity: 0.5;">Dibalas pada:
                            {{ $journal->teacher_replied_at->format('d M Y H:i') }}</div>
                    @else
                        <i>Menunggu respon guru wali...</i>
                    @endif
                </div>
            </div>
        </div>

        <div class="pa-form-section">
            <p class="pa-question">
                Konfirmasi dari guru journaling my lifebook!
            </p>
            <div class="pa-textarea-wrapper" style="background: #F9FAFB; padding-bottom: 20px;">
                <div
                    style="padding: 20px; font-size: 14px; font-weight: 600; color: var(--db-text-dark); opacity: 0.7;">
                    @if($journal && $journal->lifebook_teacher_reply)
                        <div style="margin-bottom: 5px; color: var(--db-purple); font-weight: 800;">Guru Lifebook:
                            {{ $journal->lifebook_teacher_name }}</div>
                        {{ $journal->lifebook_teacher_reply }}
                        <div style="font-size: 10px; margin-top: 10px; opacity: 0.5;">Dibalas pada:
                            {{ $journal->lifebook_teacher_replied_at->format('d M Y H:i') }}</div>
                    @else
                        <i>Menunggu konfirmasi guru lifebook...</i>
                    @endif
                </div>
            </div>
        </div>



    </div>

    <!-- Bottom Navigation -->
    <nav class="db-bottom-nav">
        <a href="{{ route('dashboard') }}" class="db-nav-item active">
            <div class="db-nav-icon">
                <i data-lucide="home"></i>
            </div>
            <span>Home</span>
        </a>
        <a href="{{ route('parents.leaderboard') }}" class="db-nav-item">
            <div class="db-nav-icon">
                <i data-lucide="trophy"></i>
            </div>
            <span>Scores</span>
        </a>
        <a href="{{ route('profile') }}" class="db-nav-item">
            <div class="db-nav-icon">
                <i data-lucide="user"></i>
            </div>
            <span>Profile</span>
        </a>
    </nav>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        lucide.createIcons();

        // Handle child selector change
        $('#childSelector').on('change', function () {
            const childId = $(this).val();
            window.location.href = "{{ route('children-tracker.parent-aspect') }}?month={{ $selectedMonth }}&child_id=" + childId;
        });

        // AJAX Save Field
        function saveField(field) {
            const value = $('#' + field).val();
            const childId = $('#childSelector').val();
            const monthYear = "{{ $selectedMonth }}";
            const btn = event.target;
            const originalText = $(btn).text();

            $(btn).prop('disabled', true).text('...');

            $.ajax({
                url: "{{ route('children-tracker.save-journal') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    student_id: childId,
                    month_year: monthYear,
                    field: field,
                    value: value
                },
                success: function (response) {
                    if (response.success) {
                        $(btn).text('Saved!').css('background', '#10B981');
                        setTimeout(() => {
                            $(btn).text(originalText).prop('disabled', false).css('background', '');
                        }, 2000);
                    }
                },
                error: function (xhr) {
                    alert('Gagal menyimpan data.');
                    $(btn).text(originalText).prop('disabled', false);
                }
            });
        }
    </script>
</body>

</html>