<?php

namespace App\Console\Commands\Goal;

use Illuminate\Console\Command;
use App\Models\Goal;

class ShowGoals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'goals:show';

    /**
     * The console command description.
     *
     * @var stringTas
     */
    protected $description = 'Show goals';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->newLine();

        $goals = Goal::all();
        $work = Goal::select(['title', 'description', 'due_date'])->work();
        $personal = Goal::select(['title', 'description', 'due_date'])->personal();

        if ($goals->count() === 0) {
            $this->info("No goals yet");
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
