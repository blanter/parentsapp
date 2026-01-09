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
    
        $activityFilter = $request->query('activity', null);
        $parentFilter   = $request->query('parent_id', null);
    
        // Query history
        $scoresQuery = Score::with('parent')->latest();
    
        // === FILTER ACTIVITY ===
        if ($activityFilter) {
            $scoresQuery->where('activity', $activityFilter);
        }
    
        // === FILTER PARENT ===
        if ($parentFilter) {
            $scoresQuery->where('parent_id', $parentFilter);
        }
    
        $scores = $scoresQuery
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();
    
        // Leaderboard Total per parent
        $leaderboardQuery = Score::selectRaw('parent_id, SUM(score) as total_score')
            ->groupBy('parent_id')
            ->with('parent')
            ->orderByDesc('total_score');
    
        if ($activityFilter) {
            $leaderboardQuery->where('activity', $activityFilter);
        }
    
        if ($parentFilter) {
            $leaderboardQuery->where('parent_id', $parentFilter);
        }
    
        $leaderboard = $leaderboardQuery->get();
    
        // THE BEST per activity (tetap global)
        $activities = ['Journaling Parents', 'Support/Kerjasama', 'Home Gardening', 'Administrasi', 'Lifebook Journey'];
        $bestScores = [];
    
        foreach ($activities as $activity) {
            $bestRow = Score::where('activity', $activity)
                ->selectRaw('parent_id, SUM(score) as total_score')
                ->groupBy('parent_id')
                ->orderByDesc('total_score')
                ->first();
    
            if ($bestRow) {
                $parent = ParentModel::find($bestRow->parent_id);
    
                if ($parent) {
                    $bestScores[$activity] = (object) [
                        'parent' => $parent,
                        'score'  => (int)$bestRow->total_score,
                    ];
                }
            }
        }
    
        return view('parents-score', compact('parents', 'scores', 'leaderboard', 'bestScores', 'activityFilter', 'parentFilter'));
    }

    // FUNGSI SIMPAN
    public function store(Request $request)
    {
        $request->validate([
            'activity' => 'required|string|max:100',
            'score'    => 'required|integer|min:1|max:100',
            'parent_ids'   => 'required|array|min:1', 
            'parent_ids.*' => 'string|max:255',
            'deskripsi' => 'nullable|string|max:500',
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
                    'parent_id' => $parent->id,
                    'activity'  => $request->activity,
                    'score'     => $request->score,
                    'deskripsi' => $request->deskripsi ?? NULL,
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
    
    // HALAMAN LEADERBOARD
    public function leaderboard(Request $request)
    {
        $activityFilter = $request->query('activity', 'all');

        // History with filter
        $scoresQuery = Score::with('parent')->latest();

        if ($activityFilter !== 'all') {
            $scoresQuery->where('activity', $activityFilter);
        }

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
            $bestRow = Score::where('activity', $activity)
                ->selectRaw('parent_id, SUM(score) as total_score')
                ->groupBy('parent_id')
                ->orderByDesc('total_score')
                ->first();
            if ($bestRow) {
                $parent = ParentModel::find($bestRow->parent_id);
                if ($parent) {
                    $bestScores[$activity] = (object) [
                        'parent' => $parent,
                        'score'  => (int) $bestRow->total_score,
                    ];
                }
            }
        }

        return view('leaderboard-parents', compact('leaderboard', 'bestScores', 'activityFilter'));
    }
    
    // HALAMAN EDIT
    public function editscore($id)
    {
        $score = Score::with('parent')->findOrFail($id);
    
        return view('edit-score', compact('score'));
    }
    
    // FUNGSI EDIT
    public function updatescore(Request $request, $id)
    {
        $request->validate([
            'activity'  => 'required|string|max:100',
            'score'     => 'required|integer|min:1|max:100',
            'deskripsi' => 'nullable|string|max:500',
        ]);
    
        $score = Score::findOrFail($id);
    
        $score->update([
            'activity'  => $request->activity,
            'score'     => $request->score,
            'deskripsi' => $request->deskripsi,
        ]);
    
        return redirect()->route('parents.index')->with('success', 'Data berhasil diperbarui');
    }
}