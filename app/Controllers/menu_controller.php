<?php

namespace App\Http\Controllers;


use App\Models\menu_model;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class menu_controller extends Controller
{



// public function get_menu(Request $request, $franchise_id)
// {
//     $menu = DB::table('menu')
//                 ->where('franchise_id', $franchise_id)
//                 ->where('status', '=', 'active')
//                 ->get();

//     if ($menu->isEmpty()) {
//         $fail['message'] = "No menu found for the franchise";
//         $fail['success'] = false;
//         return response()->json($fail, 404);
//     } else {
//         $result = [];
//         foreach ($menu as $menuItem) {
//             $image_url = '';
//             if (!empty($menuItem->menu_image)) {
//                 $image_url = '$baseUrl.uploads/images/menu_images/' . $menuItem->menu_image;
//             }
//             $result[] = [
//                 "franchise_id" => $menuItem->franchise_id,
//                 "menu_category_id" => $menuItem->menu_category_id,
//                 "menu_category_name" => $menuItem->menu_category_name,
//                 "description" => $menuItem->description,
//                 "base_price" => $menuItem->base_price,
//                 "current_price" => $menuItem->current_price,
//                 "menu_image" => $image_url
//             ];
//         }

//         return response()->json(["result" => $result], 200);
//     }
// }


    public function get_menu(Request $request, $franchise_id)
    {
        $baseUrl = env('APP_URL');
        $menu = DB::table('menu')
                    ->where('franchise_id', $franchise_id)
                    ->where('menu_type', '=', 'Bidding')
                    ->where('status', '=', 'active')
                    ->get();

        if ($menu->isEmpty()) {
            $fail['message'] = "No menu found for the franchise";
            $fail['success'] = false;
            return response()->json($fail, 404);
        } else {
            $result = [];
            foreach ($menu as $menuItem) {
                $image_url = '';
                if (!empty($menuItem->menu_image)) {
                    $image_url = $baseUrl.'uploads/images/menu_images/' . $menuItem->menu_image;
                }
                $result[] = [
                    "franchise_id" => $menuItem->franchise_id,
                    "menu_type" => $menuItem->menu_type,
                    "menu_category_id" => $menuItem->menu_category_id,
                    "menu_category_name" => $menuItem->menu_category_name,
                    "description" => $menuItem->description,
                    "base_price" => $menuItem->base_price,
                    "current_price" => $menuItem->current_price,
                    "menu_image" => $image_url
                ];
            }

            return response()->json(["result" => $result], 200);
        }
    }


    public function get_fixed_menu(Request $request, $franchise_id)
    {
        $baseUrl = env('APP_URL');
        $menu = DB::table('menu')
                    ->where('franchise_id', $franchise_id)
                    ->where('menu_type', '=', 'Fixed Price')
                    ->where('status', '=', 'active')
                    ->get();

        if ($menu->isEmpty()) {
            $fail['message'] = "No menu found for the franchise";
            $fail['success'] = false;
            return response()->json($fail, 404);
        } else {
            $result = [];
            foreach ($menu as $menuItem) {
                $image_url = '';
                if (!empty($menuItem->menu_image)) {
                    $image_url = $baseUrl.'uploads/images/menu_images/' . $menuItem->menu_image;
                }
                $result[] = [
                    "franchise_id" => $menuItem->franchise_id,
                    "menu_type" => $menuItem->menu_type,
                    "menu_category_id" => $menuItem->menu_category_id,
                    "menu_category_name" => $menuItem->menu_category_name,
                    "description" => $menuItem->description,
                    "base_price" => $menuItem->base_price,
                    "current_price" => $menuItem->current_price,
                    "menu_image" => $image_url
                ];
            }

            return response()->json(["result" => $result], 200);
        }
    }


 public function create_menu(Request $request) // ----->20.08.2024
    {
        $validator = Validator::make($request->all(), [
            'franchise_id' => 'required',
            'menu_type' => 'required',
            'menu_category_name' => 'required',
            'description' => 'required',
            'base_price' => 'required',
            'current_price' => 'required',
            'menu_image' => 'required', // Assuming 'menu_image' is the name of the file input field
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        // Get current timestamp for ID and date
        date_default_timezone_set('Asia/Calcutta');
        $time = time();
        $current_date = date("Y-m-d H:i:s", $time);

        $menu_category_id = 'MENUCAT' . $time;

        // Retrieve input data from request
        $franchise_id = $request->input('franchise_id');
        $menu_type = $request->input('menu_type');
        $menu_category_name = $request->input('menu_category_name');
        $description = $request->input('description');
        $base_price = $request->input('base_price');
        $current_price = $request->input('current_price');
        $menu_image = $request->file('menu_image'); // Retrieve file from request
        if (!empty($menu_image)) {
            $img_id = (date("dmyHis", $time));
            $path = 'MENUIMG' . $img_id . ".png";
            $directoryPath = public_path('uploads/images/menu_images/'); // Corrected path

            // Using the File facade to manage filesystem operations
            \Illuminate\Support\Facades\File::makeDirectory($directoryPath, $mode = 0777, true, true);

            // Storing image using Laravel's file system
            $menu_image->move($directoryPath, $path);
        } else {
            $path = '';
        }

        // Create new menu instance
        $menu = new menu_model();
        $menu->menu_category_id = $menu_category_id;
        $menu->franchise_id = $franchise_id;
        $menu->menu_type = $menu_type;
        $menu->menu_category_name = $menu_category_name;
        $menu->description = $description;
        $menu->base_price = $base_price;
        $menu->current_price = $current_price;
        $menu->menu_image = $path; // Store file path, not the binary data
        $menu->status = 'active';
        $menu->created_at = $current_date; // Corrected typo in 'created_at'

        // Save menu to database
        $menu->save();

        // Check if menu was saved successfully
        if (!$menu->exists) {
            $response = [
                'success' => false,
                'message' => 'Menu not added successfully'
            ];
        } else {
            $response = [
                'success' => true,
                'message' => 'Menu added successfully'
            ];
        }

        return response()->json($response);
    }


//----------------------new checking code------------


// public function create_menu(Request $request)
// {
//     // Validate the request data
//     $validator = Validator::make($request->all(), [
//         'franchise_id' => 'required',
//         'menu_type' => 'required',
//         'menu_category_name' => 'required',
//         'description' => 'required',
//         'base_price' => 'required',
//         'current_price' => 'required',
//         'menu_image' => 'required|image', // Ensure 'menu_image' is an image file
//     ]);

//     if ($validator->fails()) {
//         return response()->json(['error' => $validator->errors()->first()], 422);
//     }

//     // Get current timestamp for ID and date
//     date_default_timezone_set('Asia/Calcutta');
//     $time = time();
//     $current_date = date("Y-m-d H:i:s", $time);
//     $menu_category_id = 'MENUCAT' . $time;

//     // Retrieve input data from request
//     $franchise_id = $request->input('franchise_id');
//     $menu_type = $request->input('menu_type');
//     $menu_category_name = $request->input('menu_category_name');
//     $description = $request->input('description');
//     $base_price = $request->input('base_price');
//     $current_price = $request->input('current_price');
//     $menu_image = $request->file('menu_image'); // Retrieve file from request

//     // Handling the image upload
//     if ($menu_image) {
//         $img_id = date("dmyHis", $time);
//         $filename = 'MENUIMG' . $img_id . '.' . $menu_image->getClientOriginalExtension();
//         $path = public_path('uploads/images/menu_test_images/'); // Directory to store the images

//         // Ensure the directory exists
//         if (!File::exists($path)) {
//             File::makeDirectory($path, 0755, true, true);
//         }

//         // Move the uploaded file to the directory
//         $menu_image->move($path, $filename);
//     } else {
//         $filename = ''; // In case there's no image, though the validation should prevent this
//     }

//     // Create new menu instance and save to database
//     $menu = new menu_model();
//     $menu->menu_category_id = $menu_category_id;
//     $menu->franchise_id = $franchise_id;
//     $menu->menu_type = $menu_type;
//     $menu->menu_category_name = $menu_category_name;
//     $menu->description = $description;
//     $menu->base_price = $base_price;
//     $menu->current_price = $current_price;
//     $menu->menu_image = $filename;
//     $menu->status = 'active';
//     $menu->created_at = $current_date;
//     $menu->save();

//     // Return the response
//     if ($menu->exists) {
//         return response()->json(['success' => true, 'message' => 'Menu added successfully']);
//     } else {
//         return response()->json(['success' => false, 'message' => 'Menu not added successfully']);
//     }
// }





    public function delete_menu(Request $request, $menu_category_id)
{
   // $franchise_id = $request->input('franchise_id');

    $delete = DB::table('menu')
       ->where('menu_category_id', '=', $menu_category_id)
     //  ->where('status', '=', 'active')
       ->delete();

       if (!$delete) {
        $success['message'] = "Menu delete not successfully";
        $success['success'] = false;
        return response()->json($success);
    } else {
        $success['message'] = "Menu delete successfully";
        $success['success'] = true;
        return response()->json($success);
    }

}


// public function update_menu(Request $request, $menu_category_id, $franchise_id)  //----->working
// {
//     $menu = DB::table('menu')
//         ->where('menu_category_id', '=', $menu_category_id)
//         ->where('franchise_id', '=', $franchise_id)
//         ->where('status', '=', 'active')
//         ->first();

//     if (!$menu) {
//         $fail['message'] = "Menu not found";
//         $fail['success'] = false;
//         return response()->json($fail, 404);
//     } else {
//         date_default_timezone_set('Asia/Calcutta');
//         $time = time();
//         $current_date = date("Y-m-d H:i:s", $time);

//         $menu->menu_category_name = $request->input('menu_category_name');
//         $menu->description = $request->input('description');
//         $menu->base_price = $request->input('base_price');
//         $menu->current_price = $request->input('current_price');
//       //  $franchise->mobile = $request->input('mobile');

//         $menu_image = $request->file('menu_image');

//         if (!empty($menu_image)) {
//             $img_id = date("dmyHis", $time);
//             $path = 'MENUIMG' . $img_id . ".png";
//             $directoryPath = public_path('$baseUrl.uploads/images/menu_test_images/');

//             // Ensure directory exists
//             \Illuminate\Support\Facades\File::makeDirectory($directoryPath, $mode = 0777, true, true);

//             // Move the uploaded image to the directory
//             $menu_image->move($directoryPath, $path);

//             // Update the path in the $franchise object
//             $menu->menu_image = $path;
//         } else {
//             $path = '';
//         }

//         // Update the franchise details in the database
//         $update = DB::table('menu')
//             ->where('menu_category_id', '=', $menu_category_id)
//             ->where('franchise_id', '=', $franchise_id)
//             ->where('status', '=', 'active')
//             ->update([
//                 'menu_category_name' => $menu->menu_category_name,
//                 'description' => $menu->description,
//                 'base_price' => $menu->base_price,
//                 'current_price' => $menu->current_price,
//               //  'mobile' => $menu->mobile,
//                 'menu_image' => $menu->menu_image
//             ]);

//         if (!$update) {
//             $success['message'] = "Menu update not successful";
//             $success['success'] = false;
//             return response()->json($success);
//         } else {
//             $success['message'] = "Menu updated successfully";
//             $success['success'] = true;
//             return response()->json($success);
//         }
//     }

// }



//-------------->new checking code

public function update_menu(Request $request, $menu_category_id, $franchise_id)
{
    // Find the existing menu entry
    $menu = DB::table('menu')
        ->where('menu_category_id', '=', $menu_category_id)
        ->where('franchise_id', '=', $franchise_id)
      //  ->where('status', '=', 'active')
        ->first();

    if (!$menu) {
        return response()->json(['message' => 'Menu not found', 'success' => false], 404);
    } else {

        date_default_timezone_set('Asia/Calcutta');
        $time = time();
        $current_date = date("Y-m-d H:i:s", $time);

        // Prepare the new data
        $menu_data = [
            'menu_category_name' => $request->input('menu_category_name'),
            'description' => $request->input('description'),
            'base_price' => $request->input('base_price'),
            'current_price' => $request->input('current_price'),
            'updated_at' => $current_date
        ];

        // Handle the menu image
        $menu_image = $request->file('menu_image');
        if (!empty($menu_image)) {
            $img_id = date("dmyHis", $time);
            $path = 'MENUIMG' . $img_id . ".png";
            $directoryPath = public_path('$baseUrl.uploads/images/menu_images/');

            // Ensure directory exists
            \Illuminate\Support\Facades\File::makeDirectory($directoryPath, $mode = 0777, true, true);

            // Delete the old image file if it exists
            if (file_exists($directoryPath . $menu->menu_image)) {
                unlink($directoryPath . $menu->menu_image);
            }

            // Move the uploaded image to the directory
            $menu_img_file = $menu_image->move($directoryPath, $path);

            // Update the image path in the data array
            $menu_data['menu_image'] = $path;
        }

        // Update the menu entry in the database
        $update = DB::table('menu')
            ->where('menu_category_id', '=', $menu_category_id)
            ->where('franchise_id', '=', $franchise_id)
            ->where('status', '=', 'active')
            ->update($menu_data);

        if (!$update) {
            return response()->json(['message' => 'Menu update not successful', 'success' => false]);
        } else {
            return response()->json(['message' => 'Menu updated successfully', 'success' => true]);
        }
    }
}





public function toggle_menu_status(Request $request, $menu_category_id, $franchise_id ) {

    // Retrieve the franchise record
    $menu = DB::table('menu')
    ->where('franchise_id', '=', $franchise_id)
    ->where('menu_category_id', '=', $menu_category_id)
    ->first();

    if (!$menu) {
        $success['message'] = "Menu not found";
        $success['success'] = false;
        return response()->json($success);
    }

    // Determine the new status
    $new_status = $menu->status == 'active' ? 'inactive' : 'active';

    // Update the status
    DB::table('menu')
        ->where('franchise_id', $franchise_id)
        ->where('menu_category_id', '=', $menu_category_id)
        ->update(['status' => $new_status]);

    $success['message'] = "Menu status update successfully";
    $success['new_status'] = $new_status;
    $success['success'] = true;
    return response()->json($success);
}


public function get_bidding_hidden_menu(Request $request, $franchise_id)
{
    $menu = DB::table('menu')
        ->select('franchise_id', 'menu_category_id', 'menu_category_name', 'description', 'base_price', 'current_price', 'created_at', 'menu_image')
       ->where('franchise_id', '=', $franchise_id)
       ->where('menu_type', '=', 'Bidding')
        ->where('status', '=', 'inactive')
        ->get();

    if ($menu->isEmpty()) {
        $fail['message'] = "No inactive franchises found";
        $fail['success'] = false;
        return response()->json($fail, 404);
    } else {
        $result = array();

        foreach ($menu as $menu) {
            $menu_data = (array) $menu;
            $menu_category_id = $menu_data['menu_category_id'];
            $menu_category_name = $menu_data['menu_category_name'];
            $description = $menu_data['description'];
            $base_price = $menu_data['base_price'];
            $current_price = $menu_data['current_price'];
           // $mobile = $menu_data['mobile']; // Corrected the column name
            $created_at = $menu_data['created_at'];
            $image = $menu_data['menu_image'];

            if (empty($image)) {
                $img = '';
            } else {
                $img = '$baseUrl.uploads/images/menu_images/' . $image;
            }

            $result[] = array(
                "franchise_id" => $franchise_id,
                "menu_category_id" => $menu_category_id,
                "menu_category_name" => $menu_category_name,
                "description" => $description,
                "base_price" => $base_price,
                "current_price" => $current_price,
                "created_at" => $created_at,
                "image" => $img
            );
        }

        return response()->json(array("result" => $result), 200);
    }
}





public function get_fixed_hidden_menu(Request $request, $franchise_id)
{
    $menu = DB::table('menu')
        ->select('franchise_id', 'menu_category_id','menu_category_name', 'description', 'base_price', 'current_price', 'created_at', 'menu_image')
        ->where('franchise_id', '=', $franchise_id)
        ->where('menu_type', '=', 'Fixed Price')
        ->where('status', '=', 'inactive')
        ->get();

    if ($menu->isEmpty()) {
        $fail['message'] = "No inactive franchises found";
        $fail['success'] = false;
        return response()->json($fail, 404);
    } else {
        $result = array();

        foreach ($menu as $menu) {
            $menu_data = (array) $menu;
            $menu_category_id = $menu_data['menu_category_id'];
         //   $menu_type = $menu_data['menu_type'];
            $menu_category_name = $menu_data['menu_category_name'];
            $description = $menu_data['description'];
            $base_price = $menu_data['base_price'];
            $current_price = $menu_data['current_price'];
           // $mobile = $menu_data['mobile']; // Corrected the column name
            $created_at = $menu_data['created_at'];
            $image = $menu_data['menu_image'];

            if (empty($image)) {
                $img = '';
            } else {
                $img = '$baseUrl.uploads/images/menu_images/' . $image;
            }

            $result[] = array(
                "franchise_id" => $franchise_id,
                "menu_category_id" => $menu_category_id,
             //   "menu_type" => $menu_type,
                "menu_category_name" => $menu_category_name,
                "description" => $description,
                "base_price" => $base_price,
                "current_price" => $current_price,
                "created_at" => $created_at,
                "image" => $img
            );
        }

        return response()->json(array("result" => $result), 200);
    }
}






}
