<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VolunteerMissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $missions = [
            'Piket peternakan di hari Sabtu',
            'Piket peternakan di hari Minggu',
            'Piket perkebunan di hari Sabtu',
            'Piket perkebunan di hari Minggu',
            'Piket pertukangan di hari Sabtu',
            'Piket pertukangan di hari Minggu',
            'Membuat karya untuk dipajang di ruang gallery',
            'Menjual makanan "homemade" di kafe kebangsaan',
            'Menjadi bagian dari kepanitiaan acara Production',
            'Mengikuti (hadir dan ikut serta) di kegiatan ekosistem',
            'Mengikuti (hadir dan ikut serta) kegiatan 17 Agustusan',
            'Menjadi guru pengganti ketika guru yang bersangkutan sedang tidak masuk',
            'Mengikuti (menemani anak) saat outing dan fieldtrip',
            'Menjadi instruktur (dipromosikan oleh RBI) sesuai dengan kemampuan / keahlian yang dimiliki',
        ];

        foreach ($missions as $mission) {
            \App\Models\VolunteerMission::updateOrCreate(['name' => $mission]);
        }
    }
}
