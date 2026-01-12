<?php

namespace App\Http\Controllers;

use App\Models\ParentJournal;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminChildrenTrackerController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $currentYear = $now->format('Y');
        $currentMonthName = $now->translatedFormat('F');

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

        $mainDb = config('database.connections.mysql.database');
        $userDb = config('database.connections.lifebook_users.database');

        // Get all submissions for current quarter and month
        $submissions = DB::table($mainDb . '.parent_journals as j')
            ->where('j.tahun', $currentYear)
            ->where(function ($q) use ($currentQuarterName, $currentMonthName) {
                $q->where('j.bulan', $currentQuarterName)
                    ->orWhere('j.bulan', $currentMonthName);
            })
            ->join($mainDb . '.users as p', 'j.user_id', '=', 'p.id')
            ->join($userDb . '.users as s', 'j.student_id', '=', 's.id')
            ->leftJoin($mainDb . '.teacher_student as ts', 'j.student_id', '=', 'ts.student_id')
            ->leftJoin($userDb . '.users as t', 'ts.teacher_id', '=', 't.id')
            ->select(
                'j.id',
                'j.student_id',
                'j.bulan',
                'p.name as parent_name',
                'p.id as parent_id',
                'p.email as parent_email',
                's.name as student_name',
                's.image as student_avatar',
                't.name as teacher_wali',
                't.id as teacher_id',
                'j.parent_filled_at',
                'j.teacher_reply',
                'j.lifebook_teacher_reply',
                'j.child_filled_at',
                'j.teacher_report',
                'j.lifebook_child_reply',
                'j.internal_external_filled_at',
                'j.internal_teacher_reply',
                'j.external_teacher_reply'
            )
            ->orderBy('j.id')
            ->get()
            ->groupBy('id')
            ->map(function ($items) {
                $first = $items->first();
                $first->teacher_wali = $items->pluck('teacher_wali')->filter()->unique()->implode(', ');

                // Calculate completion status
                $first->parent_aspect_filled = $first->parent_filled_at != null;
                $first->child_aspect_filled = $first->child_filled_at != null;
                $first->internal_external_filled = $first->internal_external_filled_at != null;

                $first->parent_aspect_replied = $first->teacher_reply != null || $first->lifebook_teacher_reply != null;
                $first->child_aspect_replied = $first->teacher_report != null || $first->lifebook_child_reply != null;
                $first->internal_external_replied = $first->internal_teacher_reply != null || $first->external_teacher_reply != null;

                return $first;
            });

        // Calculate statistics
        $totalSubmissions = $submissions->count();
        $parentAspectFilled = $submissions->where('parent_aspect_filled', true)->count();
        $childAspectFilled = $submissions->where('child_aspect_filled', true)->count();
        $internalExternalFilled = $submissions->where('internal_external_filled', true)->count();

        $totalReplied = $submissions->filter(function ($s) {
            return $s->parent_aspect_replied || $s->child_aspect_replied || $s->internal_external_replied;
        })->count();

        // Get unique users count
        $activeParents = $submissions->pluck('parent_id')->unique()->count();
        $activeStudents = $submissions->pluck('student_id')->unique()->count();
        $activeTeachers = $submissions->pluck('teacher_id')->filter()->unique()->count();

        return view('admin.children-tracker', compact(
            'submissions',
            'totalSubmissions',
            'parentAspectFilled',
            'childAspectFilled',
            'internalExternalFilled',
            'totalReplied',
            'activeParents',
            'activeStudents',
            'activeTeachers',
            'currentQuarterName',
            'currentMonthName',
            'currentYear'
        ));
    }
    public function show($id)
    {
        $mainDb = config('database.connections.mysql.database');
        $userDb = config('database.connections.lifebook_users.database');

        $journal = DB::table($mainDb . '.parent_journals as j')
            ->where('j.id', $id)
            ->join($mainDb . '.users as p', 'j.user_id', '=', 'p.id')
            ->join($userDb . '.users as s', 'j.student_id', '=', 's.id')
            ->leftJoin($mainDb . '.teacher_student as ts', 'j.student_id', '=', 'ts.student_id')
            ->leftJoin($userDb . '.users as t', 'ts.teacher_id', '=', 't.id')
            ->select(
                'j.*',
                'p.name as parent_name',
                's.name as student_name',
                's.image as student_avatar',
                't.name as teacher_name'
            )
            ->first();

        if (!$journal) {
            return response()->json(['success' => false, 'message' => 'Journal not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $journal
        ]);
    }
}
