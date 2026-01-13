@extends('layouts.guest')

@section('title', 'Download App - Lifebook Parents')
@section('body-class', 'db-body')

@section('content')
    <div class="db-container">
        <div class="header-simple">
            <div class="logo-wrapper">
                <img src="{{ asset('/file/appicon.png') }}" style="width: 50px; height: 50px;" alt="">
            </div>
            <h1>Pasang Aplikasi</h1>
            <p>Gunakan aplikasi untuk pengalaman terbaik</p>

            <!-- Quick Download Section -->
            <div id="quickInstallContainer" class="pwa-promo-container" style="display: none;">
                <button id="quickInstallButton" class="pwa-download-btn">
                    <i data-lucide="download"></i>
                    <span>UNDUH APLIKASI</span>
                </button>
                <p style="font-size: 11px; font-weight: 700; color: var(--db-purple); margin-top: 5px; opacity: 0.8;">
                    Klik untuk instalasi otomatis
                </p>
            </div>
        </div>

        <div class="dl-content-card">
            <div class="dl-step-item">
                <div class="step-num">1</div>
                <div class="step-text">
                    <h3>Buka di Browser</h3>
                    <p>Pastikan anda membuka halaman ini melalui browser <b>Chrome</b> (Android) atau <b>Safari</b>
                        (iOS).</p>
                </div>
            </div>

            <div class="dl-step-item">
                <div class="step-num">2</div>
                <div class="step-text">
                    <h3>Ketuk Menu</h3>
                    <p>Ketuk <b>ikon tiga titik ⋮</b> (Chrome) atau <b>ikon Share ⎙</b> (Safari) pada navigasi browser.
                    </p>
                </div>
            </div>

            <div class="dl-step-item">
                <div class="step-num">3</div>
                <div class="step-text">
                    <h3>Pasang ke HP</h3>
                    <p>Pilih menu <b>"Pasang Aplikasi"</b> atau <b>"Add to Home Screen"</b> untuk menambahkan ke daftar
                        aplikasi.</p>
                </div>
            </div>
        </div>

        <div id="iosNotice"
            style="margin-top: 30px; display: none; background: rgba(108, 136, 224, 0.05); padding: 25px; border-radius: 24px; border: 2px dashed var(--db-purple); text-align: center;">
            <div
                style="background: var(--db-purple); width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #fff; margin: 0 auto 15px;">
                <i data-lucide="smartphone"></i>
            </div>
            <h4 style="font-size: 16px; font-weight: 800; color: var(--db-text-dark); margin-bottom: 8px;">Pengguna iOS /
                iPhone</h4>
            <p
                style="font-size: 13px; font-weight: 500; color: var(--db-text-dark); opacity: 0.8; line-height: 1.6; margin: 0;">
                Silakan gunakan menu <b>Share</b> di peramban anda lalu pilih menu <b>"Add to Home Screen"</b> untuk
                memasang aplikasi.</p>
        </div>

        <div style="margin-top: 30px; text-align: center;">
            <a href="{{ url()->previous() == url()->current() ? route('login') : url()->previous() }}"
                class="auth-btn-primary"
                style="text-decoration: none; background: #fff; color: var(--db-text-dark); border: 2px solid #F3F4F6; box-shadow: 0 6px 0px #eee;">
                <i data-lucide="arrow-left"></i>
                <span>Kembali Sebelumnya</span>
            </a>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Detect iOS
        const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
        const iosNotice = document.getElementById('iosNotice');

        if (isIOS) {
            iosNotice.style.display = 'block';
        }

        // Check if already installed
        if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true) {
            quickInstallContainer.innerHTML = '<div style="background: #ecfdf5; color: #059669; padding: 15px; border-radius: 12px; font-size: 13px; font-weight: 800;">Aplikasi sudah terpasang di perangkat anda.</div>';
            quickInstallContainer.style.display = 'block';
        }

        // PWA Install Logic
        let deferredPrompt;
        const quickInstallContainer = document.getElementById('quickInstallContainer');
        const quickInstallButton = document.getElementById('quickInstallButton');

        // Always show the container initially for non-iOS and non-installed
        if (!isIOS && !window.matchMedia('(display-mode: standalone)').matches) {
            quickInstallContainer.style.display = 'block';
            quickInstallButton.innerHTML = '<i data-lucide="smartphone"></i><span>PASANG APLIKASI</span>';
            lucide.createIcons();
        }

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            quickInstallContainer.style.display = 'block';
            quickInstallButton.innerHTML = '<i data-lucide="download"></i><span>UNDUH SEKARANG</span>';
            lucide.createIcons();
            if (iosNotice) iosNotice.style.display = 'none';
        });

        quickInstallButton.addEventListener('click', (e) => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        quickInstallContainer.style.display = 'none';
                    }
                    deferredPrompt = null;
                });
            } else {
                // If no prompt, scroll to steps or show alert
                document.querySelector('.dl-content-card').scrollIntoView({ behavior: 'smooth' });
                alert('Gunakan menu browser anda (titik tiga di pojok kanan atas) lalu pilih "Instal aplikasi" atau "Pasang ke HP" untuk pengalaman terbaik.');
            }
        });

        window.addEventListener('appinstalled', (evt) => {
            // Log install to analytics
            console.log('INSTALL: Success');
        });
    </script>
@endsection