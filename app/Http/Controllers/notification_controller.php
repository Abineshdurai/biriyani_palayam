<?php

namespace App\Http\Controllers;

use App\Notifications\push_notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class notification_controller extends Controller
{
    // public function sendNotification(Request $request)
    // {
    //     $title = $request->input('title');
    //     $body = $request->input('body');
    //     $deviceToken = $request->input('device_token');

    //     Notification::send(new push_notification($title, $body, $deviceToken));

    //     return response()->json(['message' => 'Notification sent successfully']);
    // }

    public function sendNotification(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'device_token' => 'required|string',
        ]);

        $title = $validatedData['title'];
        $body = $validatedData['body'];
        $deviceToken = $validatedData['device_token'];

        Notification::send(new push_notification($title, $body, $deviceToken)); // Update this line

        return response()->json(['message' => 'Notification sent successfully']);
    }
}
