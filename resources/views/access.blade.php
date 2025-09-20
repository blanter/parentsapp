<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Access - Lifebook Academy</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{asset('/file/style.css')}}" rel="stylesheet"/>
    <script src="{{asset('/file/jquery.min.js')}}"></script>
    <style>.subtitle,.title{text-align:center}:root{--accent:#6366f1;--muted:#6b7280;--bg:#ffffff;--box-bg:#fff;--box-border:#e5e7eb;--box-radius:12px;--success:#10b981;--danger:#ef4444;font-family:Poppins,system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial}body,html{height:100%;margin:0;color:#111827}.page{min-height:100%;display:flex;align-items:center;justify-content:center;padding:28px}.card{width:100%;max-width:460px;background:var(--bg);border-radius:16px;box-shadow:0 10px 30px rgba(16,24,40,.06);padding:32px;box-sizing:border-box}.title{font-size:20px;font-weight:700;color:var(--accent);margin:0 0 6px}.msg,.subtitle{font-size:13px}.hint,.subtitle{color:var(--muted)}.subtitle{margin:0 0 18px}.messages{margin-bottom:12px}.msg{padding:10px 12px;border-radius:10px;margin-bottom:8px}.pin-input,.pin-input input{font-size:28px;font-weight:600;outline:0;text-align:center}.msg.error{background:#fff5f5;color:var(--danger);border:1px solid rgba(239,68,68,.08)}.msg.success{background:#ecfdf5;color:var(--success);border:1px solid rgba(16,185,129,.08)}.pin-form{display:flex;flex-direction:column;gap:18px}.pin-boxes{display:flex;gap:12px;justify-content:center;align-items:center;margin:0 auto;flex-wrap:wrap}.pin-input{width:56px;height:64px;border-radius:12px;border:2px solid var(--box-border);background:var(--box-bg);display:inline-flex;align-items:center;justify-content:center;transition:.14s}.pin-input input{width:100%;height:100%;border:0;background:0 0;caret-color:transparent}.pin-input:focus-within{border-color:var(--accent);box-shadow:0 6px 20px rgba(99,102,241,.08);transform:translateY(-2px)}.hint{font-size:13px;text-align:center}.actions{display:flex;gap:8px;justify-content:center;align-items:center}.btn{padding:10px 16px;border-radius:10px;border:0;cursor:pointer;font-weight:700;font-size:14px}.btn.primary{background:var(--accent);color:#fff;box-shadow:0 6px 18px rgba(99,102,241,.12)}.btn.ghost,.small{color:var(--muted)}.btn.ghost{background:0 0;border:1px solid rgba(0,0,0,.04)}.small{font-size:12px;text-align:center}@media (max-width:480px){.pin-input{width:40px;height:50px;font-size:20px}.card{background:transparent;box-shadow:none;padding:2px}}.sr-only{position:absolute!important;height:1px;width:1px;overflow:hidden;clip:rect(1px,1px,1px,1px);white-space:nowrap}</style>
</head>
<body>
    <div class="page">
        <div class="card" role="main" aria-labelledby="pinTitle">
            <h1 id="pinTitle" class="title">Masukkan PIN Akses</h1>
            <p class="subtitle">Masukkan 6-digit PIN</p>

            <div class="messages">
                @if(session('error'))
                    <div class="msg error">{{ session('error') }}</div>
                @endif
                @if(session('success'))
                    <div class="msg success">{{ session('success') }}</div>
                @endif
                @if($errors->has('pin'))
                    <div class="msg error">{{ $errors->first('pin') }}</div>
                @endif
            </div>

            <form id="accessForm" class="pin-form" method="POST" action="{{ route('access.store') }}" autocomplete="off" novalidate>
                @csrf

                {{-- Six PIN boxes --}}
                <div class="pin-boxes" id="pinBoxes" aria-hidden="false">
                    {{-- Each box contains a single text input (numeric). Keep inputs as real inputs for mobile numeric keyboard --}}
                    <div class="pin-input">
                        <label class="sr-only" for="pin-1">Digit 1</label>
                        <input inputmode="numeric" pattern="[0-9]*" maxlength="1" id="pin-1" aria-label="Digit 1" />
                    </div>
                    <div class="pin-input">
                        <label class="sr-only" for="pin-2">Digit 2</label>
                        <input inputmode="numeric" pattern="[0-9]*" maxlength="1" id="pin-2" aria-label="Digit 2" />
                    </div>
                    <div class="pin-input">
                        <label class="sr-only" for="pin-3">Digit 3</label>
                        <input inputmode="numeric" pattern="[0-9]*" maxlength="1" id="pin-3" aria-label="Digit 3" />
                    </div>
                    <div class="pin-input">
                        <label class="sr-only" for="pin-4">Digit 4</label>
                        <input inputmode="numeric" pattern="[0-9]*" maxlength="1" id="pin-4" aria-label="Digit 4" />
                    </div>
                    <div class="pin-input">
                        <label class="sr-only" for="pin-5">Digit 5</label>
                        <input inputmode="numeric" pattern="[0-9]*" maxlength="1" id="pin-5" aria-label="Digit 5" />
                    </div>
                    <div class="pin-input">
                        <label class="sr-only" for="pin-6">Digit 6</label>
                        <input inputmode="numeric" pattern="[0-9]*" maxlength="1" id="pin-6" aria-label="Digit 6" />
                    </div>
                </div>

                {{-- hidden input untuk dikirim --}}
                <input type="hidden" name="pin" id="pinHidden" />

                <div class="hint">Tekan <strong>Enter</strong> untuk mengirim 6 digit pin</div>

                <div class="actions">
                    <button type="submit" class="btn primary">Masuk</button>
                    <button type="button" id="clearBtn" class="btn ghost" aria-label="Bersihkan PIN">Bersihkan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function(){
            const inputs = Array.from(document.querySelectorAll('#pinBoxes input'));
            const hidden = document.getElementById('pinHidden');
            const form = document.getElementById('accessForm');
            const clearBtn = document.getElementById('clearBtn');

            if (inputs.length) inputs[0].focus();

            inputs.forEach((inp, idx) => {
                inp.addEventListener('input', (e) => {
                    const v = e.target.value;
                    const digit = v.replace(/\D/g,'').slice(0,1);

                    if (digit !== '') {
                        // simpan angka asli
                        inp.dataset.real = digit;
                        inp.value = digit;

                        // masking setelah 0.5 detik
                        if (inp.maskTimeout) clearTimeout(inp.maskTimeout);
                        inp.maskTimeout = setTimeout(() => {
                            inp.value = '*';
                        }, 500);

                        // pindah ke kotak berikutnya
                        const next = inputs[idx + 1];
                        if (next) {
                            next.focus();
                            next.select && next.select();
                        }
                    } else {
                        inp.dataset.real = '';
                    }
                });

                inp.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !inp.value) {
                        const prev = inputs[idx - 1];
                        if (prev) {
                            prev.focus();
                            prev.value = '';
                            prev.dataset.real = '';
                            e.preventDefault();
                        }
                    }
                });

                inp.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const paste = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g,'');
                    if (!paste) return;
                    let i = idx;
                    paste.split('').forEach(ch => {
                        if (i < inputs.length) {
                            inputs[i].dataset.real = ch;
                            inputs[i].value = ch;
                            if (inputs[i].maskTimeout) clearTimeout(inputs[i].maskTimeout);
                            inputs[i].maskTimeout = setTimeout(() => {
                                inputs[i].value = '*';
                            }, 1500);
                            i++;
                        }
                    });
                    if (i < inputs.length) inputs[i].focus();
                });
            });

            function allFilled() {
                return inputs.every(i => (i.dataset.real || '').trim() !== '');
            }

            function submitForm() {
                if (!allFilled()) {
                    const firstEmpty = inputs.find(i => !i.dataset.real);
                    if (firstEmpty) firstEmpty.focus();
                    return;
                }
                hidden.value = inputs.map(i => i.dataset.real || '').join('');
                form.submit();
            }

            form.addEventListener('submit', (e) => {
                e.preventDefault();
                if (!allFilled()) return;
                hidden.value = inputs.map(i => i.dataset.real || '').join('');
                if (hidden.value.length === inputs.length) {
                    e.target.submit();
                }
            });

            clearBtn.addEventListener('click', function(){
                inputs.forEach(i => {
                    i.value = '';
                    i.dataset.real = '';
                });
                hidden.value = '';
                inputs[0].focus();
            });
        })();
    </script>
</body>
</html>
