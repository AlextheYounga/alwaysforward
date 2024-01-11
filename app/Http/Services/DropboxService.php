<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DropboxService
{
    protected $accessToken;
    protected $refreshToken;
    protected $dropboxPath;
    protected $localTodoPath;
    protected $appKey;
    protected $appSecret;

    public function __construct()
    {
        $refreshToken = Storage::disk('public')->get('.refresh_token');

        if (!empty($refreshToken)) {
            new \Exception('No Dropbox token found. Please run `scripts/dropbox/auth.sh`');
        }

        $tokenData = Storage::disk('public')->get('token.json');
        $tokenData = json_decode($tokenData, true);

        if (time() > $tokenData['expires_at']) {
            $tokenData = $this->refreshToken();
        }

        $this->accessToken = isset($tokenData['result']['access_token']) ?
            $tokenData['result']['access_token'] :
            $tokenData['access_token'];

        $this->refreshToken = $refreshToken;
        $this->dropboxPath = env('DROPBOX_TODO_PATH');
        $this->appKey = env('DROPBOX_APP_KEY');
        $this->appSecret = env('DROPBOX_APP_SECRET');
        $this->localTodoPath = storage_path('app/public/todo/todo.txt');
    }

    public function download()
    {
        $endpoint = 'https://content.dropboxapi.com/2/files/download';

        $payload = [
            "path" => $this->dropboxPath
        ];

        $response = Http::withToken($this->accessToken)
            ->withHeaders([
                'Dropbox-API-Arg' => json_encode($payload),
            ])
            ->get($endpoint);

        Storage::disk('public')->put('todo/todo.dropbox.bak', $response->body());
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
            'Authorization: Bearer ' . $this->accessToken,
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
            return true;
        } else {
            echo "Error: " . curl_error($ch);
            return false;
        }
    }

    public function refreshToken()
    {
        /*
        * curl https://api.dropbox.com/oauth2/token \
        *  -d grant_type=refresh_token \
        *  -d refresh_token=<REFRESH_TOKEN> \
        *  -d client_id=<APP_KEY> \
        *  -d client_secret=<APP_SECRET>
        */

        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, "https://api.dropbox.com/oauth2/token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->refreshToken,
            'client_id' => $this->appKey,
            'client_secret' => $this->appSecret,
        ]));

        // Execute cURL request
        $response = curl_exec($ch);

        // Check for errors
        if(curl_errno($ch)) {
            throw new \Exception(curl_error($ch));
        }

        // Close cURL session
        curl_close($ch);

        // Decode the JSON response
        $responseData = json_decode($response, true);

        $this->accessToken = $responseData['access_token'];
        $responseData['expires_at'] = time() + $responseData['expires_in'];

        Storage::disk('public')->put('token.json', json_encode($responseData));

        return $responseData;
    }
}
