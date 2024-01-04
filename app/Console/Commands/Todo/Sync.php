<?php

namespace App\Console\Commands\Todo;

use Illuminate\Console\Command;
use App\Http\Services\DropboxService;
use Illuminate\Support\Facades\Storage;

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
        $dropbox->download();

        $dropboxTodos = Storage::disk('public')->get('todo.dropbox.bak');
        $localTodos = Storage::disk('public')->get('todo.txt');

        // Backup local todos
        Storage::disk('public')->put('todo.local.bak', $localTodos);

        $merged = $dropboxTodos . $localTodos;

        // Merge and deduplicate todos
        $merged = $this->mergeTodos($localTodos, $dropboxTodos);
        $trimmed = $this->trimTodos($merged);


        // Save todos
        $this->info($trimmed);
        Storage::disk('public')->put('todo.txt', $trimmed);

        // Upload todos to Dropbox
        $dropbox->upload();
    }

    private function mergeTodos($local, $remote)
    {
        $localArray = explode("\n", $local);
        $mergedTodos = explode("\n", $remote); // Prioritizing remote

        foreach($localArray as $todo) {
            $duplicate = false;

            foreach($mergedTodos as $item) {
                similar_text($item, $todo, $similarity);
                if ($similarity > 75) {
                    $duplicate = true;
                    break;
                }
            }

            // If not mostly a duplicate, add local todo to final todo list.
            if ($duplicate === false) {
                array_push($mergedTodos, $todo);
            }
        }

        return implode("\n", $mergedTodos);
    }

    private function trimTodos($todos) {
        $todoList = explode("\n", $todos);

        $trimmed = array_filter($todoList, function($item) {
            return $item !== "";
        });

        return implode("\n", $trimmed);
    }
}
