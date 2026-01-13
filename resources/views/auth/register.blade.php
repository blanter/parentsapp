@extends('layouts.guest')

@section('title', 'Register - Lifebook Parents')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Adjust label for consistency */
        .auth-form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            font-size: 13.3px;
            color: var(--db-text-dark);
            padding-left: 5px;
        }

        @media screen and (max-width: 480px) {
            .auth-form-group label {
                font-size: 12.6px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <h1 class="auth-title">Create Account!</h1>
            <p class="auth-subtitle">Daftar untuk mulai memantau aktivitas anak</p>

            <div class="auth-messages">
                @if($errors->any())
                    <div class="msg error">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
            </div>

            <form action="{{ route('register.post') }}" method="POST">
                @csrf
                <div class="auth-form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" class="auth-form-control" placeholder="Nama Lengkap" required
                        value="{{ old('name') }}">
                </div>
                <div class="auth-form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="auth-form-control" placeholder="akun@email.com"
                        required value="{{ old('email') }}">
                </div>

                <div class="auth-form-group">
                    <label for="student_ids">Pilih Nama Anak</label>
                    <select name="student_ids[]" id="student_ids" class="auth-form-control select2" multiple="multiple"
                        required>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ (is_array(old('student_ids')) && in_array($student->id, old('student_ids'))) ? 'selected' : '' }} {{ $student->is_taken ? 'disabled' : '' }}>
                                {{ $student->name }} {{ $student->is_taken ? '(Sudah Ada Akun)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="auth-form-group">
                    <label for="password">Password</label>
                    <div class="auth-input-wrapper">
                        <input type="password" id="password" name="password" class="auth-form-control"
                            placeholder="••••••••" required>
                        <button type="button" class="auth-password-toggle" onclick="togglePassword('password', this)">
                            <i data-lucide="eye"></i>
                        </button>
                    </div>
                </div>
                <div class="auth-form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <div class="auth-input-wrapper">
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="auth-form-control" placeholder="••••••••" required>
                        <button type="button" class="auth-password-toggle"
                            onclick="togglePassword('password_confirmation', this)">
                            <i data-lucide="eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="auth-btn-primary">
                    <i data-lucide="user-plus"></i>
                    <span>Daftar Sekarang</span>
                </button>
            </form>

            <div class="auth-footer">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk Disini</a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                placeholder: "Cari nama anak...",
                allowClear: true
            });
        });

        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                input.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }
    </script>
@endsection