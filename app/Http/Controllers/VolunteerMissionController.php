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
        $missions = VolunteerMission::where('is_active', true)->get();

        // Get start and end of current week (Monday to Sunday)
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $completions = VolunteerMissionCompletion::where('user_id', Auth::id())
            ->whereBetween('completed_at', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->get()
            ->groupBy('volunteer_mission_id');

        $weekDays = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $weekDays[] = [
                'name' => $date->format('D'),
                'date' => $date->toDateString(),
                'full_name' => $date->translatedFormat('l')
            ];
        }

        return view('volunteer.index', compact('missions', 'completions', 'weekDays'));
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

        return response()->json([
            'status' => 'success',
            'action' => $status
        ]);
    }
}
