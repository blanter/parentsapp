<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('/file/lifebookicon.png')}}" rel='icon' type='image/x-icon' />
    <link rel="manifest" href="{{ asset('/manifest.json') }}">
    <meta name="theme-color" content="#FFD64B">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Parents App">
    <link rel="apple-touch-icon" href="{{ asset('/file/lifebookicon.png') }}">
    <title>Parents Lifebook Journey - Lifebook Parents</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="{{asset('/file/style.css')}}?v=15" rel="stylesheet" />

    <!-- Owl Carousel Assets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

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
            max-width: 600px;
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
</head>

<body>
    <div class="lj-container">
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
                Parents My<br><span>Lifebook Journey</span>
            </div>
        </div>

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
                    <div class="lj-category-item" data-id="{{ $cat['id'] }}" data-name="{{ $cat['name'] }}">
                        <div class="lj-category-icon">
                            <i data-lucide="{{ $cat['icon'] }}"></i>
                        </div>
                        <div class="lj-category-name">{{ $cat['name'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Content Section -->
        <div class="lj-content-section" id="journeyContent">
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
        // Data from server
        const journeys = @json($journeys);
        const categories = @json($categories);
        let activeCategoryId = "{{ $activeCat }}";
        let currentField = '';

        $(document).ready(function () {
            // Initialize Lucide
            lucide.createIcons();

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
    </script>
</body>

</html>