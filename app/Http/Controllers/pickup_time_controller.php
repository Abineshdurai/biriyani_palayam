<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\pickup_time_model;
use App\Models\time_model;

class pickup_time_controller extends Controller
{
    public function create_pickup_time(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'franchise_id' => 'required',
          //  'timer_id' => 'required',
            'pickup_time' => 'required|date_format:H:i:s',
            // 'address' => 'required',
            // 'owner_name' => 'required',
            // 'mobile' => 'required|numeric|digits:10|unique:franchise,mobile',
            // 'franchise_image' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        } else {


           // $time_slot = DB::table('time_slots')
            //->where('timer_id',$timer_id)
            // $timer_id = $request->input('timer_id');
            // // ->where('status','active')
            // // ->first();

            //  $time_slot = DB::table('time_slots')
            // ->where('timer_id',$timer_id)
            // ->where('status','active')
            // ->first();
            // if (!$time_slot) {
            //     return response()->json(['error' => 'Invalid or inactive timer_id'], 400);
            // }
        

            date_default_timezone_set('Asia/Calcutta');
            $time = time();
            $current_date = date("Y-m-d H:i:s", $time);

            $pickup_time_id = "PT" . date("YmdHis", $time);

            $pickup_time = new pickup_time_model();
            $pickup_time->pickup_time_id = $pickup_time_id;
            $pickup_time->franchise_id = $request->input('franchise_id');
            // $pickup_time->timer_id = $request->input('timer_id');
            // $pickup_time->time_slot = $time_slot->starting_time.'-'.$time_slot->end_time;
            $pickup_time->pickup_time = $request->input('pickup_time');
            $pickup_time->status = 'active';
            $pickup_time->created_at = $current_date;
            $pickup_time->save();

            if (!$pickup_time) {
                $success['message'] = "Pickup_time not added successfully";
                $success['success'] = false;
                return response()->json($success, 500);
            } else {
                $success['message'] = "Pickup_time added successfully";
                $success['success'] = true;
                return response()->json($success, 200);
            }
        }
    }

    // public function get_pickup_time(Request $request, $franchise_id, $timer_id)
    // {
    //     $pickup_times = DB::table('pickup_time')
    //          ->where('franchise_id', $franchise_id)
    //          ->where('timer_id', $timer_id)
    //          ->get();

    //     if ($pickup_times->isEmpty()) {
    //         $fail['message'] = "Pickup Time not found";
    //         $fail['success'] = false;
    //         return response()->json($fail, 404);
    //     } else {
    //         $result = [];

    //         $active_pickup_times = DB::table('pickup_time')
    //             ->select('pickup_time','pickup_time_id')
    //             ->where('status', 'active')
    //             ->where('franchise_id', $franchise_id)
    //             ->where('timer_id', $timer_id)
    //             ->get();

    //         foreach ($active_pickup_times as $pickup_time) {
    //             $result[] = [
    //                 "pickup_time" => $pickup_time->pickup_time,
    //                 "pickup_time_id" => $pickup_time->pickup_time_id
    //             ];
    //         }

    //         return response()->json(['pickup_times' => $result, 'success' => true], 200);
    //     }
    // }


//     public function get_pickup_time(Request $request, $franchise_id, $date)
// {
//     $pickup_times = DB::table('pickup_time')
//                     ->where('franchise_id', $franchise_id)
//                     ->where( $date, today())
//                     ->where('status', 'active')
//                     ->get();

//     if ($pickup_times->isEmpty()) {
//         $fail = [
//             'message' => "No pickup time found for the franchise",
//             'success' => false
//         ];
//         return response()->json($fail, 404);
//     } else {
//         $results = [];
//         foreach ($pickup_times as $pickup_time) {
//             $results[] = [
//                 "pickup_time" => $pickup_time->pickup_time,
//                 "pickup_time_id" => $pickup_time->pickup_time_id,
//                 "time_slot" => $pickup_time->time_slot
                
//             ];
//         }

//      // return response()->json(["results" => $results], 200);
//      return response()->json(["success" => true, "results" => $results], 200);
//     }
// }



public function get_pickup_time(Request $request, $franchise_id, $date)
{
    // Set the timezone
    date_default_timezone_set('Asia/Calcutta');

    // Get the current date
    $current_date = date('Y-m-d');

    // Start building the query
    $dateCheck = DB::table('pickup_time')
                ->where('franchise_id', $franchise_id)
             //   ->whereDate('pickup_time', $date)  // Ensure date part of pickup_time matches $date
                ->where('status', 'active');

    // Check if the given date is today
    if ($date == $current_date) {
        // If the date is today, filter to only show pickup times after 13:00:00
        $dateCheck->whereTime('pickup_time', '<', '13:00:00');
    }

    // Get the filtered results
    $pickup_times = $dateCheck->get();

    // Check if results are empty
    if ($pickup_times->isEmpty()) {
        $fail = [
            'message' => "No pickup time found for the franchise",
            'success' => false
        ];
        return response()->json($fail, 404);
    } else {
        $results = [];
        foreach ($pickup_times as $pickup_time) {
            $results[] = [
                "pickup_time" => $pickup_time->pickup_time,
                "pickup_time_id" => $pickup_time->pickup_time_id,
            ];
        }

        // Return the successful response
        return response()->json(["success" => true, "results" => $results], 200);
    }
}


    public function update_pickup_time(Request $request, $pickup_time_id)
{
    $pickup_time = DB::table('pickup_time')
        ->where('pickup_time_id', '=', $pickup_time_id)
        ->where('status', '=', 'active')
        ->first();

    if (!$pickup_time) {
        $fail['message'] = "pickup time not found";
        $fail['success'] = false;
        return response()->json($fail, 404);
    } else {

        $pickup_time = $request->input('pickup_time');
        $update = DB::table('pickup_time')
        ->where('pickup_time_id',$pickup_time_id)
        ->update([
           'pickup_time' => $pickup_time,
       
           'updated_at' => now()

        ]);
        if (!$update){
            $success['message'] = 'Pickup time not updated successfully';
            $success['success'] = false;
            return response()->json($success);
        } else{
            $success['message'] = 'Pickup time updated successfully';
            $success['success'] = true;
            return response()->json($success);
        }
    } 
}


public function delete_pickup_time(Request $request, $pickup_time_id)
{


    $delete = DB::table('pickup_time')
       ->where('pickup_time_id', '=', $pickup_time_id)
       ->where('status', '=', 'active')
       ->delete();

       if (!$delete) {
        $success['message'] = "Pickup_time delete not successfully";
        $success['success'] = false;
        return response()->json($success);
    } else {
        $success['message'] = "Pickup_time delete successfully";
        $success['success'] = true;
        return response()->json($success);
    }

}


// public function get_pickup_time_admin(Request $request, $franchise_id, $timer_id)
// {
//     $pickup_times = DB::table('pickup_time')
//                     ->where('franchise_id', $franchise_id)
//                     ->where('timer_id', $timer_id)
//                     ->where('status', 'active')
//                     ->get();

//     if ($pickup_times->isEmpty()) {
//         $fail = [
//             'message' => "No pickup time found for the franchise",
//             'success' => false
//         ];
//         return response()->json($fail, 404);
//     } else {
//         $results = [];
//         foreach ($pickup_times as $pickup_time) {
//             $results[] = [
//                 "pickup_time" => $pickup_time->pickup_time,
//                 "pickup_time_id" => $pickup_time->pickup_time_id,
//                 "time_slot" => $pickup_time->time_slot
                
//             ];
//         }

//      // return response()->json(["results" => $results], 200);
//      return response()->json(["success" => true, "results" => $results], 200);
//     }
// }

public function get_pickup_time_admin(Request $request, $franchise_id, $timer_id)
{
    // Fetch active pickup times for the specified franchise and timer ID
    $pickup_times = DB::table('pickup_time')
                    ->where('franchise_id', $franchise_id)
                    ->where('timer_id', $timer_id)
                    ->where('status', 'active')
                    ->get();

    // Check if no pickup times are found
    if ($pickup_times->isEmpty()) {
        $fail = [
            'message' => "No pickup time found for the franchise",
            'success' => false
        ];
        return response()->json($fail, 404);
    } else {
        // Create a results array to store the pickup times
        $results = [];
        
        // Iterate through each pickup time and add to results array
        foreach ($pickup_times as $pickup_time) {
            $results[] = [
                "pickup_time" => $pickup_time->pickup_time,
                "pickup_time_id" => $pickup_time->pickup_time_id,
                "time_slot" => $pickup_time->time_slot
            ];
        }

        // Return the results in JSON format
        return response()->json(["success" => true, "results" => $results], 200);
    }
}




}
