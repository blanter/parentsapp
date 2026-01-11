<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('/file/lifebookicon.png')}}" rel='icon' type='image/x-icon' />
    <link rel="manifest" href="{{ asset('/manifest.json') }}">
    <meta name="theme-color" content="#FFD64B">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Parents App">
    <link rel="apple-touch-icon" href="{{ asset('/file/lifebookicon.png') }}">
    <title>Download App - Lifebook Parents</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="{{asset('/file/style.css')}}?v=14" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="db-body">
    <!-- Background Elements -->
    <img src="{{ asset('/file/bee.png') }}" class="db-bg-pattern db-bee" alt="">
    <img src="{{ asset('/file/flower.png') }}" class="db-bg-pattern db-flower" alt="">

    <div class="db-container">
        <div class="header-simple">
            <div class="logo-wrapper">
                <img src="{{ asset('/file/lifebookicon.png') }}" style="width: 50px; height: 50px;" alt="">
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

        <div id="iosNotice" style="margin-top: 30px; display: none; background: rgba(108, 136, 224, 0.05); padding: 25px; border-radius: 24px; border: 2px dashed var(--db-purple); text-align: center;">
            <div style="background: var(--db-purple); width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #fff; margin: 0 auto 15px;">
                <i data-lucide="smartphone"></i>
            </div>
            <h4 style="font-size: 16px; font-weight: 800; color: var(--db-text-dark); margin-bottom: 8px;">Pengguna iOS / iPhone</h4>
            <p style="font-size: 13px; font-weight: 500; color: var(--db-text-dark); opacity: 0.8; line-height: 1.6; margin: 0;">Silakan gunakan menu <b>Share</b> di peramban anda lalu pilih menu <b>"Add to Home Screen"</b> untuk memasang aplikasi.</p>
        </div>

        <div style="margin-top: 30px; text-align: center;">
            <a href="{{ url()->previous() == url()->current() ? route('login') : url()->previous() }}"
                class="auth-btn-primary" style="text-decoration: none; background: #fff; color: var(--db-text-dark); border: 2px solid #F3F4F6; box-shadow: 0 6px 0px #eee;">
                <i data-lucide="arrow-left"></i>
                <span>Kembali Sebelumnya</span>
            </a>
        </div>
    </div>

    <script>
        lucide.createIcons();

        // Detect iOS
        const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
        const iosNotice = document.getElementById('iosNotice');
        
        if (isIOS) {
            iosNotice.style.display = 'block';
        }

        // PWA Install Logic
        let deferredPrompt;
        const quickInstallContainer = document.getElementById('quickInstallContainer');
        const quickInstallButton = document.getElementById('quickInstallButton');

        window.addEventListener('beforeinstallprompt', (e) => {
            // Prevent the mini-infobar from appearing on mobile
            e.preventDefault();
            // Stash the event so it can be triggered later.
            deferredPrompt = e;
            // Update UI notify the user they can install the PWA
            quickInstallContainer.style.display = 'block';
            
            // If we have an auto-install prompt, we can hide the iOS manual notice
            if (iosNotice) iosNotice.style.display = 'none';
        });

        quickInstallButton.addEventListener('click', (e) => {
            // Hide the app provided install promotion
            quickInstallContainer.style.display = 'none';
            // Show the install prompt
            deferredPrompt.prompt();
            // Wait for the user to respond to the prompt
            deferredPrompt.userChoice.then((choiceResult) => {
                if (choiceResult.outcome === 'accepted') {
                    console.log('User accepted the PWA prompt');
                } else {
                    console.log('User dismissed the PWA prompt');
                }
                deferredPrompt = null;
            });
        });

        window.addEventListener('appinstalled', (evt) => {
            // Log install to analytics
            console.log('INSTALL: Success');
            quickInstallContainer.style.display = 'none';
        });
    </script>
</body>

</html>