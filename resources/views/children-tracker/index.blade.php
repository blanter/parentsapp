<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('/file/lifebookicon.png')}}" rel='icon' type='image/x-icon' />
    <title>Children Tracker - Lifebook Parents</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="{{asset('/file/style.css')}}?v=21" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="db-body">
    <!-- Background Elements -->
    <img src="{{ asset('/file/bee.png') }}" class="db-bg-pattern db-bee" alt="">
    <img src="{{ asset('/file/flower.png') }}" class="db-bg-pattern db-flower" alt="">

    @php
        $isTeacher = Auth::guard('teacher')->check();
        $displayUser = $isTeacher ? Auth::guard('teacher')->user() : Auth::user();
        $backRoute = $isTeacher ? route('teacher.dashboard') : route('dashboard');
    @endphp
    <div class="db-container">
        <div class="db-header">
            <div class="db-brand-section">
                <h1 style="font-size: 28px;">Hello<br><span
                        style="color: var(--db-purple)">{{ $displayUser->name }}</span></h1>
                <p style="font-size: 14px; font-weight: 600; opacity: 0.6; margin-top: 5px;">
                    {{ $isTeacher ? 'Pantau perkembangan anak didik Anda' : 'Have a great day with your children, parents' }}
                </p>
            </div>
            <a href="{{ $backRoute }}" class="db-avatar-section"
                style="width: 50px; height: 50px; text-decoration: none;">
                <i data-lucide="chevron-left" style="font-size: 24px; opacity: 1;"></i>
            </a>
        </div>

        @if($isAdmin || $isTeacher)
            <h2 class="ct-section-title">Laporan Jurnal Masuk</h2>
            <div class="ct-list">
                @forelse($submissions as $sub)
                    @php
                        $isQuarterly = str_contains($sub->bulan, 'Kuartal');
                    @endphp

                    {{-- Card for Aspek Orang Tua --}}
                    @if($sub->parent_aspect_filled && ($isQuarterly || $sub->bulan == 'Orang Tua')) {{-- Handling potential old data or identifying quarterly --}}
                        <a href="{{ route('children-tracker.parent-aspect', ['time' => $sub->bulan . ' ' . $currentYear, 'child_id' => $sub->student_id]) }}"
                            class="ct-card">
                            <div class="ct-card-icon color-purple">
                                <i data-lucide="user"></i>
                            </div>
                            <div class="ct-card-info">
                                <h3 style="font-size: 15px;">{{ $sub->parent_name }}</h3>
                                <p style="font-size: 10px; opacity: 0.6; font-weight: 800; text-transform: uppercase; margin-bottom: 2px;">
                                    Aspek Orang Tua <span style="background: var(--db-purple); color: white; padding: 2px 6px; border-radius: 4px; font-size: 8px;">{{ $sub->bulan }}</span>
                                </p>
                                <p style="font-size: 11px; opacity: 0.7; font-weight: 600; line-height: 1.4; margin-top: 2px;">
                                    <span style="display: flex; align-items: center; gap: 4px;"><i data-lucide="baby" style="width: 12px; height: 12px;"></i> {{ $sub->student_name }}</span>
                                    <span style="display: flex; align-items: center; gap: 4px;"><i data-lucide="user-check" style="width: 12px; height: 12px;"></i> Wali: {{ $sub->teacher_wali ?: '-' }}</span>
                                </p>
                                <div class="ct-indicator-wrapper" style="margin-top: 8px;">
                                    @if($sub->lifebook_teacher_reply)
                                        <span class="ct-indicator-pill success">
                                            <i data-lucide="check-circle" style="width: 10px; height: 10px;"></i>
                                            Selesai Konfirmasi
                                        </span>
                                    @elseif($sub->teacher_reply)
                                        <span class="ct-indicator-pill info">
                                            <i data-lucide="message-square" style="width: 10px; height: 10px;"></i>
                                            Dibalas Guru Wali
                                        </span>
                                    @else
                                        <span class="ct-indicator-pill warning">
                                            <i data-lucide="clock" style="width: 10px; height: 10px;"></i>
                                            Menunggu Respon
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <i data-lucide="arrow-right" style="opacity: 0.3;"></i>
                        </a>
                    @endif

                    {{-- Card for Aspek Anak --}}
                    @if($sub->child_aspect_filled && $isQuarterly)
                        <a href="{{ route('children-tracker.child-aspect', ['time' => $sub->bulan . ' ' . $currentYear, 'child_id' => $sub->student_id]) }}"
                            class="ct-card">
                            <div class="ct-card-icon color-orange">
                                <i data-lucide="users"></i>
                            </div>
                            <div class="ct-card-info">
                                <h3 style="font-size: 15px;">{{ $sub->parent_name }}</h3>
                                <p style="font-size: 10px; opacity: 0.6; font-weight: 800; text-transform: uppercase; margin-bottom: 2px;">
                                    Aspek Anak <span style="background: var(--db-secondary); color: white; padding: 2px 6px; border-radius: 4px; font-size: 8px;">{{ $sub->bulan }}</span>
                                </p>
                                <p style="font-size: 11px; opacity: 0.7; font-weight: 600; line-height: 1.4; margin-top: 2px;">
                                    <span style="display: flex; align-items: center; gap: 4px;"><i data-lucide="baby" style="width: 12px; height: 12px;"></i> {{ $sub->student_name }}</span>
                                    <span style="display: flex; align-items: center; gap: 4px;"><i data-lucide="user-check" style="width: 12px; height: 12px;"></i> Wali: {{ $sub->teacher_wali ?: '-' }}</span>
                                </p>
                                <div class="ct-indicator-wrapper" style="margin-top: 8px;">
                                    @if($sub->lifebook_child_reply)
                                        <span class="ct-indicator-pill success">
                                            <i data-lucide="check-circle" style="width: 10px; height: 10px;"></i>
                                            Selesai Konfirmasi
                                        </span>
                                    @elseif($sub->teacher_report)
                                        <span class="ct-indicator-pill info">
                                            <i data-lucide="message-square" style="width: 10px; height: 10px;"></i>
                                            Dilaporkan Guru Wali
                                        </span>
                                    @else
                                        <span class="ct-indicator-pill warning">
                                            <i data-lucide="clock" style="width: 10px; height: 10px;"></i>
                                            Menunggu Respon
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <i data-lucide="arrow-right" style="opacity: 0.3;"></i>
                        </a>
                    @endif

                    {{-- Card for Aspek Internal/Eksternal --}}
                    @if($sub->internal_external_filled && !$isQuarterly)
                        <a href="{{ route('children-tracker.internal-external-aspect', ['month' => $sub->bulan . ' ' . $currentYear, 'child_id' => $sub->student_id]) }}"
                            class="ct-card">
                            <div class="ct-card-icon color-green">
                                <i data-lucide="calendar"></i>
                            </div>
                            <div class="ct-card-info">
                                <h3 style="font-size: 15px;">{{ $sub->parent_name }}</h3>
                                <p style="font-size: 10px; opacity: 0.6; font-weight: 800; text-transform: uppercase; margin-bottom: 2px;">
                                    Internal/Eksternal <span style="background: #10B981; color: white; padding: 2px 6px; border-radius: 4px; font-size: 8px;">{{ $sub->bulan }}</span>
                                </p>
                                <p style="font-size: 11px; opacity: 0.7; font-weight: 600; line-height: 1.4; margin-top: 2px;">
                                    <span style="display: flex; align-items: center; gap: 4px;"><i data-lucide="baby" style="width: 12px; height: 12px;"></i> {{ $sub->student_name }}</span>
                                    <span style="display: flex; align-items: center; gap: 4px;"><i data-lucide="user-check" style="width: 12px; height: 12px;"></i> Wali: {{ $sub->teacher_wali ?: '-' }}</span>
                                </p>
                                <div class="ct-indicator-wrapper" style="margin-top: 8px;">
                                    @if($sub->strategi_baru)
                                        <span class="ct-indicator-pill success">
                                            <i data-lucide="check-circle" style="width: 10px; height: 10px;"></i>
                                            Selesai Konfirmasi
                                        </span>
                                    @elseif($sub->internal_teacher_reply || $sub->external_teacher_reply)
                                        <span class="ct-indicator-pill info">
                                            <i data-lucide="message-square" style="width: 10px; height: 10px;"></i>
                                            Dibalas Guru Wali
                                        </span>
                                    @else
                                        <span class="ct-indicator-pill warning">
                                            <i data-lucide="clock" style="width: 10px; height: 10px;"></i>
                                            Menunggu Respon
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <i data-lucide="arrow-right" style="opacity: 0.3;"></i>
                        </a>
                    @endif
                @empty
                    <div style="text-align: center; padding: 60px 20px; opacity: 0.4;">
                        <i data-lucide="file-search" style="width: 48px; height: 48px; margin-bottom: 15px;"></i>
                        <p style="font-weight: 700;">Belum ada data jurnal masuk<br>untuk periode saat ini.</p>
                    </div>
                @endforelse
            </div>
        @else
            <h2 class="ct-section-title">Children Tracker Monitoring</h2>

            <div class="ct-list">
                @foreach($aspects as $key => $aspect)
                    @php
                        $routeParams = ($key === 'internal_external') ? ['month' => $aspect['time_label']] : ['time' => $aspect['time_label']];
                    @endphp
                    <a href="{{ route($aspect['route'], $routeParams) }}" class="ct-card">
                        <div class="ct-card-icon {{ $aspect['color'] }}">
                            <i data-lucide="{{ $aspect['icon'] }}"></i>
                        </div>
                        <div class="ct-card-info">
                            <h3 style="font-size: 16px;">{{ $aspect['name'] }}</h3>
                            <p style="font-size: 11px; opacity: 0.6; font-weight: 600; margin-bottom: 5px;">{{ $aspect['time_label'] }}</p>
                            <div class="ct-indicator-wrapper">
                                @if($aspect['status'] === 'unfilled')
                                    <span class="ct-indicator-pill warning">
                                        <i data-lucide="alert-circle" style="width: 10px; height: 10px;"></i>
                                        Belum Diisi
                                    </span>
                                @elseif($aspect['status'] === 'filled')
                                    <span class="ct-indicator-pill success">
                                        <i data-lucide="check-circle" style="width: 10px; height: 10px;"></i>
                                        Sudah Diisi
                                    </span>
                                @elseif($aspect['status'] === 'replied')
                                    <span class="ct-indicator-pill info">
                                        <i data-lucide="message-square" style="width: 10px; height: 10px;"></i>
                                        Ada Balasan Guru
                                    </span>
                                @endif
                            </div>
                        </div>
                        <i data-lucide="chevron-right" style="opacity: 0.3;"></i>
                    </a>
                @endforeach
            </div>
        @endif

        <!-- Note Penjelasan -->
        <div style="background: rgba(108, 136, 224, 0.05); border: 1px solid rgba(108, 136, 224, 0.1); border-radius: 20px; padding: 20px; margin-top: 40px; margin-bottom: 20px;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px; color: var(--db-purple);">
                <i data-lucide="info" style="width: 20px; height: 20px;"></i>
                <h4 style="font-size: 14px; font-weight: 800; margin: 0;">PANDUAN PENGISIAN JURNAL</h4>
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <div style="display: flex; gap: 12px;">
                    <div style="width: 8px; height: 8px; background: var(--db-purple); border-radius: 50%; margin-top: 5px; flex-shrink: 0;"></div>
                    <p style="font-size: 12px; color: var(--db-text-dark); opacity: 0.8; margin: 0; line-height: 1.5;">
                        <b>Kuartalan (Per 3 Bulan):</b> Untuk <b>Aspek Orang Tua & Anak</b>. Bertujuan untuk melihat perkembangan jangka panjang anak di rumah dan sekolah.
                    </p>
                </div>
                <div style="display: flex; gap: 12px;">
                    <div style="width: 8px; height: 8px; background: var(--db-secondary); border-radius: 50%; margin-top: 5px; flex-shrink: 0;"></div>
                    <p style="font-size: 12px; color: var(--db-text-dark); opacity: 0.8; margin: 0; line-height: 1.5;">
                        <b>Bulanan (Per 1 Bulan):</b> Untuk <b>Aspek Internal & Eksternal</b>. Bertujuan untuk monitoring pertumbuhan karakter dan strategi bulanan secara rutin.
                    </p>
                </div>
            </div>
        </div>

        <div
            style="text-align: center; margin-top: 20px; opacity: 0.3; font-size: 10px; font-weight: 700; color: var(--db-text-dark); margin-bottom: 100px;">
            Version {{ $appVersion }} • Parents App
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

    <script>
        lucide.createIcons();
    </script>
</body>

</html>