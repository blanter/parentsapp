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
        $selectedDate = $request->get('date') ? Carbon::parse($request->get('date')) : Carbon::now()->subMonth()->startOfMonth();
        $selectedMonthName = $selectedDate->translatedFormat('F Y');

        // Mock alerts for previous months (prior to selectedDate)
        $alerts = [
            [
                'type' => 'warning',
                'message' => 'Jurnal November 2025 belum lengkap diisi.',
                'icon' => 'alert-circle'
            ],
            [
                'type' => 'info',
                'message' => 'Ada balasan guru pada jurnal Oktober 2025.',
                'icon' => 'message-square'
            ]
        ];

        // Statuses for the 3 main aspects based on selected month
        $aspects = [
            'parent' => [
                'name' => 'Aspek Orang Tua',
                'desc' => 'Monitoring peran orang tua',
                'icon' => 'user',
                'color' => 'color-purple',
                'status' => 'replied',
                'route' => 'children-tracker.parent-aspect'
            ],
            'child' => [
                'name' => 'Aspek Anak',
                'desc' => 'Monitoring perkembangan anak',
                'icon' => 'users',
                'color' => 'color-orange',
                'status' => 'filled',
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

        return view('children-tracker.index', compact('aspects', 'selectedMonthName', 'selectedDate', 'alerts'));
    }

    public function parentAspect(Request $request)
    {
        $isTeacher = Auth::guard('teacher')->check();
        $user = $isTeacher ? Auth::guard('teacher')->user() : Auth::user();

        $children = $user->students;

        $selectedMonthStr = $request->get('month', Carbon::now()->subMonth()->translatedFormat('F Y'));
        // Parse "Bulan Tahun" string
        $monthDate = Carbon::createFromFormat('F Y', $selectedMonthStr);
        $bulan = $monthDate->translatedFormat('F');
        $tahun = $monthDate->format('Y');

        $selectedChildId = $request->get('child_id', $children->first()->id ?? null);

        // Fetch journal by student, month, and year
        // We find the journal for this student. There should only be one per student per month.
        $journal = ParentJournal::where('student_id', $selectedChildId)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->first();

        $selectedMonth = $selectedMonthStr;

        $lifebookTeacherId = \App\Models\WebSetting::where('key', 'lifebook_teacher_id')->value('value');
        $activeLifebookTeacher = \App\Models\Teacher::find($lifebookTeacherId);

        return view('children-tracker.parent-aspect', compact('children', 'selectedMonth', 'selectedChildId', 'journal', 'activeLifebookTeacher', 'isTeacher'));
    }

    public function saveJournal(Request $request)
    {
        $isTeacher = Auth::guard('teacher')->check();
        $user = $isTeacher ? Auth::guard('teacher')->user() : Auth::user();

        $request->validate([
            'student_id' => 'required',
            'month_year' => 'required',
            'field' => 'required|in:pendekatan,interaksi,teacher_reply,lifebook_teacher_reply',
            'value' => 'nullable|string'
        ]);

        $monthDate = Carbon::createFromFormat('F Y', $request->month_year);
        $bulan = $monthDate->translatedFormat('F');
        $tahun = $monthDate->format('Y');

        // Identify the parent ID for this student if it's a teacher saving
        $parentId = null;
        if (!$isTeacher) {
            $parentId = $user->id;
        } else {
            // Find the parent associated with this student
            $parentId = DB::table('parent_student')
                ->where('student_id', $request->student_id)
                ->value('user_id');

            // If no parent yet, we might have an issue with the unique constraint 
            // but let's assume one parent exists as per rules.
        }

        $data = [];
        if (!$isTeacher) {
            $data[$request->field] = $request->value;
            $data['parent_filled_at'] = now();
        } else {
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
        }

        if (!$parentId && $isTeacher) {
            return response()->json(['success' => false, 'message' => 'Orang tua murid belum terdaftar.'], 422);
        }

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
    }
}
