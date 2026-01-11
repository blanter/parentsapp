<?php

namespace App\Http\Controllers;

use App\Models\VolunteerMission;
use App\Models\VolunteerMissionCompletion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminVolunteerController extends Controller
{
    public function index()
    {
        // Get all active missions
        $missions = VolunteerMission::where('is_active', true)->get();

        // Get all users with their completion data
        $users = User::where('role', '!=', 'admin')
            ->where('is_approved', 1)
            ->with([
                'volunteerCompletions' => function ($query) {
                    $query->whereBetween('completed_at', [
                        Carbon::now()->startOfWeek()->toDateString(),
                        Carbon::now()->endOfWeek()->toDateString()
                    ]);
                }
            ])
            ->get();

        // Calculate weekly statistics
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $weeklyCompletions = VolunteerMissionCompletion::whereBetween('completed_at', [
            $startOfWeek->toDateString(),
            $endOfWeek->toDateString()
        ])->count();

        $todayCompletions = VolunteerMissionCompletion::whereDate('completed_at', Carbon::today())->count();

        $totalCompletions = VolunteerMissionCompletion::count();

        // Active users this week
        $activeUsersThisWeek = VolunteerMissionCompletion::whereBetween('completed_at', [
            $startOfWeek->toDateString(),
            $endOfWeek->toDateString()
        ])->distinct('user_id')->count('user_id');

        // Get detailed user data with stats
        $userStats = [];
        foreach ($users as $user) {
            $userCompletions = VolunteerMissionCompletion::where('user_id', $user->id)
                ->whereBetween('completed_at', [
                    $startOfWeek->toDateString(),
                    $endOfWeek->toDateString()
                ])
                ->get();

            $totalUserCompletions = VolunteerMissionCompletion::where('user_id', $user->id)->count();

            // Calculate current streak
            $allDailyCompletions = VolunteerMissionCompletion::where('user_id', $user->id)
                ->select('completed_at')
                ->distinct()
                ->orderBy('completed_at', 'desc')
                ->pluck('completed_at')
                ->map(fn($date) => Carbon::parse($date)->toDateString())
                ->toArray();

            $currentStreak = 0;
            if (!empty($allDailyCompletions)) {
                $checkDate = Carbon::yesterday();
                if (in_array(Carbon::today()->toDateString(), $allDailyCompletions)) {
                    $checkDate = Carbon::today();
                }

                $tempDate = $checkDate->copy();
                while (in_array($tempDate->toDateString(), $allDailyCompletions)) {
                    $currentStreak++;
                    $tempDate->subDay();
                }
            }

            $userStats[] = [
                'user' => $user,
                'weekly_completions' => $userCompletions->count(),
                'total_completions' => $totalUserCompletions,
                'current_streak' => $currentStreak,
                'completions' => $userCompletions->groupBy('volunteer_mission_id')
            ];
        }

        // Sort by weekly completions
        usort($userStats, function ($a, $b) {
            return $b['weekly_completions'] <=> $a['weekly_completions'];
        });

        return view('admin.volunteer-data', compact(
            'missions',
            'userStats',
            'weeklyCompletions',
            'todayCompletions',
            'totalCompletions',
            'activeUsersThisWeek'
        ));
    }
}
