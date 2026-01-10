<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('/file/lifebookicon.png')}}" rel='icon' type='image/x-icon' />
    <title>Sistem Settings - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="{{asset('/file/style.css')}}?v=17" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="db-body">
    <!-- Background Elements -->
    <img src="{{ asset('/file/bee.png') }}" class="db-bg-pattern db-bee" alt="">
    <img src="{{ asset('/file/flower.png') }}" class="db-bg-pattern db-flower" alt="">

    <div class="adm-page-container">
        <div class="adm-header">
            <div class="adm-brand">
                <h1>Admin Panel</h1>
                <p>System Settings</p>
            </div>
            <a href="{{ route('profile') }}" class="db-avatar-section" style="width: 50px; height: 50px;">
                @if(Auth::user()->avatar)
                    <img src="{{ asset('avatars/' . Auth::user()->avatar) }}" alt="Avatar"
                        style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <i data-lucide="user"></i>
                @endif
            </a>
        </div>

        <div class="adm-menu-wrapper">
            <a href="{{ route('admin.dashboard') }}"
                class="adm-menu-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                <i data-lucide="layout-grid"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('parents.index') }}"
                class="adm-menu-item {{ Route::is('parents.index') || Route::is('score.edit') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard"></i>
                <span>Parents Score</span>
            </a>
            <a href="{{ route('admin.users') }}" class="adm-menu-item {{ Route::is('admin.users') ? 'active' : '' }}">
                <i data-lucide="users"></i>
                <span>Manage Users</span>
            </a>
            <a href="{{ route('admin.settings') }}"
                class="adm-menu-item {{ Route::is('admin.settings') ? 'active' : '' }}">
                <i data-lucide="settings"></i>
                <span>System Settings</span>
            </a>
        </div>

        @if(session('success'))
            <div
                style="background: #E8F5E9; color: #2E7D32; padding: 15px; border-radius: 20px; margin-bottom: 20px; font-weight: 700; font-size: 13px; border: 2px solid #C8E6C9;">
                {{ session('success') }}
            </div>
        @endif

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

                <button type="submit" class="auth-btn-primary" style="margin-top: 10px; width: 100%;">
                    <i data-lucide="save"></i>
                    <span>Update Settings</span>
                </button>
            </form>
        </div>

        <div
            style="text-align: center; margin-top: 30px; opacity: 0.3; font-size: 10px; font-weight: 700; color: var(--db-text-dark); margin-bottom: 100px;">
            Version {{ $appVersion }} • Parents App
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>