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
        if ($isAdmin || $isTeacher) {
            $mainDb = config('database.connections.mysql.database');
            $userDb = config('database.connections.lifebook_users.database');

            $query = DB::table($mainDb . '.parent_journals as j')
                ->where('j.bulan', $bulan)
                ->where('j.tahun', $tahun)
                ->join($mainDb . '.users as p', 'j.user_id', '=', 'p.id')
                ->join($userDb . '.users as s', 'j.student_id', '=', 's.id')
                ->leftJoin($mainDb . '.teacher_student as ts', 'j.student_id', '=', 'ts.student_id')
                ->leftJoin($userDb . '.users as t', 'ts.teacher_id', '=', 't.id');

            // Regular teacher only sees their assigned students' journals
            if ($isTeacher && !$isLifebookTeacher && !$isAdmin) {
                $query->where('ts.teacher_id', $user->id);
            }

            $submissions = $query->select(
                'j.id',
                'j.student_id',
                'p.name as parent_name',
                's.name as student_name',
                't.name as teacher_wali',
                'j.parent_filled_at',
                'j.teacher_reply',
                'j.lifebook_teacher_reply',
                'j.rutinitas',
                'j.child_filled_at',
                'j.teacher_report',
                'j.lifebook_child_reply',
                'j.aspek_internal',
                'j.aspek_external',
                'j.internal_external_filled_at',
                'j.internal_teacher_reply',
                'j.external_teacher_reply',
                'j.strategi_baru'
            )
                ->orderBy('j.id')
                ->get()
                ->groupBy('id')
                ->map(function ($items) {
                    $first = $items->first();
                    $first->teacher_wali = $items->pluck('teacher_wali')->filter()->unique()->implode(', ');

                    // Count filled aspects for this journal
                    $first->parent_aspect_filled = $first->parent_filled_at != null;
                    $first->child_aspect_filled = $first->child_filled_at != null;
                    $first->internal_external_filled = $first->internal_external_filled_at != null;

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
                'route' => 'children-tracker.child-aspect'
            ],
            'internal_external' => [
                'name' => 'Aspek Internal/Eksternal',
                'desc' => 'Monitoring pertumbuhan karakter',
                'icon' => 'calendar',
                'color' => 'color-green',
                'status' => 'unfilled',
                'route' => 'children-tracker.internal-external-aspect'
            ]
        ];

        if (!$isTeacher && !$isAdmin) {
            foreach ($user->students as $student) {
                $j = ParentJournal::where('student_id', $student->id)
                    ->where('bulan', $bulan)
                    ->where('tahun', $tahun)
                    ->first();
                if ($j) {
                    // Parent Aspect Status
                    if ($j->teacher_reply)
                        $aspects['parent']['status'] = 'replied';
                    else if ($j->parent_filled_at)
                        $aspects['parent']['status'] = 'filled';

                    // Child Aspect Status
                    if ($j->teacher_report)
                        $aspects['child']['status'] = 'replied';
                    else if ($j->child_filled_at)
                        $aspects['child']['status'] = 'filled';

                    // Internal/External Status
                    if ($j->internal_teacher_reply || $j->external_teacher_reply)
                        $aspects['internal_external']['status'] = 'replied';
                    else if ($j->internal_external_filled_at)
                        $aspects['internal_external']['status'] = 'filled';
                }
            }
        }

        $appVersion = \App\Models\WebSetting::where('key', 'app_version')->value('value') ?? '1.2.0';

        return view('children-tracker.index', compact('aspects', 'selectedMonthName', 'selectedDate', 'isTeacher', 'isAdmin', 'isLifebookTeacher', 'submissions', 'appVersion'));
    }

    public function parentAspect(Request $request)
    {
        return $this->loadAspectView($request, 'children-tracker.parent-aspect');
    }

    public function childAspect(Request $request)
    {
        return $this->loadAspectView($request, 'children-tracker.child-aspect');
    }

    public function internalExternalAspect(Request $request)
    {
        return $this->loadAspectView($request, 'children-tracker.internal-external-aspect');
    }

    private function loadAspectView(Request $request, $viewName)
    {
        $isTeacher = Auth::guard('teacher')->check();
        $user = $isTeacher ? Auth::guard('teacher')->user() : Auth::user();

        $lifebookTeacherId = \App\Models\WebSetting::where('key', 'lifebook_teacher_id')->value('value');
        $activeLifebookTeacher = \App\Models\Teacher::find($lifebookTeacherId);
        $isAdmin = !$isTeacher && $user && $user->role === 'admin';
        $isLifebookTeacher = ($isTeacher && ($user->id == $lifebookTeacherId)) || $isAdmin;

        if ($isLifebookTeacher) {
            $children = Student::active()->orderBy('name')->get();
        } else {
            $children = $user->students;
        }

        $selectedMonthStr = $request->get('month', Carbon::now()->subMonth()->translatedFormat('F Y'));
        $monthDate = Carbon::createFromFormat('F Y', $selectedMonthStr);
        $bulan = $monthDate->translatedFormat('F');
        $tahun = $monthDate->format('Y');

        $selectedChildId = $request->get('child_id', $children->first()->id ?? null);

        $journal = ParentJournal::where('student_id', $selectedChildId)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->first();

        $selectedMonth = $selectedMonthStr;

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

        return view($viewName, compact('children', 'selectedMonth', 'selectedChildId', 'journal', 'activeLifebookTeacher', 'isTeacher', 'isLifebookTeacher', 'parentName', 'teacherWali'));
    }

    public function saveJournal(Request $request)
    {
        $isTeacher = Auth::guard('teacher')->check();
        $user = $isTeacher ? Auth::guard('teacher')->user() : Auth::user();

        $validFields = [
            'pendekatan',
            'interaksi',
            'teacher_reply',
            'lifebook_teacher_reply',
            'rutinitas',
            'hubungan_keluarga',
            'hubungan_teman',
            'aspek_sosial',
            'teacher_report',
            'lifebook_child_reply',
            'aspek_internal',
            'internal_teacher_reply',
            'aspek_external',
            'external_teacher_reply',
            'strategi_baru',
            'strategi_parent_reply'
        ];

        $request->validate([
            'student_id' => 'required',
            'month_year' => 'required',
            'field' => 'required|in:' . implode(',', $validFields),
            'value' => 'nullable|string'
        ]);

        $monthYearParts = explode(' ', $request->month_year);
        $bulan = $monthYearParts[0] ?? '';
        $tahun = $monthYearParts[1] ?? '';

        if (!$bulan || !$tahun) {
            return response()->json(['success' => false, 'message' => 'Format bulan/tahun tidak valid.'], 422);
        }

        $parentId = DB::table('parent_student')
            ->where('student_id', $request->student_id)
            ->value('user_id');

        if (!$parentId) {
            return response()->json(['success' => false, 'message' => 'Murid ini belum terhubung dengan akun orang tua.'], 422);
        }

        $isAdmin = !$isTeacher && $user && $user->role === 'admin';
        $lifebookTeacherId = \App\Models\WebSetting::where('key', 'lifebook_teacher_id')->value('value');
        $isLifebookTeacher = ($isTeacher && ($user->id == $lifebookTeacherId)) || $isAdmin;

        $data = [];
        $childParentFields = ['rutinitas', 'hubungan_keluarga', 'hubungan_teman', 'aspek_sosial'];
        $internalExternalParentFields = ['aspek_internal', 'aspek_external', 'strategi_parent_reply'];

        if ($isTeacher) {
            $data['teacher_name'] = $user->name;
            if ($request->field === 'teacher_reply') {
                $data['teacher_id'] = $user->id;
                $data['teacher_reply'] = $request->value;
                $data['teacher_replied_at'] = now();
            } else if ($request->field === 'teacher_report') {
                $data['teacher_report'] = $request->value;
                $data['teacher_report_at'] = now();
            } else if ($request->field === 'internal_teacher_reply') {
                $data['internal_teacher_reply'] = $request->value;
                $data['internal_teacher_replied_at'] = now();
            } else if ($request->field === 'external_teacher_reply') {
                $data['external_teacher_reply'] = $request->value;
                $data['external_teacher_replied_at'] = now();
            } else if ($isLifebookTeacher) {
                if ($request->field === 'lifebook_teacher_reply') {
                    $data['lifebook_teacher_id'] = $user->id;
                    $data['lifebook_teacher_name'] = $user->name;
                    $data['lifebook_teacher_reply'] = $request->value;
                    $data['lifebook_teacher_replied_at'] = now();
                } else if ($request->field === 'lifebook_child_reply') {
                    $data['lifebook_teacher_id'] = $user->id;
                    $data['lifebook_teacher_name'] = $user->name;
                    $data['lifebook_child_reply'] = $request->value;
                    $data['lifebook_child_replied_at'] = now();
                } else if ($request->field === 'strategi_baru') {
                    $data['lifebook_teacher_id'] = $user->id;
                    $data['lifebook_teacher_name'] = $user->name;
                    $data['strategi_baru'] = $request->value;
                    $data['lifebook_strategy_at'] = now();
                }
            }
        } elseif ($isAdmin) {
            if (in_array($request->field, ['lifebook_teacher_reply', 'lifebook_child_reply', 'strategi_baru'])) {
                $data['lifebook_teacher_id'] = $user->id;
                $data['lifebook_teacher_name'] = $user->name . ' (Admin)';
                if ($request->field === 'lifebook_teacher_reply') {
                    $data['lifebook_teacher_reply'] = $request->value;
                    $data['lifebook_teacher_replied_at'] = now();
                } else if ($request->field === 'lifebook_child_reply') {
                    $data['lifebook_child_reply'] = $request->value;
                    $data['lifebook_child_replied_at'] = now();
                } else {
                    $data['strategi_baru'] = $request->value;
                    $data['lifebook_strategy_at'] = now();
                }
            } else {
                $data[$request->field] = $request->value;
                if (in_array($request->field, $childParentFields))
                    $data['child_filled_at'] = now();
                else if (in_array($request->field, $internalExternalParentFields))
                    $data['internal_external_filled_at'] = now();
                else
                    $data['parent_filled_at'] = now();
            }
        } else {
            $data[$request->field] = $request->value;
            if (in_array($request->field, $childParentFields))
                $data['child_filled_at'] = now();
            else if (in_array($request->field, $internalExternalParentFields))
                $data['internal_external_filled_at'] = now();
            else
                $data['parent_filled_at'] = now();
        }

        try {
            ParentJournal::updateOrCreate(
                ['user_id' => $parentId, 'student_id' => $request->student_id, 'bulan' => $bulan, 'tahun' => $tahun],
                $data
            );
            return response()->json(['success' => true, 'message' => 'Journal berhasil disimpan!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }

    public function saveReflection(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'month_year' => 'required',
            'ratings' => 'required|array'
        ]);

        $monthYearParts = explode(' ', $request->month_year);
        $bulan = $monthYearParts[0] ?? '';
        $tahun = $monthYearParts[1] ?? '';

        $parentId = DB::table('parent_student')->where('student_id', $request->student_id)->value('user_id');
        if (!$parentId)
            return response()->json(['success' => false], 422);

        $data = $request->ratings;
        $data['refleksi_filled_at'] = now();

        try {
            ParentJournal::updateOrCreate(
                ['user_id' => $parentId, 'student_id' => $request->student_id, 'bulan' => $bulan, 'tahun' => $tahun],
                $data
            );
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }
}
