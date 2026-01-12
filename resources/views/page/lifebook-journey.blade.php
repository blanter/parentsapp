@extends('layouts.app')

@section('title', 'Parents Lifebook Journey - Lifebook Parents')

@section('styles')
    <!-- Owl Carousel Assets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <style>
        body {
            background-color: #FBFBFB;
            font-family: 'Poppins', sans-serif;
            color: var(--db-text-dark);
            margin: 0;
            padding: 0;
        }

        .lj-container {
            padding: 30px 20px;
            max-width: 500px;
            margin: 0 auto;
        }

        .lj-header-title {
            margin-bottom: 40px;
        }

        .lj-title {
            font-size: 28px;
            font-weight: 900;
            line-height: 1.2;
            color: var(--db-text-dark);
        }

        .lj-title span {
            color: var(--db-purple);
        }

        /* Owl Carousel Customization */
        .owl-nav {
            display: none !important;
        }
    </style>
@endsection

@section('content')
    <div class="lj-container">
        <!-- Success Alert Popup -->
        <div id="successPopup" class="pa-popup-overlay" style="display: none;">
            <div class="pa-popup-card">
                <div class="pa-popup-icon" style="background: rgba(54, 179, 126, 0.1); color: var(--db-secondary);">
                    <i data-lucide="award"></i>
                </div>
                <h3 class="pa-popup-title">Berhasil Disimpan!</h3>
                <p class="pa-popup-message">
                    Selamat Ayah / Bunda! Anda mendapatkan <b id="earnedPointsText">0 Poin</b> untuk aktivitas Lifebook
                    Journey.
                </p>
                <button class="pa-popup-btn" onclick="closePopup()">Siap, Terima Kasih</button>
            </div>
        </div>

        <!-- Header Section -->
        <div class="db-header" style="margin-bottom: 20px;">
            <div class="db-brand-section">
                <a href="{{ route('dashboard') }}"
                    style="text-decoration: none; color: inherit; display: flex; align-items: center; gap: 10px;">
                    <i data-lucide="chevron-left" style="width: 30px; height: 30px;"></i>
                    <h1 style="font-size: 24px; margin: 0;">Back</h1>
                </a>
            </div>
            <a href="{{ route('profile') }}" class="db-avatar-section" style="width: 45px; height: 45px;">
                @if(Auth::user()->avatar)
                    <img src="{{ asset('avatars/' . Auth::user()->avatar) }}" alt="Avatar"
                        style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <i data-lucide="user"></i>
                @endif
            </a>
        </div>

        <div class="lj-header-title">
            <div class="lj-title">
                Parents <br><span>Lifebook Journey</span>
            </div>
        </div>

        <!-- Tabs -->
        <div class="vm-tabs">
            <button class="vm-tab-btn active" onclick="switchTab('journey')">
                <i data-lucide="milestone"></i>
                Journey Data
            </button>
            <button class="vm-tab-btn" onclick="switchTab('habits')">
                <i data-lucide="calendar-check"></i>
                Habit Tracker
            </button>
        </div>

        <div id="journeyView" class="tab-content active">
            <!-- Carousel Section -->
            <div class="lj-carousel-container">
                <button class="lj-nav-btn prev" id="carouselPrev">
                    <i data-lucide="chevron-left"></i>
                </button>
                <button class="lj-nav-btn next" id="carouselNext">
                    <i data-lucide="chevron-right"></i>
                </button>

                <div class="owl-carousel owl-theme" id="categoryCarousel">
                    @foreach($categories as $cat)
                        @php
                            $j = $journeys[$cat['id']] ?? null;
                            $filledCount = 0;
                            if ($j) {
                                if (!empty($j->premise))
                                    $filledCount++;
                                if (!empty($j->vision))
                                    $filledCount++;
                                if (!empty($j->purpose))
                                    $filledCount++;
                                if (!empty($j->strategy))
                                    $filledCount++;
                            }

                            $statusClass = 'empty';
                            if ($filledCount === 4)
                                $statusClass = 'complete';
                            elseif ($filledCount > 0)
                                $statusClass = 'incomplete';
                        @endphp
                        <div class="lj-category-item" data-id="{{ $cat['id'] }}" data-name="{{ $cat['name'] }}">
                            <div class="lj-category-icon-wrapper">
                                <div class="lj-status-dot {{ $statusClass }}" id="dot-{{ $cat['id'] }}"></div>
                                <div class="lj-category-icon">
                                    <i data-lucide="{{ $cat['icon'] }}"></i>
                                </div>
                            </div>
                            <div class="lj-category-name">{{ $cat['name'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Content Section -->
            <div class="lj-content-section" id="journeyContent" style="margin-bottom:90px">
                @php
                    $activeCat = $categories[0]['id'];
                    $activeJourney = $journeys[$activeCat] ?? null;
                @endphp

                <div class="lj-card">
                    <div class="lj-card-info">
                        <div class="lj-card-title">Premise</div>
                        <div class="lj-card-text" id="text-premise">{{ $activeJourney->premise ?? 'Belum ada data.' }}</div>
                    </div>
                    <button class="lj-btn-edit" onclick="openEditModal('premise')">Edit</button>
                </div>

                <div class="lj-card">
                    <div class="lj-card-info">
                        <div class="lj-card-title">Vision</div>
                        <div class="lj-card-text" id="text-vision">{{ $activeJourney->vision ?? 'Belum ada data.' }}</div>
                    </div>
                    <button class="lj-btn-edit" onclick="openEditModal('vision')">Edit</button>
                </div>

                <div class="lj-card">
                    <div class="lj-card-info">
                        <div class="lj-card-title">Purpose</div>
                        <div class="lj-card-text" id="text-purpose">{{ $activeJourney->purpose ?? 'Belum ada data.' }}</div>
                    </div>
                    <button class="lj-btn-edit" onclick="openEditModal('purpose')">Edit</button>
                </div>

                <div class="lj-card">
                    <div class="lj-card-info">
                        <div class="lj-card-title">Strategy</div>
                        <div class="lj-card-text" id="text-strategy">{{ $activeJourney->strategy ?? 'Belum ada data.' }}
                        </div>
                    </div>
                    <button class="lj-btn-edit" onclick="openEditModal('strategy')">Edit</button>
                </div>

                <div class="lj-footer-nav">
                    <button class="lj-footer-btn" id="footerPrev">
                        <i data-lucide="chevron-left"></i>
                    </button>
                    <button class="lj-footer-btn" id="footerNext">
                        <i data-lucide="chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <div id="habitsView" class="tab-content" style="display: none;">
            <div class="lj-content-section" style="margin-bottom:90px">
                <!-- Habit Tracker Section -->
                <div class="ht-container">
                    <div class="ht-header">
                        <div class="ht-title-group">
                            <h2>Habit Tracker</h2>
                            <p id="ht-current-month-text">Monthly Progress</p>
                        </div>
                        <div class="ht-controls">
                            <select class="ht-select" id="ht-month-select">
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $m == date('n') ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endfor
                            </select>
                            <select class="ht-select" id="ht-year-select">
                                @for($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                                    <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <!-- Total Monthly Progress Card -->
                    <div class="ht-total-progress-card">
                        <div class="ht-donut-container" id="ht-monthly-donut">
                            <div class="ht-donut-val" id="ht-monthly-percentage">0%</div>
                        </div>
                        <div>
                            <div style="font-size: 14px; font-weight: 800; color: var(--db-text-dark);">Total Monthly
                                Progress</div>
                            <div style="font-size: 11px; font-weight: 600; color: #9CA3AF;" id="ht-monthly-stats">0 of 0
                                habits completed</div>
                        </div>
                    </div>

                    <div class="ht-section-header"
                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <div style="font-size: 14px; font-weight: 800; color: var(--db-text-dark);">Daily Habits</div>
                        <button class="ht-add-btn" onclick="openAddHabitModal()">
                            <i data-lucide="plus" style="width: 14px;"></i> Tambah Habit
                        </button>
                    </div>

                    <div class="ht-table-wrapper">
                        <table class="ht-table" id="ht-daily-table">
                            <thead>
                                <tr id="ht-table-days-row">
                                    <!-- Days will be injected here -->
                                </tr>
                            </thead>
                            <tbody id="ht-daily-body">
                                <!-- Habits will be injected here -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Weekly Habits -->
                    <div style="font-size: 14px; font-weight: 800; color: var(--db-text-dark); margin-top: 40px;">Weekly
                        Habits</div>
                    <div class="ht-weekly-section" id="ht-weekly-container">
                        <!-- Weeks 1-5 cards will be injected here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals for Habit Tracker -->
        <div class="lj-edit-modal" id="addHabitModal">
            <div class="lj-edit-card">
                <div class="lj-edit-header">
                    <div class="lj-edit-title">Tambah Habit Baru</div>
                    <button style="background: none; border: none; cursor: pointer;"
                        onclick="closeHTModal('addHabitModal')">
                        <i data-lucide="x" style="color: #999;"></i>
                    </button>
                </div>
                <div style="margin-bottom: 15px;">
                    <input type="text" id="habitTitleInput" class="lj-textarea" style="min-height: 50px;"
                        placeholder="Nama habit (contoh: Sholat Subuh)">
                    <input type="hidden" id="habitIdInput">
                </div>
                <button class="lj-save-btn" id="saveHabitBtn">Simpan Habit</button>
            </div>
        </div>

        <div class="lj-edit-modal" id="addWeeklyTaskModal">
            <div class="lj-edit-card">
                <div class="lj-edit-header">
                    <div class="lj-edit-title" id="weeklyModalTitle">Tambah Tugas Mingguan</div>
                    <button style="background: none; border: none; cursor: pointer;"
                        onclick="closeHTModal('addWeeklyTaskModal')">
                        <i data-lucide="x" style="color: #999;"></i>
                    </button>
                </div>
                <div style="margin-bottom: 15px;">
                    <input type="text" id="weeklyTaskTitleInput" class="lj-textarea" style="min-height: 50px;"
                        placeholder="Nama tugas (contoh: Deep clean my room)">
                    <input type="hidden" id="weeklyTaskId">
                </div>
                <input type="hidden" id="weeklyTaskIndex">
                <button class="lj-save-btn" id="saveWeeklyTaskBtn">Simpan Tugas</button>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="lj-edit-modal" id="editModal">
            <div class="lj-edit-card">
                <div class="lj-edit-header">
                    <div class="lj-edit-title" id="modalTitle">Edit Data</div>
                    <button style="background: none; border: none; cursor: pointer;" onclick="closeModal()">
                        <i data-lucide="x" style="color: #999;"></i>
                    </button>
                </div>
                <textarea class="lj-textarea" id="editContent" placeholder="Masukkan konten di sini..."></textarea>
                <button class="lj-save-btn" id="saveBtn">Simpan Perubahan</button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script>
        // Data from server
        const journeys = @json($journeys);
        const categories = @json($categories);
        let activeCategoryId = "{{ $activeCat }}";
        let currentField = '';

        $(document).ready(function () {
            // Setup Owl Carousel
            const owl = $("#categoryCarousel").owlCarousel({
                items: 5,
                margin: 0,
                center: true,
                loop: false,
                dots: false,
                responsive: {
                    0: { items: 3 },
                    600: { items: 5 }
                }
            });

            // Set first item as active
            $('.lj-category-item[data-id="' + activeCategoryId + '"]').addClass('active');

            // Handle carousel navigation
            $('#carouselPrev').click(() => owl.trigger('prev.owl.carousel'));
            $('#carouselNext').click(() => owl.trigger('next.owl.carousel'));

            // Handle category click
            $('.lj-category-item').click(function () {
                const id = $(this).data('id');
                const index = $(this).parent().index();

                $('.lj-category-item').removeClass('active');
                $(this).addClass('active');

                activeCategoryId = id;
                updateContent();

                // Center the clicked item
                owl.trigger('to.owl.carousel', [index, 300]);
            });

            // Footer navigation
            $('#footerPrev, #footerNext').click(function () {
                const isNext = $(this).attr('id') === 'footerNext';
                const currentIndex = categories.findIndex(c => c.id === activeCategoryId);
                let nextIndex = isNext ? currentIndex + 1 : currentIndex - 1;

                if (nextIndex < 0) nextIndex = categories.length - 1;
                if (nextIndex >= categories.length) nextIndex = 0;

                const nextId = categories[nextIndex].id;

                // Trigger click on the carousel item
                $('.lj-category-item[data-id="' + nextId + '"]').click();
            });

            // Save logic
            $('#saveBtn').click(function () {
                const originalText = $(this).text();
                $(this).text('Menyimpan...').prop('disabled', true);

                const content = $('#editContent').val();

                $.ajax({
                    url: "{{ route('lifebook-journey.update') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        category: activeCategoryId,
                        field: currentField,
                        content: content
                    },
                    success: function (response) {
                        if (response.success) {
                            // Update local data
                            if (!journeys[activeCategoryId]) journeys[activeCategoryId] = {};
                            journeys[activeCategoryId][currentField] = content;

                            // Update UI
                            $('#text-' + currentField).text(content || 'Belum ada data.');
                            updateStatusDot(activeCategoryId);

                            if (response.earned_points > 0) {
                                $('#earnedPointsText').text(response.earned_points + ' Poin');
                                $('#successPopup').fadeIn(300).css('display', 'flex');
                            }

                            closeModal();
                        }
                    },
                    error: function () {
                        alert('Gagal menyimpan data.');
                    },
                    complete: function () {
                        $('#saveBtn').text(originalText).prop('disabled', false);
                    }
                });
            });
        });

        function updateStatusDot(catId) {
            const data = journeys[catId] || {};
            const fields = ['premise', 'vision', 'purpose', 'strategy'];
            let filledCount = 0;

            fields.forEach(f => {
                if (data[f] && data[f].trim() !== '') filledCount++;
            });

            const dot = $('#dot-' + catId);
            dot.removeClass('complete incomplete empty');

            if (filledCount === 4) {
                dot.addClass('complete');
            } else if (filledCount > 0) {
                dot.addClass('incomplete');
            } else {
                dot.addClass('empty');
            }
        }

        function updateContent() {
            const data = journeys[activeCategoryId] || {};
            $('#text-premise').text(data.premise || 'Belum ada data.');
            $('#text-vision').text(data.vision || 'Belum ada data.');
            $('#text-purpose').text(data.purpose || 'Belum ada data.');
            $('#text-strategy').text(data.strategy || 'Belum ada data.');
        }

        function openEditModal(field) {
            currentField = field;
            const data = journeys[activeCategoryId] || {};
            const content = data[field] || '';

            const fieldName = field.charAt(0).toUpperCase() + field.slice(1);
            const catName = categories.find(c => c.id === activeCategoryId).name;

            $('#modalTitle').text(`Edit ${fieldName} - ${catName}`);
            $('#editContent').val(content);
            $('#editModal').css('display', 'flex').hide().fadeIn(200);
        }

        function closeModal() {
            $('#editModal').fadeOut(200);
        }

        // Close on overlay click
        $('#editModal').on('click', function (e) {
            if ($(e.target).hasClass('lj-edit-modal')) {
                closeModal();
            }
        });

        /* Habit Tracker Logic */
        let htData = { habits: [], logs: {}, weeklyTasks: {} };
        let currentHTMonth = {{ date('n') }};
        let currentHTYear = {{ date('Y') }};

        const loadHTData = () => {
            $.ajax({
                url: "{{ route('habit-tracker.data') }}",
                method: 'GET',
                data: { month: currentHTMonth, year: currentHTYear },
                success: function (response) {
                    if (response.success) {
                        htData = response.data;
                        renderHT();
                    }
                }
            });
        };

        const renderHT = () => {
            const { habits, logs, weeklyTasks, daysInMonth } = htData;

            // Render Table Header (Days)
            let daysHtml = '<th class="ht-habit-name-col">Daily Habits</th>';
            for (let d = 1; d <= daysInMonth; d++) {
                const date = new Date(currentHTYear, currentHTMonth - 1, d);
                const dayName = date.toLocaleDateString('en-US', { weekday: 'short' }).substring(0, 2);
                const weekNum = Math.ceil((d + new Date(currentHTYear, currentHTMonth - 1, 1).getDay()) / 7);
                daysHtml += `<th class="ht-day-cell ht-week-${weekNum}">${dayName}<br>${d}</th>`;
            }
            $('#ht-table-days-row').html(daysHtml);

            // Render Table Body
            let bodyHtml = '';
            habits.forEach(habit => {
                let habitRow = `<tr><td class="ht-habit-name-col">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <button class="ht-btn-delete" onclick="deleteHabit(${habit.id})"><i data-lucide="trash-2" style="width:14px;"></i></button>
                            <button class="ht-btn-delete" onclick="openEditHabitModal(${habit.id}, '${habit.title.replace(/'/g, "\\'")}')" style="color:var(--db-purple);"><i data-lucide="edit-3" style="width:14px;"></i></button>
                            ${habit.title}
                        </div>
                    </td>`;

                const habitLogs = logs[habit.id] || [];
                for (let d = 1; d <= daysInMonth; d++) {
                    const dateStr = `${currentHTYear}-${String(currentHTMonth).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                    const isDone = habitLogs.some(l => l.log_date === dateStr && l.is_completed);
                    const weekNum = Math.ceil((d + new Date(currentHTYear, currentHTMonth - 1, 1).getDay()) / 7);

                    habitRow += `<td class="ht-day-cell ht-week-${weekNum}">
                            <input type="checkbox" class="ht-checkbox" 
                                ${isDone ? 'checked' : ''} 
                                onchange="toggleHabit(${habit.id}, '${dateStr}')">
                        </td>`;
                }
                habitRow += '</tr>';
                bodyHtml += habitRow;
            });
            $('#ht-daily-body').html(bodyHtml);

            // Render Weekly Tasks
            let weeklyHtml = '';
            for (let i = 1; i <= 6; i++) {
                const tasks = weeklyTasks[i] || [];
                let tasksList = '';
                tasks.forEach(task => {
                    tasksList += `<li class="ht-weekly-item">
                            <input type="checkbox" class="ht-checkbox" ${task.is_completed ? 'checked' : ''} onchange="toggleWeeklyTask(${task.id})">
                            <span class="ht-weekly-text ${task.is_completed ? 'completed' : ''}">${task.title}</span>
                            <button class="ht-btn-delete" onclick="openEditWeeklyTaskModal(${task.id}, '${task.title.replace(/'/g, "\\'")}', ${i})" style="color:var(--db-purple);"><i data-lucide="edit-3" style="width:14px;"></i></button>
                            <button class="ht-btn-delete" onclick="deleteWeeklyTask(${task.id})"><i data-lucide="trash-2" style="width:14px;"></i></button>
                        </li>`;
                });

                const label = i === 6 ? 'MONTHLY' : `WEEK ${i}`;

                weeklyHtml += `
                        <div class="ht-weekly-card">
                            <div class="ht-weekly-header">
                                <div class="ht-weekly-title">${label}</div>
                                <button class="ht-add-btn" onclick="openAddWeeklyTaskModal(${i})"><i data-lucide="plus" style="width:12px;"></i></button>
                            </div>
                            <ul class="ht-weekly-list">${tasksList || '<li style="font-size:11px; opacity:0.5;">No tasks</li>'}</ul>
                        </div>
                    `;
            }
            $('#ht-weekly-container').html(weeklyHtml);

            calculateProgress();
            lucide.createIcons();
        };

        const calculateProgress = () => {
            const { habits, logs, daysInMonth } = htData;
            let totalPossible = habits.length * daysInMonth;
            let totalDone = 0;

            Object.values(logs).forEach(habitLogs => {
                habitLogs.forEach(log => {
                    if (log.is_completed) totalDone++;
                });
            });

            const percentage = totalPossible > 0 ? Math.round((totalDone / totalPossible) * 100) : 0;

            $('#ht-monthly-percentage').text(percentage + '%');
            $('#ht-monthly-donut').css('background', `conic-gradient(var(--db-purple) ${percentage}%, #E5E7EB ${percentage}%)`);
            $('#ht-monthly-stats').text(`${totalDone} checks this month`);
        };

        window.toggleHabit = (habitId, date) => {
            $.ajax({
                url: "{{ route('habit-tracker.toggle') }}",
                method: 'POST',
                data: { _token: "{{ csrf_token() }}", habit_id: habitId, date: date },
                success: function (response) {
                    if (response.earned_points > 0) {
                        $('#earnedPointsText').text(response.earned_points + ' Poin');
                        $('#successPopup').fadeIn(300).css('display', 'flex');
                    }
                    loadHTData();
                }
            });
        };

        window.deleteHabit = (id) => {
            if (!confirm('Hapus habit ini? Seluruh log akan ikut terhapus.')) return;
            $.ajax({
                url: `/habit-tracker/habit/${id}`,
                method: 'DELETE',
                data: { _token: "{{ csrf_token() }}" },
                success: function () { loadHTData(); }
            });
        };

        window.openAddHabitModal = () => {
            $('#habitIdInput').val('');
            $('#habitTitleInput').val('');
            $('#addHabitModal').css('display', 'flex').hide().fadeIn(200);
        };

        window.openEditHabitModal = (id, title) => {
            $('#habitIdInput').val(id);
            $('#habitTitleInput').val(title);
            $('#addHabitModal').css('display', 'flex').hide().fadeIn(200);
        };

        $('#saveHabitBtn').click(function () {
            const title = $('#habitTitleInput').val();
            const id = $('#habitIdInput').val();
            if (!title) return;
            $.ajax({
                url: "{{ route('habit-tracker.store') }}",
                method: 'POST',
                data: { _token: "{{ csrf_token() }}", title: title, id: id },
                success: function () {
                    $('#habitTitleInput').val('');
                    $('#habitIdInput').val('');
                    closeHTModal('addHabitModal');
                    loadHTData();
                }
            });
        });

        window.openAddWeeklyTaskModal = (index) => {
            $('#weeklyTaskId').val('');
            $('#weeklyTaskTitleInput').val('');
            $('#weeklyTaskIndex').val(index);
            $('#weeklyModalTitle').text(`Add task for Week ${index}`);
            $('#addWeeklyTaskModal').css('display', 'flex').hide().fadeIn(200);
        };

        window.openEditWeeklyTaskModal = (id, title, index) => {
            $('#weeklyTaskId').val(id);
            $('#weeklyTaskTitleInput').val(title);
            $('#weeklyTaskIndex').val(index);
            $('#weeklyModalTitle').text(`Edit task for Week ${index}`);
            $('#addWeeklyTaskModal').css('display', 'flex').hide().fadeIn(200);
        };

        $('#saveWeeklyTaskBtn').click(function () {
            const title = $('#weeklyTaskTitleInput').val();
            const index = $('#weeklyTaskIndex').val();
            const id = $('#weeklyTaskId').val();
            if (!title) return;
            $.ajax({
                url: "{{ route('habit-tracker.weekly.store') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    title: title,
                    week_index: index,
                    month: currentHTMonth,
                    year: currentHTYear,
                    id: id
                },
                success: function () {
                    $('#weeklyTaskTitleInput').val('');
                    $('#weeklyTaskId').val('');
                    closeHTModal('addWeeklyTaskModal');
                    loadHTData();
                }
            });
        });

        window.toggleWeeklyTask = (id) => {
            $.ajax({
                url: `/habit-tracker/weekly-task/${id}/toggle`,
                method: 'POST',
                data: { _token: "{{ csrf_token() }}" },
                success: function (response) {
                    if (response.earned_points > 0) {
                        $('#earnedPointsText').text(response.earned_points + ' Poin');
                        $('#successPopup').fadeIn(300).css('display', 'flex');
                    }
                    loadHTData();
                }
            });
        };

        window.deleteWeeklyTask = (id) => {
            if (!confirm('Hapus tugas ini?')) return;
            $.ajax({
                url: `/habit-tracker/weekly-task/${id}`,
                method: 'DELETE',
                data: { _token: "{{ csrf_token() }}" },
                success: function () { loadHTData(); }
            });
        };

        window.closeHTModal = (id) => { $(`#${id}`).fadeOut(200); };

        $('#ht-month-select, #ht-year-select').change(function () {
            currentHTMonth = $('#ht-month-select').val();
            currentHTYear = $('#ht-year-select').val();
            loadHTData();
        });

        loadHTData();

        function switchTab(tab) {
            $('.vm-tab-btn').removeClass('active');
            $('.tab-content').hide();

            if (tab === 'journey') {
                $('.vm-tab-btn:first-child').addClass('active');
                $('#journeyView').show();
            } else {
                $('.vm-tab-btn:last-child').addClass('active');
                $('#habitsView').show();
                // Scroll heatmaps to the end
                $('.vm-heatmap-scroll').each(function () {
                    $(this).scrollLeft($(this)[0].scrollWidth);
                });
            }
            lucide.createIcons();
        }

        function closePopup() {
            $('#successPopup').fadeOut(300);
        }
    </script>
@endsection