<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\admin_model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\Exception;






class admin_controller extends Controller
{

    // public function admin_login(Request $request)
    // {
    //     // Validation rules
    //     $validator = Validator::make($request->all(), [
    //         'user_name' => 'required|alpha',
    //         'password' => 'required|min:8',
    //     ]);

    //     // Check if validation fails
    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 422);
    //     }
    //     $current_date = (date("YmdHis", time()));
    //     // Generate unique admin ID
    //     $admin_id = 'ADMIN' . now()->format('YmdHis');
    //     $token = tokenKey($admin_id);
    //     // Create new admin instance
    //     $admin = new admin_model();
    //     $admin->admin_id = $admin_id;
    //     $admin->token = $token;
    //     $admin->user_name = $request->input('user_name');
    //     $password = $request->input('password');
    //     $admin->password = bcrypt($password); // Hash the password securely
    //     $admin->status = "active";
    //     $admin->created_at = $current_date;
    //     $admin->save();

    //     // Check if admin was saved successfully
    //     if (!$admin) {
    //         return response()->json(['error' => 'Admin not added successfully'], 500);
    //     } else {
    //         return response()->json(['success' => 'Admin added successfully'], 200);
    //     }
    // }
    // public function login(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'user_name' => 'required',
    //         'password' => 'required|min:8'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 422);
    //     } else {
    //         $user_name = $request->input('user_name');
    //         $password = $request->input('password');

    //         // Retrieve the admin record from the database with case-sensitive comparison
    //         $adminRecord = admin_model::whereRaw("BINARY user_name = ?", [$user_name])->first();

    //         if ($adminRecord && Hash::check($password, $adminRecord->password)) {
    //             $success['result'] = "login success";
    //             $success['token'] = $adminRecord->token; // Include the token in the response

    //             return response()->json($success);
    //         } else {
    //             $fail['result'] = "Authentication failed";
    //             return response()->json($fail);
    //         }
    //     }
    // }

    public function TSIT_BPM_Create_Admin_Login(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'mobile' => 'required|unique:admin,mobile',
            'email' => 'required',
            'address' => 'required',
            'admin_type' => 'required',
            'category' => 'required',
            'joining_date' => 'required|date_format:d.m.Y'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $date = Carbon::createFromFormat('d.m.Y', $request->input('joining_date'))->format('Y.m.d');
        // Generate unique admin ID
        $mobile = $request->input('mobile');
        $admin_id = 'ADMIN' . now()->format('YmdHis');
        $token = tokenKey($mobile);
        // Create new admin instance

        $admin = new admin_model();
        $admin->admin_id = $admin_id;
        $admin->token = $token;
        $admin->name = $request->input('name');
        $admin->mobile = $request->input('mobile');
        $admin->email = $request->input('email');
        $admin->address = $request->input('address');
        $admin->admin_type = $request->input('admin_type');
        $admin->category = $request->input('category');
        $admin->joining_date = $date;
        $admin->status = "active";
        $admin->created_at = now();
        $admin->save();

        // Check if admin was saved successfully
        if (!$admin) {
            $success['message'] = 'Admin not added successfully';
            $success['success'] = false;
            return response()->json([$success], 500);
        } else {
            $success['message'] = 'Admin added successfully';
            $success['success'] = false;
            return response()->json([$success], 200);
        }
    }


    // public function login(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'user_name' => 'required',
    //         'password' => 'required|min:8'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 422);
    //     } else {
    //         $user_name = $request->input('user_name');
    //         $password = $request->input('password');

    //         // Retrieve the admin record from the database with case-sensitive comparison
    //         $adminRecord = admin_model::whereRaw("BINARY user_name = ?", [$user_name])->first();

    //         if ($adminRecord && Hash::check($password, $adminRecord->password)) {
    //             $success['result'] = "login success";
    //             $success['token'] = $adminRecord->token; // Include the token in the response
    //             $success['admin_type'] = $adminRecord->admin_type;
    //             return response()->json($success);
    //         } else {
    //             $fail['result'] = "Authentication failed";
    //             return response()->json($fail);
    //         }
    //     }
    // }


    public function Tsit_BPM_Admin_SentOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required',
            //    'password' => 'required|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Prepare the data for the API call
        $mobile = $request->input('mobile');
        $url = "https://api.msg91.com/api/v5/otp";
        $postData = json_encode([
            "template_id" => "663b1a84d6fc052b3162e221",
            "mobile" => "91" . $mobile, // Add the country code explicitly if needed
            "authkey" => "419918ACNopOZIgHI663dfcc6P1",
            "Param1" => "value1" // Assuming 'Param1' is a placeholder you want to replace
        ]);
        try {
            // Initialize CURL session
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $postData,
                CURLOPT_HTTPHEADER => [
                    "Content-Type: application/json"
                ],
            ]);

            // Execute CURL session
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            // Return success response
            $success['message'] = "OTP sent successfully";
            $success['success'] = true;
            return response()->json($success);
        } catch (Exception $e) {
            // Return error response if email sending fails
            $error['message'] = "Error sending OTP";
            $error['success'] = false;
            return response()->json($error, 500);
        }
    }





    public function Tsit_BPM_Admin_VerifyOTP(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits:10',
            'otp' => 'required|numeric|digits:4',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        // Prepare API URL and parameters
        $mobile = $request->input('mobile');
        $otp = $request->input('otp');
        $authKey = env('MSG91_AUTH_KEY', 'default_auth_key');
        $url = "https://api.msg91.com/api/v5/otp/verify?mobile=91{$mobile}&otp={$otp}&authkey={$authKey}";

        // Initialize CURL session
        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ],
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        // Execute CURL session
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return response()->json(['message' => "API Error: " . $err, 'success' => false], 500);
        }

        $responseData = json_decode($response, true);

        // Check API response for OTP verification result
        if (!$responseData || $responseData['type'] == 'error') {
            return response()->json(['message' => "Invalid OTP", 'success' => true], 400);
        }

        // Check if the user is registered
        $isRegistered = DB::table('admin')
            ->where('mobile', $mobile)
            ->where('status', 'active')
            ->first();

        if (!$isRegistered) {
            return response()->json(['message' => "Not registered", 'success' => false], 404);
        }

        $admin_id = $isRegistered->admin_id;
        // Generate and update the token
        $token = tokenKey($admin_id); // Ensure this function is securely implemented
        $update1 = DB::table('admin')
            ->where('mobile', '=', $mobile)
            ->update(["token" => '1']);  //  $token = tokenKey($user_id);
        $update = DB::table('admin')
            ->where('mobile', $mobile)
            ->update(['token' => $update1]);

        if (!$update) {
            return response()->json(['message' => "Authentication failed", 'success' => false], 500);
        }
        $admin_type = $isRegistered->admin_type;

        return response()->json(['message' => "OTP verified successfully", 'token' => $token, 'admin_type' => $admin_type,'success' => false], 200);
    }


    public function TSIT_BPM_Get_All_Admin()
    {
        $admins = admin_model::all();
        if($admins->isEmpty()){
            return response()->json([
                'message' => 'No Admins Found',
                'success' => false,
            ], 404);
        }
        else {
            return response()->json([
                'message' => $admins,
                'success' => true,
            ]);
        }
    }

    public function TSIT_BPM_Delete_Admin($token)
    {
        $del_admin = DB::table('admin')
        ->where('token', $token)
        ->where('status', 'active')
        ->first();
        if(!$del_admin)
        {
            $success['message']   = 'Admin Not Found';
            $success['success'] = false;
              return response()->json([$success]);
        }
        else
        {
        $delete = DB::table('admin')
      //  ->where('admin_id', $admin_id)
        ->where('token', $token)
        ->where('status', 'active')
        ->delete();

        if(!$delete){
          $success['message']   = 'Admin Not Deleted Successfully';
          $success['success'] = false;
            return response()->json([$success]);
        } else {
            $success['message']   = 'Admin Deleted Successfully';
            $success['success'] = true;
              return response()->json([$success]);
        }
    }
    }


    public function TSIT_BPM_Edit_Admin(Request $request, $token)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'mobile' => 'required|unique:admin,mobile',
            'email' => 'required',
            'address' => 'required',
            'admin_type' => 'required',
            'category' => 'required',
            'joining_date' => 'required'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $edit_admin = DB::table('admin')
        ->where('token', $token)
        ->where('status', 'active')
        ->first();
        if (!$edit_admin) {
            $success['message']   = 'Admin Not Found';
            $success['success'] = false;
            return response()->json([$success]);
        } else {
            $date = Carbon::createFromFormat('d.m.Y', $request->input('joining_date'))->format('Y.m.d');
            $admin_id = $edit_admin->admin_id;
            $name = $request->input('name');
            $mobile = $request->input('mobile');
            $email = $request->input('email');
            $address = $request->input('address');
            $admin_type = $request->input('admin_type');
            $category = $request->input('category');
            $joining_date = $date;

            $update = DB::table('admin')
            ->where('token', $token)
            ->where('admin_id', $admin_id)
            ->where('status', 'active')
                ->update([
                    'name' => $name,
                    'mobile' => $mobile,
                    'email' => $email,
                    'address' => $address,
                    'admin_type' => $admin_type,
                    'category' => $category,
                    'joining_date' => $joining_date,
                ]);

            if (!$update) {
                $success['message'] = 'Admin Not Updated';
                $success['success'] = false;
                return response()->json([$success]);
            } else {
                $success['message'] = 'Admin Updated Successfully';
                $success['success'] = true;
                return response()->json([$success]);
            }
        }
    }




}



