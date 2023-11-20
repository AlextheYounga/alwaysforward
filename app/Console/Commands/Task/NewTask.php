<?php

namespace App\Console\Commands\Task;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Models\Goal;
use App\Enums\Priority;
use App\Enums\TaskStatus;
use App\Models\Week;
use Illuminate\Support\Carbon;

class NewTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:new';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new task';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userInput = [];
        $skipColumns = ['id', 'created_at', 'updated_at'];
        $schema = getTableSchema('tasks');

        

        foreach ($schema as $column => $type) {
            if (in_array($column, $skipColumns)) {
                continue;
            }

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

            if ($column === 'status') {
                $userInput[$column] = $this->choice("Choose $column?", TaskStatus::values(), 'todo');
                continue;
            }

            $value = $this->ask("What is the $column?");
            \settype($value, $type);
            $userInput[$column] = $value;
        }

        $timezone = env('APP_TIMEZONE', 'America/New_York');

        $task = [
            'goal_id' => !empty($userInput['goal_id']) ? $userInput['goal_id'] : null,
            'title' => !empty($userInput['title']) ? $userInput['title'] : null,
            'description' => !empty($userInput['description']) ? $userInput['description'] : null,
            'type' => !empty($userInput['type']) ? $userInput['type'] : null,
            'priority' => !empty($userInput['priority']) ? Priority::tryFrom($userInput['priority']) : Priority::NORMAL,
            'duration' => !empty($userInput['duration']) ? $userInput['duration'] : null,
            'time_spent' => !empty($userInput['time_spent']) ? $userInput['time_spent'] : null,
            'start_date' => !empty($userInput['start_date']) ? Carbon::parse($userInput['start_date'])->timezone($timezone) : null,
            'due_date' => !empty($userInput['due_date']) ? Carbon::parse($userInput['due_date'])->timezone($timezone) : null,
            'subtasks' => !empty($userInput['subtasks']) ? $userInput['subtasks'] : null,
            'notes' => !empty($userInput['notes']) ? $userInput['notes'] : null,
            'status' => !empty($userInput['status']) ? TaskStatus::tryFrom($userInput['status']) : TaskStatus::TODO,
        ];

        $week = Week::current();
        $taskRecord = Task::create($task);
        $week->tasks()->attach($taskRecord->id);

        $this->info('Task created!');
    }
}
