<?php

namespace App\Http\Controllers;

use App\Models\LearningProject;
use App\Models\LearningLog;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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
            foreach ($projects as $project) {
                $studentIds = DB::table('learning_project_student')
                    ->where('learning_project_id', $project->id)
                    ->pluck('student_id');

                $project->setRelation('students', Student::whereIn('id', $studentIds)->get());
                $project->load('logs.teacher', 'logs.user');
            }

            // For the select dropdown
            $students = Student::active()->get();

            return view('page.learning-tracker.index', compact('projects', 'students', 'isTeacher'));
        } else {
            // Parent view - get student IDs first
            $studentIds = $user->students->pluck('id')->toArray();

            if (empty($studentIds)) {
                $projects = collect();
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
                foreach ($projects as $project) {
                    $projectStudentIds = DB::table('learning_project_student')
                        ->where('learning_project_id', $project->id)
                        ->pluck('student_id');

                    $project->setRelation('students', Student::whereIn('id', $projectStudentIds)->get());
                    $project->load('logs.teacher', 'logs.user', 'teacher');
                }
            }

            return view('page.learning-tracker.index', compact('projects', 'isTeacher'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:weekly,monthly,semester',
            'student_ids' => 'required|array',
            'description' => 'required|string',
            'image' => 'nullable|image|max:5120', // 5MB max
        ]);

        $teacher = Auth::guard('teacher')->user();

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->uploadAndCompress($request->file('image'));
        }

        // Create project without transaction to avoid timeout
        $project = LearningProject::create([
            'teacher_id' => $teacher->id,
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'image' => $imagePath,
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
            'image' => 'nullable|image|max:5120',
        ]);

        $project = LearningProject::findOrFail($id);

        // Check if teacher owns this project
        if (Auth::guard('teacher')->id() !== $project->teacher_id) {
            return back()->with('error', 'Unauthorized action.');
        }

        $imagePath = $project->image;
        if ($request->hasFile('image')) {
            // Delete old image
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $this->uploadAndCompress($request->file('image'));
        }

        $project->update([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'image' => $imagePath,
        ]);

        return back()->with('success', 'Project updated successfully!');
    }

    public function destroy($id)
    {
        $project = LearningProject::findOrFail($id);

        // Check if teacher owns this project
        if (Auth::guard('teacher')->id() !== $project->teacher_id) {
            return back()->with('error', 'Unauthorized action.');
        }

        // Delete image if exists
        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }

        $project->delete();

        return back()->with('success', 'Project deleted successfully!');
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
