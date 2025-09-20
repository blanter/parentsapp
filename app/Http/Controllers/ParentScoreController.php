<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParentModel;
use App\Models\Score;

class ParentScoreController extends Controller
{
    // HALAMAN SCORE
    public function index(Request $request)
    {
        $parents = ParentModel::orderBy('name')->get();
        $activityFilter = $request->query('activity', 'all');

        // History with filter
        $scoresQuery = Score::with('parent')->latest();

        if ($activityFilter !== 'all') {
            $scoresQuery->where('activity', $activityFilter);
        }

        $scores = $scoresQuery->paginate(10)->withQueryString();

        // Leaderboard total per parent
        $leaderboardQuery = Score::selectRaw('parent_id, SUM(score) as total_score')
            ->groupBy('parent_id')
            ->with('parent')
            ->orderByDesc('total_score');

        if ($activityFilter !== 'all') {
            $leaderboardQuery->where('activity', $activityFilter);
        }

        $leaderboard = $leaderboardQuery->get();

        // THE BEST per activity (tetap global, tidak ikut filter)
        $activities = ['Journaling Parents', 'Support/Kerjasama', 'Home Gardening', 'Administrasi', 'Lifebook Journey'];
        $bestScores = [];

        foreach ($activities as $activity) {
            $best = Score::where('activity', $activity)
                ->with('parent')
                ->orderByDesc('score')
                ->first();

            if ($best) {
                $bestScores[$activity] = $best;
            }
        }

        return view('parents-score', compact('parents', 'scores', 'leaderboard', 'bestScores', 'activityFilter'));
    }

    // FUNGSI SIMPAN
    public function store(Request $request)
    {
        $request->validate([
            'activity' => 'required|string|max:100',
            'score'    => 'required|integer|min:1|max:100',
            'parent_ids'   => 'required|array|min:1', 
            'parent_ids.*' => 'string|max:255',
        ]);

        foreach ($request->parent_ids as $value) {
            if (is_numeric($value)) {
                // pilih parent yang sudah ada
                $parent = ParentModel::find($value);
            } else {
                // buat parent baru kalau input teks
                $parent = ParentModel::firstOrCreate(['name' => $value]);
            }

            if ($parent) {
                Score::create([
                    'parent_id' => $parent->id,   // 👈 simpan ID parent yang benar
                    'activity'  => $request->activity,
                    'score'     => $request->score,
                ]);
            }
        }

        return redirect()->route('parents.index')->with('success', 'Data berhasil disimpan');
    }

    // HAPUS SCORE
    public function destroy($id)
    {
        $score = Score::findOrFail($id);
        $score->delete();

        return redirect()->route('parents.index')->with('success', 'Data berhasil dihapus');
    }
}