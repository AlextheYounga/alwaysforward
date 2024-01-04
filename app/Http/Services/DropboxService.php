<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DropboxService
{
    protected $token;
    protected $dropboxPath;
    protected $localTodoPath;

    public function __construct()
    {
        $this->token = env('DROPBOX_API_KEY');
        $this->dropboxPath = env('DROPBOX_TODO_PATH');
        $this->localTodoPath = storage_path('app/public/todo.txt');
    }

    public function download()
    {
        $endpoint = 'https://content.dropboxapi.com/2/files/download';

        $payload = [
            "path" => $this->dropboxPath
        ];

        $response = Http::withToken($this->token)
            ->withHeaders([
                'Dropbox-API-Arg' => json_encode($payload),
            ])
            ->get($endpoint);

        Storage::disk('public')->put('todo.dropbox.bak', $response->body());
    }

    public function upload()
    {
        /* I hate this, but the upload API endpoint works much better when using CURL.
        * curl -X POST https://content.dropboxapi.com/2/files/upload \
        *     --header "Authorization: Bearer $DROPBOX_API_KEY" \
        *     --header "Dropbox-API-Arg: {\"autorename\":false,\"mode\":\"overwrite\",\"mute\":false,\"path\":\"/Apps/AlwaysForward/todo.txt\",\"strict_conflict\":false}" \
        *     --header "Content-Type: application/octet-stream" \
        *     --data-binary @$ALWAYS_FORWARD_DIRECTORY/storage/app/public/todo.txt
        */

        $uploadPath = $this->dropboxPath;

        $params = array(
            "path" => $uploadPath,
            "mode" => "overwrite",
            "autorename" => true,
            "mute" => false
        );

        // Setup the headers and data
        $headers = array(
            'Authorization: Bearer ' . $this->token,
            'Content-Type: application/octet-stream',
            'Dropbox-API-Arg: ' . json_encode($params)
        );

        // Initialize cURL session
        $ch = curl_init('https://content.dropboxapi.com/2/files/upload');

        // Set cURL options
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents($this->localTodoPath));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // To return the transfer as a string

        // Execute the cURL session and close it
        $response = curl_exec($ch);
        curl_close($ch);

        // Handle the response
        if ($response) {
            echo "Success: " . $response . PHP_EOL;
            return true;
        } else {
            echo "Error: " . curl_error($ch);
            return false;
        }
    }
}
