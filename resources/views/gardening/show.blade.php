<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('/file/lifebookicon.png')}}" rel='icon' type='image/x-icon' />
    <title>{{ $plant->plant_name }} - Lifebook Parents</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="{{asset('/file/style.css')}}?v=16" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="db-body">
    <!-- Background Elements -->
    <img src="{{ asset('/file/bee.png') }}" class="db-bg-pattern db-bee" alt="">
    <img src="{{ asset('/file/flower.png') }}" class="db-bg-pattern db-flower" alt="">

    <div class="gn-container">
        <!-- Header -->
        <div class="db-header" style="margin-bottom: 5px;">
            <div class="db-brand-section">
                <h1 style="font-size: 32px; font-weight: 800; line-height: 1.1;">Hi
                    {{ explode(' ', Auth::user()->name)[0] }}</h1>
                <p style="font-size: 14px; opacity: 0.6; font-weight: 600; margin-top: 5px;">Apa rencana kamu hari ini?
                </p>
            </div>
            <a href="{{ route('profile') }}" class="db-avatar-section"
                style="width: 60px; height: 60px; background: #E5E7EB; border: none;">
                @if(Auth::user()->avatar)
                    <img src="{{ asset('avatars/' . Auth::user()->avatar) }}" alt="Avatar"
                        style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <i data-lucide="user" style="width: 30px; height: 30px; opacity: 0.2;"></i>
                @endif
            </a>
        </div>

        <div style="margin: 30px 0 15px;">
            <h3 style="font-size: 20px; font-weight: 800; color: var(--db-text-dark);">My Plants</h3>
        </div>

        <!-- Plant Header Card -->
        <div class="gn-item" style="background: #F9FAFB; border: 2px solid #E5E7EB; cursor: default; padding: 15px;">
            <div class="gn-item-img" style="border-radius: 15px;">
                @if($plant->image)
                    <img src="{{ asset('gardening/' . $plant->image) }}" alt="{{ $plant->plant_name }}">
                @else
                    <i data-lucide="{{ $plant->icon ?: 'sprout' }}"></i>
                @endif
            </div>
            <div class="gn-item-info">
                <div class="gn-item-name" style="font-size: 18px;">{{ $plant->plant_name }}</div>
                <div style="font-size: 13px; opacity: 0.6; margin-top: 2px; font-weight: 700;">
                    Ditanam: {{ \Carbon\Carbon::parse($plant->planting_date)->format('d F Y') }}
                </div>
            </div>
        </div>

        <!-- Calendar Section -->
        <div style="margin: 25px 0 10px;">
            <h3 style="font-size: 18px; font-weight: 800; color: var(--db-text-dark);">Kalender</h3>
        </div>
        <div class="gn-cal-container" style="border-radius: 20px; padding: 15px;">
            <div class="gn-cal-header">
                <div class="gn-cal-title" id="month-year-label">Januari 2026</div>
                <div style="display: flex; gap: 10px;">
                    <button onclick="prevMonth()"
                        style="background: #F3F4F6; border: none; border-radius: 8px; padding: 5px; cursor: pointer;"><i
                            data-lucide="chevron-left" style="width: 18px;"></i></button>
                    <button onclick="nextMonth()"
                        style="background: #F3F4F6; border: none; border-radius: 8px; padding: 5px; cursor: pointer;"><i
                            data-lucide="chevron-right" style="width: 18px;"></i></button>
                </div>
            </div>
            <div class="gn-cal-grid" id="calendar-grid">
                <div class="gn-cal-day-label">M</div>
                <div class="gn-cal-day-label">S</div>
                <div class="gn-cal-day-label">S</div>
                <div class="gn-cal-day-label">R</div>
                <div class="gn-cal-day-label">K</div>
                <div class="gn-cal-day-label">J</div>
                <div class="gn-cal-day-label">S</div>
                <!-- Days will be inserted here -->
            </div>
        </div>

        @if(session('success'))
            <div
                style="background: #E8F5E9; color: #2E7D32; padding: 15px; border-radius: 20px; margin-bottom: 20px; font-weight: 700; font-size: 13px; border: 2px solid #C8E6C9;">
                {{ session('success') }}
            </div>
        @endif

        <button class="gn-add-btn" onclick="openProgressModal()">
            <i data-lucide="plus-circle"></i>
            <span>Tambah Progress</span>
        </button>

        <div
            style="text-align: center; margin-top: 40px; opacity: 0.3; font-size: 10px; font-weight: 700; color: var(--db-text-dark); margin-bottom: 100px;">
            Version {{ $appVersion }} • Parents App
        </div>
    </div>

    <!-- Progress Modal -->
    <div id="progressModal" class="gn-modal">
        <div class="gn-modal-content">
            <div class="gn-modal-header">
                <h2 class="gn-modal-title" id="modal-title">Laporan Progress</h2>
                <button class="gn-close-modal" onclick="closeProgressModal()">
                    <i data-lucide="x"></i>
                </button>
            </div>

            <div id="modal-view-content" style="display: none;">
                <div class="gn-progress-card" style="border: none; padding: 0;">
                    <img id="view-img" src="" class="gn-progress-img" style="display: none;">
                    <p id="view-desc" class="gn-progress-desc"></p>
                    <div class="gn-progress-meta">
                        <span id="view-date" class="gn-progress-date"></span>
                        <span id="view-score" class="gn-progress-score"></span>
                    </div>
                </div>
                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button class="auth-btn-primary" onclick="switchToEdit()" style="flex: 1;">
                        <i data-lucide="edit-3"></i> <span>Edit</span>
                    </button>
                    <form id="delete-progress-form" action="" method="POST" style="flex: 1;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="auth-btn-primary"
                            style="background: #ef4444; box-shadow: 0 8px 0px #b91c1c; width: 100%;"
                            onclick="return confirm('Hapus laporan ini?')">
                            <i data-lucide="trash-2"></i> <span>Hapus</span>
                        </button>
                    </form>
                </div>
            </div>

            <form id="progress-form" action="{{ route('gardening.progress.store', $plant->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="report_date" id="input-report-date">
                <div class="gn-form-group">
                    <label class="gn-label">Tanggal Progress: <span id="label-report-date"></span></label>
                </div>

                <div class="gn-form-group">
                    <label class="gn-label">Foto Progress</label>
                    <input type="file" name="image" class="gn-input" accept="image/*">
                    <p style="font-size: 11px; margin-top: 5px; opacity: 0.5; font-weight: 600;">Gunakan foto terbaru
                        (max 3MB)</p>
                </div>

                <div class="gn-form-group">
                    <label class="gn-label">Deskripsi Perkembangan</label>
                    <textarea name="description" id="input-description" class="gn-input"
                        style="height: 100px; resize: none;"
                        placeholder="Ceritakan bagaimana perkembangan tanaman sehat Anda hari ini..."></textarea>
                </div>

                <button type="submit" class="auth-btn-primary" style="width: 100%;">
                    <i data-lucide="save"></i> <span>Simpan Progress</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <nav class="db-bottom-nav">
        <a href="{{ route('dashboard') }}" class="db-nav-item">
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

    <script>
        lucide.createIcons();

        const events = @json($events);
        let currentMonth = new Date().getMonth();
        let currentYear = new Date().getFullYear();

        function renderCalendar() {
            const grid = document.getElementById('calendar-grid');
            const label = document.getElementById('month-year-label');

            // Clear existing days (keep labels)
            const labelsCount = 7;
            while (grid.children.length > labelsCount) {
                grid.removeChild(grid.lastChild);
            }

            const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            label.innerText = `${monthNames[currentMonth]} ${currentYear}`;

            const firstDay = new Date(currentYear, currentMonth, 1).getDay();
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

            const today = new Date();
            const todayStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;

            // Empty cells for first week
            for (let i = 0; i < firstDay; i++) {
                const cell = document.createElement('div');
                cell.className = 'gn-cal-cell other-month';
                grid.appendChild(cell);
            }

            // Actual days
            for (let d = 1; d <= daysInMonth; d++) {
                const dateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                const cell = document.createElement('div');
                cell.className = 'gn-cal-cell';
                cell.innerText = d;

                if (dateStr === todayStr) cell.classList.add('today');
                if (events[dateStr]) cell.classList.add('has-data');

                cell.onclick = () => handleDateClick(dateStr);
                grid.appendChild(cell);
            }
        }

        function prevMonth() {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            renderCalendar();
            lucide.createIcons();
        }

        function nextMonth() {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            renderCalendar();
            lucide.createIcons();
        }

        function handleDateClick(date) {
            const data = events[date];
            if (data) {
                showViewModal(date, data);
            } else {
                showAddModal(date);
            }
        }

        function showAddModal(date) {
            document.getElementById('modal-title').innerText = 'Tambah Progress';
            document.getElementById('modal-view-content').style.display = 'none';
            document.getElementById('progress-form').style.display = 'block';

            document.getElementById('input-report-date').value = date;
            document.getElementById('label-report-date').innerText = formatDate(date);
            document.getElementById('input-description').value = '';

            openModal();
        }

        function showViewModal(date, data) {
            document.getElementById('modal-title').innerText = 'Detail Progress';
            document.getElementById('progress-form').style.display = 'none';
            document.getElementById('modal-view-content').style.display = 'block';

            const viewImg = document.getElementById('view-img');
            if (data.image) {
                viewImg.src = data.image;
                viewImg.style.display = 'block';
            } else {
                viewImg.style.display = 'none';
            }

            document.getElementById('view-desc').innerText = data.description || 'Tidak ada deskripsi.';
            document.getElementById('view-date').innerText = formatDate(date);
            document.getElementById('view-score').innerText = 'Score: ' + (data.score || 0);

            document.getElementById('delete-progress-form').action = `/gardening/progress/${data.id}`;

            // Save state for edit
            window.currentSelectedDate = date;
            window.currentSelectedData = data;

            openModal();
        }

        function switchToEdit() {
            const date = window.currentSelectedDate;
            const data = window.currentSelectedData;

            document.getElementById('modal-title').innerText = 'Edit Progress';
            document.getElementById('modal-view-content').style.display = 'none';
            document.getElementById('progress-form').style.display = 'block';

            document.getElementById('input-report-date').value = date;
            document.getElementById('label-report-date').innerText = formatDate(date);
            document.getElementById('input-description').value = data.description;
        }

        function openProgressModal() {
            const today = new Date();
            const dateStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
            handleDateClick(dateStr);
        }

        function openModal() {
            document.getElementById('progressModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
            lucide.createIcons();
        }

        function closeProgressModal() {
            document.getElementById('progressModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        function formatDate(dateStr) {
            const d = new Date(dateStr);
            return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        }

        window.onclick = function (event) {
            const modal = document.getElementById('progressModal');
            if (event.target == modal) closeProgressModal();
        }

        renderCalendar();
    </script>
</body>

</html>