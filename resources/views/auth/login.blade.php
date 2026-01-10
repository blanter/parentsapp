<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('/file/lifebookicon.png')}}" rel='icon' type='image/x-icon' />
    <title>Login - Lifebook Parents</title>
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
            <h1 class="auth-title">Welcome Back!</h1>
            <p class="auth-subtitle">Masuk untuk melihat perkembangan si kecil</p>

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
                    <input type="password" id="password" name="password" class="auth-form-control"
                        placeholder="••••••••" required>
                </div>

                <button type="submit" class="auth-btn-primary">
                    <i data-lucide="log-in"></i>
                    <span>Masuk</span>
                </button>
            </form>

            <div class="auth-footer">
                Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>