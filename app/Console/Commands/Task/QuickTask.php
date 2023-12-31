<?php

namespace App\Console\Commands\Task;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Models\Goal;
use App\Enums\Priority;
use App\Enums\TaskStatus;
use App\Enums\Type;
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
        $onlyColumns = ['goal_id', 'title', 'description', 'due_date', 'type'];
        $schema = getTableSchema('tasks');

        foreach ($schema as $column => $type) {
            if (in_array($column, $onlyColumns)) {
                if ($column === 'goal_id') {
                    $goals = Goal::all();
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

                $value = $this->ask("What is the $column?");
                \settype($value, $type);
                $userInput[$column] = $value;
            }
        }

        $task = [
            'goal_id' => !empty($userInput['goal_id']) ? $userInput['goal_id'] : null,
            'title' => !empty($userInput['title']) ? $userInput['title'] : null,
            'description' => !empty($userInput['description']) ? $userInput['description'] : null,
            'type' => !empty($userInput['type']) ? $userInput['type'] : Type::PERSONAL,
            'priority' => Priority::NORMAL,
            'due_date' => $this->parseDueDate($userInput['due_date']),
            'notes' => null,
            'status' => !empty($userInput['status']) ? TaskStatus::tryFrom($userInput['status']) : TaskStatus::TODO,
        ];

        $week = Week::current();
        $taskRecord = Task::create($task);
        $week->tasks()->attach($taskRecord->id);

        $this->info('Task created!');
    }

    private function parseDueDate($input)
    {
        if ($input === 'eow') {
            return Carbon::now(env('APP_TIMEZONE', 'UTC'))->endOfWeek();
        }

        return !empty($input['due_date']) ? Carbon::parse($input['due_date'])->endOfDay() : null;
    }
}
