<?php

namespace App\Http\Controllers;

use App\Models\user_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class push_notification_controller extends Controller
{
    public function sendPushNotification(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Fetch active users with device tokens
        $users = user_model::where('status', 'active')
            ->whereNotNull('device_token')
            ->pluck('device_token', 'name'); // pluck token and name

        // Check if there are no active users
        if ($users->isEmpty()) {
            return response()->json(['message' => 'No active users found.'], 404);
        }

        // Prepare device tokens
        $deviceTokens = $users->keys()->toArray(); // Device tokens as values
        $userNames = $users->keys()->toArray(); // User names as keys (but pluck above might not align, need to check)

        // **Note:** The above line might be incorrect depending on how `pluck` is used. It would be better to have device tokens and user names properly aligned.

        // Alternatively, prepare an array of tokens with corresponding names
        $tokensWithNames = $users->map(function($token, $name) {
            return ['token' => $token, 'name' => $name];
        })->toArray();

        // Initialize payload
        $payload = [
            'registration_ids' => array_column($tokensWithNames, 'token'),
            'notification' => [
                'title' => $request->input('title'),
                'body' => '', // We'll set this per user
            ],
            'priority' => 'high',
        ];

        // **Issue:** Customizing notification body per user is not directly supported when sending to multiple tokens.
        // **Solution:** To personalize messages, you need to send individual notifications or use FCM topics or data messages.

        // Given the requirement to personalize each message ("Hi {name}, ..."), we need to send individual requests or use a different approach.

        // **Option 1: Send Individual Notifications**
        foreach ($tokensWithNames as $user) {
            $notification = [
                'to' => $user['token'],
                'notification' => [
                    'title' => $request->input('title'),
                    'body' => 'Hi ' . $user['name'] . ', ' . $request->input('message'),
                    'sound' => 'default',
                ],
                'priority' => 'high',
            ];

            try {
                $response = Http::withHeaders([
                    'Authorization' => 'key=' . config('services.fcm.server_key'),
                    'Content-Type' => 'application/json',
                ])->post('https://fcm.googleapis.com/fcm/send', $notification);

                if ($response->failed()) {
                    Log::error('FCM error for user ' . $user['name'] . ': ' . $response->body());
                    // Continue to next user
                    continue;
                }

                $responseData = $response->json();

                if (isset($responseData['error'])) {
                    Log::error('FCM error for user ' . $user['name'] . ': ' . $responseData['error']);
                    // Continue to next user
                    continue;
                }

            } catch (\Exception $e) {
                Log::error('Exception while sending FCM to user ' . $user['name'] . ': ' . $e->getMessage());
                // Continue to next user
                continue;
            }
        }

        return response()->json(['message' => 'Notifications sent successfully.'], 200);
    }



// public function sendPushNotification(Request $request)
// {
//     // Your FCM project ID
//     $projectId = env('FIREBASE_PROJECT_ID'); // Set your Firebase Project ID in the .env file
//     $accessToken = $this->getAccessToken(); // You will need a method to retrieve the access token

//     // Validate request data
//     $validator = Validator::make($request->all(), [
//         'title' => 'required',
//         'message' => 'required',
//     ]);

//     if ($validator->fails()) {
//         return response()->json(['error' => $validator->errors()], 400);
//     }

//     // Fetch active users with device tokens
//     $users = DB::table('user')
//         ->select('device_token', 'name')
//         ->where('status', 'active')
//         ->get();

//     // Check if there are no active users
//     if ($users->isEmpty()) {
//         return response()->json(['message' => 'No active users found.'], 404);
//     }

//     // Prepare the payload for each user
//     foreach ($users as $user) {
//         $data = [
//             'message' => [
//                 'token' => $user->device_token,
//                 'notification' => [
//                     'title' => $request->input('title'),
//                     'body' => 'Hi ' . $user->name . ', ' . $request->input('message'),
//                 ],
//             ],
//         ];

//         // Initialize cURL session
//         $ch = curl_init();

//         // Set cURL options
//         curl_setopt_array($ch, [
//             CURLOPT_URL => "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send",
//             CURLOPT_POST => true,
//             CURLOPT_POSTFIELDS => json_encode($data),
//             CURLOPT_RETURNTRANSFER => true,
//             CURLOPT_HTTPHEADER => [
//                 'Authorization: Bearer ' . $accessToken,
//                 'Content-Type: application/json',
//             ],
//             CURLOPT_SSL_VERIFYPEER => false, // Disable SSL certificate verification (optional)
//         ]);

//         // Execute cURL request
//         $response = curl_exec($ch);

//         // Check for errors
//         if ($response === false) {
//             // Handle cURL error
//             $error = curl_error($ch);
//             curl_close($ch);
//             return response()->json(['error' => 'cURL error: ' . $error], 500);
//         }

//         // Close cURL session
//         curl_close($ch);

//         // Decode the response
//         $responseData = json_decode($response, true);

//         // Check for FCM response status
//         if (isset($responseData['error'])) {
//             // Handle FCM response error
//             return response()->json(['error' => 'FCM error: ' . $responseData['error']], 500);
//         }
//     }

//     // Notification sent successfully
//     return response()->json(['message' => 'Notifications sent successfully.'], 200);
// }

// // Function to get access token
// private function getAccessToken()
// {
//     // Your logic to retrieve the access token
//     // You may use a service account and Google Client Library for PHP
// }


}
