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
    protected $signature = 'app:terminal';

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
            'New Task'
        ];

        $choice = $this->choice('Select an option', $directory);

        switch ($choice) {
            case 'View Time':
                $this->call('life:time');
                break;
            case 'See Goals':
                echo 'test';
                break;
            case 'See Tasks':
                echo 'test';
                break;
            case 'Make Goal':
                echo 'test';
                break;
            case 'Make Task':
                echo 'test';
                break;
        }
    }
}
