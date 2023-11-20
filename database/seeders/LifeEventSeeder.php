<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Week;
use App\Models\LifeEvent;
use App\Modules\LifeStatistics;

class LifeEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        LifeEvent::truncate();

        $birthday = LifeStatistics::getBirthDate();
        $quarterLife = LifeStatistics::getQuarterLifeDate();
        $age30 = LifeStatistics::getAge30Date();
        $midLife = LifeStatistics::getMidLifeDate();
        $deathDate = LifeStatistics::getDeathDate();

        $defaultLifeEvents = [
            [
                'week_id' => Week::getWeekByDate($birthday)->id, 
                'title' => 'Birth',
                'date' => $birthday,
                'description' => 'You were born!',
                'properties' => [
                    'icon' => '👶🏼',
                ]
            ],
            [
                'week_id' => Week::getWeekByDate($quarterLife)->id, 
                'title' => 'Quarter Life',
                'date' => $quarterLife,
                'description' => 'Quarter of the way there',
                'properties' => [
                    'icon' => '¼',
                ]
            ],
            [
                'week_id' => Week::getWeekByDate($age30)->id, 
                'title' => 'Age 30',
                'date' => $age30,
                'description' => 'Ugh',
                'properties' => [
                    'icon' => '😫',
                ]
            ],
            [
                'week_id' => Week::getWeekByDate($midLife)->id, 
                'title' => 'Mid Life',
                'date' => $midLife,
                'description' => 'Halfway there',
                'properties' => [
                    'icon' => '🌗',
                ]
            ],
            [
                'week_id' => Week::getWeekByDate($deathDate)->id, 
                'title' => 'Death',
                'date' => $deathDate,
                'description' => 'It\'s time to go',
                'properties' => [
                    'icon' => '💀',
                ]
            ],

        ];
        
        foreach($defaultLifeEvents as $lifeEvent) {
            LifeEvent::create($lifeEvent);
        }

    }
}