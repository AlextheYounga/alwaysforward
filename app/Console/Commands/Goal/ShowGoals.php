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
        $goals = Goal::select(['title', 'description', 'due_date'])
            ->active()
            ->get()
            ->toArray();

        if (empty($goals)) {
            $this->info("No goals yet");
            return;
        }

        $this->info('Goals');
        $this->info('-----------------');
        $this->table(
            ['Name', 'Description', 'Due Date'],
            $goals
        );
    }
}
