<?php

namespace App\Console\Commands\Task;

use Illuminate\Console\Command;
use App\Models\Task;

class ShowTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:show';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show tasks for this week';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tasks = Task::current(['title', 'description', 'due_date'])->toArray();

        if (empty($tasks)) {
            $this->info("No tasks yet");
            return;
        }

        $this->info('Tasks');
        $this->info('-----------------');
        $this->table(
            ['Name', 'Description', 'Due Date'],
            $tasks
        );
    }
}
