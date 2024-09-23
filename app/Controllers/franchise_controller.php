<?php

namespace App\Http\Controllers;


//use DB;

use App\Models\franchise_model;
use Illuminate\Http\Request;
//use Validator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Psy\Command\WhereamiCommand;





class franchise_controller extends Controller
{





public function create_franchise(Request $request)
{
    $validator = Validator::make($request->all(), [
        'state_id' => 'required',
        'district_id' => 'required',
        'franchise' => 'required',
        'description' => 'required',
        'address' => 'required',
        'owner_name' => 'required',
        'mobile' => 'required|numeric|digits:10|unique:franchise,mobile',
        'franchise_image' => 'required|image' // Ensure franchise_image is an image file
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    } else {
        date_default_timezone_set('Asia/Calcutta');
        $current_date = now(); // Using Laravel's helper function to get current date and time
        $franchise_id = 'FRANCHISE' . $current_date->format('YmdHis');

        $state_id = $request->input('state_id');
        $district_id = $request->input('district_id');
        $franchise = $request->input('franchise');
        $description = $request->input('description');
        $address = $request->input('address');
        $owner_name = $request->input('owner_name');
        $mobile = $request->input('mobile');
        $franchise_image = $request->file('franchise_image'); // Retrieving file from request

        if (!empty($franchise_image)) {
            $img_id = $current_date->format('dmyHis');
            $path = 'FRANCHISEIMG' . $img_id . ".png";
            $directoryPath = public_path('$baseUrl.uploads/images/franchise_images/'); // Corrected path

            // Using the File facade to manage filesystem operations
            \Illuminate\Support\Facades\File::makeDirectory($directoryPath, $mode = 0777, true, true);

            // Storing image using Laravel's file system
            $franchise_image->move($directoryPath, $path);
        } else {
            $path = '';
        }

        $fran = new franchise_model(); // Spelling unchanged
        $fran->state_id = $state_id;
        $fran->district_id = $district_id;
        $fran->franchise = $franchise;
        $fran->franchise_id = $franchise_id;
        $fran->description = $description;
        $fran->address = $address;
        $fran->owner_name = $owner_name;
        $fran->mobile = $mobile;
        $fran->franchise_image = $path;
        $fran->status = "active";
        $fran->created_at = $current_date;
        $fran->save();

        if (!$fran) {
            $success['message'] = "Franchise not added successfully";
            $success['success'] = false;
            return response()->json($success);
        } else {
            $success['message'] = "Franchise added successfully";
            $success['success'] = true;
            return response()->json($success);
        }
    }
}








// public function create_franchise(Request $request)
// {
//     $validator = Validator::make($request->all(), [
//         'franchise' => 'required',
//         'description' => 'required',
//         'address' => 'required',
//         'owner_name' => 'required',
//         'mobile' => 'required|numeric|digits:10|unique:franchise,mobile',
//         'franchise_image' => 'required'   //|image|mimes:jpeg,png,jpg,gif|max:2048', // Ensure franchise_image is an image file
//     ]);

//     if ($validator->fails()) {
//         return response()->json(['error' => $validator->errors()], 400);
//     }

//     date_default_timezone_set('Asia/Calcutta');
//     $time = time();
//     $current_date = date("Y-m-d H:i:s", $time);

//     $franchise_id = 'FRANCHISE' . date("YmdHis", $time);

//     $franchise = new franchise_model();
//     $franchise->franchise_id = $franchise_id;
//     $franchise->franchise = $request->input('franchise');
//     $franchise->description = $request->input('description');
//     $franchise->address = $request->input('address');
//     $franchise->owner_name = $request->input('owner_name');
//     $franchise->mobile = $request->input('mobile');

//   // Handling franchise_image
//     if ($request->hasFile('franchise_image')) {
//         $image = $request->file('franchise_image');
//         $imageName = 'FRANCHISEIMG' . $time . '.' . $image->getClientOriginalExtension();
//         $image->move(public_path('Abinesh_be_work/tsit_biriyani_palayam/uploads/images/franchise_image/'), $imageName);
//         $franchise->franchise_image = $imageName;
//     }

//     $franchise->status = 'active';
//     $franchise->created_at = $current_date;
//     $franchise->save();

//     if (!$franchise) {
//         $success['message'] = "Franchise not added successfully";
//         $success['success'] = false;
//         return response()->json($success, 500);
//     } else {
//         $success['message'] = "Franchise added successfully";
//         $success['success'] = true;
//         return response()->json($success, 200);
//     }
// }




// public function get_franchise(Request $request,$state_id , $district_id)
// {
//     $franchises = DB::table('franchise')
//         ->select('state_id', 'district_id', 'franchise_id', 'franchise', 'description', 'address', 'owner_name', 'mobile', 'franchise_image', 'created_at')
//         ->where('state_id', '=', $state_id)
//         ->where('district_id', '=', $district_id)
//         ->where('status', '=', 'active')
//         ->get();

//     if ($franchises->isEmpty()) {
//         $fail['message'] = "No active franchises found";
//         $fail['success'] = false;
//         return response()->json($fail, 404);
//     } else {
//         $result = array();

//         foreach ($franchises as $franchise) {
//             $franchise_data = (array) $franchise;
//             $state_id = $franchise_data['state_id'];
//             $district_id = $franchise_data['district_id'];
//             $franchise_id = $franchise_data['franchise_id'];
//             $franchise_name = $franchise_data['franchise'];
//             $description = $franchise_data['description'];
//             $address = $franchise_data['address'];
//             $owner_name = $franchise_data['owner_name'];
//             $mobile = $franchise_data['mobile']; // Corrected the column name
//             $created_at = $franchise_data['created_at'];
//             $image = $franchise_data['franchise_image'];

//             if (empty($image)) {
//                 $img = '';
//             } else {
//                 $img = '$baseUrl.uploads/images/franchise_images/' . $image;
//             }

//             $result[] = array(
//                 "state_id" => $state_id,
//                 "district_id" => $district_id,
//                 "franchise_id" => $franchise_id,
//                 "franchise" => $franchise_name,
//                 "description" => $description,
//                 "address" => $address,
//                 "owner_name" => $owner_name,
//                 "mobile" => $mobile,
//                 "created_at" => $created_at,
//                 "image" => $img
//             );
//         }

//         return response()->json(array("result" => $result), 200);
//     }
// }


public function Tsit_BPM_Check_Franchise(Request $request, $state_id, $district_id)
{
    $franchises = DB::table('franchise')
       // ->select('state_id', 'district_id', 'franchise_id', 'franchise', 'description', 'address', 'owner_name', 'mobile', 'franchise_image', 'created_at')
        ->where('state_id', '=', $state_id)
        ->where('district_id', '=', $district_id)
        ->where('status', '=', 'active')
        ->get();

    if ($franchises->isEmpty()) {
        return response()->json(['message' => 'No active Resturants found', 'success' => false], 404);
    } else{
        return response()->json(["message" => 'Some Restaurants Found', 'success' => true ], 200);
    }
}


public function get_franchise(Request $request, $state_id, $district_id)
{
    $franchises = DB::table('franchise')
        ->select('state_id', 'district_id', 'franchise_id', 'franchise', 'description', 'address', 'owner_name', 'mobile', 'franchise_image', 'created_at')
        ->where('state_id', '=', $state_id)
        ->where('district_id', '=', $district_id)
        ->where('status', '=', 'active')
        ->get();

    if ($franchises->isEmpty()) {
        return response()->json(['message' => 'No active franchises found', 'success' => false], 404);
    }

    $result = [];

    foreach ($franchises as $franchise) {
        $franchise_data = (array) $franchise;

        // Retrieve state name from the states table
        $state_name = DB::table('states')
            ->where('state_id', $franchise_data['state_id']) // Assuming 'id' is the primary key of the 'states' table
            ->value('name');

        // Retrieve district name from the districts table
        $district_name = DB::table('districts')
            ->where('district_id', $franchise_data['district_id']) // Assuming 'district_id' is the primary key of the 'districts' table
            ->value('district_name');

        $image_url = empty($franchise_data['franchise_image'])
            ? ''
            : '$baseUrl.uploads/images/franchise_images/' . $franchise_data['franchise_image'];

        $result[] = [
            "state_id" => $franchise_data['state_id'],
            "state_name" => $state_name,  // Added state name
            "district_id" => $franchise_data['district_id'],
            "district_name" => $district_name,  // Added district name
            "franchise_id" => $franchise_data['franchise_id'],
            "franchise" => $franchise_data['franchise'],
            "description" => $franchise_data['description'],
            "address" => $franchise_data['address'],
            "owner_name" => $franchise_data['owner_name'],
            "mobile" => $franchise_data['mobile'],
            "created_at" => $franchise_data['created_at'],
            "image" => $image_url
        ];
    }

    return response()->json(["result" => $result], 200);
}




public function get_hidden_franchise(Request $request, $state_id, $district_id)
{
    $franchises = DB::table('franchise')
        ->select('state_id', 'district_id', 'franchise_id', 'franchise', 'description', 'address', 'owner_name', 'mobile', 'franchise_image', 'created_at')
        ->where('state_id', '=', $state_id)
        ->where('district_id', '=', $district_id)
        ->where('status', '=', 'inactive')
        ->get();

    if ($franchises->isEmpty()) {
        return response()->json(['message' => 'No active franchises found', 'success' => false], 404);
    }

    $result = [];

    foreach ($franchises as $franchise) {
        $franchise_data = (array) $franchise;

        // Retrieve state name from the states table
        $state_name = DB::table('states')
            ->where('state_id', $franchise_data['state_id']) // Assuming 'id' is the primary key of the 'states' table
            ->value('name');

        // Retrieve district name from the districts table
        $district_name = DB::table('districts')
            ->where('district_id', $franchise_data['district_id']) // Assuming 'district_id' is the primary key of the 'districts' table
            ->value('district_name');

        $image_url = empty($franchise_data['franchise_image'])
            ? ''
            : '$baseUrl.uploads/images/franchise_images/' . $franchise_data['franchise_image'];

        $result[] = [
            "state_id" => $franchise_data['state_id'],
            "state_name" => $state_name,  // Added state name
            "district_id" => $franchise_data['district_id'],
            "district_name" => $district_name,  // Added district name
            "franchise_id" => $franchise_data['franchise_id'],
            "franchise" => $franchise_data['franchise'],
            "description" => $franchise_data['description'],
            "address" => $franchise_data['address'],
            "owner_name" => $franchise_data['owner_name'],
            "mobile" => $franchise_data['mobile'],
            "created_at" => $franchise_data['created_at'],
            "image" => $image_url
        ];
    }

    return response()->json(["result" => $result], 200);
}





// public function get_hidden_franchise(Request $request)
// {
//     $franchises = DB::table('franchise')
//         ->select('franchise_id', 'franchise', 'description', 'address', 'owner_name', 'mobile', 'franchise_image', 'created_at')
//       // ->where('franchise_id', '=', $franchise_id)
//         ->where('status', '=', 'inactive')
//         ->get();

//     if ($franchises->isEmpty()) {
//         $fail['message'] = "No inactive franchises found";
//         $fail['success'] = false;
//         return response()->json($fail, 404);
//     } else {
//         $result = array();

//         foreach ($franchises as $franchise) {
//             $franchise_data = (array) $franchise;
//             $franchise_id = $franchise_data['franchise_id'];
//             $franchise_name = $franchise_data['franchise'];
//             $description = $franchise_data['description'];
//             $address = $franchise_data['address'];
//             $owner_name = $franchise_data['owner_name'];
//             $mobile = $franchise_data['mobile']; // Corrected the column name
//             $created_at = $franchise_data['created_at'];
//             $image = $franchise_data['franchise_image'];

//             if (empty($image)) {
//                 $img = '';
//             } else {
//                 $img = '$baseUrl.uploads/images/franchise_images/' . $image;
//             }

//             $result[] = array(
//                 "franchise_id" => $franchise_id,
//                 "franchise" => $franchise_name,
//                 "description" => $description,
//                 "address" => $address,
//                 "owner_name" => $owner_name,
//                 "mobile" => $mobile,
//                 "created_at" => $created_at,
//                 "image" => $img
//             );
//         }

//         return response()->json(array("result" => $result), 200);
//     }
// }




public function get_franchise_owner(Request $request, $franchise_id)
{
    $franchise = DB::table('franchise')
        ->where('franchise_id', '=', $franchise_id)
        ->where('status', '=', 'active')
        ->first();

    if (!$franchise) {
        $fail['message'] = "No active franchise found for the given franchise_id";
        $fail['success'] = false;
        return response()->json($fail, 404);
    } else {
        $image_url = '';
        if (!empty($franchise->franchise_image)) {
            $image_url = '$baseUrl.uploads/images/franchise_images/' . $franchise->franchise_image;
        }
        $state_name = DB::table('states')
            ->where('state_id', $franchise->state_id) // Assuming 'id' is the primary key of the 'states' table
            ->value('name');

        // Retrieve district name from the districts table
        $district_name = DB::table('districts')
            ->where('district_id', $franchise->district_id) // Assuming 'district_id' is the primary key of the 'districts' table
            ->value('district_name');


        $result = [
            "state" => $state_name,
            "district_name" => $district_name,
            "franchise_id" => $franchise->franchise_id,
            "franchise" => $franchise->franchise,
            "description" => $franchise->description,
            "address" => $franchise->address,
            "owner_name" => $franchise->owner_name,
            "mobile" => $franchise->mobile, // Corrected the column name
            "created_at" => $franchise->created_at,
            "image" => $image_url
        ];

        return response()->json(["result" => $result], 200);
    }
}






// public function get_franchise_owner(Request $request, $franchise_id)
// {
//     $franchises = DB::table('franchise')
//       // ->select('franchise_id', 'franchise', 'description', 'address', 'owner_name', 'mobile', 'franchise_image', 'created_at')
//         ->where('franchise_id', '=', $franchise_id)
//         ->where('status', '=', 'active')
//         ->get();

//     if ($franchises->isEmpty()) {
//         $fail['message'] = "No active franchises_id found";
//         $fail['success'] = false;
//         return response()->json($fail, 404);
//     } else {
//         $result = array();

//         foreach ($franchises as $franchise) {
//             $franchise_data = (array) $franchise;
//             $franchise_id = $franchise_data['franchise_id'];
//             $franchise_name = $franchise_data['franchise'];
//             $description = $franchise_data['description'];
//             $address = $franchise_data['address'];
//             $owner_name = $franchise_data['owner_name'];
//             $mobile = $franchise_data['mobile']; // Corrected the column name
//             $created_at = $franchise_data['created_at'];
//             $image = $franchise_data['franchise_image'];

//             if (empty($image)) {
//                 $img = '';
//             } else {
//                 $img = '$baseUrl.uploads/images/franchise_images/' . $image;
//             }

//             $result[] = array(
//                 "franchise_id" => $franchise_id,
//                 "franchise" => $franchise_name,
//                 "description" => $description,
//                 "address" => $address,
//                 "owner_name" => $owner_name,
//                 "mobile" => $mobile,
//                 "created_at" => $created_at,
//                 "image" => $img
//             );
//         }

//         return response()->json(array("result" => $result), 200);
//     }
// }




public function get_pickuppoint(Request $request, $franchise_id )
{
    $franchises = DB::table('franchise')
        ->select('franchise_id', 'franchise', 'description', 'address', 'owner_name', 'mobile', 'franchise_image', 'created_at')
        ->where('franchise_id', '=', $franchise_id)
        ->where('status', '=', 'active')
        ->get();

    if ($franchises->isEmpty()) {
        $fail['message'] = "No active franchises found";
        $fail['success'] = false;
        return response()->json($fail, 404);
    } else {
        $result = array();

        foreach ($franchises as $franchise) {
            $franchise_data = (array) $franchise;
            $franchise_id = $franchise_data['franchise_id'];
            $franchise_name = $franchise_data['franchise'];
            $description = $franchise_data['description'];
            $address = $franchise_data['address'];
            $owner_name = $franchise_data['owner_name'];
            $mobile = $franchise_data['mobile']; // Corrected the column name
            $created_at = $franchise_data['created_at'];
            $image = $franchise_data['franchise_image'];

            if (empty($image)) {
                $img = '';
            } else {
                $img = '$baseUrl.uploads/images/franchise_images/' . $image;
            }

            $result[] = array(
                "franchise_id" => $franchise_id,
                "franchise" => $franchise_name,
                "description" => $description,
                "address" => $address,
                "owner_name" => $owner_name,
                "mobile" => $mobile,
                "created_at" => $created_at,
                "image" => $img
            );
        }

        return response()->json(array("result" => $result), 200);
    }
}


public function update_franchise(Request $request, $franchise_id)
{
    $franchise = DB::table('franchise')
        ->where('franchise_id', '=', $franchise_id)
        ->where('status', '=', 'active')
        ->first();

    if (!$franchise) {
        $fail['message'] = "Franchise not found";
        $fail['success'] = false;
        return response()->json($fail, 404);
    } else {
        date_default_timezone_set('Asia/Calcutta');
        $time = time();
        $current_date = date("Y-m-d H:i:s", $time);

        $franchise->franchise = $request->input('franchise');
        $franchise->description = $request->input('description');
        $franchise->address = $request->input('address');
        $franchise->owner_name = $request->input('owner_name');
        $franchise->mobile = $request->input('mobile');

        $franchise_image = $request->file('franchise_image');
        if (!empty($franchise_image)) {
            $img_id = date("dmyHis", $time);
            $path = 'FRANCHISEIMG' . $img_id . ".png";
            $directoryPath = public_path('$baseUrl.uploads/images/franchise_images/');

            // Ensure directory exists
            \Illuminate\Support\Facades\File::makeDirectory($directoryPath, $mode = 0777, true, true);

            // Move the uploaded image to the directory
            $franchise_image->move($directoryPath, $path);

            // Update the path in the $franchise object
            $franchise->franchise_image = $path;
        } else {
            $path = '';
        }

        // Update the franchise details in the database
        $update = DB::table('franchise')
            ->where('franchise_id', '=', $franchise_id)
            ->where('status', '=', 'active')
            ->update([
                'franchise' => $franchise->franchise,
                'description' => $franchise->description,
                'address' => $franchise->address,
                'owner_name' => $franchise->owner_name,
                'mobile' => $franchise->mobile,
                'franchise_image' => $franchise->franchise_image
            ]);

        if (!$update) {
            $success['message'] = "Franchise update not successful";
            $success['success'] = false;
            return response()->json($success);
        } else {
            $success['message'] = "Franchise updated successfully";
            $success['success'] = true;
            return response()->json($success);
        }
    }
}



// public function update_franchise(Request $request, $franchise_id)
// {
//     $franchise = DB::table('franchise')
//         ->where('franchise_id', '=', $franchise_id)
//         ->where('status', '=', 'active')
//         ->first();

//     if (!$franchise) {
//         $fail['message'] = "Franchise not found";
//         $fail['success'] = false;
//         return response()->json($fail, 404);
//     } else {
//         date_default_timezone_set('Asia/Calcutta');
//         $current_date = now(); // Using Laravel's helper function to get current date and time

//         // Update franchise attributes
//         $franchise->franchise = $request->input('franchise');
//         $franchise->description = $request->input('description');
//         $franchise->address = $request->input('address');
//         $franchise->owner_name = $request->input('owner_name');
//         $franchise->mobile = $request->input('mobile');

//         $franchise_image = $request->file('franchise_image'); // Retrieving file from request
//         if (!empty($franchise_image)) {
//             $img_id = $current_date->format('dmyHis');
//             $path = 'FRANCHISEIMG' . "$img_id" . ".png";
//             $directoryPath = public_path("uploads/images/franchise_image/");

//             // Using the File facade to manage filesystem operations
//             \Illuminate\Support\Facades\File::makeDirectory($directoryPath, $mode = 0777, true, true);

//             // Storing image using Laravel's file system
//             $franchise_image->move($directoryPath, $path);
//             $franchise->franchise_image = $path;
//         }

//         // Update franchise in the database
//         $update = DB::table('franchise')
//             ->where('franchise_id', '=', $franchise_id)
//             ->where('status', '=', 'active')
//             ->update([
//                 'franchise' => $franchise->franchise,
//                 'description' => $franchise->description,
//                 'address' => $franchise->address,
//                 'owner_name' => $franchise->owner_name,
//                 'mobile' => $franchise->mobile,
//                 'franchise_image' => $franchise->franchise_image
//                 // Make sure to handle franchise_image separately if needed
//             ]);

//         if (!$update) {
//             $success['message'] = "Franchise update not successful";
//             $success['success'] = false;
//             return response()->json($success);
//         } else {
//             $success['message'] = "Franchise updated successfully";
//             $success['success'] = true;
//             return response()->json($success);
//         }
//     }
// }







// public function delete_franchise(Request $request, $franchise_id)
// {
//   // $franchise_id = $request->input('franchise_id');

//     $delete = DB::table('franchise')
//       ->where('franchise_id', '=', $franchise_id)
//       ->where('status', '=', 'active')
//       ->delete();

//       if (!$delete) {
//         $success['message'] = "franchise delete not successfully";
//         $success['success'] = false;
//         return response()->json($success);
//     } else {
//         $success['message'] = "franchise delete successfully";
//         $success['success'] = true;
//         return response()->json($success);
//     }

// }



public function delete_franchise(Request $request, $franchise_id)
{
    try {
        $delete = DB::table('franchise')
            ->where('franchise_id', '=', $franchise_id)
            ->where('status', '=', 'active')
            ->delete();

        if (!$delete) {
            $success['message'] = "Franchise delete not successful";
            $success['success'] = false;
        } else {
            $success['message'] = "Franchise deleted successfully";
            $success['success'] = true;
        }
    } catch (\Exception $e) {
        // Log the database exception
        Log::error('Database error: ' . $e->getMessage());

        $success['message'] = "Error deleting franchise. Please try again.";
        $success['success'] = false;
    }

    return response()->json($success);
}






public function toggle_franchise_status(Request $request, $franchise_id) {

    // Retrieve the franchise record
    $franchise = DB::table('franchise')
    ->where('franchise_id', '=', $franchise_id)
    ->first();

    if (!$franchise) {
        $success['message'] = "Franchise not found";
        $success['success'] = false;
        return response()->json($success);
    }

    // Determine the new status
    $new_status = $franchise->status == 'active' ? 'inactive' : 'active';

    // Update the status
    DB::table('franchise')
        ->where('franchise_id', $franchise_id)
        ->update(['status' => $new_status]);

    $success['message'] = "Franchise status toggled successfully";
    $success['new_status'] = $new_status;
    $success['success'] = true;
    return response()->json($success);
}










}



