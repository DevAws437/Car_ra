<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NotificationController extends Controller
{
    public function sendNotification(Request $request)
    {
        $fcmToken = $request->fcm_token;

        if (!$fcmToken) {
            return response()->json(['error' => 'FCM Token مفقود'], 422);
        }

        $firebaseServerKey = 'BJDE5mbUXQuTSw4n5KCPHwqx2u4NpmM5qJsIkfmhtPps3Sla6qi8Uqnh99D6vPSpAL7LMTvvNu_Hbh4XUvZSQv0'; // انسخه من Firebase Console

        $response = Http::withHeaders([
            'Authorization' => 'key=' . $firebaseServerKey,
            'Content-Type' => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', [
            'to' => $fcmToken,
            'notification' => [
                'title' => 'إشعار من Laravel 🚀',
                'body' => 'وصل إشعارك للمتصفح!',
                'icon' => '/logo.png', // اختياري
                'click_action' => 'https://your-site.com/', // لما يضغط على الإشعار
            ],
        ]);

        return response()->json([
            'status' => 'تم الإرسال',
            'firebase_response' => $response->json()
        ]);
    }
}
