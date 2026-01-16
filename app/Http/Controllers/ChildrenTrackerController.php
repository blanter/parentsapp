<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ParentJournal;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\TeacherLifebookStudent;

class ChildrenTrackerController extends Controller
{
    /**
     * Get the lifebook teacher ID for a specific student
     * Returns specific lifebook teacher if assigned, otherwise returns default
     */
    private function getLifebookTeacherId($studentId)
    {
        // Check if student has a specific lifebook teacher
        $specificTeacher = TeacherLifebookStudent::where('student_id', $studentId)->first();

        if ($specificTeacher) {
            return $specificTeacher->teacher_id;
        }

        // Fall back to default lifebook teacher
        return \App\Models\WebSetting::where('key', 'lifebook_teacher_id')->value('value');
    }

    /**
     * Check if current teacher is the lifebook teacher for a student
     */
    private function isLifebookTeacherForStudent($teacherId, $studentId)
    {
        $lifebookTeacherId = $this->getLifebookTeacherId($studentId);
        return $teacherId == $lifebookTeacherId;
    }

    public function index(Request $request)
    {
        $isTeacher = Auth::guard('teacher')->check();
        $user = $isTeacher ? Auth::guard('teacher')->user() : Auth::user();

        // Time calculations
        $now = Carbon::now();
        $currentMonthName = $now->translatedFormat('F');
        $currentYear = $now->format('Y');
        $currentMonthYear = $now->translatedFormat('F Y');

        $quarterMap = [
            1 => 'Kuartal 1',
            2 => 'Kuartal 1',
            3 => 'Kuartal 1',
            4 => 'Kuartal 2',
            5 => 'Kuartal 2',
            6 => 'Kuartal 2',
            7 => 'Kuartal 3',
            8 => 'Kuartal 3',
            9 => 'Kuartal 3',
            10 => 'Kuartal 4',
            11 => 'Kuartal 4',
            12 => 'Kuartal 4'
        ];
        $currentQuarterName = $quarterMap[$now->month];
        $currentQuarterYear = $currentQuarterName . ' ' . $currentYear;

        $lifebookTeacherId = \App\Models\WebSetting::where('key', 'lifebook_teacher_id')->value('value');
        $isAdmin = !$isTeacher && $user && $user->role === 'admin';
        $isLifebookTeacher = $isTeacher && ($user->id == $lifebookTeacherId);

        $submissions = [];
        if ($isAdmin || $isTeacher) {
            $mainDb = config('database.connections.mysql.database');
            $userDb = config('database.connections.lifebook_users.database');

            // Fetch submissions for current Quarter (Parent/Child) OR current Month (Internal/External)
            $query = DB::table($mainDb . '.parent_journals as j')
                ->where('j.tahun', $currentYear)
                ->where(function ($q) use ($currentQuarterName, $currentMonthName) {
                    $q->where('j.bulan', $currentQuarterName)
                        ->orWhere('j.bulan', $currentMonthName);
                })
                ->join($mainDb . '.users as p', 'j.user_id', '=', 'p.id')
                ->join($userDb . '.users as s', 'j.student_id', '=', 's.id')
                ->leftJoin($mainDb . '.teacher_student as ts', 'j.student_id', '=', 'ts.student_id')
                ->leftJoin($userDb . '.users as t', 'ts.teacher_id', '=', 't.id');

            if ($isTeacher && !$isLifebookTeacher && !$isAdmin) {
                $query->where('ts.teacher_id', $user->id);
            }

            $submissions = $query->select(
                'j.id',
                'j.student_id',
                'j.bulan',
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

                    $first->parent_aspect_filled = $first->parent_filled_at != null;
                    $first->child_aspect_filled = $first->child_filled_at != null;
                    $first->internal_external_filled = $first->internal_external_filled_at != null;

                    return $first;
                });
        }

        // Collect unique students from submissions with their response status for teacher filter
        $studentsWithStatus = collect();
        if ($isAdmin || $isTeacher) {
            $studentMap = [];
            foreach ($submissions as $sub) {
                $studentId = $sub->student_id;
                if (!isset($studentMap[$studentId])) {
                    $studentMap[$studentId] = [
                        'id' => $studentId,
                        'name' => $sub->student_name,
                        'pending_count' => 0,
                        'responded_count' => 0,
                    ];
                }

                $isQuarterly = str_contains($sub->bulan, 'Kuartal');

                // Check parent aspect response status
                if ($sub->parent_aspect_filled && ($isQuarterly || $sub->bulan == 'Orang Tua')) {
                    if ($sub->lifebook_teacher_reply) {
                        $studentMap[$studentId]['responded_count']++;
                    } elseif (!$sub->teacher_reply) {
                        $studentMap[$studentId]['pending_count']++;
                    } else {
                        // Has teacher_reply but not lifebook_teacher_reply
                        $studentMap[$studentId]['pending_count']++;
                    }
                }

                // Check child aspect response status
                if ($sub->child_aspect_filled && $isQuarterly) {
                    if ($sub->lifebook_child_reply) {
                        $studentMap[$studentId]['responded_count']++;
                    } elseif (!$sub->teacher_report) {
                        $studentMap[$studentId]['pending_count']++;
                    } else {
                        $studentMap[$studentId]['pending_count']++;
                    }
                }

                // Check internal/external aspect response status
                if ($sub->internal_external_filled && !$isQuarterly) {
                    if ($sub->strategi_baru) {
                        $studentMap[$studentId]['responded_count']++;
                    } elseif (!$sub->internal_teacher_reply && !$sub->external_teacher_reply) {
                        $studentMap[$studentId]['pending_count']++;
                    } else {
                        $studentMap[$studentId]['pending_count']++;
                    }
                }
            }

            // Convert to collection with status
            foreach ($studentMap as $studentId => $data) {
                $status = 'none'; // No submissions
                if ($data['pending_count'] > 0) {
                    $status = 'pending';
                } elseif ($data['responded_count'] > 0) {
                    $status = 'responded';
                }

                $studentsWithStatus->push((object) [
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'status' => $status,
                    'pending_count' => $data['pending_count'],
                    'responded_count' => $data['responded_count'],
                ]);
            }

            $studentsWithStatus = $studentsWithStatus->sortBy('name')->values();
        }

        $aspects = [
            'parent' => [
                'name' => 'Aspek Orang Tua',
                'desc' => 'Monitoring peran orang tua (Kuartalan)',
                'icon' => 'user',
                'color' => 'color-purple',
                'status' => 'unfilled',
                'route' => 'children-tracker.parent-aspect',
                'time_label' => $currentQuarterYear
            ],
            'child' => [
                'name' => 'Aspek Anak',
                'desc' => 'Monitoring perkembangan anak (Kuartalan)',
                'icon' => 'users',
                'color' => 'color-orange',
                'status' => 'unfilled',
                'route' => 'children-tracker.child-aspect',
                'time_label' => $currentQuarterYear
            ],
            'internal_external' => [
                'name' => 'Aspek Internal/Eksternal',
                'desc' => 'Monitoring pertumbuhan karakter (Bulanan)',
                'icon' => 'calendar',
                'color' => 'color-green',
                'status' => 'unfilled',
                'route' => 'children-tracker.internal-external-aspect',
                'time_label' => $currentMonthYear
            ]
        ];

        if (!$isTeacher && !$isAdmin) {
            foreach ($user->students as $student) {
                // Check Quarterly status
                $qj = ParentJournal::where('student_id', $student->id)
                    ->where('bulan', $currentQuarterName)
                    ->where('tahun', $currentYear)
                    ->first();
                if ($qj) {
                    if ($qj->teacher_reply)
                        $aspects['parent']['status'] = 'replied';
                    else if ($qj->parent_filled_at)
                        $aspects['parent']['status'] = 'filled';

                    if ($qj->teacher_report)
                        $aspects['child']['status'] = 'replied';
                    else if ($qj->child_filled_at)
                        $aspects['child']['status'] = 'filled';
                }

                // Check Monthly status
                $mj = ParentJournal::where('student_id', $student->id)
                    ->where('bulan', $currentMonthName)
                    ->where('tahun', $currentYear)
                    ->first();
                if ($mj) {
                    if ($mj->internal_teacher_reply || $mj->external_teacher_reply)
                        $aspects['internal_external']['status'] = 'replied';
                    else if ($mj->internal_external_filled_at)
                        $aspects['internal_external']['status'] = 'filled';
                }
            }
        }

        $appVersion = \App\Models\WebSetting::where('key', 'app_version')->value('value') ?? '1.2.0';

        return view('children-tracker.index', compact('aspects', 'isTeacher', 'isAdmin', 'isLifebookTeacher', 'submissions', 'studentsWithStatus', 'appVersion', 'currentMonthYear', 'currentQuarterYear', 'currentYear'));
    }

    public function parentAspect(Request $request)
    {
        return $this->loadAspectView($request, 'children-tracker.parent-aspect', true);
    }

    public function childAspect(Request $request)
    {
        return $this->loadAspectView($request, 'children-tracker.child-aspect', true);
    }

    public function internalExternalAspect(Request $request)
    {
        return $this->loadAspectView($request, 'children-tracker.internal-external-aspect', false);
    }

    private function loadAspectView(Request $request, $viewName, $isQuarterly = false)
    {
        $isTeacher = Auth::guard('teacher')->check();
        $user = $isTeacher ? Auth::guard('teacher')->user() : Auth::user();

        $defaultLifebookTeacherId = \App\Models\WebSetting::where('key', 'lifebook_teacher_id')->value('value');
        $activeLifebookTeacher = \App\Models\Teacher::find($defaultLifebookTeacherId);
        $isAdmin = !$isTeacher && $user && $user->role === 'admin';

        // For initial check, use default lifebook teacher
        $isDefaultLifebookTeacher = ($isTeacher && ($user->id == $defaultLifebookTeacherId)) || $isAdmin;

        if ($isDefaultLifebookTeacher) {
            $children = Student::active()->orderBy('name')->get();
        } else {
            $children = $user->students;
        }

        if ($isQuarterly) {
            // Default to current quarter
            $now = Carbon::now();
            $quarterMap = [1 => 'Kuartal 1', 2 => 'Kuartal 1', 3 => 'Kuartal 1', 4 => 'Kuartal 2', 5 => 'Kuartal 2', 6 => 'Kuartal 2', 7 => 'Kuartal 3', 8 => 'Kuartal 3', 9 => 'Kuartal 3', 10 => 'Kuartal 4', 11 => 'Kuartal 4', 12 => 'Kuartal 4'];
            $defaultQuarter = $quarterMap[$now->month] . ' ' . $now->year;
            $selectedTimeStr = $request->get('time', $defaultQuarter);

            $parts = explode(' ', $selectedTimeStr);
            $bulan = $parts[0] . ' ' . ($parts[1] ?? ''); // e.g. "Kuartal 1"
            $tahun = $parts[2] ?? $now->year;
        } else {
            // Default to current month
            $selectedTimeStr = $request->get('month', Carbon::now()->translatedFormat('F Y'));
            $monthDate = Carbon::createFromFormat('F Y', $selectedTimeStr);
            $bulan = $monthDate->translatedFormat('F');
            $tahun = $monthDate->format('Y');
        }

        $selectedChildId = $request->get('child_id', $children->first()->id ?? null);

        // Get the actual lifebook teacher for THIS specific student
        $lifebookTeacherIdForStudent = $this->getLifebookTeacherId($selectedChildId);
        $activeLifebookTeacher = \App\Models\Teacher::find($lifebookTeacherIdForStudent);

        // Check if current teacher is the lifebook teacher for THIS specific student
        $isLifebookTeacher = $isAdmin || ($isTeacher && $this->isLifebookTeacherForStudent($user->id, $selectedChildId));

        $journal = ParentJournal::where('student_id', $selectedChildId)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->first();

        $selectedTime = $selectedTimeStr;

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

        return view($viewName, compact('children', 'selectedTime', 'selectedChildId', 'journal', 'activeLifebookTeacher', 'isTeacher', 'isLifebookTeacher', 'parentName', 'teacherWali'));
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
            'data' => 'required|array'
        ]);

        $monthYearParts = explode(' ', $request->month_year);
        if (($monthYearParts[0] ?? '') === 'Kuartal') {
            $bulan = ($monthYearParts[0] ?? '') . ' ' . ($monthYearParts[1] ?? '');
            $tahun = $monthYearParts[2] ?? '';
        } else {
            $bulan = $monthYearParts[0] ?? '';
            $tahun = $monthYearParts[1] ?? '';
        }

        if (!$bulan || !$tahun) {
            return response()->json(['success' => false, 'message' => 'Format waktu tidak valid.'], 422);
        }

        $parentId = DB::table('parent_student')->where('student_id', $request->student_id)->value('user_id');
        if (!$parentId) {
            return response()->json(['success' => false, 'message' => 'Murid ini belum terhubung dengan akun orang tua.'], 422);
        }

        $isAdmin = !$isTeacher && $user && $user->role === 'admin';

        // Check if current teacher is the lifebook teacher for THIS specific student
        $isLifebookTeacher = $isAdmin || ($isTeacher && $this->isLifebookTeacherForStudent($user->id, $request->student_id));

        $updateData = [];
        $childParentFields = ['rutinitas', 'hubungan_keluarga', 'hubungan_teman', 'aspek_sosial'];
        $internalExternalParentFields = ['aspek_internal', 'aspek_external', 'strategi_parent_reply'];

        foreach ($request->data as $field => $value) {
            if (!in_array($field, $validFields))
                continue;

            if ($isTeacher) {
                if ($field === 'teacher_reply' && $value) {
                    $updateData['teacher_id'] = $user->id;
                    $updateData['teacher_name'] = $user->name;
                    $updateData['teacher_reply'] = $value;
                    $updateData['teacher_replied_at'] = now();
                } else if ($field === 'teacher_report' && $value) {
                    $updateData['teacher_name'] = $user->name;
                    $updateData['teacher_report'] = $value;
                    $updateData['teacher_report_at'] = now();
                } else if ($field === 'internal_teacher_reply' && $value) {
                    $updateData['teacher_name'] = $user->name;
                    $updateData['internal_teacher_reply'] = $value;
                    $updateData['internal_teacher_replied_at'] = now();
                } else if ($field === 'external_teacher_reply' && $value) {
                    $updateData['teacher_name'] = $user->name;
                    $updateData['external_teacher_reply'] = $value;
                    $updateData['external_teacher_replied_at'] = now();
                } else if ($isLifebookTeacher) {
                    if ($field === 'lifebook_teacher_reply' && $value) {
                        $updateData['lifebook_teacher_id'] = $user->id;
                        $updateData['lifebook_teacher_name'] = $user->name;
                        $updateData['lifebook_teacher_reply'] = $value;
                        $updateData['lifebook_teacher_replied_at'] = now();
                    } else if ($field === 'lifebook_child_reply' && $value) {
                        $updateData['lifebook_teacher_id'] = $user->id;
                        $updateData['lifebook_teacher_name'] = $user->name;
                        $updateData['lifebook_child_reply'] = $value;
                        $updateData['lifebook_child_replied_at'] = now();
                    } else if ($field === 'strategi_baru' && $value) {
                        $updateData['lifebook_teacher_id'] = $user->id;
                        $updateData['lifebook_teacher_name'] = $user->name;
                        $updateData['strategi_baru'] = $value;
                        $updateData['lifebook_strategy_at'] = now();
                    }
                }
            } elseif ($isAdmin) {
                if (in_array($field, ['lifebook_teacher_reply', 'lifebook_child_reply', 'strategi_baru'])) {
                    $updateData['lifebook_teacher_id'] = $user->id;
                    $updateData['lifebook_teacher_name'] = $user->name . ' (Admin)';
                    if ($field === 'lifebook_teacher_reply' && $value) {
                        $updateData['lifebook_teacher_reply'] = $value;
                        $updateData['lifebook_teacher_replied_at'] = now();
                    } else if ($field === 'lifebook_child_reply' && $value) {
                        $updateData['lifebook_child_reply'] = $value;
                        $updateData['lifebook_child_replied_at'] = now();
                    } else if ($value) {
                        $updateData['strategi_baru'] = $value;
                        $updateData['lifebook_strategy_at'] = now();
                    }
                } else {
                    $updateData[$field] = $value;
                    if ($value) {
                        if (in_array($field, $childParentFields))
                            $updateData['child_filled_at'] = now();
                        else if (in_array($field, $internalExternalParentFields))
                            $updateData['internal_external_filled_at'] = now();
                        else
                            $updateData['parent_filled_at'] = now();
                    }
                }
            } else {
                // Parent
                $updateData[$field] = $value;
                if ($value) {
                    if (in_array($field, $childParentFields))
                        $updateData['child_filled_at'] = now();
                    else if (in_array($field, $internalExternalParentFields))
                        $updateData['internal_external_filled_at'] = now();
                    else
                        $updateData['parent_filled_at'] = now();
                }
            }
        }

        if (empty($updateData)) {
            return response()->json(['success' => true, 'message' => 'Tidak ada data untuk disimpan.']);
        }

        try {
            ParentJournal::updateOrCreate(
                ['user_id' => $parentId, 'student_id' => $request->student_id, 'bulan' => $bulan, 'tahun' => $tahun],
                $updateData
            );

            // Scoring for Parents
            $pointsEarned = 0;
            if (!$isTeacher && !$isAdmin) {
                $student = Student::find($request->student_id);
                $studentName = $student ? $student->name : 'Murid';

                // Check which aspect was filled
                $aspectScoreType = null;
                $aspectTitle = "";

                if (isset($updateData['parent_filled_at'])) {
                    $aspectScoreType = 'Parent Aspect';
                    $aspectTitle = "Aspek Orang Tua ($studentName)";
                } else if (isset($updateData['child_filled_at'])) {
                    $aspectScoreType = 'Child Aspect';
                    $aspectTitle = "Aspek Anak ($studentName)";
                } else if (isset($updateData['internal_external_filled_at'])) {
                    $aspectScoreType = 'Internal/External Aspect';
                    $aspectTitle = "Aspek Internal/Eksternal ($studentName)";
                }

                if ($aspectScoreType) {
                    $scoreDesc = "Journal: $aspectTitle ($bulan $tahun)";
                    // Check if already scored for this specific aspect, journal and month
                    $existingScore = \App\Models\Score::where('user_id', Auth::id())
                        ->where('activity', 'Journaling Parents')
                        ->where('deskripsi', $scoreDesc)
                        ->first();

                    if (!$existingScore) {
                        \App\Models\Score::create([
                            'user_id' => Auth::id(),
                            'activity' => 'Journaling Parents',
                            'score' => 100,
                            'deskripsi' => $scoreDesc
                        ]);
                        $pointsEarned = 100;
                    }
                }
            }

            $message = 'Journal berhasil disimpan!';
            if ($pointsEarned > 0) {
                $message .= " Anda mendapatkan $pointsEarned poin!";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'earned_points' => $pointsEarned
            ]);
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
