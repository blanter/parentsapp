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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--multiple {
            background: #fff;
            border: 2px solid #F3F4F6;
            border-radius: 15px;
            padding: 8px 12px;
            min-height: 55px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 5px;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: var(--db-purple);
            box-shadow: 0 0 0 4px rgba(108, 136, 224, 0.1);
            outline: none;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: rgba(108, 136, 224, 0.1);
            border: none;
            border-radius: 10px;
            padding: 4px 10px;
            color: var(--db-purple);
            font-weight: 700;
            font-size: 13px;
            margin: 2px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: var(--db-purple);
            border-right: none;
            margin-right: 0;
            font-weight: 900;
            font-size: 16px;
            opacity: 0.5;
            transition: opacity 0.2s;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            background: none;
            opacity: 1;
        }

        .select2-container--default .select2-search--inline .select2-search__field {
            margin-top: 5px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
        }

        .select2-dropdown {
            border: 2px solid var(--db-purple);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            z-index: 9999;
        }

        .select2-results__option {
            padding: 12px 15px;
            font-size: 14px;
            font-weight: 600;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--db-purple);
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #F3F4F6;
            color: var(--db-purple);
        }

        /* Adjust label for consistency */
        .auth-form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 800;
            font-size: 13px;
            color: var(--db-text-dark);
            opacity: 0.7;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>
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
                    <label for="student_ids">Pilih Nama Anak</label>
                    <select name="student_ids[]" id="student_ids" class="auth-form-control select2" multiple="multiple"
                        required>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ (is_array(old('student_ids')) && in_array($student->id, old('student_ids'))) ? 'selected' : '' }}>
                                {{ $student->name }}
                            </option>
                        @endforeach
                    </select>
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
        $(document).ready(function () {
            $('.select2').select2({
                placeholder: "Cari nama anak...",
                allowClear: true
            });
        });
    </script>
</body>

</html>