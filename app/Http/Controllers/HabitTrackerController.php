<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\HabitDailyLog;
use App\Models\HabitWeeklyTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HabitTrackerController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));

        // Fetch daily habits
        $habits = Habit::where('user_id', $user->id)->get();

        // Fetch logs for the month
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $logs = HabitDailyLog::whereIn('habit_id', $habits->pluck('id'))
            ->whereBetween('log_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->get()
            ->groupBy('habit_id');

        // Fetch weekly tasks
        $weeklyTasks = HabitWeeklyTask::where('user_id', $user->id)
            ->where('month', $month)
            ->where('year', $year)
            ->get()
            ->groupBy('week_index');

        return response()->json([
            'success' => true,
            'data' => [
                'habits' => $habits,
                'logs' => $logs,
                'weeklyTasks' => $weeklyTasks,
                'month' => (int) $month,
                'year' => (int) $year,
                'daysInMonth' => $startDate->daysInMonth
            ]
        ]);
    }

    public function storeHabit(Request $request)
    {
        $request->validate(['title' => 'required|string|max:255']);

        if ($request->id) {
            $habit = Habit::where('user_id', Auth::id())->findOrFail($request->id);
            $oldTitle = $habit->title;
            $habit->update(['title' => $request->title]);

            // Update existing score descriptions if title changed
            if ($oldTitle !== $request->title) {
                $scores = \App\Models\Score::where('user_id', Auth::id())
                    ->where('activity', 'Lifebook Journey')
                    ->where('deskripsi', 'like', "Habit Daily: $oldTitle (%")
                    ->get();

                foreach ($scores as $score) {
                    $newDesc = str_replace("Habit Daily: $oldTitle (", "Habit Daily: $request->title (", $score->deskripsi);
                    $score->update(['deskripsi' => $newDesc]);
                }
            }
        } else {
            $habit = Habit::create([
                'user_id' => Auth::id(),
                'title' => $request->title
            ]);
        }

        return response()->json(['success' => true, 'habit' => $habit]);
    }

    public function toggleHabit(Request $request)
    {
        $request->validate([
            'habit_id' => 'required|exists:habits,id',
            'date' => 'required|date'
        ]);

        $log = HabitDailyLog::where('habit_id', $request->habit_id)
            ->where('log_date', $request->date)
            ->first();

        $pointsEarned = 0;
        $habit = Habit::find($request->habit_id);
        $habitTitle = $habit ? $habit->title : 'Habit';
        $scoreDesc = "Habit Daily: $habitTitle ($request->date)";

        if ($log) {
            $log->is_completed = !$log->is_completed;
            $log->save();

            if (!$log->is_completed) {
                // Remove points
                \App\Models\Score::where('user_id', Auth::id())
                    ->where('activity', 'Lifebook Journey')
                    ->where('deskripsi', $scoreDesc)
                    ->delete();
            }
        } else {
            $log = HabitDailyLog::create([
                'habit_id' => $request->habit_id,
                'log_date' => $request->date,
                'is_completed' => true
            ]);
        }

        if ($log->is_completed) {
            // Add Points (10)
            $existingScore = \App\Models\Score::where('user_id', Auth::id())
                ->where('activity', 'Lifebook Journey')
                ->where('deskripsi', $scoreDesc)
                ->first();

            if (!$existingScore) {
                \App\Models\Score::create([
                    'user_id' => Auth::id(),
                    'activity' => 'Lifebook Journey',
                    'score' => 10,
                    'deskripsi' => $scoreDesc
                ]);
                $pointsEarned = 10;
            }
        }

        return response()->json([
            'success' => true,
            'is_completed' => $log->is_completed,
            'earned_points' => $pointsEarned
        ]);
    }

    public function deleteHabit($id)
    {
        $habit = Habit::where('user_id', Auth::id())->findOrFail($id);

        // Remove points for all logs of this habit
        \App\Models\Score::where('user_id', Auth::id())
            ->where('activity', 'Lifebook Journey')
            ->where('deskripsi', 'like', "Habit Daily: $habit->title (%")
            ->delete();

        $habit->delete();
        return response()->json(['success' => true]);
    }

    public function storeWeeklyTask(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'month' => 'required|integer',
            'year' => 'required|integer',
            'week_index' => 'required|integer|min:1|max:6'
        ]);

        if ($request->id) {
            $task = HabitWeeklyTask::where('user_id', Auth::id())->findOrFail($request->id);
            $oldTitle = $task->title;
            $task->update(['title' => $request->title]);

            // Update existing score description if title changed
            if ($oldTitle !== $request->title) {
                $typeLabel = ($task->week_index == 6) ? "Monthly" : "Weekly (Week $task->week_index)";
                $oldScoreDesc = "Habit $typeLabel: $oldTitle (" . date('F', mktime(0, 0, 0, $task->month, 1)) . " $task->year)";
                $newScoreDesc = "Habit $typeLabel: $task->title (" . date('F', mktime(0, 0, 0, $task->month, 1)) . " $task->year)";

                \App\Models\Score::where('user_id', Auth::id())
                    ->where('activity', 'Lifebook Journey')
                    ->where('deskripsi', $oldScoreDesc)
                    ->update(['deskripsi' => $newScoreDesc]);
            }
        } else {
            $task = HabitWeeklyTask::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'month' => $request->month,
                'year' => $request->year,
                'week_index' => $request->week_index,
                'is_completed' => false
            ]);
        }

        return response()->json(['success' => true, 'task' => $task]);
    }

    public function toggleWeeklyTask($id)
    {
        $task = HabitWeeklyTask::where('user_id', Auth::id())->findOrFail($id);
        $task->is_completed = !$task->is_completed;
        $task->save();

        $pointsEarned = 0;
        $points = ($task->week_index == 6) ? 100 : 50;
        $typeLabel = ($task->week_index == 6) ? "Monthly" : "Weekly (Week $task->week_index)";
        $scoreDesc = "Habit $typeLabel: $task->title (" . date('F', mktime(0, 0, 0, $task->month, 1)) . " $task->year)";

        if ($task->is_completed) {
            // Add Points
            $existingScore = \App\Models\Score::where('user_id', Auth::id())
                ->where('activity', 'Lifebook Journey')
                ->where('deskripsi', $scoreDesc)
                ->first();

            if (!$existingScore) {
                \App\Models\Score::create([
                    'user_id' => Auth::id(),
                    'activity' => 'Lifebook Journey',
                    'score' => $points,
                    'deskripsi' => $scoreDesc
                ]);
                $pointsEarned = $points;
            }
        } else {
            // Remove points
            \App\Models\Score::where('user_id', Auth::id())
                ->where('activity', 'Lifebook Journey')
                ->where('deskripsi', $scoreDesc)
                ->delete();
        }

        return response()->json([
            'success' => true,
            'is_completed' => $task->is_completed,
            'earned_points' => $pointsEarned
        ]);
    }

    public function deleteWeeklyTask($id)
    {
        $task = HabitWeeklyTask::where('user_id', Auth::id())->findOrFail($id);

        // Remove points if any
        $typeLabel = ($task->week_index == 6) ? "Monthly" : "Weekly (Week $task->week_index)";
        $scoreDesc = "Habit $typeLabel: $task->title (" . date('F', mktime(0, 0, 0, $task->month, 1)) . " $task->year)";

        \App\Models\Score::where('user_id', Auth::id())
            ->where('activity', 'Lifebook Journey')
            ->where('deskripsi', $scoreDesc)
            ->delete();

        $task->delete();
        return response()->json(['success' => true]);
    }
}
