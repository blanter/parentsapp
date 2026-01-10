<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\GardeningPlant;
use App\Models\VolunteerMissionCompletion;
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

        return view('admin.dashboard', compact(
            'totalUsers',
            'pendingUsers',
            'totalGardening',
            'recentGardening',
            'totalCompletions',
            'todayCompletions',
            'thisWeekCompletions'
        ));
    }
}
