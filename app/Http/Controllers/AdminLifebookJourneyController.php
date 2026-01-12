<?php

namespace App\Http\Controllers;

use App\Models\LifebookJourney;
use App\Models\User;
use App\Models\Habit;
use App\Models\HabitDailyLog;
use App\Models\HabitWeeklyTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminLifebookJourneyController extends Controller
{
    private $categories = [
        ['id' => 'spiritual', 'name' => 'Spiritual', 'icon' => 'moon'],
        ['id' => 'health', 'name' => 'Health and Fitness', 'icon' => 'heart'],
        ['id' => 'intellectual', 'name' => 'Intellectual', 'icon' => 'brain'],
        ['id' => 'life_skill', 'name' => 'Life Skill', 'icon' => 'wrench'],
        ['id' => 'emotional', 'name' => 'Emotional', 'icon' => 'smile'],
        ['id' => 'family', 'name' => 'Family life', 'icon' => 'users'],
        ['id' => 'social', 'name' => 'Social life', 'icon' => 'message-circle'],
        ['id' => 'financial', 'name' => 'Financial life', 'icon' => 'dollar-sign'],
        ['id' => 'career', 'name' => 'Career life', 'icon' => 'briefcase'],
        ['id' => 'quality', 'name' => 'Quality life', 'icon' => 'star']
    ];

    public function index()
    {
        $users = User::where('role', '!=', 'admin')
            ->with(['lifebookJourneys'])
            ->get();

        $today = Carbon::today();
        $currentMonth = (int) $today->format('n');
        $currentYear = (int) $today->format('Y');

        $journeyStats = $users->map(function ($user) {
            $journeys = $user->lifebookJourneys->keyBy('category');
            $totalFields = count($this->categories) * 4;
            $filledFields = 0;

            foreach ($this->categories as $cat) {
                $j = $journeys[$cat['id']] ?? null;
                if ($j) {
                    if (!empty($j->premise))
                        $filledFields++;
                    if (!empty($j->vision))
                        $filledFields++;
                    if (!empty($j->purpose))
                        $filledFields++;
                    if (!empty($j->strategy))
                        $filledFields++;
                }
            }

            return [
                'user' => $user,
                'filled_fields' => $filledFields,
                'total_fields' => $totalFields,
                'percentage' => $totalFields > 0 ? round(($filledFields / $totalFields) * 100) : 0,
                'last_update' => $user->lifebookJourneys->max('updated_at')
            ];
        })->sortByDesc('percentage');

        $habitStats = $users->map(function ($user) use ($today, $currentMonth, $currentYear) {
            $habits = Habit::where('user_id', $user->id)->get();
            $habitIds = $habits->pluck('id');

            $completedToday = HabitDailyLog::whereIn('habit_id', $habitIds)
                ->where('log_date', $today->toDateString())
                ->where('is_completed', true)
                ->count();

            $weeklyTasksTotal = HabitWeeklyTask::where('user_id', $user->id)
                ->where('month', $currentMonth)
                ->where('year', $currentYear)
                ->count();

            $weeklyTasksCompleted = HabitWeeklyTask::where('user_id', $user->id)
                ->where('month', $currentMonth)
                ->where('year', $currentYear)
                ->where('is_completed', true)
                ->count();

            return [
                'user' => $user,
                'total_habits' => $habits->count(),
                'completed_today' => $completedToday,
                'weekly_tasks_total' => $weeklyTasksTotal,
                'weekly_tasks_completed' => $weeklyTasksCompleted,
                'weekly_progress' => $weeklyTasksTotal > 0 ? round(($weeklyTasksCompleted / $weeklyTasksTotal) * 100) : 0
            ];
        })->sortByDesc('weekly_progress');

        // General stats
        $totalParents = $users->count();
        $activeParents = $journeyStats->where('filled_fields', '>', 0)->count();
        $completedJourneys = $journeyStats->where('percentage', 100)->count();
        $avgProgress = $journeyStats->avg('percentage');

        return view('admin.lifebook-journey', [
            'journeyStats' => $journeyStats,
            'habitStats' => $habitStats,
            'categories' => $this->categories,
            'totalParents' => $totalParents,
            'activeParents' => $activeParents,
            'completedJourneys' => $completedJourneys,
            'avgProgress' => $avgProgress
        ]);
    }

    public function show($userId)
    {
        $user = User::findOrFail($userId);
        $journeys = LifebookJourney::where('user_id', $userId)->get()->keyBy('category');

        return response()->json([
            'success' => true,
            'user' => $user,
            'journeys' => $journeys,
            'categories' => $this->categories
        ]);
    }
}
