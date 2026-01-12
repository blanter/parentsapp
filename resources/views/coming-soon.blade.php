@extends('layouts.app')

@section('title', 'Coming Soon - Lifebook Parents')

@section('content')
    <!-- Background Elements -->
    <img src="{{ asset('/file/bee.png') }}" class="db-bg-pattern db-bee" alt="">
    <img src="{{ asset('/file/flower.png') }}" class="db-bg-pattern db-flower" alt="">

    <div class="db-container cs-container">
        <div class="cs-box">
            <div class="cs-icon-wrapper">
                <i data-lucide="construction"></i>
            </div>

            <h1 class="cs-title">Fitur Belum Tersedia</h1>
            <p class="cs-subtitle">
                Halaman ini sedang dalam tahap pengembangan. <br>Nantikan pembaruan selanjutnya ya!
            </p>

            <a href="{{ route('dashboard') }}" class="auth-btn-primary"
                style="text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 10px;">
                <i data-lucide="arrow-left"></i>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>

        <div class="cs-footer" style="margin-bottom: 100px;">
            Version {{ $appVersion }} • Parents App
        </div>
    </div>
@endsection