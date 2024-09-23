<?php

// namespace App\Console;

// use Illuminate\Console\Scheduling\Schedule;
// use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

// class Kernel extends ConsoleKernel
// {
//     /**
//      * Define the application's command schedule.
//      *
//      * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
//      * @return void
//      */
//     protected function schedule(Schedule $schedule)
//     {
//         // $schedule->command('inspire')->hourly();
//     }

//     /**
//      * Register the commands for the application.
//      *
//      * @return void
//      */
//     protected function commands()
//     {
//         $this->load(__DIR__.'/Commands');

//         require base_path('routes/console.php');
//     }
// }
namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\time_model;
use App\Models\user_model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    protected $commands = [];

    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            // Get current time
            $currentTime = Carbon::now();

            // Find time slots starting at or before the current time and ending after the current time
            $timeSlots = time_model::where('starting_time', '<=', $currentTime)
                ->where('end_time', '>=', $currentTime)
                ->get();

            // Loop through found time slots
            foreach ($timeSlots as $timeSlot) {
                // Get the user associated with the time slot
                $user = user_model::where('device_token', $timeSlot->user_id)->first();

                if ($user) {
                    // Send push notification to the user's device
                    $this->timerPushNotification($user->device_token, $timeSlot->message);
                }
            }
        })->everyMinute();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    // Function to send push notification
    protected function timerPushNotification($deviceToken, $message)
    {
        // Your FCM server key
        $fcmServerKey = env('FCM_SERVER_KEY');

        // Construct the data payload for the push notification
        $data = [
            'to' => $deviceToken,
            'notification' => [
                'title' => 'Bidding Slot is opened!',
                'body' => $message,
            ],
            'time_to_live' => 5, // Set the time to live to 5 seconds
        ];

        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: key=' . $fcmServerKey,
                'Content-Type: application/json',
            ],
            CURLOPT_SSL_VERIFYPEER => false, // Disable SSL certificate verification (optional)
        ]);

        // Execute cURL request
        $response = curl_exec($ch);

        // Check for errors
        if ($response === false) {
            // Handle cURL error
            $error = curl_error($ch);
            curl_close($ch);
            // Log the error
            Log::error('cURL error: ' . $error);
            return;
        }

        // Close cURL session
        curl_close($ch);

        // Decode the response
        $responseData = json_decode($response, true);

        // Check for FCM response status
        if (isset($responseData['error'])) {
            // Handle FCM response error
            Log::error('FCM error: ' . $responseData['error']);
            return;
        }

        // Notification sent successfully
        Log::info('Notification sent successfully');
    }
}



