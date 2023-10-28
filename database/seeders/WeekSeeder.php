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

        if (PlatformConfig::count() === 0) {
            PlatformConfig::create([
                'key' => 'birthday', 
                'value' => '1995-11-13',
                'type' => 'string',
            ]);

            PlatformConfig::create([
                'key' => 'death_age', 
                'value' => '90',
                'type' => 'integer',
            ]);
        }

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
