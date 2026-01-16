@extends('layouts.app')

@section('title', 'Children Tracker - Lifebook Parents')

@section('content')
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
                <h1 style="font-size: 28px;">Hello<br><span style="color: var(--db-purple)">{{ $displayUser->name }}</span>
                </h1>
                <p style="font-size: 13.5px; font-weight: 600; opacity: 0.6; margin-top: 5px;">
                    {{ $isTeacher ? 'Pantau perkembangan anak didik Anda' : 'Have a great day with your children, parents' }}
                </p>
            </div>
            <a href="{{ $backRoute }}" class="db-avatar-section" style="width: 50px; height: 50px; text-decoration: none;">
                <i data-lucide="chevron-left" style="font-size: 24px; opacity: 1;"></i>
            </a>
        </div>

        @if($isAdmin || $isTeacher)
            @if($studentsWithStatus->isNotEmpty())
                <!-- Student Filter Selector -->
                <div class="ct-student-selector">
                    <h3 class="ct-filter-title">Filter Nama Anak</h3>
                    <div class="ct-student-grid">
                        <div class="ct-student-filter-card active" onclick="filterByStudent('all', this)">
                            <div class="ct-student-filter-avatar" style="background: var(--db-purple); color: #fff;">
                                <i data-lucide="users"></i>
                            </div>
                            <span class="ct-student-filter-name">Semua</span>
                        </div>
                        @foreach($studentsWithStatus as $student)
                            <div class="ct-student-filter-card" onclick="filterByStudent({{ $student->id }}, this)"
                                data-student-id="{{ $student->id }}">
                                <div class="ct-student-filter-avatar">
                                    <i data-lucide="user"></i>
                                    @if($student->status === 'pending')
                                        <span class="ct-status-dot ct-status-pending" title="Ada jurnal belum direspon"></span>
                                    @elseif($student->status === 'responded')
                                        <span class="ct-status-dot ct-status-responded" title="Semua jurnal sudah direspon"></span>
                                    @endif
                                </div>
                                <span class="ct-student-filter-name">{{ explode(' ', $student->name)[0] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <h2 class="ct-section-title">Laporan Jurnal Masuk</h2>
            <div class="ct-list">
                @forelse($submissions as $sub)
                    @php
                        $isQuarterly = str_contains($sub->bulan, 'Kuartal');
                    @endphp

                    {{-- Card for Aspek Orang Tua --}}
                    @if($sub->parent_aspect_filled && ($isQuarterly || $sub->bulan == 'Orang Tua'))
                        <a href="{{ route('children-tracker.parent-aspect', ['time' => $sub->bulan . ' ' . $currentYear, 'child_id' => $sub->student_id]) }}"
                            class="ct-card" data-student-id="{{ $sub->student_id }}">
                            <div class="ct-card-icon color-purple">
                                <i data-lucide="user"></i>
                            </div>
                            <div class="ct-card-info">
                                <h3 style="font-size: 15px;">{{ $sub->parent_name }}</h3>
                                <p
                                    style="font-size: 10px; opacity: 0.6; font-weight: 800; text-transform: uppercase; margin-bottom: 2px;">
                                    Aspek Orang Tua <span
                                        style="background: var(--db-purple); color: white; padding: 2px 6px; border-radius: 4px; font-size: 8px;">{{ $sub->bulan }}</span>
                                </p>
                                <p style="font-size: 11px; opacity: 0.7; font-weight: 600; line-height: 1.4; margin-top: 2px;">
                                    <span style="display: flex; align-items: center; gap: 4px;"><i data-lucide="baby"
                                            style="width: 12px; height: 12px;"></i> {{ $sub->student_name }}</span>
                                    <span style="display: flex; align-items: center; gap: 4px;"><i data-lucide="user-check"
                                            style="width: 12px; height: 12px;"></i> Wali: {{ $sub->teacher_wali ?: '-' }}</span>
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
                            class="ct-card" data-student-id="{{ $sub->student_id }}">
                            <div class="ct-card-icon color-orange">
                                <i data-lucide="users"></i>
                            </div>
                            <div class="ct-card-info">
                                <h3 style="font-size: 15px;">{{ $sub->parent_name }}</h3>
                                <p
                                    style="font-size: 10px; opacity: 0.6; font-weight: 800; text-transform: uppercase; margin-bottom: 2px;">
                                    Aspek Anak <span
                                        style="background: var(--db-secondary); color: white; padding: 2px 6px; border-radius: 4px; font-size: 8px;">{{ $sub->bulan }}</span>
                                </p>
                                <p style="font-size: 11px; opacity: 0.7; font-weight: 600; line-height: 1.4; margin-top: 2px;">
                                    <span style="display: flex; align-items: center; gap: 4px;"><i data-lucide="baby"
                                            style="width: 12px; height: 12px;"></i> {{ $sub->student_name }}</span>
                                    <span style="display: flex; align-items: center; gap: 4px;"><i data-lucide="user-check"
                                            style="width: 12px; height: 12px;"></i> Wali: {{ $sub->teacher_wali ?: '-' }}</span>
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
                            class="ct-card" data-student-id="{{ $sub->student_id }}">
                            <div class="ct-card-icon color-green">
                                <i data-lucide="calendar"></i>
                            </div>
                            <div class="ct-card-info">
                                <h3 style="font-size: 15px;">{{ $sub->parent_name }}</h3>
                                <p
                                    style="font-size: 10px; opacity: 0.6; font-weight: 800; text-transform: uppercase; margin-bottom: 2px;">
                                    Internal/Eksternal <span
                                        style="background: #10B981; color: white; padding: 2px 6px; border-radius: 4px; font-size: 8px;">{{ $sub->bulan }}</span>
                                </p>
                                <p style="font-size: 11px; opacity: 0.7; font-weight: 600; line-height: 1.4; margin-top: 2px;">
                                    <span style="display: flex; align-items: center; gap: 4px;"><i data-lucide="baby"
                                            style="width: 12px; height: 12px;"></i> {{ $sub->student_name }}</span>
                                    <span style="display: flex; align-items: center; gap: 4px;"><i data-lucide="user-check"
                                            style="width: 12px; height: 12px;"></i> Wali: {{ $sub->teacher_wali ?: '-' }}</span>
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
                            <p style="font-size: 11px; opacity: 0.6; font-weight: 600; margin-bottom: 5px;">
                                {{ $aspect['time_label'] }}
                            </p>
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
        <div
            style="background: rgba(108, 136, 224, 0.05); border: 1px solid rgba(108, 136, 224, 0.1); border-radius: 20px; padding: 20px; margin-top: 40px; margin-bottom: 20px;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px; color: var(--db-purple);">
                <i data-lucide="info" style="width: 20px; height: 20px;"></i>
                <h4 style="font-size: 14px; font-weight: 800; margin: 0;">PANDUAN PENGISIAN JURNAL</h4>
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <div style="display: flex; gap: 12px;">
                    <div
                        style="width: 8px; height: 8px; background: var(--db-purple); border-radius: 50%; margin-top: 5px; flex-shrink: 0;">
                    </div>
                    <p style="font-size: 12px; color: var(--db-text-dark); opacity: 0.8; margin: 0; line-height: 1.5;">
                        <b>Kuartalan (Per 3 Bulan):</b> Untuk <b>Aspek Orang Tua & Anak</b>. Bertujuan untuk melihat
                        perkembangan jangka panjang anak di rumah dan sekolah.
                    </p>
                </div>
                <div style="display: flex; gap: 12px;">
                    <div
                        style="width: 8px; height: 8px; background: var(--db-secondary); border-radius: 50%; margin-top: 5px; flex-shrink: 0;">
                    </div>
                    <p style="font-size: 12px; color: var(--db-text-dark); opacity: 0.8; margin: 0; line-height: 1.5;">
                        <b>Bulanan (Per 1 Bulan):</b> Untuk <b>Aspek Internal & Eksternal</b>. Bertujuan untuk
                        monitoring pertumbuhan karakter dan strategi bulanan secara rutin.
                    </p>
                </div>
            </div>
        </div>

        <div
            style="text-align: center; margin-top: 20px; opacity: 0.3; font-size: 10px; font-weight: 700; color: var(--db-text-dark); margin-bottom: 100px;">
            Version {{ $appVersion }} • Parents App
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function filterByStudent(studentId, element) {
            // Update active state on filter cards
            document.querySelectorAll('.ct-student-filter-card').forEach(function (card) {
                card.classList.remove('active');
            });
            element.classList.add('active');

            // Filter journal cards
            const cards = document.querySelectorAll('.ct-card[data-student-id]');

            if (studentId === 'all') {
                cards.forEach(function (card) {
                    card.style.display = '';
                    card.style.animation = 'ctFadeIn 0.3s ease forwards';
                });
            } else {
                cards.forEach(function (card) {
                    const cardStudentId = parseInt(card.getAttribute('data-student-id'));
                    if (cardStudentId === studentId) {
                        card.style.display = '';
                        card.style.animation = 'ctFadeIn 0.3s ease forwards';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            // Reinitialize lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
    </script>
@endsection