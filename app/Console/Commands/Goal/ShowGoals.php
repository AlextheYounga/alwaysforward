<?php

namespace App\Console\Commands\Goal;

use Illuminate\Console\Command;

class ShowGoals extends Command
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
     * @var stringTas
     */
    protected $description = 'Show tasks for this week.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}
