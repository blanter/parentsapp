<?php

namespace App\Http\Controllers;

use App\Models\LearningProject;
use App\Models\LearningLog;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminLearningTrackerController extends Controller
{
    public function index()
    {
        $projects = LearningProject::with(['teacher', 'logs'])
            ->latest()
            ->get()
            ->map(function ($project) {
                // Get students and their parents
                $projectStudents = DB::table('learning_project_student as lps')
                    ->where('lps.learning_project_id', $project->id)
                    ->join(config('database.connections.lifebook_users.database') . '.users as s', 'lps.student_id', '=', 's.id')
                    ->leftJoin('parent_student as ps', 'lps.student_id', '=', 'ps.student_id')
                    ->leftJoin('users as p', 'ps.user_id', '=', 'p.id')
                    ->select('s.name as student_name', 'p.name as parent_name')
                    ->get();
                
                $project->student_details = $projectStudents;
                $project->student_count = $projectStudents->count();
                $project->last_activity = $project->logs->max('created_at');
                $project->log_count = $project->logs->count();
                
                return $project;
            });

        // Statistics
        $totalProjects = $projects->count();
        $totalLogs = LearningLog::count();
        $activeTeachers = LearningProject::distinct('teacher_id')->count('teacher_id');
        $activeStudents = DB::table('learning_project_student')->distinct('student_id')->count('student_id');
        $activeParents = LearningLog::whereNotNull('user_id')->distinct('user_id')->count('user_id');
        $avgProgress = $projects->avg('progress_percentage') ?? 0;

        return view('admin.learning-tracker', compact(
            'projects',
            'totalProjects',
            'totalLogs',
            'activeTeachers',
            'activeStudents',
            'activeParents',
            'avgProgress'
        ));
    }

    public function show($id)
    {
        $project = LearningProject::with(['teacher', 'logs.teacher', 'logs.user'])->findOrFail($id);

        $studentIds = DB::table('learning_project_student')
            ->where('learning_project_id', $project->id)
            ->pluck('student_id');

        $students = Student::whereIn('id', $studentIds)->get();
        $project->setRelation('students', $students);

        return response()->json([
            'success' => true,
            'data' => $project
        ]);
    }
}
