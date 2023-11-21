<?php

namespace App\Console\Commands\Task;

use App\Enums\TaskStatus;
use Illuminate\Console\Command;
use App\Models\Task;

class MarkTaskStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:mark';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark a task complete';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tasks = Task::active()->get();
        $taskArray = $tasks->map(fn($task) => $task->title)->toArray();

        if ($tasks) {
            $taskSelect = $this->choice("Mark a task complete.", $taskArray, null);
            $task = $tasks->firstWhere('title', $taskSelect);

            $statuses = TaskStatus::values();
            $status = $this->choice("Mark a task complete.", $statuses, null);

            $task->status = TaskStatus::tryFrom($status);
            $task->save();
        } else {
            $this->info("No tasks yet");
            return;
        }
    }
}
