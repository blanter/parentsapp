@extends('layouts.guest')

@section('title', 'Login - Lifebook Parents')

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <div style="display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 20px;">
                <span
                    style="background: var(--db-purple); color: #fff; padding: 5px 15px; border-radius: 99px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">Akses
                    Orang Tua / Admin</span>
                <a href="{{ route('teacher.login') }}" 
                   style="background: #F3F4F6; color: var(--db-purple); width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.2s; box-shadow: 0 2px 5px rgba(0,0,0,0.05);" 
                   title="Masuk sebagai Guru">
                    <i data-lucide="refresh-cw" style="width: 14px; height: 14px;"></i>
                </a>
            </div>

            <h1 class="auth-title">Welcome Back!</h1>
            <p class="auth-subtitle">Masuk untuk mengelola misi dan jurnal.</p>

            <div class="auth-messages">
                @if(session('error'))
                    <div class="msg error">{{ session('error') }}</div>
                @endif
                @if(session('success'))
                    <div class="msg success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="msg error">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
            </div>

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="auth-form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="auth-form-control" placeholder="akun@email.com"
                        required value="{{ old('email') }}">
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

                <button type="submit" class="auth-btn-primary">
                    <i data-lucide="log-in"></i>
                    <span>Masuk Dashboard</span>
                </button>
            </form>

            <div class="auth-footer" style="margin-top: 30px; padding-top: 20px; border-top: 2px dashed #F3F4F6;">
                <a href="{{ route('register') }}" class="auth-btn-primary"
                    style="background: #fff; color: var(--db-purple); border: 2px solid var(--db-purple); box-shadow: 0 6px 0px rgba(108, 136, 224, 0.2); text-decoration: none; width: auto; display: inline-flex; padding: 10px 25px;">
                    <i data-lucide="user-plus" style="width: 18px; height: 18px;"></i>
                    <span>Daftar Sekarang</span>
                </a>

                <div style="margin-top: 20px;">
                    <a href="{{ route('app.download') }}"
                        style="color: #6B7280; font-size: 11px; font-weight: 700; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 5px;">
                        <i data-lucide="download" style="width: 12px; height: 12px;"></i>
                        Cara Pasang Aplikasi di HP (PWA)
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
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