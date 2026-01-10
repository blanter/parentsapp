<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('/file/lifebookicon.png')}}" rel='icon' type='image/x-icon' />
    <title>Edit Score - Lifebook Academy</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="{{asset('/file/style.css')}}?v=6" rel="stylesheet" />
    <script src="{{asset('/file/jquery.min.js')}}"></script>
</head>

<body class="db-body">
    <!-- Background Elements -->
    <img src="{{ asset('/file/bee.png') }}" class="db-bg-pattern db-bee" alt="">
    <img src="{{ asset('/file/flower.png') }}" class="db-bg-pattern db-flower" alt="">

    <div class="adm-page-container">
        <div class="adm-header">
            <div class="adm-brand">
                <h1>Admin Panel</h1>
                <p>Parents Score App</p>
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
            <a href="{{ route('admin.settings') }}" class="adm-menu-item">
                <i data-lucide="settings"></i>
                <span>System Settings</span>
            </a>
        </div>

        <div class="container">
            <div class="content">
                <!-- Form Section -->
                <div class="form-section">
                    <form method="POST" action="{{ route('score.update', $score->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-row">

                            {{-- Parent Name (Readonly) --}}
                            <div class="form-group full-width">
                                <label class="form-label">Parents Name</label>
                                <input type="text" class="form-control" value="{{ $score->parent->name }}" readonly>
                            </div>

                            {{-- Activity --}}
                            <div class="form-group">
                                <label class="form-label">Edit Activity</label>
                                <select class="form-control" name="activity" required>
                                    <option value="">Select Activity</option>
                                    <option value="Journaling Parents" {{ $score->activity == 'Journaling Parents' ? 'selected' : '' }}>Journaling Parents</option>
                                    <option value="Support/Kerjasama" {{ $score->activity == 'Support/Kerjasama' ? 'selected' : '' }}>Support/Kerjasama</option>
                                    <option value="Home Gardening" {{ $score->activity == 'Home Gardening' ? 'selected' : '' }}>Home Gardening</option>
                                    <option value="Administrasi" {{ $score->activity == 'Administrasi' ? 'selected' : '' }}>
                                        Administrasi</option>
                                    <option value="Lifebook Journey" {{ $score->activity == 'Lifebook Journey' ? 'selected' : '' }}>Lifebook Journey</option>
                                </select>
                            </div>

                            {{-- Score --}}
                            <div class="form-group">
                                <label class="form-label">Edit Score</label>
                                <input type="number" class="form-control" name="score" min="1" max="100"
                                    value="{{ $score->score }}" required>
                            </div>

                            {{-- Description --}}
                            <div class="form-group full-width">
                                <label class="form-label">Description</label>
                                <input type="text" class="form-control" name="deskripsi"
                                    value="{{ $score->deskripsi }}">
                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                    {{-- Pesan sukses --}}
                    @if(session('success'))
                        <div class="success-message mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                </div>

                <div
                    style="text-align: center; margin-top: 30px; opacity: 0.3; font-size: 10px; font-weight: 700; color: var(--db-text-dark);">
                    Version {{ $appVersion }} • Parents App
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</body>

</html>