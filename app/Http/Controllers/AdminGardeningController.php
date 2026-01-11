<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GardeningPlant;
use App\Models\GardeningProgress;
use App\Models\WebSetting;
use Illuminate\Support\Facades\DB;

class AdminGardeningController extends Controller
{
    public function index()
    {
        // Get plants grouped by user to make it easier to navigate
        $plants = GardeningPlant::with([
            'user',
            'progress' => function ($query) {
                $query->orderBy('report_date', 'desc');
            }
        ])
            ->latest()
            ->get();

        return view('admin.gardening', compact('plants'));
    }

    public function updateScore(Request $request)
    {
        $request->validate([
            'progress_id' => 'required|exists:gardening_progress,id',
            'score' => 'required|numeric|min:0',
        ]);

        $progress = GardeningProgress::findOrFail($request->progress_id);
        $progress->score = $request->score;
        $progress->save();

        return back()->with('success', 'Score updated successfully for ' . $progress->report_date);
    }
}
