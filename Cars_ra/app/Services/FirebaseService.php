<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class FirebaseService
{
    protected $url = 'https://fcm.googleapis.com/fcm/send';

    public function sendNotification($token, $title, $body, $data = [])
    {
        $serverKey = config('services.fcm.server_key');

        $response = Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type' => 'application/json',
        ])->post($this->url, [
            'to' => $token,
            'notification' => [
                'title' => $title,
                'body' => $body,
                'sound' => 'default',
            ],
            'data' => $data,
        ]);

        return $response->json();
    }
}
