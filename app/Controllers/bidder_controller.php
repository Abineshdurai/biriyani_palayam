<?php

namespace App\Http\Controllers;

use App\Models\bidder_model;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class bidder_controller extends Controller{





// public function create_bidder(Request $request)
// {
//     $validator = Validator::make($request->all(), [
//         'timer_id' => 'required',
//         'user_id' => 'required',
//         'name' => 'required', 
//     ]);

//     if ($validator->fails()) {
//         return response()->json(['error' => $validator->errors()], 400);
//     }

//     // Check if a bidder with the provided user_id already exists for the given timer_id
//     $existing_bidder = bidder_model::where('timer_id', $request->input('timer_id'))
//                                     ->where('user_id', $request->input('user_id'))
//                                     ->exists();

//     if ($existing_bidder) {
//         $response = [
//             'success' => false,
//             'message' => 'Bidder with provided user_id already exists for the given timer_id'
//         ];
//     } else {
//         // Create a new bidder since it doesn't already exist
//         $bidder = new bidder_model();
//         $bidder->timer_id = $request->input('timer_id');
//         $bidder->user_id = $request->input('user_id');
//         $bidder->name = $request->input('name');
//         $bidder->save();

//         if (!$bidder) {
//             $response = [
//                 'success' => false,
//                 'message' => 'Bidder not added successfully'
//             ];
//         } else {
//             $response = [
//                 'success' => true,
//                 'message' => 'Bidder added successfully'
//             ];
//         }
//     }

//     return response()->json($response);
// }




public function create_bidder(Request $request)
{
    $validator = Validator::make($request->all(), [
        'timer_id' => 'required',
        'user_id' => 'required',
        'name' => 'required', 
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    // Get current date
    $current_date = now()->toDateString();

    // Check if a bidder with the provided user_id already exists for the given timer_id and current date
    $existing_bidder = bidder_model::where('timer_id', $request->input('timer_id'))
                                    ->where('user_id', $request->input('user_id'))
                                    ->whereDate('date', $current_date)
                                    ->exists();

    if ($existing_bidder) {
        $response = [
            'success' => false,
            'message' => 'Bidder with provided user_id already exists for the given timer_id and current date'
        ];
    } else {
        // Set default timezone
        date_default_timezone_set('Asia/Calcutta');
        $time = time();
        $bidder_id = 'BIDDER' . date("ymdHis", $time);

        // Create a new bidder since it doesn't already exist for today
        $bidder = new bidder_model();
        $bidder->bidder_id = $bidder_id;
        $bidder->timer_id = $request->input('timer_id');
        $bidder->user_id = $request->input('user_id');
        $bidder->name = $request->input('name');
        $bidder->date = $current_date;
        $bidder->status = 'active';
        $bidder->created_at = now()->toDateTimeString();
        $bidder->save();

        if (!$bidder) {
            $response = [
                'success' => false,
                'message' => 'Bidder not added successfully'
            ];
        } else {
            $response = [
                'success' => true,
                'message' => 'Bidder added successfully'
            ];
        }
    }

    return response()->json($response);
}


//     public function get_bidder_count(Request $request, $timer_id)
//  {
//     $bidder_count = DB::table('bidder')
//         ->where('timer_id', '=', $timer_id)
//         ->count();

//     $success['message'] = "Bidder data count retrieved successfully";
//     $success['success'] = true;
//     $success['bidder_count'] = $bidder_count;

//     return response()->json($success);
//  }


public function get_bidder_count(Request $request, $timer_id)
{
    // Get current date
    $current_date = now()->toDateString();

    $bidder_count = DB::table('bidder')
        ->where('timer_id', $timer_id)
        ->whereDate('date', $current_date) // Filter by current date
        ->count();

    $success['message'] = "Bidder data count retrieved successfully for today's timer_id";
    $success['success'] = true;
    $success['bidder_count'] = $bidder_count;

    return response()->json($success);
}





}