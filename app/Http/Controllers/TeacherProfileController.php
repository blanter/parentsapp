<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class TeacherProfileController extends Controller
{
    public function index()
    {
        $teacher = Auth::guard('teacher')->user();
        $assignedStudents = $teacher->students;
        $allStudents = Student::active()->orderBy('name')->get();

        // Mark taken students (by other teachers if needed, but here simple all)
        return view('teacher.profile', compact('teacher', 'assignedStudents', 'allStudents'));
    }

    public function updateStudents(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        $request->validate([
            'student_ids' => 'array',
        ]);

        $teacher->students()->sync($request->student_ids ?? []);

        return back()->with('success', 'Data anak didik berhasil diperbarui.');
    }
}
