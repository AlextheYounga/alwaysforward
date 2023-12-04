<?php

namespace App\Console\Commands\LifeEvent;

use Illuminate\Console\Command;
use App\Models\LifeEvent;

class ShowLifeEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'life-events:new';

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

        $events = LifeEvent::upcoming()
            ->select(['title', 'description', 'date']);

        if ($events->count() === 0) {
            $this->info("No tasks yet");
            return;
        }

        $this->table(
            ['Title', 'Description', 'Due Date'],
            $events->get()->toArray()
        );

    }
}
