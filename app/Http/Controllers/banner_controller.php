<?php

namespace App\Http\Controllers;

use App\Models\banner_model;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;



class banner_controller extends Controller
{


    public function add_banner(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'banner_image' => 'required|image', // Adding validation for image file
            // 'redirect_url' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        date_default_timezone_set('Asia/Calcutta');
        $current_date = now(); // Using Laravel's helper function to get current date and time

        $banner_id = 'BNR' . $current_date->format('YmdHis');

        $banner_image = $request->file('banner_image');
        $redirectionLink = $request->input('redirect_url'); // Retrieving file from request

        if (!empty($banner_image)) {
            $img_id = $current_date->format('YmdHis');
            $bnrextension = $banner_image->getClientOriginalExtension();
            $bannerName = 'BNRIMG' . $img_id . "." . $bnrextension;
            $directoryPath = public_path('uploads/images/banner_images/'); // Corrected path

            // Using the File facade to manage filesystem operations
            \Illuminate\Support\Facades\File::makeDirectory($directoryPath, $mode = 0777, true, true);

            // Storing image using Laravel's file system
            $banner_image->move($directoryPath, $bannerName);
        } else {
            $bannerName = '';
        }

        // Creating and saving banner model
        $banner = new banner_model(); // Spelling unchanged
        $banner->banner_id = $banner_id;
        $banner->banner_image = $bannerName;
        $banner->redirect_url = $redirectionLink;
        $banner->status = 'active';
        $banner->created_at = $current_date;
        $banner->save();

        if (!$banner) {
            $success['message'] = "Banner not added successfully";
            $success['success'] = false;
            return response()->json($success);
        } else {
            $success['message'] = "Banner added successfully";
            $success['success'] = true;
            return response()->json($success);
        }
    }


    public function get_banner(Request $request)
    {
        $banner = DB::table('banner')
            // ->where('franchise_id', $franchise_id)
            ->where('status', '=', 'active')
            ->get();

        if ($banner->isEmpty()) {
            $fail['message'] = "No Banner found";
            $fail['success'] = false;
            return response()->json($fail, 404);
        } else {
            $result = [];
            foreach ($banner as $bannerImg) {
                $banner_image_url = '';
                if (!empty($bannerImg->banner_image)) {
                    $banner_image_url = asset('uploads/images/banner_images/' . $bannerImg->banner_image);
                }
                $result[] = [
                    // "franchise_id" => $bannerImg->franchise_id,
                    "banner_image" => $banner_image_url,
                    "redirect_url" => $bannerImg->redirect_url,
                ];
            }

            return response()->json(["result" => $result], 200);
        }
    }


    public function update_banner(Request $request, $banner_id)
    {
        // Find the existing menu entry
        $banner = DB::table('banner')
            ->where('banner_id', $banner_id)
            //  ->where('status', '=', 'active')
            ->first();

        if (!$banner) {
            return response()->json(['message' => 'Banner not found', 'success' => false], 404);
        } else {

            date_default_timezone_set('Asia/Calcutta');
            $time = time();
            $current_date = date("Y-m-d H:i:s", $time);

            // Prepare the new data
            $banner_data = [
                'banner_image' => $request->input('banner_image'),
                'redirect_url' => $request->input('redirect_url'),
                // 'base_price' => $request->input('base_price'),
                // 'current_price' => $request->input('current_price'),
                'updated_at' => now()
            ];

            // Handle the menu image
            $banner_image = $request->file('banner_image');
            if (!empty($banner_image)) {
                $img_id = date("dmyHis", $time);
                $bnrextension = $banner_image->getClientOriginalExtension();
                $bannerName = 'BNRIMG' . $img_id . ".$bnrextension";
                $directoryPath = public_path('uploads/images/banner_images/');

                // Ensure directory exists
                \Illuminate\Support\Facades\File::makeDirectory($directoryPath, $mode = 0777, true, true);

                // Delete the old image file if it exists
                if (file_exists($directoryPath . $banner->banner_image)) {
                    unlink($directoryPath . $banner->banner_image);
                }

                // Move the uploaded image to the directory
                $banner_img_file = $banner_image->move($directoryPath, $bannerName);

                // Update the image path in the data array
                $banner_data['banner_image'] = $bannerName;
            }

            // Update the menu entry in the database
            $update = DB::table('banner')
                ->where('banner_id', $banner_id)
              //  ->where('franchise_id', '=', $franchise_id)
              //  ->where('status', '=', 'active')
                ->update($banner_data);

            if (!$update) {
                return response()->json(['message' => 'Banner update not successful', 'success' => false]);
            } else {
                return response()->json(['message' => 'Banner updated successfully', 'success' => true]);
            }
        }
    }

    public function Tsit_BPM_Del_Banner(Request $request, $token, $banner_id) {
        // Check if the admin exists
        $admin = DB::table('admin')
            ->where('token', $token)
            ->where('admin_type', 'primary')
            ->first();

        if (!$admin) {
            return response()->json([
                'message' => "You are Restricted for this Action",
                'success' => false,
            ]);
        }

        // Retrieve the banner
        $bnrimg = DB::table('banner')
            ->where('banner_id', $banner_id)
            ->first();

        if (!$bnrimg) {
            return response()->json([
                'message' => "No Banner Found",
                'success' => false,
            ]);
        }

        // Prepare the banner image path
        $banner_path = public_path('uploads/images/banner_images/' . $bnrimg->banner_image);

        // Delete the banner image file if it exists
        if (file_exists($banner_path)) {
            unlink($banner_path);
        }

        // Attempt to delete the banner from the database
        $delete = DB::table('banner')
            ->where('banner_id', $banner_id)
            ->where('status', 'active')
            ->delete();

        if ($delete) {
            return response()->json([
                'message' => "Banner Deleted successfully",
                'success' => true,
            ]);
        } else {
            return response()->json([
                'message' => "Banner Not Deleted",
                'success' => false,
            ]);
        }
    }




}
