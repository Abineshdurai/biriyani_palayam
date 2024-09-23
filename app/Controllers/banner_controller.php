<?php

namespace App\Http\Controllers;

use App\Models\banner_model;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;



class banner_controller extends Controller{

    // public function add_banner(Request $request){
    //     $validator = Validator::make($request->all(), [
    //         'banner_image' => 'required|image', // Adding validation for image file
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 400);
    //     }

    //     date_default_timezone_set('Asia/Calcutta');
    //     $current_date = now(); // Using Laravel's helper function to get current date and time

    //     $banner_id = 'BANNER' . $current_date->format('YmdHis');

    //     $banner_image = $request->file('banner_image'); // Retrieving file from request

    //     if (!empty($banner_image)) {
    //         $img_id = $current_date->format('YmdHis');
    //         $path = 'BANNERIMG' . $img_id . ".png";
    //         $directoryPath = public_path('..uploads/images/banner_images/'); // Using public_path to get the absolute path
    //         if (!file_exists($directoryPath)) {
    //             mkdir($directoryPath, 0777, true);
    //         }

    //         // Storing image using Laravel's file system
    //         $banner_image->move($directoryPath, $path);
    //     } else {
    //         $path = '';
    //     }

    //     // Creating and saving banner model
    //     $banner = new banner_model();
    //     $banner->banner_id = $banner_id;
    //     $banner->banner_image = $path;
    //     $banner->status = 'active';
    //     $banner->created_at = $current_date;
    //     $banner->save();

    //     if (!$banner) {
    //         $success['message'] = "Banner image not added successfully";
    //         $success['success'] = false;
    //         return response()->json($success);
    //     } else {
    //         $success['message'] = "Banner image added successfully";
    //         $success['success'] = true;
    //         return response()->json($success);
    //     }




    // }




    public function add_banner(Request $request){
    $validator = Validator::make($request->all(), [
        'banner_image' => 'required|image', // Adding validation for image file
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    date_default_timezone_set('Asia/Calcutta');
    $current_date = now(); // Using Laravel's helper function to get current date and time

    $banner_id = 'BANNER' . $current_date->format('YmdHis');

    $banner_image = $request->file('banner_image'); // Retrieving file from request

    if (!empty($banner_image)) {
        $img_id = $current_date->format('YmdHis');
        $path = 'BANNERIMG' . $img_id . ".png";
        $directoryPath = public_path('$baseUrl.uploads/images/banner_images/'); // Corrected path

        // Using the File facade to manage filesystem operations
        \Illuminate\Support\Facades\File::makeDirectory($directoryPath, $mode = 0777, true, true);

        // Storing image using Laravel's file system
        $banner_image->move($directoryPath, $path);
    } else {
        $path = '';
    }

    // Creating and saving banner model
    $banner = new banner_model(); // Spelling unchanged
    $banner->banner_id = $banner_id;
    $banner->banner_image = $path;
    $banner->status = 'active';
    $banner->created_at = $current_date;
    $banner->save();

    if (!$banner) {
        $success['message'] = "Banner image not added successfully";
        $success['success'] = false;
        return response()->json($success);
    } else {
        $success['message'] = "Banner image added successfully";
        $success['success'] = true;
        return response()->json($success);
    }
}






}
