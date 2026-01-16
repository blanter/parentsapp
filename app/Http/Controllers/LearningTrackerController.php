<?php

namespace App\Http\Controllers;

use App\Models\LearningProject;
use App\Models\LearningLog;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LearningTrackerController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isTeacher = Auth::guard('teacher')->check();
        $teacher = Auth::guard('teacher')->user();

        if ($isTeacher) {
            $projects = LearningProject::where('teacher_id', $teacher->id)
                ->latest()
                ->get();

            // Manually load students for each project
            $allProjectStudentIds = [];
            foreach ($projects as $project) {
                $studentIds = DB::table('learning_project_student')
                    ->where('learning_project_id', $project->id)
                    ->pluck('student_id')
                    ->toArray();

                $project->setRelation('students', Student::whereIn('id', $studentIds)->get());
                $project->load('logs.teacher', 'logs.user');

                $allProjectStudentIds = array_merge($allProjectStudentIds, $studentIds);
            }

            $studentsWithProjects = Student::whereIn('id', array_unique($allProjectStudentIds))->get();

            // For the select dropdown
            $students = Student::active()->get();

            return view('page.learning-tracker.index', compact('projects', 'students', 'isTeacher', 'studentsWithProjects'));
        } else {
            // Parent view - get student IDs first
            $studentIds = $user->students->pluck('id')->toArray();

            if (empty($studentIds)) {
                $projects = collect();
                $studentsWithProjects = collect();
            } else {
                // Get project IDs that have these students
                $projectIds = DB::table('learning_project_student')
                    ->whereIn('student_id', $studentIds)
                    ->pluck('learning_project_id')
                    ->unique()
                    ->toArray();

                $projects = LearningProject::whereIn('id', $projectIds)
                    ->latest()
                    ->get();

                // Manually load relationships
                $allProjectStudentIds = [];
                foreach ($projects as $project) {
                    $projectStudentIds = DB::table('learning_project_student')
                        ->where('learning_project_id', $project->id)
                        ->pluck('student_id')
                        ->toArray();

                    $project->setRelation('students', Student::whereIn('id', $projectStudentIds)->get());
                    $project->load('logs.teacher', 'logs.user', 'teacher');

                    $allProjectStudentIds = array_merge($allProjectStudentIds, $projectStudentIds);
                }

                $studentsWithProjects = Student::whereIn('id', array_unique($allProjectStudentIds))
                    ->whereIn('id', $studentIds) // Only parents' own kids
                    ->get();
            }

            return view('page.learning-tracker.index', compact('projects', 'isTeacher', 'studentsWithProjects'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:weekly,monthly,semester',
            'student_ids' => 'required|array',
            'description' => 'required|string',
        ]);

        $teacher = Auth::guard('teacher')->user();

        // Create project without transaction to avoid timeout
        $project = LearningProject::create([
            'teacher_id' => $teacher->id,
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'image' => null, // No image for project anymore
        ]);

        // Attach students using raw insert for better performance
        $studentData = [];
        foreach ($request->student_ids as $studentId) {
            $studentData[] = [
                'learning_project_id' => $project->id,
                'student_id' => $studentId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('learning_project_student')->insert($studentData);

        return back()->with('success', 'Project created successfully!');
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
            'image' => 'nullable|image|max:5120',
        ]);

        $project = LearningProject::findOrFail($id);
        $isTeacher = Auth::guard('teacher')->check();

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->uploadAndCompress($request->file('image'));
        }

        $log = LearningLog::create([
            'learning_project_id' => $project->id,
            'teacher_id' => $isTeacher ? Auth::guard('teacher')->id() : null,
            'user_id' => !$isTeacher ? Auth::id() : null,
            'content' => $request->input('content'),
            'progress_percentage' => $isTeacher ? $request->input('progress_percentage') : null,
            'image' => $imagePath,
        ]);

        // Update project progress if teacher replied with percentage
        if ($isTeacher && $request->has('progress_percentage')) {
            $project->update(['progress_percentage' => $request->progress_percentage]);
        }

        return back()->with('success', 'Reply posted successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:weekly,monthly,semester',
            'description' => 'required|string',
        ]);

        $project = LearningProject::findOrFail($id);

        $teacherId = Auth::guard('teacher')->id();

        // Check if teacher owns this project
        // Use loose comparison ( != ) to handle string/int id mismatch between databases
        if ($teacherId != $project->teacher_id) {
            Log::error("LearningTracker Update Unauthorized: Teacher ID {$teacherId} (Type: " . gettype($teacherId) . ") vs Project Teacher ID {$project->teacher_id} (Type: " . gettype($project->teacher_id) . ")");

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
            }
            return back()->with('error', 'Unauthorized action.');
        }

        $project->update([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Project updated successfully!']);
        }

        return back()->with('success', 'Project updated successfully!');
    }

    public function destroy($id)
    {
        $project = LearningProject::findOrFail($id);

        $teacherId = Auth::guard('teacher')->id();

        // Check if teacher owns this project
        // Use loose comparison ( != )
        if ($teacherId != $project->teacher_id) {
            Log::error("LearningTracker Destroy Unauthorized: Teacher ID {$teacherId} (Type: " . gettype($teacherId) . ") vs Project Teacher ID {$project->teacher_id} (Type: " . gettype($project->teacher_id) . ")");

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
            }
            return back()->with('error', 'Unauthorized action.');
        }

        // Delete project image if exists (though we stopped adding it)
        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }

        // Delete all comment images
        foreach ($project->logs as $log) {
            if ($log->image) {
                Storage::disk('public')->delete($log->image);
            }
        }

        $project->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Project deleted successfully!']);
        }

        return back()->with('success', 'Project deleted successfully!');
    }

    public function updateLog(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
            'image' => 'nullable|image|max:5120',
        ]);

        $log = LearningLog::findOrFail($id);
        $isTeacher = Auth::guard('teacher')->check();

        // Check ownership
        if ($isTeacher) {
            $teacherId = Auth::guard('teacher')->id();
            if ($log->teacher_id != $teacherId) {
                Log::error("LearningTracker UpdateLog Unauthorized (Teacher): Teacher ID {$teacherId} vs Log Teacher ID {$log->teacher_id}");
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
                }
                return back()->with('error', 'Unauthorized action.');
            }
        } else {
            $userId = Auth::id();
            if ($log->user_id != $userId) {
                Log::error("LearningTracker UpdateLog Unauthorized (Parent): User ID {$userId} vs Log User ID {$log->user_id}");
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
                }
                return back()->with('error', 'Unauthorized action.');
            }
        }

        $imagePath = $log->image;
        if ($request->hasFile('image')) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $this->uploadAndCompress($request->file('image'));
        }

        $log->update([
            'content' => $request->input('content'),
            'progress_percentage' => $isTeacher ? $request->input('progress_percentage') : $log->progress_percentage,
            'image' => $imagePath,
        ]);

        // Update project progress if teacher updated log with percentage
        if ($isTeacher && $request->has('progress_percentage')) {
            $log->project->update(['progress_percentage' => $request->progress_percentage]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Comment updated successfully!']);
        }

        return back()->with('success', 'Comment updated successfully!');
    }

    public function destroyLog($id)
    {
        $log = LearningLog::findOrFail($id);
        $isTeacher = Auth::guard('teacher')->check();

        // Check ownership
        if ($isTeacher) {
            $teacherId = Auth::guard('teacher')->id();
            if ($log->teacher_id != $teacherId) {
                Log::error("LearningTracker DestroyLog Unauthorized (Teacher): Teacher ID {$teacherId} vs Log Teacher ID {$log->teacher_id}");
                if (request()->ajax() || request()->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
                }
                return back()->with('error', 'Unauthorized action.');
            }
        } else {
            $userId = Auth::id();
            if ($log->user_id != $userId) {
                Log::error("LearningTracker DestroyLog Unauthorized (Parent): User ID {$userId} vs Log User ID {$log->user_id}");
                if (request()->ajax() || request()->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
                }
                return back()->with('error', 'Unauthorized action.');
            }
        }

        if ($log->image) {
            Storage::disk('public')->delete($log->image);
        }

        $log->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Comment deleted successfully!']);
        }

        return back()->with('success', 'Comment deleted successfully!');
    }

    private function uploadAndCompress($file)
    {
        // Use Intervention Image for efficient compression
        $filename = time() . '_' . uniqid() . '.jpg';
        $path = 'learning_tracker/' . $filename;

        try {
            // Create image manager instance
            $manager = new \Intervention\Image\ImageManager(
                new \Intervention\Image\Drivers\Gd\Driver()
            );

            // Read and process image
            $image = $manager->read($file);

            // Resize if too large (max 1200px width)
            if ($image->width() > 1200) {
                $image->scale(width: 1200);
            }

            // Encode to JPEG with 50% quality
            $encoded = $image->encodeByMediaType('image/jpeg', quality: 50);

            // Save to storage
            Storage::disk('public')->put($path, (string) $encoded);

            return $path;
        } catch (\Exception $e) {
            // Fallback to simple upload if compression fails
            $fallbackFilename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            return $file->storeAs('learning_tracker', $fallbackFilename, 'public');
        }
    }
}
