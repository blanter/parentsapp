<?php

namespace App\Http\Controllers;

use App\Models\VolunteerMission;
use App\Models\VolunteerMissionCompletion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VolunteerMissionController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $missions = VolunteerMission::where('is_active', true)->get();

        // Get start and end of current week (Monday to Sunday) for the daily selector
        $now = Carbon::now();
        $startOfWeek = $now->copy()->startOfWeek();
        $endOfWeek = $now->copy()->endOfWeek();

        $completions = VolunteerMissionCompletion::where('user_id', $userId)
            ->whereBetween('completed_at', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->get()
            ->groupBy('volunteer_mission_id');

        $weekDays = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $weekDays[] = [
                'name' => $date->translatedFormat('D'),
                'date' => $date->toDateString(),
                'full_name' => $date->translatedFormat('l')
            ];
        }

        // --- OVERVIEW CALCULATIONS ---
        $stats = $this->getOverviewStats($userId);

        // 4. Grid Data (Last 20 weeks)
        // We want 20 columns (weeks) and 7 rows (days)
        $gridWeeks = 20;
        $gridEndDate = Carbon::today();
        $gridStartDate = $gridEndDate->copy()->subWeeks($gridWeeks - 1)->startOfWeek();

        $allCompletionsList = VolunteerMissionCompletion::where('user_id', $userId)
            ->where('completed_at', '>=', $gridStartDate->toDateString())
            ->get()
            ->groupBy('volunteer_mission_id')
            ->map(fn($items) => $items->pluck('completed_at')->map(fn($d) => Carbon::parse($d)->toDateString())->toArray());

        $overviewData = [
            'current_streak' => $stats['current_streak'],
            'best_streak' => $stats['best_streak'],
            'success_rate' => $stats['success_rate'],
            'total_completed' => $stats['total_completed'],
            'grid_start_date' => $gridStartDate,
            'grid_weeks' => $gridWeeks,
            'all_completions' => $allCompletionsList
        ];

        $appVersion = \App\Models\WebSetting::where('key', 'app_version')->value('value') ?? '1.2.0';

        return view('volunteer.index', compact('missions', 'completions', 'weekDays', 'overviewData', 'appVersion'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'mission_id' => 'required|exists:volunteer_missions,id',
            'date' => 'required|date',
        ]);

        $userId = Auth::id();
        $missionId = $request->mission_id;
        $date = $request->date;

        $completion = VolunteerMissionCompletion::where('user_id', $userId)
            ->where('volunteer_mission_id', $missionId)
            ->where('completed_at', $date)
            ->first();

        if ($completion) {
            $completion->delete();
            $status = 'unchecked';
        } else {
            VolunteerMissionCompletion::create([
                'user_id' => $userId,
                'volunteer_mission_id' => $missionId,
                'completed_at' => $date,
            ]);
            $status = 'checked';
        }

        $stats = $this->getOverviewStats($userId);

        return response()->json([
            'status' => 'success',
            'action' => $status,
            'stats' => [
                'current_streak' => $stats['current_streak'],
                'best_streak' => $stats['best_streak'],
                'success_rate' => $stats['success_rate'],
                'total_completed' => $stats['total_completed']
            ]
        ]);
    }

    private function getOverviewStats($userId)
    {
        // 1. Total Completed Habits
        $totalCompleted = VolunteerMissionCompletion::where('user_id', $userId)->count();

        // 2. Streaks (Based on days with AT LEAST ONE mission completed)
        $allDailyCompletions = VolunteerMissionCompletion::where('user_id', $userId)
            ->select('completed_at')
            ->distinct()
            ->orderBy('completed_at', 'desc')
            ->pluck('completed_at')
            ->map(fn($date) => Carbon::parse($date)->toDateString())
            ->toArray();

        $currentStreak = 0;
        $bestStreak = 0;
        $tempStreak = 0;

        if (!empty($allDailyCompletions)) {
            $checkDate = Carbon::yesterday();
            if (in_array(Carbon::today()->toDateString(), $allDailyCompletions)) {
                $checkDate = Carbon::today();
            }

            // Current Streak
            $tempDate = $checkDate->copy();
            while (in_array($tempDate->toDateString(), $allDailyCompletions)) {
                $currentStreak++;
                $tempDate->subDay();
            }

            // Best Streak
            $dates = array_reverse($allDailyCompletions);
            $lastDate = null;
            foreach ($dates as $dateStr) {
                $curr = Carbon::parse($dateStr);
                if ($lastDate && $curr->diffInDays($lastDate) == 1) {
                    $tempStreak++;
                } else {
                    $tempStreak = 1;
                }
                $bestStreak = max($bestStreak, $tempStreak);
                $lastDate = $curr;
            }
        }

        // 3. Success Rate (Last 30 days)
        $last30DaysCount = VolunteerMissionCompletion::where('user_id', $userId)
            ->whereBetween('completed_at', [Carbon::now()->subDays(29)->toDateString(), Carbon::now()->toDateString()])
            ->select('completed_at')
            ->distinct()
            ->count();
        $successRate = round(($last30DaysCount / 30) * 100);

        return [
            'total_completed' => $totalCompleted,
            'current_streak' => $currentStreak,
            'best_streak' => $bestStreak,
            'success_rate' => $successRate
        ];
    }
}
