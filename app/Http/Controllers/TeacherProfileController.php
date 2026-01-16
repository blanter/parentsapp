<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\TeacherLifebookStudent;
use Illuminate\Support\Facades\DB;

class TeacherProfileController extends Controller
{
    public function index()
    {
        $teacher = Auth::guard('teacher')->user();
        $assignedStudents = $teacher->students;
        $allStudents = Student::active()->orderBy('name')->get();

        // Get students where this teacher is the lifebook teacher
        $lifebookStudentIds = TeacherLifebookStudent::where('teacher_id', $teacher->id)
            ->pluck('student_id')
            ->toArray();

        $appVersion = \App\Models\WebSetting::where('key', 'app_version')->value('value') ?? '1.2.0';

        return view('teacher.profile', compact('teacher', 'assignedStudents', 'allStudents', 'lifebookStudentIds', 'appVersion'));
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

    public function claimLifebookStudent(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:lifebook_users.users,id',
        ]);

        $teacher = Auth::guard('teacher')->user();
        $studentId = $request->student_id;

        // Check if student is assigned to this teacher
        if (!$teacher->students()->where('student_id', $studentId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak bisa menjadi guru lifebook untuk murid yang bukan anak didik Anda.'
            ], 403);
        }

        // Check if student already has a lifebook teacher
        $existing = TeacherLifebookStudent::where('student_id', $studentId)->first();
        if ($existing) {
            if ($existing->teacher_id == $teacher->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah menjadi guru wali & lifebook untuk murid ini.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Murid ini sudah memiliki guru wali & lifebook lain.'
                ], 409);
            }
        }

        // Create the lifebook assignment
        TeacherLifebookStudent::create([
            'teacher_id' => $teacher->id,
            'student_id' => $studentId,
        ]);

        // Also ensure teacher is assigned as guru wali (if not already)
        // This adds the teacher to teacher_student table
        if (!$teacher->students()->where('student_id', $studentId)->exists()) {
            $teacher->students()->attach($studentId);
        }

        $student = Student::find($studentId);

        return response()->json([
            'success' => true,
            'message' => "Anda sekarang menjadi Guru Wali & Guru Lifebook untuk {$student->name}."
        ]);
    }

    public function unclaimLifebookStudent(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:lifebook_users.users,id',
        ]);

        $teacher = Auth::guard('teacher')->user();
        $studentId = $request->student_id;

        $deleted = TeacherLifebookStudent::where('teacher_id', $teacher->id)
            ->where('student_id', $studentId)
            ->delete();

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Anda bukan guru wali & lifebook untuk murid ini.'
            ], 404);
        }

        // Note: We keep the teacher_student relationship intact
        // because the teacher might still want to be a regular homeroom teacher
        // If you want to remove the teacher_student relationship as well, uncomment:
        // $teacher->students()->detach($studentId);

        $student = Student::find($studentId);

        return response()->json([
            'success' => true,
            'message' => "Anda tidak lagi menjadi Guru Wali & Guru Lifebook untuk {$student->name}."
        ]);
    }
}
