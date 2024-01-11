<?php

namespace App\Console\Commands\Todo;

use Illuminate\Console\Command;
use App\Http\Services\DropboxService;

class Pull extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'todo:pull';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pull todo.txt from Dropbox';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dropbox = new DropboxService();

        # Download todos from Dropbox
        $dropbox->download();
    }
}
