<?php

namespace App\Console\Commands\Goal;

use Illuminate\Console\Command;
use App\Models\Goal;
use App\Enums\Priority;
use App\Enums\GoalStatus;
use Carbon\Carbon;

class NewGoal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'goals:new';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new goal';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userInput = [];
        $skipColumns = ['id', 'created_at', 'updated_at'];
        $schema = getTableSchema('goals');

        foreach ($schema as $column => $type) {
            if (in_array($column, $skipColumns)) {
                continue;
            }

            if ($column === 'priority') {
                $userInput[$column] = $this->choice("What is the $column?", Priority::values(), 0);
                continue;
            }

            if ($column === 'status') {
                $userInput[$column] = $this->choice("What is the $column?", GoalStatus::values(), 'active');
                continue;
            }

            $value = $this->ask("What is the $column?");
            \settype($value, $type);

            $userInput[$column] = $value;
        }

        $goal = [
            'title' => !empty($userInput['title']) ? $userInput['title'] : null,
            'description' => !empty($userInput['description']) ? $userInput['description'] : null,
            'target_value' => !empty($userInput['target_value']) ? $userInput['target_value'] : null,
            'target_units' => !empty($userInput['target_units']) ? $userInput['target_units'] : null,
            'type' => !empty($userInput['type']) ? $userInput['type'] : null,
            'completion_type' => !empty($userInput['completion_type']) ? $userInput['completion_type'] : null,
            'priority' => !empty($userInput['priority']) ? Priority::tryFrom($userInput['priority']) : Priority::NORMAL,
            'due_date' => !empty($userInput['due_date']) ? Carbon::parse($userInput['due_date']) : null,
            'status' => !empty($userInput['status']) ? GoalStatus::tryFrom($userInput['status']) : GoalStatus::ACTIVE,
            'notes' => !empty($userInput['notes']) ? $userInput['notes'] : null,
        ];

        Goal::create($goal);
        $this->info('Goal created!');
    }
}
