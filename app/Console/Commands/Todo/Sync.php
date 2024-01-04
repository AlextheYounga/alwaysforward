<?php

namespace App\Console\Commands\Todo;

use Illuminate\Console\Command;
use App\Http\Services\DropboxService;

class Sync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'todo:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync todo.txt with Dropbox';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        # Download todos from Dropbox
        $dropbox = new DropboxService();
        $dropbox->upload();
        $dropbox->download();
    }
}
