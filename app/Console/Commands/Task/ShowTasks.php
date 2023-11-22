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
        $this->newLine();

        $tasks = Task::all();
        $work = Task::select(['title', 'description', 'due_date'])->work();
        $personal = Task::select(['title', 'description', 'due_date'])->personal();

        if ($tasks->count() === 0) {
            $this->info("No tasks yet");
            return;
        }

        if ($personal->count() !== 0) {
            $this->comment('Personal');
            $this->table(
                ['Name', 'Description', 'Due Date'],
                $personal->get()
                    ->toArray()
            );

            $this->newLine();
        }


        if ($work->count() !== 0) {
            $this->comment('Work');
            $this->table(
                ['Name', 'Description', 'Due Date'],
                $work->get()
                    ->toArray()
            );
        }
    }
}
