<?php

namespace App\Console\Commands\Task;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Models\Goal;
use App\Enums\Priority;
use App\Enums\TaskStatus;
use App\Enums\Type;
use App\Models\Week;
use Exception;
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

        $userInput = $this->getUserInput();

        $task = [
            'goal_id' => !empty($userInput['goal_id']) ? $userInput['goal_id'] : null,
            'title' => !empty($userInput['title']) ? $userInput['title'] : null,
            'description' => !empty($userInput['description']) ? $userInput['description'] : null,
            'type' => !empty($userInput['type']) ? Type::tryFrom($userInput['type']) : Type::PERSONAL,
            'priority' => !empty($userInput['priority']) ? Priority::tryFrom($userInput['priority']) : Priority::NORMAL,
            'due_date' => $this->parseDueDate($userInput['due_date']),
            'notes' => !empty($userInput['notes']) ? $userInput['notes'] : null,
            'status' => !empty($userInput['status']) ? TaskStatus::tryFrom($userInput['status']) : TaskStatus::BACKLOG,
        ];

        $week = Week::current();
        $taskRecord = Task::create($task);
        $week->tasks()->attach($taskRecord->id);

        $this->info('Task created!');
    }

    private function getUserInput()
    {
        $userInput = [];
        $skipColumns = ['id', 'status', 'date_completed', 'created_at', 'updated_at'];
        $schema = getTableSchema('tasks');

        foreach ($schema as $column => $type) {
            if (in_array($column, $skipColumns)) {
                continue;
            }

            if ($column === 'goal_id') {
                $goals = Goal::active()->get();
                $goalsArray = $goals->map(fn($goal) => $goal->title)->toArray();
                $goalChoices = array_merge([0 => 'None'], $goalsArray);

                if ($goals) {
                    $goalSelect = $this->choice("Attach to goal?", $goalChoices, null);
                    $goalSelect = $goalSelect === 'None' ? null : $goalSelect;

                    if ($goalSelect) {
                        $goal = $goals->firstWhere('title', $goalSelect);
                        $userInput['goal_id'] = $goal->id;
                    } else {
                        $userInput['goal_id'] = null;
                    }
                }
                continue;
            }

            if ($column === 'type') {
                $userInput[$column] = $this->choice("Choose type", Type::values(), "personal");
                continue;
            }

            if ($column === 'priority') {
                $userInput[$column] = $this->choice("Choose priority", Priority::values(), 0);
                continue;
            }

            $verb = $column === 'notes' ? 'are' : 'is';
            $value = $this->ask("What $verb the $column?");
            \settype($value, $type);
            $userInput[$column] = $value;
        }

        return $userInput;
    }

    private function parseDueDate($input)
    {
        if ($input === 'eow') {
            return Carbon::now()->endOfWeek();
        }

        return !empty($input['due_date']) ? Carbon::parse($input['due_date'])->endOfDay() : null;
    }
}
