<?php

namespace App\Console\Commands\LifeEvent;

use Illuminate\Console\Command;
use App\Models\LifeEvent;
use App\Models\Week;
use App\Enums\LifeEventType;

class NewLifeEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'life-event:new';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new event';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userInput = [];
        $skipColumns = ['id', 'week_id', 'properties', 'created_at', 'updated_at'];
        $schema = getTableSchema('life_events');

        foreach ($schema as $column => $type) {
            if (in_array($column, $skipColumns)) {
                continue;
            }

            if ($column === 'type') {
                $userInput[$column] = $this->choice("Choose type", LifeEventType::values(), "personal");
                continue;
            }

            $verb = $column === 'notes' ? 'are' : 'is';
            $value = $this->ask("What $verb the $column?");
            \settype($value, $type);
            $userInput[$column] = $value;
        }

        $week = Week::current();
        $event = [
            'week_id' => $week->id,
            'date' => !empty($userInput['date']) ? $userInput['date'] : null,
            'title' => !empty($userInput['title']) ? $userInput['title'] : null,
            'description' => !empty($userInput['description']) ? $userInput['description'] : null,
            'type' => !empty($userInput['type']) ? LifeEventType::tryFrom($userInput['type']) : LifeEventType::PERSONAL,
            'notes' => !empty($userInput['notes']) ? $userInput['notes'] : null,
        ];

        LifeEvent::create($event);
        $this->info('Event created!');
    }
}
