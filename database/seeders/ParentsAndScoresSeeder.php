<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ParentModel;
use App\Models\Score;

class ParentsAndScoresSeeder extends Seeder
{
    public function run(): void
    {
        // --- 10 data parents ---
        $parents = [];
        for ($i = 1; $i <= 10; $i++) {
            $parents[] = ParentModel::create([
                'name'  => 'Parent '.$i,
                'image' => null,
            ]);
        }

        // --- daftar activity ---
        $activities = [
            'Journaling Parents',
            'Support/Kerjasama',
            'Home Gardening',
            'Administrasi',
            'Lifebook Journey',
        ];

        // --- 15 data scores random ---
        for ($j = 1; $j <= 15; $j++) {
            $parent = $parents[array_rand($parents)];
            $activity = $activities[array_rand($activities)];
            $score = rand(40, 100);

            Score::create([
                'parent_id' => $parent->id,
                'activity'  => $activity,
                'score'     => $score,
            ]);
        }
    }
}