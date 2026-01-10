<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('/file/lifebookicon.png')}}" rel='icon' type='image/x-icon' />
    <title>Volunteer Mission - Lifebook Parents</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="{{asset('/file/style.css')}}?v=18" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="db-body">
    <!-- Background Elements -->
    <img src="{{ asset('/file/bee.png') }}" class="db-bg-pattern db-bee" alt="">
    <img src="{{ asset('/file/flower.png') }}" class="db-bg-pattern db-flower" alt="">

    <div class="vm-container">
        <!-- Header -->
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

        <div class="vm-header">
            <h1>Volunteer Mission</h1>
            <p class="vm-subtitle">Pilih misi yang telah Anda selesaikan!</p>
        </div>

        <div class="vm-list">
            @foreach($missions as $mission)
                <div class="vm-card">
                    <div class="vm-card-header">
                        <div class="vm-mission-name">{{ $mission->name }}</div>
                        <div class="vm-mission-icon">
                            <i
                                data-lucide="{{ str_contains(strtolower($mission->name), 'peternakan') ? 'dog' : (str_contains(strtolower($mission->name), 'perkebunan') ? 'flower' : (str_contains(strtolower($mission->name), 'karya') ? 'palette' : 'star')) }}"></i>
                        </div>
                    </div>

                    <div class="vm-days-grid">
                        @foreach($weekDays as $day)
                            @php
                                $isCompleted = isset($completions[$mission->id]) && $completions[$mission->id]->contains('completed_at', $day['date']);
                            @endphp
                            <div class="vm-day-item">
                                <span class="vm-day-label">{{ $day['name'] }}</span>
                                <div class="vm-checkbox {{ $isCompleted ? 'checked' : '' }}"
                                    onclick="toggleMission({{ $mission->id }}, '{{ $day['date'] }}', this)">
                                    @if($isCompleted)
                                        <i data-lucide="check"></i>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div
            style="text-align: center; margin-top: 40px; opacity: 0.3; font-size: 10px; font-weight: 700; color: var(--db-text-dark); margin-bottom: 100px;">
            Version {{ $appVersion }} • Parents App
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

        function toggleMission(missionId, date, element) {
            $(element).css('pointer-events', 'none');

            $.ajax({
                url: "{{ route('volunteer.toggle') }}",
                type: "POST",
                data: {
                    mission_id: missionId,
                    date: date,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.status === 'success') {
                        if (response.action === 'checked') {
                            $(element).addClass('checked').html('<i data-lucide="check"></i>');
                        } else {
                            $(element).removeClass('checked').empty();
                        }
                    }
                },
                error: function () {
                    alert('Terjadi kesalahan, silakan coba lagi.');
                },
                complete: function () {
                    $(element).css('pointer-events', 'auto');
                    lucide.createIcons();
                }
            });
        }
    </script>
</body>

</html>