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
        $adminWhatsapp = \App\Models\WebSetting::where('key', 'admin_whatsapp')->first()->value ?? '';

        return view('profile', compact('appVersion', 'totalScore', 'rank', 'adminWhatsapp'));
    }

    public function settings()
    {
        $user = Auth::user();
        $students = \App\Models\Student::active()->orderBy('name')->get();

        $takenStudentIds = \Illuminate\Support\Facades\DB::table('parent_student')
            ->where('user_id', '!=', $user->id)
            ->pluck('student_id')
            ->toArray();

        $students->map(function ($student) use ($takenStudentIds) {
            $student->is_taken = in_array($student->id, $takenStudentIds);
            return $student;
        });

        $appVersion = \App\Models\WebSetting::where('key', 'app_version')->first()->value ?? '1.0.0';

        return view('settings', compact('students', 'appVersion'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => [
                'exists:lifebook_users.users,id',
                function ($attribute, $value, $fail) use ($user) {
                    $isTaken = \Illuminate\Support\Facades\DB::table('parent_student')
                        ->where('student_id', $value)
                        ->where('user_id', '!=', $user->id)
                        ->exists();
                    if ($isTaken) {
                        $student = \App\Models\Student::find($value);
                        $name = $student ? $student->name : $value;
                        $fail("Murid $name sudah memiliki akun orang tua terdaftar.");
                    }
                },
            ],
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

            try {
                $file = $request->file('avatar');
                $filename = time() . '.jpg';
                $path = public_path('avatars/' . $filename);

                // Use Intervention Image for compression (60%)
                $manager = new \Intervention\Image\ImageManager(
                    new \Intervention\Image\Drivers\Gd\Driver()
                );

                $image = $manager->read($file);

                // Resize if too large (max 800px width for avatar)
                if ($image->width() > 800) {
                    $image->scale(width: 800);
                }

                $image->toJpeg(60)->save($path);

                $user->avatar = $filename;
            } catch (\Exception $e) {
                // Fallback to simple move
                $avatarName = time() . '.' . $request->avatar->extension();
                $request->avatar->move(public_path('avatars'), $avatarName);
                $user->avatar = $avatarName;
            }
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
