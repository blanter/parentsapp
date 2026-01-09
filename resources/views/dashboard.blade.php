<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('/file/lifebookicon.png')}}" rel='icon' type='image/x-icon' />
    <title>Dashboard - Parents App</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="{{asset('/file/style.css')}}?v=6" rel="stylesheet" />
    <style>
        :root {
            --accent: #6366f1;
            --muted: #6b7280;
            --bg: #ffffff;
            --box-bg: #fff;
            --box-border: #e5e7eb;
            --box-radius: 12px;
            font-family: Poppins, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
        }

        body,
        html {
            height: 100%;
            margin: 0;
            color: #111827;
            background: #f9fafb;
        }

        .page {
            padding: 40px 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .title {
            font-size: 24px;
            font-weight: 700;
            color: var(--accent);
            margin: 0;
        }

        .card {
            background: var(--bg);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 32px;
            text-align: center;
        }

        .welcome-msg {
            font-size: 18px;
            color: #374151;
            margin-bottom: 12px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
            background: #ecfdf5;
            color: #059669;
            margin-bottom: 24px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 10px;
            border: 0;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            background: var(--accent);
            color: #fff;
            text-decoration: none;
            display: inline-block;
        }

        .logout-btn {
            background: transparent;
            color: var(--muted);
            border: 1px solid var(--box-border);
            padding: 8px 16px;
        }

        .logout-btn:hover {
            background: #f3f4f6;
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="header">
            <h1 class="title">Dashboard</h1>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn logout-btn">Logout</button>
            </form>
        </div>

        <div class="card">
            <div class="welcome-msg">Hello, {{ Auth::user()->name }}!</div>
            <div class="status-badge">Account Approved</div>
            <p style="color: var(--muted); margin-bottom: 32px;">Welcome to your parents dashboard. You can now access
                the leaderboard and other public features.</p>

            <div style="display: flex; gap: 12px; justify-content: center;">
                <a href="{{ route('parents.leaderboard') }}" class="btn">View Leaderboard</a>
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('parents.index') }}" class="btn" style="background: #111827;">Admin Settings</a>
                @endif
            </div>
        </div>
    </div>
</body>

</html>