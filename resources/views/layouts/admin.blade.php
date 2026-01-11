<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('/file/lifebookicon.png')}}" rel='icon' type='image/x-icon' />
    <title>@yield('title', 'Admin Panel') - Parents App</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="{{asset('/file/style.css')}}?v=21" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* Common Admin Styles */
        .btn {
            padding: 6px 12px;
            border-radius: 8px;
            border: 0;
            cursor: pointer;
            font-weight: 600;
            font-size: 12px;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-edit {
            background: #f3f4f6;
            color: #374151;
        }

        .btn-edit:hover {
            background: #e5e7eb;
        }

        .btn-block {
            background: #fff1f2;
            color: #e11d48;
        }

        .btn-block:hover {
            background: #ffe4e6;
        }

        .btn-approve {
            background: #6366f1;
            color: #fff;
        }

        .btn-approve:hover {
            background: #4f46e5;
        }

        .btn-warning {
            background: #fff7ed;
            color: #c2410c;
        }

        .btn-warning:hover {
            background: #ffedd5;
        }

        .btn-danger {
            background: #fef2f2;
            color: #dc2626;
        }

        .btn-danger:hover {
            background: #fee2e2;
        }

        .btn-group {
            display: flex;
            gap: 8px;
        }

        /* Select2 Premium Styling (Matched with Settings) */
        .select2-container--default .select2-selection--multiple {
            background: #fff;
            border: 2px solid #F3F4F6;
            border-radius: 12px;
            padding: 5px 10px;
            min-height: 45px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 5px;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #6c88e0;
            box-shadow: 0 0 0 4px rgba(108, 136, 224, 0.1);
            outline: none;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: rgba(108, 136, 224, 0.1);
            border: none;
            border-radius: 8px;
            padding: 4px 10px;
            color: #6c88e0;
            font-weight: 700;
            font-size: 12px;
            margin: 2px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #6c88e0;
            border-right: none;
            margin-right: 0;
            font-weight: 900;
            font-size: 14px;
            opacity: 0.5;
            transition: opacity 0.2s;
        }

        .select2-dropdown {
            border: 2px solid #6c88e0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            z-index: 9999;
        }
    </style>
    @yield('styles')
</head>

<body class="db-body">
    <!-- Background Elements -->
    <img src="{{ asset('/file/bee.png') }}" class="db-bg-pattern db-bee" alt="">
    <img src="{{ asset('/file/flower.png') }}" class="db-bg-pattern db-flower" alt="">

    <div class="adm-page-container">
        <div class="adm-header">
            <div class="adm-brand">
                <h1>@yield('header_title', 'Admin Panel')</h1>
                <p>@yield('header_subtitle', 'User Management')</p>
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
            <div class="msg"
                style="background: #ecfdf5; color: #059669; border: 1px solid rgba(16, 185, 129, 0.08); padding: 12px; border-radius: 10px; margin-bottom: 20px; font-size: 13px;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="msg"
                style="margin-bottom: 20px; background: #fff1f2; color: #e11d48; border: 1px solid rgba(225, 29, 72, 0.1); padding: 12px; border-radius: 10px; font-size: 13px;">
                <ul style="margin: 0; padding-left: 15px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')

        <div
            style="text-align: center; margin-top: 50px; opacity: 0.3; font-size: 10px; font-weight: 700; color: var(--db-text-dark); margin-bottom: 100px;">
            Version {{ $appVersion ?? '1.2.0' }} • Parents App
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
    @yield('scripts')
</body>

</html>