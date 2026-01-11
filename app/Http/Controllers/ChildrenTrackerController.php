<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\ParentJournal;
use App\Models\Student;

class ChildrenTrackerController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->get('date') ? Carbon::parse($request->get('date')) : Carbon::now()->subMonth()->startOfMonth();
        $selectedMonthName = $selectedDate->translatedFormat('F Y');

        // Mock alerts for previous months (prior to selectedDate)
        $alerts = [
            [
                'type' => 'warning',
                'message' => 'Jurnal November 2025 belum lengkap diisi.',
                'icon' => 'alert-circle'
            ],
            [
                'type' => 'info',
                'message' => 'Ada balasan guru pada jurnal Oktober 2025.',
                'icon' => 'message-square'
            ]
        ];

        // Statuses for the 3 main aspects based on selected month
        $aspects = [
            'parent' => [
                'name' => 'Aspek Orang Tua',
                'desc' => 'Monitoring peran orang tua',
                'icon' => 'user',
                'color' => 'color-purple',
                'status' => 'replied',
                'route' => 'children-tracker.parent-aspect'
            ],
            'child' => [
                'name' => 'Aspek Anak',
                'desc' => 'Monitoring perkembangan anak',
                'icon' => 'users',
                'color' => 'color-orange',
                'status' => 'filled',
                'route' => '#'
            ],
            'internal_external' => [
                'name' => 'Aspek Internal/Eksternal',
                'desc' => 'Monitoring pertumbuhan karakter',
                'icon' => 'calendar',
                'color' => 'color-green',
                'status' => 'unfilled',
                'route' => '#'
            ]
        ];

        return view('children-tracker.index', compact('aspects', 'selectedMonthName', 'selectedDate', 'alerts'));
    }

    public function parentAspect(Request $request)
    {
        $user = Auth::user();
        $children = $user->students;

        $selectedMonthStr = $request->get('month', Carbon::now()->subMonth()->translatedFormat('F Y'));
        // Parse "Bulan Tahun" string
        $monthDate = Carbon::createFromFormat('F Y', $selectedMonthStr);
        $bulan = $monthDate->translatedFormat('F');
        $tahun = $monthDate->format('Y');

        $selectedChildId = $request->get('child_id', $children->first()->id ?? null);

        $journal = ParentJournal::where('user_id', $user->id)
            ->where('student_id', $selectedChildId)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->first();

        $selectedMonth = $selectedMonthStr;

        return view('children-tracker.parent-aspect', compact('children', 'selectedMonth', 'selectedChildId', 'journal'));
    }

    public function saveJournal(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'month_year' => 'required',
            'field' => 'required|in:pendekatan,interaksi',
            'value' => 'nullable|string'
        ]);

        $monthDate = Carbon::createFromFormat('F Y', $request->month_year);
        $bulan = $monthDate->translatedFormat('F');
        $tahun = $monthDate->format('Y');

        $journal = ParentJournal::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'student_id' => $request->student_id,
                'bulan' => $bulan,
                'tahun' => $tahun,
            ],
            [
                $request->field => $request->value,
                'parent_filled_at' => now(),
            ]
        );

        return response()->json(['success' => true, 'message' => 'Journal berhasil disimpan!']);
    }
}
