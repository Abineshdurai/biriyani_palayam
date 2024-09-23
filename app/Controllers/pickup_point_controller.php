<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Validator;
use App\Models\pickup_point_model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Psy\Command\WhereamiCommand;

class pickup_point_controller extends Controller
{


//     public function create_pickup_point(Request $request)
// {
//     $validator = Validator::make($request->all(), [
//         'franchise_id' => 'required',
//         'pickup_location' => 'required',
//         // 'address' => 'required',
//         // 'owner_name' => 'required',
//         // 'mobile' => 'required|numeric|digits:10|unique:franchise,mobile',
//         // 'franchise_image' => 'required',
//     ]);
//     if ($validator->fails()) {
//         return response()->json(['error' => $validator->errors()], 400);
//     }
//     else{
        
//     date_default_timezone_set('Asia/Calcutta');
//     $time = time();
//     $current_date = date("Y-m-d H:i:s", $time);

//     $pickup_id = "PICKUP" . date("YmdHis", $time);

//     $pickup_point = new pickup_point_model();
//     $pickup_point->pickup_id = $pickup_id;
//     $pickup_point->franchise_id = $request->input('franchise_id');
//     $pickup_point->pickup_location = $request->input('pickup_location');
//     $pickup_point->status = 'active';
//     $pickup_point->created_at = $current_date;
//     $pickup_point->save();

//     if (!$pickup_point) {
//         $success['message'] = "Pickup_point not added successfully";
//         $success['success'] = false;
//         return response()->json($success, 500);
//     } else {
//         $success['message'] = "Pickup_point added successfully";
//         $success['success'] = true;
//         return response()->json($success, 200);
//     }


//     }

// }




    public function create_pickup_point(Request $request)
{
    $validator = Validator::make($request->all(), [
        'franchise_id' => 'required',
        'owner_name' => 'required',
        'owner_number' => 'required',
        'pickup_location' => 'required',
        'googlemap_link' => 'required',
       
    ]);
    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }
    else{
        
    date_default_timezone_set('Asia/Calcutta');
    $time = time();
    $current_date = date("Y-m-d H:i:s", $time);

    $pickup_id = "PICKUP" . date("YmdHis", $time);

    $pickup_point = new pickup_point_model();
    $pickup_point->pickup_id = $pickup_id;
    $pickup_point->franchise_id = $request->input('franchise_id');
    $pickup_point->owner_name = $request->input('owner_name');
    $pickup_point->owner_number = $request->input('owner_number');
    $pickup_point->pickup_location = $request->input('pickup_location');
    $pickup_point->googlemap_link = $request->input('googlemap_link');
    $pickup_point->status = 'active';
    $pickup_point->created_at = $current_date;
    $pickup_point->save();

    if (!$pickup_point) {
        $success['message'] = "Pickup_point not added successfully";
        $success['success'] = false;
        return response()->json($success, 500);
    } else {
        $success['message'] = "Pickup_point added successfully";
        $success['success'] = true;
        return response()->json($success, 200);
    }


    }

}

public function get_pickup_point(Request $request, $franchise_id)
{
    $pickup_points = DB::table('pickup_point')
                    ->where('franchise_id', $franchise_id)
                    ->where('status', 'active')
                    ->get();

    if ($pickup_points->isEmpty()) {
        $fail = [
            'message' => "No pickup points found for the franchise",
            'success' => false
        ];
        return response()->json($fail, 404);
    } else {
        $results = [];
        foreach ($pickup_points as $pickup_point) {
            $results[] = [
                "owner_name" => $pickup_point->owner_name,
                "owner_number" => $pickup_point->owner_number,
                "pickup_location" => $pickup_point->pickup_location,
                "pickup_id" => $pickup_point->pickup_id,
                "googlemap_link" => $pickup_point->googlemap_link
            ];
        }

      // return response()->json(["results" => $results], 200);
    return response()->json(["success" => true, "results" => $results], 200);
    }
}



public function update_pickup_point(Request $request, $pickup_id)
{
    $pickup_point = DB::table('pickup_point')
        ->where('pickup_id', '=', $pickup_id)
        ->where('status', '=', 'active')
        ->first();

    if (!$pickup_point) {
        $fail['message'] = "pickup_point not found";
        $fail['success'] = false;
        return response()->json($fail, 404);
    } else {

        $owner_name = $request->input('owner_name');
        $owner_number = $request->input('owner_number');
        $pickup_location = $request->input('pickup_location');
        $googlemap_link = $request->input('googlemap_link');

        $update = DB::table('pickup_point')
        ->where('pickup_id',$pickup_id)
        ->update([
           'owner_name' => $owner_name,
           'owner_number' => $owner_number,
           'pickup_location' => $pickup_location,
           'googlemap_link' => $googlemap_link,
           'updated_at' => now()

        ]);
        if (!$update){
            $success['message'] = 'Pickup point not updated successfully';
            $success['success'] = false;
            return response()->json($success);
        } else{
            $success['message'] = 'Pickup point updated successfully';
            $success['success'] = true;
            return response()->json($success);
        }
    } 
}



public function delete_pickup_point(Request $request, $pickup_id)
{
   // $franchise_id = $request->input('franchise_id');

    $delete = DB::table('pickup_point')
       ->where('pickup_id', '=', $pickup_id)
       ->where('status', '=', 'active')
       ->delete();

       if (!$delete) {
        $success['message'] = "Pickup_point delete not successfully";
        $success['success'] = false;
        return response()->json($success);
    } else {
        $success['message'] = "Pickup_point delete successfully";
        $success['success'] = true;
        return response()->json($success);
    }

}


}