<?php

namespace App\Console\Commands\Task;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Models\Goal;
use App\Enums\Priority;
use App\Enums\TaskStatus;
use App\Models\Week;
use Illuminate\Support\Carbon;

class QuickTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:quick';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new task quickly';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userInput = [];
        $onlyColumns = ['goal_id', 'title', 'description', 'due_date'];
        $schema = getTableSchema('tasks');

        $week = Week::current();

        foreach ($schema as $column => $type) {
            if (in_array($column, $onlyColumns)) {
                if ($column === 'goal_id') {
                    $goals = Goal::all(['title'])->toArray();
                    if ($goals) {
                        $userInput[$column] = $this->choice("Attach to goal?", $goals, null);
                    }
                    continue;
                }

                if ($column === 'priority') {
                    $userInput[$column] = $this->choice("Choose $column?", Priority::values(), 0);
                    continue;
                }

                $value = $this->ask("What is the $column?");
                \settype($value, $type);
                $userInput[$column] = $value;
            }
        }

        $timezone = env('APP_TIMEZONE', 'America/New_York');

        $task = [
            'week_id' => $week->id,
            'goal_id' => !empty($userInput['goal_id']) ? $userInput['goal_id'] : null,
            'title' => !empty($userInput['title']) ? $userInput['title'] : null,
            'description' => !empty($userInput['description']) ? $userInput['description'] : null,
            'type' => null,
            'priority' => Priority::NORMAL,
            'duration' => null,
            'time_spent' => null,
            'start_date' => null,
            'due_date' => !empty($userInput['due_date']) ? Carbon::parse($userInput['due_date'])->timezone($timezone) : null,
            'subtasks' => null,
            'notes' => null,
            'status' => !empty($userInput['status']) ? TaskStatus::tryFrom($userInput['status']) : TaskStatus::TODO,
        ];

        Task::create($task);
        $this->info('Task created!');
    }
}
