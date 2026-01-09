<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('/file/lifebookicon.png')}}" rel='icon' type='image/x-icon' />
    <title>Manage Users - Admin</title>
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
            --success: #10b981;
            --danger: #ef4444;
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
            max-width: 1000px;
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

        .back-link {
            text-decoration: none;
            color: var(--muted);
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .card {
            background: var(--bg);
            border-radius: 16px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            background: #f8fafc;
            padding: 16px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--muted);
            border-bottom: 1px solid var(--box-border);
        }

        td {
            padding: 16px;
            font-size: 14px;
            border-bottom: 1px solid var(--box-border);
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 600;
        }

        .status-approved {
            background: #ecfdf5;
            color: #059669;
        }

        .status-pending {
            background: #fff7ed;
            color: #c2410c;
        }

        .btn {
            padding: 6px 12px;
            border-radius: 8px;
            border: 0;
            cursor: pointer;
            font-weight: 600;
            font-size: 12px;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-approve {
            background: var(--accent);
            color: #fff;
        }

        .btn-approve:hover {
            background: #4f46e5;
        }

        .msg {
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 16px;
            font-size: 13px;
        }

        .msg-success {
            background: #ecfdf5;
            color: #059669;
            border: 1px solid rgba(16, 185, 129, 0.08);
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="header">
            <div>
                <a href="{{ route('parents.index') }}" class="back-link">← Back to Scores</a>
                <h1 class="title">User Management</h1>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn"
                    style="background: transparent; color: var(--muted); border: 1px solid var(--box-border);">Logout</button>
            </form>
        </div>

        @if(session('success'))
            <div class="msg msg-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td style="font-weight: 500;">{{ $user->name }}</td>
                            <td style="color: var(--muted);">{{ $user->email }}</td>
                            <td>
                                @if($user->is_approved)
                                    <span class="status-badge status-approved">Approved</span>
                                @else
                                    <span class="status-badge status-pending">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if(!$user->is_approved)
                                    <form action="{{ route('admin.users.approve', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-approve">Approve</button>
                                    </form>
                                @else
                                    <span style="color: var(--muted); font-size: 12px;">No actions</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    @if($users->isEmpty())
                        <tr>
                            <td colspan="4" style="text-align: center; color: var(--muted); padding: 32px;">No users found.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>