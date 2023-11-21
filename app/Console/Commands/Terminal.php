<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Terminal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cli';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A terminal command line interface for the app.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $directory = [
            'View Time',
            'See Goals',
            'See Tasks',
            'New Goal',
            'New Task',
            'Change Task Status',
            'Change Goal Status',
        ];

        $choice = $this->choice('Select an option', $directory);

        switch ($choice) {
            case 'View Time':
                $this->call('life:time');
                break;
            case 'See Goals':
                $this->call('goals:show');
                break;
            case 'See Tasks':
                $this->call('tasks:show');
                break;
            case 'New Goal':
                $this->call('goals:new');
                break;
            case 'New Task':
                $this->call('tasks:new');
                break;
            case 'Change Task Status':
                $this->call('tasks:mark');
                break;
            case 'Change Goal Status':
                $this->call('goals:mark');
                break;
        }
    }
}
