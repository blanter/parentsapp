<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('/file/lifebookicon.png')}}" rel='icon' type='image/x-icon' />
    <title>Login - Parents App</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="{{asset('/file/style.css')}}?v=6" rel="stylesheet" />
    <script src="{{asset('/file/jquery.min.js')}}"></script>
    <style>
        :root {
            --accent: #6366f1;
            --muted: #6b7280;
            --bg: #ffffff;
            --box-bg: #fff;
            --box-border: #e5e7eb;
            --box-radius: 12px;
            --success: #10b981;
            --danger: #ef4444;
            font-family: Poppins, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
        }

        body,
        html {
            height: 100%;
            margin: 0;
            color: #111827;
        }

        .page {
            min-height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px;
        }

        .card {
            width: 100%;
            max-width: 400px;
            background: var(--bg);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(16, 24, 40, .06);
            padding: 32px;
            box-sizing: border-box;
        }

        .title {
            font-size: 24px;
            font-weight: 700;
            color: var(--accent);
            margin: 0 0 6px;
            text-align: center;
        }

        .subtitle {
            font-size: 14px;
            color: var(--muted);
            margin: 0 0 24px;
            text-align: center;
        }

        .messages {
            margin-bottom: 16px;
        }

        .msg {
            font-size: 13px;
            padding: 10px 12px;
            border-radius: 10px;
            margin-bottom: 8px;
        }

        .msg.error {
            background: #fff5f5;
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, .08);
        }

        .msg.success {
            background: #ecfdf5;
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, .08);
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 6px;
            color: #374151;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border-radius: 10px;
            border: 1px solid var(--box-border);
            background: var(--box-bg);
            font-size: 14px;
            box-sizing: border-box;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .btn {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: 0;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s;
        }

        .btn.primary {
            background: var(--accent);
            color: #fff;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
        }

        .btn.primary:hover {
            background: #4f46e5;
            transform: translateY(-1px);
        }

        .footer-text {
            margin-top: 24px;
            text-align: center;
            font-size: 13px;
            color: var(--muted);
        }

        .footer-text a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
        }

        @media (max-width: 480px) {
            .card {
                background: transparent;
                box-shadow: none;
                padding: 16px;
            }
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="card">
            <h1 class="title">Welcome Back</h1>
            <p class="subtitle">Please enter your details</p>

            <div class="messages">
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
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="name@company.com"
                        required value="{{ old('email') }}">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••"
                        required>
                </div>

                <button type="submit" class="btn primary">Sign In</button>
            </form>

            <div class="footer-text">
                Don't have an account? <a href="{{ route('register') }}">Sign up</a>
            </div>
        </div>
    </div>
</body>

</html>