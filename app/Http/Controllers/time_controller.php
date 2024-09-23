<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\time_model;

class time_controller extends Controller
{
    public function create_time(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'franchise_id' => 'required',
            'starting_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
           // 'pickup_time' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 200);
        }
   
        else {
            date_default_timezone_set('Asia/Calcutta');
            $time = time();
            $id = (date("YmdHis", $time));
            $timer_id = 'TIMER' . date("YmdHis", $time);
            $current_date = (date("YmdHis", $time));
            $time_slots = new time_model();
            $time_slots->franchise_id = $request->input('franchise_id');;
            $time_slots->timer_id = $timer_id;
            $time_slots->starting_time = $request->input('starting_time');
            $time_slots->end_time = $request->input('end_time');
           // $time_slots->pickup_time = $request->input('pickup_time');
            $time_slots->status = "active";
            $time_slots->created_at = $current_date;
            $time_slots->save();
            if (!$time_slots) {
                $success['message'] = "Time Slot not added successfully";
                $success['success'] = false;
                return response()->json($success);
            } else {
                $success['message'] = "Time Slot added successfully";
                $success['success'] = true;
                return response()->json($success);
            //}
        }
        }
    }

  
    public function get_time(Request $request, $franchise_id)
    {
        // Check if the franchise_id exists and is active
        $franchise_exists = DB::table('time_slots')
                             ->where('franchise_id', $franchise_id)
                             ->where('status', '=', 'active')
                             ->exists();
    
        if (!$franchise_exists) {
            $fail['message'] = "Franchise_id not found or not active";
            $fail['success'] = false;
            return response()->json($fail, 404);
        }
    
        // Retrieve time slots for the given franchise_id
        $time_slots = DB::table('time_slots')
                        ->where('franchise_id', $franchise_id)
                        ->where('status', '=', 'active')
                        ->get();
    
        if ($time_slots->isEmpty()) {
            $fail['message'] = "No time slots found";
            $fail['success'] = false;
            return response()->json($fail, 404);
        } else {
            $results = [];
            foreach ($time_slots as $slot) {
                $results[] = [
                    "franchise_id" => $slot->franchise_id, // Changed from "id" to "franchise_id"
                    "timer_id" => $slot->timer_id,
                    "starting_time" => $slot->starting_time,
                    "end_time" => $slot->end_time,
                 //   "pickup_time" => $slot->pickup_time,
                ];
            }
    
            return response()->json(["results" => $results], 200);
        }
    }

    
    // public function get_pickup_time(Request $request, $timer_id)
    // {
    //     // Check if the franchise_id exists and is active
    //     $pickup_time_exist = DB::table('time_slots')
    //                          ->where('timer_id', $timer_id)
    //                          ->where('status', '=', 'active')
    //                          ->exists();
    
    //     if (!$pickup_time_exist) {
    //         $fail['message'] = "timer_id not found or not active";
    //         $fail['success'] = false;
    //         return response()->json($fail, 404);
    //     }
    
    //     // Retrieve time slots for the given franchise_id
    //     $pickup_time = DB::table('time_slots')
    //                     ->where('timer_id', $timer_id)
    //                     ->where('status', '=', 'active')
    //                     ->get();
    
    //     if ($pickup_time->isEmpty()) {
    //         $fail['message'] = "No time slots found";
    //         $fail['success'] = false;
    //         return response()->json($fail, 404);
    //     } else {
    //         $results = [];
    //         foreach ($pickup_time as $slot) {
    //             $results[] = [
    //                 // "franchise_id" => $slot->franchise_id, // Changed from "id" to "franchise_id"
    //                 // "timer_id" => $slot->timer_id,
    //                 // "starting_time" => $slot->starting_time,
    //                 // "end_time" => $slot->end_time,
    //                 "pickup_time" => $slot->pickup_time,
    //             ];
    //         }
    
    //         return response()->json(["results" => $results], 200);
    //     }
    // }



    public function timer_push_notification($device_token)
    {
        // Your FCM server key
        $fcmServerKey = env('FCM_SERVER_KEY');
    
        // Get the current time
        $currentTime =date('h:i A');
    
        // Construct the message with the current time appended twice
        $message = $currentTime . ' Timeslot is going Now!! ';
    
        // Construct the data payload for the push notification
        $data = [
            'to' => $device_token,
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
            return 'cURL error: ' . $error;
        }
    
        // Close cURL session
        curl_close($ch);
    
        // Decode the response
        $responseData = json_decode($response, true);
    
        // Check for FCM response status
        if (isset($responseData['error'])) {
            // Handle FCM response error
            return 'FCM error: ' . $responseData['error'];
        }
    
        // Notification sent successfully
        return 'Notification sent successfully';
    }


    

public function update_timeslot(Request $request, $timer_id)
{
    // Fetch the time slot using the provided timer_id
    $time_slot = DB::table('time_slots')
        ->where('timer_id', $timer_id)
        ->where('status', 'active')
        ->first();

    // Check if the time slot exists
    if (!$time_slot) {
        // If not found, return a 404 response
        return response()->json(['message' => "TimeSlot not found", 'success' => false], 404);
    } else {
        // If found, update the time slot
        date_default_timezone_set('Asia/Calcutta');
        $time = time();
        $currentTime = now();

        $time_slot->starting_time = $request->input('starting_time');
        $time_slot->end_time = $request->input('end_time');
        $time_slot->pickup_time = $request->input('pickup_time');


        $update = DB::table('time_slots')
            ->where('timer_id', $timer_id)
            ->where('status', 'active')
            ->update([
                // Update the fields with the same values
                'starting_time' => $time_slot->starting_time,
                'end_time' => $time_slot->end_time,
               // 'pickup_time' => $time_slot->pickup_time,
                'updated_at' => $currentTime
            ]);

        // Check if the update was successful
        if ($update) {
            // If update succeeded, return a success response
            return response()->json(['message' => "TimeSlot updated successfully", 'success' => true]);
        } else {
            // If update failed, return a failure response with the reason
            return response()->json(['message' => "TimeSlot update failed: No changes made or error occurred", 'success' => false]);
        }
    }
}
      public function delete_timeslot(Request $request, $timer_id) 
      {
        $delete = DB::table('time_slots')
        ->where('timer_id', $timer_id)
        ->where('status', '=', 'active')
        ->delete(); 

        if (!$delete) {
            $success['message'] = "TimeSlot delete not successfully";
            $success['success'] = false;
            return response()->json($success);
        } else {
            $success['message'] = "TimeSlot delete successfully";
            $success['success'] = true;
            return response()->json($success);
        }
      }


}