<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Week;
use App\Models\PlatformConfig;

class WeekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Week::truncate();

        $birthday = PlatformConfig::whereKey('birthday')->getValue();
        $deathAge = PlatformConfig::whereKey('death_age')->getValue();

        $weeks = Week::generateWeeksTimeline($birthday, $deathAge);

        foreach($weeks as $week) {
            Week::create([
                'start' => $week['start'],
                'end' => $week['end'],
                'age' => $week['age'],
            ]);
        }
    }
}
