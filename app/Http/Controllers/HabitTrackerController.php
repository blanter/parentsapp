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
            $habit->update(['title' => $request->title]);
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

        if ($log) {
            $log->is_completed = !$log->is_completed;
            $log->save();
        } else {
            $log = HabitDailyLog::create([
                'habit_id' => $request->habit_id,
                'log_date' => $request->date,
                'is_completed' => true
            ]);
        }

        return response()->json(['success' => true, 'is_completed' => $log->is_completed]);
    }

    public function deleteHabit($id)
    {
        $habit = Habit::where('user_id', Auth::id())->findOrFail($id);
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
            $task->update(['title' => $request->title]);
        } else {
            $task = HabitWeeklyTask::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'month' => $request->month,
                'year' => $request->year,
                'week_index' => $request->week_index
            ]);
        }

        return response()->json(['success' => true, 'task' => $task]);
    }

    public function toggleWeeklyTask($id)
    {
        $task = HabitWeeklyTask::where('user_id', Auth::id())->findOrFail($id);
        $task->is_completed = !$task->is_completed;
        $task->save();

        return response()->json(['success' => true, 'is_completed' => $task->is_completed]);
    }

    public function deleteWeeklyTask($id)
    {
        $task = HabitWeeklyTask::where('user_id', Auth::id())->findOrFail($id);
        $task->delete();
        return response()->json(['success' => true]);
    }
}
