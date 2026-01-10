<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ChildrenTrackerController extends Controller
{
    public function index()
    {
        // For demonstration, let's generate months from the user's registration date 
        // or just the last few months if they are new.
        $startDate = Auth::user()->created_at->startOfMonth();
        $now = Carbon::now()->startOfMonth();

        // Ensure we show at least 3 months even if brand new
        if ($startDate->diffInMonths($now) < 3) {
            $startDate = $now->copy()->subMonths(2);
        }

        $months = [];
        $current = $now->copy();

        while ($current->greaterThanOrEqualTo($startDate)) {
            $months[] = [
                'name' => $current->translatedFormat('F Y'),
                'is_current' => $current->equalTo($now),
                'is_past' => $current->lessThan($now),
                'completed' => $current->lessThan($now->copy()->subMonth()), // Mock logic for "Selesai Diisi"
            ];
            $current->subMonth();
        }

        return view('children-tracker.index', compact('months'));
    }
}
