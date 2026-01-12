<?php

namespace App\Http\Controllers;

use App\Models\LifebookJourney;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LifebookJourneyController extends Controller
{
    private $categories = [
        ['id' => 'spiritual', 'name' => 'Spiritual', 'icon' => 'moon'],
        ['id' => 'health', 'name' => 'Health and Fitness', 'icon' => 'heart'],
        ['id' => 'intellectual', 'name' => 'Intellectual', 'icon' => 'brain'],
        ['id' => 'life_skill', 'name' => 'Life Skill', 'icon' => 'wrench'],
        ['id' => 'emotional', 'name' => 'Emotional', 'icon' => 'smile'],
        ['id' => 'family', 'name' => 'Family life', 'icon' => 'users'],
        ['id' => 'social', 'name' => 'Social life', 'icon' => 'message-circle'],
        ['id' => 'financial', 'name' => 'Financial life', 'icon' => 'dollar-sign'],
        ['id' => 'career', 'name' => 'Career life', 'icon' => 'briefcase'],
        ['id' => 'quality', 'name' => 'Quality life', 'icon' => 'star']
    ];

    public function index()
    {
        $user = Auth::user();
        $journeys = LifebookJourney::where('user_id', $user->id)->get()->keyBy('category');

        $categories = $this->categories;
        return view('page.lifebook-journey', compact('journeys', 'categories'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'field' => 'required|string|in:premise,vision,purpose,strategy',
            'content' => 'nullable|string'
        ]);

        $user = Auth::user();

        $journey = LifebookJourney::updateOrCreate(
            ['user_id' => $user->id, 'category' => $request->input('category')],
            [$request->input('field') => $request->input('content')]
        );

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan',
            'data' => $journey
        ]);
    }
}
