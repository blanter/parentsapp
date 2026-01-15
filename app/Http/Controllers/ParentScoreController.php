<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Score;
use Illuminate\Support\Facades\DB;

class ParentScoreController extends Controller
{
    // HALAMAN SCORE
    public function index(Request $request)
    {
        // Get only users with 'user' role for the dropdown
        $parents = User::where('role', 'user')->with('students')->orderBy('name')->get();

        $activityFilter = $request->query('activity', null);
        $userIdFilter = $request->query('user_id', null);

        // Query history
        $scoresQuery = Score::with('user')->latest();

        // === FILTER ACTIVITY ===
        if ($activityFilter && $activityFilter !== 'all') {
            $scoresQuery->where('activity', $activityFilter);
        }

        // === FILTER PARENT (USER) ===
        if ($userIdFilter) {
            $scoresQuery->where('user_id', $userIdFilter);
        }

        $scores = $scoresQuery
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Leaderboard Total per user
        $leaderboardQuery = Score::select('user_id', DB::raw('SUM(score) as total_score'))
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->with('user')
            ->orderByDesc('total_score');

        if ($activityFilter && $activityFilter !== 'all') {
            $leaderboardQuery->where('activity', $activityFilter);
        }

        if ($userIdFilter) {
            $leaderboardQuery->where('user_id', $userIdFilter);
        }

        $leaderboard = $leaderboardQuery->get();

        // THE BEST per activity
        $activities = ['Journaling Parents', 'Support/Kerjasama', 'Home Gardening', 'Administrasi', 'Lifebook Journey'];
        $bestScores = [];

        foreach ($activities as $activity) {
            $bestRow = Score::where('activity', $activity)
                ->whereNotNull('user_id')
                ->select('user_id', DB::raw('SUM(score) as total_score'))
                ->groupBy('user_id')
                ->orderByDesc('total_score')
                ->first();

            if ($bestRow && $bestRow->user) {
                $bestScores[$activity] = (object) [
                    'parent' => $bestRow->user,
                    'score' => (int) $bestRow->total_score,
                ];
            }
        }

        return view('admin.parents-score', compact('parents', 'scores', 'leaderboard', 'bestScores', 'activityFilter', 'userIdFilter'));
    }

    // FUNGSI SIMPAN
    public function store(Request $request)
    {
        $request->validate([
            'activity' => 'required|string|max:100',
            'score' => 'required|integer|min:1|max:100',
            'parent_ids' => 'required|array|min:1',
            'parent_ids.*' => 'exists:users,id',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        foreach ($request->parent_ids as $userId) {
            Score::create([
                'user_id' => $userId,
                'activity' => $request->activity,
                'score' => $request->score,
                'deskripsi' => $request->deskripsi ?? NULL,
            ]);
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

    // HALAMAN LEADERBOARD (Untuk Front-end)
    public function leaderboard(Request $request)
    {
        $activityFilter = $request->query('activity', 'all');

        // Leaderboard total per user
        $leaderboardQuery = Score::select('user_id', DB::raw('SUM(score) as total_score'))
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->with('user')
            ->orderByDesc('total_score');

        if ($activityFilter !== 'all') {
            $leaderboardQuery->where('activity', $activityFilter);
        }

        $leaderboard = $leaderboardQuery->get();

        // THE BEST per activity
        $activities = ['Journaling Parents', 'Support/Kerjasama', 'Home Gardening', 'Administrasi', 'Lifebook Journey'];
        $bestScores = [];

        foreach ($activities as $activity) {
            $bestRow = Score::where('activity', $activity)
                ->whereNotNull('user_id')
                ->select('user_id', DB::raw('SUM(score) as total_score'))
                ->groupBy('user_id')
                ->orderByDesc('total_score')
                ->first();

            if ($bestRow && $bestRow->user) {
                $bestScores[$activity] = (object) [
                    'parent' => $bestRow->user,
                    'score' => (int) $bestRow->total_score,
                ];
            }
        }

        return view('leaderboard-parents', compact('leaderboard', 'bestScores', 'activityFilter'));
    }

    // HALAMAN EDIT
    public function editscore($id)
    {
        $score = Score::with('user')->findOrFail($id);
        return view('admin.edit-score', compact('score'));
    }

    // FUNGSI EDIT
    public function updatescore(Request $request, $id)
    {
        $request->validate([
            'activity' => 'required|string|max:100',
            'score' => 'required|integer|min:1|max:100',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        $score = Score::findOrFail($id);

        $score->update([
            'activity' => $request->activity,
            'score' => $request->score,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('parents.index')->with('success', 'Data berhasil diperbarui');
    }
}