@extends('layouts.admin')

@section('title', 'System Settings')
@section('header_title', 'Admin Panel')
@section('header_subtitle', 'System Settings')

@section('content')
    <div class="profile-card">
        <h3 style="font-weight: 800; font-size: 16px; margin-bottom: 20px; color: var(--db-text-dark);">Konfigurasi
            Aplikasi</h3>

        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            <div class="auth-form-group">
                <label>App Version</label>
                <input type="text" name="app_version" class="auth-form-control"
                    value="{{ $settings['app_version'] ?? '1.0.4' }}" placeholder="Contoh: 1.0.5" required>
                <p style="font-size: 11px; margin-top: 5px; opacity: 0.5; font-weight: 600;">Ini akan mengubah teks
                    versi di seluruh halaman.</p>
            </div>

            <div class="auth-form-group" style="margin-top: 25px;">
                <label>Lifebook Teacher</label>
                <select name="lifebook_teacher_id" class="pa-select" style="width: 100%;" required>
                    <option value="">Pilih Guru...</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ ($settings['lifebook_teacher_id'] ?? '') == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }} ({{ $teacher->email }})
                        </option>
                    @endforeach
                </select>
                <p style="font-size: 11px; margin-top: 5px; opacity: 0.5; font-weight: 600;">Guru ini akan muncul
                    sebagai pembimbing di jurnal orang tua.</p>
            </div>

            <button type="submit" class="auth-btn-primary" style="margin-top: 10px; width: 100%;">
                <i data-lucide="save"></i>
                <span>Update Settings</span>
            </button>
        </form>
    </div>
@endsection