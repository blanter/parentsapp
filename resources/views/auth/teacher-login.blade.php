@extends('layouts.guest')

@section('title', 'Guru Login - Lifebook Parents')

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <div style="text-align: center; margin-bottom: 20px;">
                <span
                    style="background: var(--db-purple); color: #fff; padding: 5px 15px; border-radius: 99px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">Akses
                    Guru</span>
            </div>
            <h1 class="auth-title">Hello, Teacher!</h1>
            <p class="auth-subtitle">Masuk untuk mengelola jurnal dan perkembangan murid</p>

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

            <form action="{{ route('teacher.login.post') }}" method="POST">
                @csrf
                <div class="auth-form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="auth-form-control" placeholder="teacher@email.com"
                        required value="{{ old('email') }}">
                </div>
                <div class="auth-form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="auth-form-control" placeholder="••••••••"
                        required>
                </div>

                <button type="submit" class="auth-btn-primary"
                    style="background: var(--db-purple); box-shadow: 0 8px 0px #4A63B3;">
                    <i data-lucide="log-in"></i>
                    <span>Masuk ke Dashboard Guru</span>
                </button>
            </form>

            <div class="auth-footer">
                Bukan guru? <a href="{{ route('login') }}">Masuk sebagai Orang Tua</a>
            </div>
        </div>
    </div>
@endsection