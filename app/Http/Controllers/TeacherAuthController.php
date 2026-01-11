<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Teacher;

class TeacherAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.teacher-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('teacher')->attempt($credentials)) {
            $teacher = Auth::guard('teacher')->user();

            if ($teacher->role !== 'guru') {
                Auth::guard('teacher')->logout();
                return back()->with('error', 'Akses hanya untuk akun Guru.');
            }

            $request->session()->regenerate();
            return redirect()->intended('/guru/dashboard');
        }

        return back()->withErrors([
            'email' => 'Kredensial tidak cocok dengan data guru kami.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('teacher')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('teacher.login');
    }

    public function dashboard()
    {
        return view('teacher.dashboard');
    }
}
