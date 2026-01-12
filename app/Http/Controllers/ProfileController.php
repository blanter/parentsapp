<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $totalScore = \App\Models\Score::where('user_id', $user->id)->sum('score');

        // Calculate rank
        $rank = "-";
        if ($totalScore > 0) {
            $allScores = \App\Models\Score::select('user_id', \Illuminate\Support\Facades\DB::raw('SUM(score) as total'))
                ->groupBy('user_id')
                ->orderByDesc('total')
                ->get();

            $pos = $allScores->search(function ($item) use ($user) {
                return $item->user_id == $user->id;
            });

            if ($pos !== false) {
                $rank = $pos + 1;
            }
        }

        $appVersion = \App\Models\WebSetting::where('key', 'app_version')->first()->value ?? '1.0.0';
        return view('profile', compact('appVersion', 'totalScore', 'rank'));
    }

    public function settings()
    {
        $students = \App\Models\Student::active()->orderBy('name')->get();
        return view('settings', compact('students'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:lifebook_users.users,id',
        ], [
            'student_ids.required' => 'Pilih setidaknya satu nama anak.',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                $oldPath = public_path('avatars/' . $user->avatar);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $avatarName = time() . '.' . $request->avatar->extension();
            $request->avatar->move(public_path('avatars'), $avatarName);
            $user->avatar = $avatarName;
        }

        $user->save();
        $user->students()->sync($request->student_ids);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak cocok.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password berhasil diubah!');
    }
}
