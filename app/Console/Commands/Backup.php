<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Goal;
use App\Models\Task;
use App\Models\Board;
use App\Models\LifeEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Backup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup important app data.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Backing up app data...');
        $boards = Board::all()->makeHidden('lanes');
        $goals = Goal::all();
        $tasks = Task::all();
        $weekTasks = DB::table('task_week')->get();
        $events = LifeEvent::all();

        $data = collect([
            'boards' => $boards,
            'goals' => $goals,
            'tasks' => $tasks,
            'week_tasks' => $weekTasks,
            'events' => $events,
        ]);

        Storage::disk('public')->put('backup.json', json_encode($data));
    }
}
