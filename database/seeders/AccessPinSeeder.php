<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AccessPin;

class AccessPinSeeder extends Seeder
{
    public function run(): void
    {
        AccessPin::create([
            'pin' => '123456',   // default PIN
            'is_active' => true,
        ]);
    }
}