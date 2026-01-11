<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ParentJournal;
use App\Models\Student;
use App\Models\Teacher;

class ChildrenTrackerController extends Controller
{
    public function index(Request $request)
    {
        $isTeacher = Auth::guard('teacher')->check();
        $user = $isTeacher ? Auth::guard('teacher')->user() : Auth::user();

        $selectedDate = $request->get('date') ? Carbon::parse($request->get('date')) : Carbon::now()->subMonth()->startOfMonth();
        $selectedMonthName = $selectedDate->translatedFormat('F Y');
        $bulan = $selectedDate->translatedFormat('F');
        $tahun = $selectedDate->format('Y');

        $lifebookTeacherId = \App\Models\WebSetting::where('key', 'lifebook_teacher_id')->value('value');
        $isAdmin = !$isTeacher && $user && $user->role === 'admin';
        $isLifebookTeacher = $isTeacher && ($user->id == $lifebookTeacherId);

        $submissions = [];
        if ($isAdmin || $isLifebookTeacher) {
            $mainDb = config('database.connections.mysql.database');
            $userDb = config('database.connections.lifebook_users.database');

            $submissions = DB::table($mainDb . '.parent_journals as j')
                ->where('j.bulan', $bulan)
                ->where('j.tahun', $tahun)
                ->join($mainDb . '.users as p', 'j.user_id', '=', 'p.id')
                ->join($userDb . '.users as s', 'j.student_id', '=', 's.id')
                ->leftJoin($mainDb . '.teacher_student as ts', 'j.student_id', '=', 'ts.student_id')
                ->leftJoin($userDb . '.users as t', 'ts.teacher_id', '=', 't.id')
                ->select(
                    'j.id',
                    'j.student_id',
                    'p.name as parent_name',
                    's.name as student_name',
                    't.name as teacher_wali',
                    'j.parent_filled_at',
                    'j.teacher_reply',
                    'j.lifebook_teacher_reply'
                )
                ->orderBy('j.id')
                ->get()
                ->groupBy('id')
                ->map(function ($items) {
                    $first = $items->first();
                    $first->teacher_wali = $items->pluck('teacher_wali')->filter()->unique()->implode(', ');
                    return $first;
                });
        }

        $aspects = [
            'parent' => [
                'name' => 'Aspek Orang Tua',
                'desc' => 'Monitoring peran orang tua',
                'icon' => 'user',
                'color' => 'color-purple',
                'status' => 'unfilled',
                'route' => 'children-tracker.parent-aspect'
            ],
            'child' => [
                'name' => 'Aspek Anak',
                'desc' => 'Monitoring perkembangan anak',
                'icon' => 'users',
                'color' => 'color-orange',
                'status' => 'unfilled',
                'route' => '#'
            ],
            'internal_external' => [
                'name' => 'Aspek Internal/Eksternal',
                'desc' => 'Monitoring pertumbuhan karakter',
                'icon' => 'calendar',
                'color' => 'color-green',
                'status' => 'unfilled',
                'route' => '#'
            ]
        ];

        if (!$isTeacher && !$isAdmin) {
            foreach ($user->students as $student) {
                $j = ParentJournal::where('student_id', $student->id)
                    ->where('bulan', $bulan)
                    ->where('tahun', $tahun)
                    ->first();
                if ($j) {
                    if ($j->teacher_reply)
                        $aspects['parent']['status'] = 'replied';
                    else
                        $aspects['parent']['status'] = 'filled';
                }
            }
        }

        $appVersion = '1.2.0';

        return view('children-tracker.index', compact('aspects', 'selectedMonthName', 'selectedDate', 'isTeacher', 'isAdmin', 'isLifebookTeacher', 'submissions', 'appVersion'));
    }

    public function parentAspect(Request $request)
    {
        $isTeacher = Auth::guard('teacher')->check();
        $user = $isTeacher ? Auth::guard('teacher')->user() : Auth::user();

        $lifebookTeacherId = \App\Models\WebSetting::where('key', 'lifebook_teacher_id')->value('value');
        $activeLifebookTeacher = \App\Models\Teacher::find($lifebookTeacherId);
        $isAdmin = !$isTeacher && $user && $user->role === 'admin';
        $isLifebookTeacher = ($isTeacher && ($user->id == $lifebookTeacherId)) || $isAdmin;

        // If Lifebook Teacher, show ALL active students to allow system-wide confirmation
        // Otherwise show assigned students (for regular teachers) or own children (for parents)
        if ($isLifebookTeacher) {
            $children = Student::active()->orderBy('name')->get();
        } else {
            $children = $user->students;
        }

        $selectedMonthStr = $request->get('month', Carbon::now()->subMonth()->translatedFormat('F Y'));
        // Parse "Bulan Tahun" string
        $monthDate = Carbon::createFromFormat('F Y', $selectedMonthStr);
        $bulan = $monthDate->translatedFormat('F');
        $tahun = $monthDate->format('Y');

        $selectedChildId = $request->get('child_id', $children->first()->id ?? null);

        // Fetch journal by student, month, and year
        $journal = ParentJournal::where('student_id', $selectedChildId)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->first();

        $selectedMonth = $selectedMonthStr;

        // Fetch parent and teacher wali for display
        $mainDb = config('database.connections.mysql.database');
        $userDb = config('database.connections.lifebook_users.database');

        $childInfo = DB::table($userDb . '.users as s')
            ->where('s.id', $selectedChildId)
            ->leftJoin($mainDb . '.parent_student as ps', 's.id', '=', 'ps.student_id')
            ->leftJoin($mainDb . '.users as p', 'ps.user_id', '=', 'p.id')
            ->leftJoin($mainDb . '.teacher_student as ts', 's.id', '=', 'ts.student_id')
            ->leftJoin($userDb . '.users as t', 'ts.teacher_id', '=', 't.id')
            ->select('p.name as parent_name', 't.name as teacher_name')
            ->get();

        $parentName = $childInfo->pluck('parent_name')->filter()->first() ?: '-';
        $teacherWali = $childInfo->pluck('teacher_name')->filter()->unique()->implode(', ') ?: '-';

        return view('children-tracker.parent-aspect', compact('children', 'selectedMonth', 'selectedChildId', 'journal', 'activeLifebookTeacher', 'isTeacher', 'isLifebookTeacher', 'parentName', 'teacherWali'));
    }

    public function saveJournal(Request $request)
    {
        $isTeacher = Auth::guard('teacher')->check();
        $user = $isTeacher ? Auth::guard('teacher')->user() : Auth::user();

        $request->validate([
            'student_id' => 'required',
            'month_year' => 'required', // String like "Januari 2026"
            'field' => 'required|in:pendekatan,interaksi,teacher_reply,lifebook_teacher_reply',
            'value' => 'nullable|string'
        ]);

        // Manually parse Indonesian month name to avoid locale issues
        $monthYearParts = explode(' ', $request->month_year);
        $bulan = $monthYearParts[0] ?? '';
        $tahun = $monthYearParts[1] ?? '';

        if (!$bulan || !$tahun) {
            return response()->json(['success' => false, 'message' => 'Format bulan/tahun tidak valid.'], 422);
        }

        // Identify the parent ID for this student
        $parentId = DB::table('parent_student')
            ->where('student_id', $request->student_id)
            ->value('user_id');

        if (!$parentId) {
            return response()->json([
                'success' => false,
                'message' => 'Murid ini belum terhubung dengan akun orang tua. Mohon hubungkan terlebih dahulu melalui pendaftaran orang tua.'
            ], 422);
        }

        $isAdmin = !$isTeacher && $user && $user->role === 'admin';

        $data = [];
        if ($isTeacher) {
            if ($request->field === 'teacher_reply') {
                $data['teacher_id'] = $user->id;
                $data['teacher_name'] = $user->name;
                $data['teacher_reply'] = $request->value;
                $data['teacher_replied_at'] = now();
            } else if ($request->field === 'lifebook_teacher_reply') {
                $data['lifebook_teacher_id'] = $user->id;
                $data['lifebook_teacher_name'] = $user->name;
                $data['lifebook_teacher_reply'] = $request->value;
                $data['lifebook_teacher_replied_at'] = now();
            }
        } elseif ($isAdmin) {
            if ($request->field === 'lifebook_teacher_reply') {
                $data['lifebook_teacher_id'] = $user->id;
                $data['lifebook_teacher_name'] = $user->name . ' (Admin)';
                $data['lifebook_teacher_reply'] = $request->value;
                $data['lifebook_teacher_replied_at'] = now();
            } else {
                $data[$request->field] = $request->value;
                $data['parent_filled_at'] = now();
            }
        } else {
            // Parent
            $data[$request->field] = $request->value;
            $data['parent_filled_at'] = now();
        }

        try {
            $journal = ParentJournal::updateOrCreate(
                [
                    'user_id' => $parentId,
                    'student_id' => $request->student_id,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                ],
                $data
            );

            return response()->json(['success' => true, 'message' => 'Journal berhasil disimpan!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan ke database: ' . $e->getMessage()], 500);
        }
    }
}
