<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Week;
use App\Models\LifeEvent;
use App\Models\LifeStatistics;
use App\Enums\LifeEventType;

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
                'week_id' => Week::first()->id, 
                'title' => 'Birth',
                'date' => $birthday,
                'description' => 'You were born!',
                'type' => LifeEventType::INEVITABLE,
                'properties' => [
                    'icon' => '👶🏼',
                ]
            ],
            [
                'week_id' => Week::getWeekByDate($quarterLife)->id, 
                'title' => 'Quarter Life',
                'date' => $quarterLife,
                'description' => 'Quarter of the way there',
                'type' => LifeEventType::INEVITABLE,
                'properties' => [
                    'icon' => '¼',
                ]
            ],
            [
                'week_id' => Week::getWeekByDate($age30)->id, 
                'title' => 'Age 30',
                'date' => $age30,
                'description' => 'Ugh',
                'type' => LifeEventType::INEVITABLE,
                'properties' => [
                    'icon' => '😫',
                ]
            ],
            [
                'week_id' => Week::getWeekByDate($midLife)->id, 
                'title' => 'Mid Life',
                'date' => $midLife,
                'description' => 'Halfway there',
                'type' => LifeEventType::INEVITABLE,
                'properties' => [
                    'icon' => '🌗',
                ]
            ],
            [
                'week_id' => Week::all()->last()->id, 
                'title' => 'Death',
                'date' => $deathDate,
                'description' => 'It\'s time to go',
                'type' => LifeEventType::INEVITABLE,
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