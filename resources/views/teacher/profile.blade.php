@extends('layouts.app')

@section('title', 'Teacher Profile - Lifebook Parents')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--multiple {
            background: #fff;
            border: 2px solid #F3F4F6;
            border-radius: 15px;
            padding: 8px 12px;
            min-height: 55px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 5px;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: var(--db-purple);
            box-shadow: 0 0 0 4px rgba(108, 136, 224, 0.1);
            outline: none;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: rgba(108, 136, 224, 0.1);
            border: none;
            border-radius: 10px;
            padding: 4px 10px;
            color: var(--db-purple);
            font-weight: 700;
            font-size: 13px;
            margin: 2px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: var(--db-purple);
            border-right: none;
            margin-right: 0;
            font-weight: 900;
            font-size: 16px;
            opacity: 0.5;
            transition: opacity 0.2s;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            background: none;
            opacity: 1;
        }

        .select2-dropdown {
            border: 2px solid var(--db-purple);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .select2-results__option {
            padding: 12px 15px;
            font-size: 14px;
            font-weight: 600;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--db-purple);
        }
    </style>
@endsection

@section('content')
    <!-- Background Elements -->
    <img src="{{ asset('/file/bee.png') }}" class="db-bg-pattern db-bee" alt="">
    <img src="{{ asset('/file/flower.png') }}" class="db-bg-pattern db-flower" alt="">

    <div class="db-container">
        <div class="db-header">
            <div class="db-brand-section">
                <h1 style="font-size: 28px;">Teacher Profile</h1>
            </div>
            <a href="{{ route('teacher.dashboard') }}" class="db-avatar-section"
                style="width: 50px; height: 50px; text-decoration: none;">
                <i data-lucide="chevron-left" style="font-size: 24px; opacity: 1;"></i>
            </a>
        </div>

        <div class="profile-card">
            <div class="profile-header-info">
                <div class="profile-avatar-large">
                    <i data-lucide="user-check"></i>
                </div>
                <h2 class="profile-name">{{ $teacher->name }}</h2>
                <p class="profile-email">{{ $teacher->email }}</p>
                <div style="margin-top: 10px;">
                    <span
                        style="background: var(--db-purple); color: #fff; padding: 4px 12px; border-radius: 99px; font-size: 11px; font-weight: 800; text-transform: uppercase;">Teacher
                        Role</span>
                </div>
            </div>

            <div class="profile-info-grid">
                <div class="profile-info-item">
                    <span class="profile-info-label">Status Guru</span>
                    <span class="profile-info-value" style="color: var(--db-secondary);">Aktif</span>
                </div>
                <div class="profile-info-item">
                    <span class="profile-info-label">Total Anak Didik</span>
                    <span class="profile-info-value">{{ count($assignedStudents) }} Murid</span>
                </div>
            </div>
        </div>

        <div class="profile-card" style="margin-top: 20px;">
            <h3 style="font-weight: 800; font-size: 16px; margin-bottom: 20px; color: var(--db-text-dark);">Kelola Anak
                Didik</h3>
            <p style="font-size: 12px; color: var(--db-text-dark); opacity: 0.6; margin-bottom: 20px;">Pilih murid-murid
                yang berada di bawah bimbingan Anda untuk memantau jurnal mereka.</p>

            <form action="{{ route('teacher.profile.students') }}" method="POST">
                @csrf
                <div class="auth-form-group">
                    <label for="student_ids">Pilih Murid</label>
                    <select name="student_ids[]" id="student_ids" class="select2" multiple="multiple">
                        @foreach($allStudents as $student)
                            <option value="{{ $student->id }}" {{ in_array($student->id, $assignedStudents->pluck('id')->toArray()) ? 'selected' : '' }}>
                                {{ $student->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="auth-btn-primary" style="margin-top: 20px;">
                    <i data-lucide="save"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </form>
        </div>

        <form action="{{ route('teacher.logout') }}" method="POST" style="margin-top: 30px;">
            @csrf
            <button type="submit" class="btn-logout" style="background: #EF4444; box-shadow: 0 8px 0px #991B1B;">
                <i data-lucide="log-out"></i>
                <span>Keluar Aplikasi Guru</span>
            </button>
        </form>

        <div
            style="text-align: center; margin-top: 30px; opacity: 0.3; font-size: 10px; font-weight: 700; color: var(--db-text-dark); margin-bottom: 100px;">
            Version {{ $appVersion ?? '1.0.0' }} • Teacher Hub
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                placeholder: "Cari nama murid...",
                allowClear: true
            });
        });
    </script>
@endsection