<?php

namespace App\Console\Commands\Todo;

use Illuminate\Console\Command;
use App\Http\Services\DropboxService;

class Push extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'todo:push';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push local todo.txt to Dropbox';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dropbox = new DropboxService();

        # Download todos from Dropbox
        $dropbox->download();

        // Upload todos to Dropbox
        $dropbox->upload();
    }
}
