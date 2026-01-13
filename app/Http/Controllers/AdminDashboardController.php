<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\GardeningPlant;
use App\Models\VolunteerMissionCompletion;
use App\Models\ParentJournal;
use App\Models\LearningProject;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'user')->count();
        $pendingUsers = User::where('is_approved', false)->count();

        $totalGardening = GardeningPlant::count();
        $recentGardening = GardeningPlant::where('created_at', '>=', Carbon::now()->subDays(7))->count();

        $totalCompletions = VolunteerMissionCompletion::count();
        $todayCompletions = VolunteerMissionCompletion::whereDate('completed_at', Carbon::today())->count();
        $thisWeekCompletions = VolunteerMissionCompletion::whereBetween('completed_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

        // Children Tracker Statistics
        $now = Carbon::now();
        $currentYear = $now->format('Y');
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
        $currentMonthName = $now->translatedFormat('F');

        $totalJournals = ParentJournal::where('tahun', $currentYear)
            ->where(function ($q) use ($currentQuarterName, $currentMonthName) {
                $q->where('bulan', $currentQuarterName)
                    ->orWhere('bulan', $currentMonthName);
            })
            ->count();

        $recentJournals = ParentJournal::where('tahun', $currentYear)
            ->where(function ($q) use ($currentQuarterName, $currentMonthName) {
                $q->where('bulan', $currentQuarterName)
                    ->orWhere('bulan', $currentMonthName);
            })
            ->where(function ($q) {
                $q->whereNotNull('parent_filled_at')
                    ->orWhereNotNull('child_filled_at')
                    ->orWhereNotNull('internal_external_filled_at');
            })
            ->whereBetween('updated_at', [Carbon::now()->subDays(7), Carbon::now()])
            ->count();

        // Learning Tracker Statistics
        $totalProjects = LearningProject::count();
        $recentProjects = LearningProject::where('created_at', '>=', Carbon::now()->subDays(7))->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'pendingUsers',
            'totalGardening',
            'recentGardening',
            'totalCompletions',
            'todayCompletions',
            'thisWeekCompletions',
            'totalJournals',
            'recentJournals',
            'totalProjects',
            'recentProjects'
        ));
    }
}
