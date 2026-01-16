@extends('layouts.app')

@section('title', 'Account Settings - Lifebook Parents')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <!-- Background Elements -->
    <img src="{{ asset('/file/bee.png') }}" class="db-bg-pattern db-bee" alt="">
    <img src="{{ asset('/file/flower.png') }}" class="db-bg-pattern db-flower" alt="">

    <div class="db-container">
        <div class="db-header">
            <div class="db-brand-section">
                <h1 style="font-size: 24px;">Pengaturan Akun</h1>
            </div>
            <a href="{{ route('profile') }}" class="db-avatar-section"
                style="width: 45px; height: 45px; text-decoration: none;">
                <i data-lucide="chevron-left" style="font-size: 24px; opacity: 1;"></i>
            </a>
        </div>

        <div class="auth-messages">
            @if(session('success'))
                <div class="msg success"
                    style="background: #F0FFF4; color: #2D8A4E; border: 2px solid #E0FFE8; padding: 15px; border-radius: 15px; margin-bottom: 20px; font-size: 14px; font-weight: 600;">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="msg error"
                    style="background: #FFF0F0; color: #E04B4B; border: 2px solid #FFE0E0; padding: 15px; border-radius: 15px; margin-bottom: 20px; font-size: 14px; font-weight: 600;">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Personal Info Form -->
        <div class="profile-card" style="margin-bottom: 20px;">
            <h3 style="font-weight: 800; font-size: 16px; margin-bottom: 20px; color: var(--db-text-dark);">Informasi
                Pribadi</h3>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="profile-header-info" style="margin-bottom: 25px;">
                    <div class="profile-avatar-large" style="position: relative; cursor: pointer;"
                        onclick="document.getElementById('avatar-input').click()">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('avatars/' . Auth::user()->avatar) }}" id="avatar-preview" alt="Avatar"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i data-lucide="user" id="avatar-placeholder"></i>
                        @endif
                        <div
                            style="position: absolute; bottom: 0; right: 0; background: var(--db-purple); width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid #fff;">
                            <i data-lucide="camera" style="color: #fff; width: 14px; height: 14px;"></i>
                        </div>
                    </div>
                    <input type="file" id="avatar-input" name="avatar" style="display: none;"
                        onchange="previewImage(this)">
                    <p style="font-size: 11px; margin-top: 10px; opacity: 0.5; font-weight: 600;">Klik foto untuk
                        mengubah</p>
                </div>

                <div class="auth-form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" class="auth-form-control" value="{{ Auth::user()->name }}" required>
                </div>

                <div class="auth-form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="auth-form-control" value="{{ Auth::user()->email }}"
                        required>
                </div>

                <div class="auth-form-group">
                    <label>Daftar Nama Anak</label>
                    <select name="student_ids[]" class="auth-form-control select2" multiple="multiple" required>
                        @foreach($students as $student)
                            @php
                                $isSelected = Auth::user()->students->contains($student->id);
                            @endphp
                            <option value="{{ $student->id }}" 
                                {{ $isSelected ? 'selected' : '' }} 
                                {{ ($student->is_taken && !$isSelected) ? 'disabled' : '' }}>
                                {{ $student->name }} {{ ($student->is_taken && !$isSelected) ? '(Sudah Ada Akun)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="auth-btn-primary" style="margin-top: 10px;">
                    <i data-lucide="save"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </form>
        </div>

        <!-- Password Update Form -->
        <div class="profile-card">
            <h3 style="font-weight: 800; font-size: 16px; margin-bottom: 20px; color: var(--db-text-dark);">Ubah
                Password</h3>

            <form action="{{ route('profile.update-password') }}" method="POST">
                @csrf
                <div class="auth-form-group">
                    <label>Password Saat Ini</label>
                    <input type="password" name="current_password" class="auth-form-control" placeholder="••••••••"
                        required>
                </div>

                <div class="auth-form-group">
                    <label>Password Baru</label>
                    <input type="password" name="password" class="auth-form-control" placeholder="Min. 8 karakter"
                        required>
                </div>

                <div class="auth-form-group">
                    <label>Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="auth-form-control" placeholder="••••••••"
                        required>
                </div>

                <button type="submit" class="auth-btn-primary"
                    style="background: var(--db-dark-blue); box-shadow: 0 8px 0px #003680; margin-top: 10px;">
                    <i data-lucide="key"></i>
                    <span>Perbarui Password</span>
                </button>
            </form>
        </div>

        <div
            style="text-align: center; margin-top: 30px; opacity: 0.3; font-size: 10px; font-weight: 700; color: var(--db-text-dark); margin-bottom: 100px;">
            Version {{ $appVersion }} • Parents App
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Cari nama anak...",
                allowClear: true
            });
        });

        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var preview = document.getElementById('avatar-preview');
                    if (preview) {
                        preview.src = e.target.result;
                    } else {
                        // If there was no preview image yet (placeholder was visible)
                        var placeholder = document.getElementById('avatar-placeholder');
                        if (placeholder) {
                            var img = document.createElement('img');
                            img.id = 'avatar-preview';
                            img.src = e.target.result;
                            img.style.width = '100%';
                            img.style.height = '100%';
                            img.style.objectFit = 'cover';
                            placeholder.parentNode.appendChild(img);
                            placeholder.style.display = 'none';
                        }
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection