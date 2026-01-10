<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('/file/lifebookicon.png')}}" rel='icon' type='image/x-icon' />
    <title>Register - Lifebook Parents</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="{{asset('/file/style.css')}}?v=10" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="auth-body">
    <!-- Background Elements -->
    <img src="{{ asset('/file/bee.png') }}" class="db-bg-pattern db-bee" alt="">
    <img src="{{ asset('/file/flower.png') }}" class="db-bg-pattern db-flower" alt="">

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
                    <input type="text" id="name" name="name" class="auth-form-control" placeholder="Nama Lengkap"
                        required value="{{ old('name') }}">
                </div>
                <div class="auth-form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="auth-form-control" placeholder="akun@email.com"
                        required value="{{ old('email') }}">
                </div>
                <div class="auth-form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="auth-form-control"
                        placeholder="••••••••" required>
                </div>
                <div class="auth-form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="auth-form-control" placeholder="••••••••" required>
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

    <script>
        lucide.createIcons();
    </script>
</body>

</html>