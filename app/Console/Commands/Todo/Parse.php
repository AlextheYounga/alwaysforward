<?php

namespace App\Console\Commands\Todo;

use Illuminate\Console\Command;
use TodoTxtParser\TodoTxtParser;
use Illuminate\Support\Facades\Storage;
use App\Enums\Priority;
use App\Enums\TaskStatus;
use Carbon\Carbon;
use App\Models\Task;

class Parse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'todo:parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse todo.txt file and save to tasks table.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // TODO: Save contexts into tasks table

        $todoFile = Storage::disk('public')->get('todo/todo.txt');
        $parser = new TodoTxtParser();
            
        $todos = explode("\n", $todoFile);

        foreach($todos as $todo) {
            $task = $parser->buildTaskFromString($todo);

            $addOns= $task->getAddOns();
            $title = $task->getCleanText();
            $rawString = $task->getOriginalText();
            $isComplete = $task->isCompleted();
            $completedAt = $task->getCompletedAt();
            $priority = $task->getPriority();
            $createdAt = $task->getCreatedAt();
            $projects = $task->getProjects();
            $contexts = $task->getContexts();
            $dueDate = $addOns['due'] ?? null;

            $priorityLevel = [
                'A' => Priority::LOW,
                'B' => Priority::NORMAL,
                'C' => Priority::HIGH,
                'D' => Priority::SUPER,
            ];

            $notes = 'Contexts: ' . implode($contexts) . "\n" . 'Projects: ' . implode($projects);
            $status = $isComplete ? TaskStatus::COMPLETED : TaskStatus::BACKLOG;

            if (Task::where('title', $title)->exists()) {
                continue;
            }
            
            Task::create([
                'title' => $title,
                'description' => $rawString,
                'type' => in_array('work', $contexts) ? 'work' : 'personal',
                'priority' => !empty($priority) ? $priorityLevel[$priority] : Priority::NORMAL,
                'due_date' => Carbon::parse($dueDate)->endOfDay(),
                'date_completed' => Carbon::parse($completedAt),
                'notes' => $notes,
                'status' => $status,
                'created_at' => Carbon::parse($createdAt),  
            ]);
        }
    }
}
