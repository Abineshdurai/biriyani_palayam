<?php

namespace App\Http\Controllers;

use App\Models\user_model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
//use Validator;
// use Illuminate\Support\Facades\Validator;
// use Illuminate\Support\Facades\DB;
use App\Models\email_verification_model;
use Carbon\Carbon;

use Illuminate\Support\Facades\Validator;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
//require 'vendor/autoload.php';


//>>>>>>>>>>>>>>>>>>>>>>>>>DEVELOPEMENT_CODE<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<\\

class user_controller extends Controller
{



// public function sentOTP(Request $request)
// {
//     // Validate request data
//     $validator = Validator::make($request->all(), [
//         'email' => 'required|email',
//     ]);

//     if ($validator->fails()) {
//         // Return validation error messages if validation fails
//         return response()->json($validator->messages(), 200);
//     } else {
//         // Set a specific OTP for a specific email
//         $specificEmail = "bpmdev24@gmail.com"; // The specific email to not send OTP
//         if ($request->input('email') === $specificEmail) {
//             $otp = 1234;
//             $sendEmail = false; // Do not send email for the specific email address
//         } else {
//             // Generate a random 4-digit OTP for other emails
//             $otp = rand(1000, 9999);
//             $sendEmail = true; // Send email for other addresses
//         }

//         // Save OTP and email in the database
//         try {
//             date_default_timezone_set('Asia/Calcutta');
//             $current_date = date("Y-m-d H:i:s", time());
//             $email_verification = new email_verification_model();
//             $email_verification->email = $request->input('email');
//             $email_verification->otp = $otp;
//             $email_verification->created_at = $current_date;
//             $email_verification->save();
//         } catch (Exception $e) {
//             // Handle database insertion error
//             $error['message'] = "Error saving OTP and email in the database: " . $e->getMessage();
//             $error['success'] = false;
//             return response()->json($error, 500);
//         }

//         if ($sendEmail) {
//             // Send OTP via email
//             try {
//                 $email = new PHPMailer(true);
//                 $email->isSMTP();
//                 $email->Host = 'tabsquareinfotech.com';
//                 $email->SMTPAuth = true;
//                 $email->Username = 'simson@tabsquareinfotech.com';
//                 $email->Password = 'Simson@tabsquareinfotech'; // Update with the correct password
//                 $email->SMTPSecure = 'ssl';
//                 $email->Port = 465;
//                 $email->setFrom('simson@tabsquareinfotech.com', 'MyFood MyPrice');
//                 $email->addAddress($request->email);
//                   $email->isHTML(true);
//                 $email->Subject = 'MyFood MyPrice One-Time Password (OTP)';
//                 // $email->Body = 'MyFood MyPrice OTP for BiryaniPalayam is  ' . $otp . ' kindly use this for login into your BiryaniPalayam App';
//                 $email->Body = 'MyFood MyPrice OTP for BiryaniPalayam is <strong><span style="color:blue">' . $otp . '</span></strong> kindly use this for login into your MyFood MyPrice App';

//                 $email->send();

//                 $success['message'] = "OTP sent successfully";
//                 $success['success'] = true;
//                 return response()->json($success);
//             } catch (Exception $e) {
//                 $error['message'] = "Error sending OTP via email: " . $e->getMessage();
//                 $error['success'] = false;
//                 return response()->json($error, 500);
//             }
//         } else {
//             // Return success message without sending email for the specific email
//             $success['message'] = "OTP generated but not sent for specific email";
//             $success['success'] = true;
//             return response()->json($success);
//         }


//     }

// }



public function sentOTP(Request $request)
{
    // Validate request data
    $validator = Validator::make($request->all(), [
        'mobile' => 'required|digits:10', // Ensure mobile number is required and has exactly 10 digits
    ]);

    if ($validator->fails()) {
        // Return validation error messages if validation fails
        return response()->json($validator->errors(), 400); // Return a 400 Bad Request with the error details
    }

    // Prepare the data for the API call
    $mobile = $request->input('mobile');
    $url = "https://api.msg91.com/api/v5/otp";
    $postData = json_encode([
        "template_id" => "6663091cd6fc054ac938ae31",
        "mobile" => "91" . $mobile, // Add the country code explicitly if needed
        "authkey" => "419918ACNopOZIgHI663dfcc6P1",
        "Param1" => "value1" // Assuming 'Param1' is a placeholder you want to replace
    ]);
try{
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

    // // Handle CURL error
    // if ($err) {
    //     return response()->json(['error' => 'cURL Error #: ' . $err], 500); // Return server error
    // }

    // // Convert the response into an associative array and return it
    // return response()->json(json_decode($response, true), 200

       // Return success response
                $success['message'] = "OTP sent successfully";
                $success['success'] = true;
                return response()->json($success);
            } catch (Exception $e) {
                // Return error response if email sending fails
                $error['message'] = "Error sending OTP via email";
                $error['success'] = false;
                return response()->json($error, 500);
            }
}





    // public function verifyOTP(Request $request)
    // {
    //     // Validate request data
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email',
    //         'otp' => 'required|numeric|digits:4',
    //     ]);

    //     if ($validator->fails()) {
    //         // Return validation error messages if validation fails
    //         return response()->json($validator->messages(), 200);
    //     } else {
    //         // Retrieve the OTP from the database using the provided email
    //         $emailVerification = email_verification_model::where('email', $request->email)->latest()->first();

    //         if ($emailVerification && $emailVerification->otp == $request->otp) {
    //             // OTP matches, check if the user is registered
    //             $isregistered = DB::table('user')
    //                 ->where('email', '=', $request->email)
    //                 ->exists();

    //             if (!$isregistered) {
    //                 // User not registered, return appropriate response
    //                 $success['message'] = "Not registered";
    //                 $success['success'] = false;
    //                 return response()->json($success);
    //             }

    //             // User is registered, generate and update token
    //             $token = tokenKey($request->email);
    //             $update1 = DB::table('user')
    //                 ->where('email', '=', $request->email)
    //                 ->update(["token" => '1']);
    //             $update = DB::table('user')
    //                 ->where('email', '=', $request->email)
    //                 ->update(["token" => $token]);

    //             if (!$update) {
    //                 // Token update failed, return error response
    //                 $error['message'] = "Authentication failed";
    //                 $error['success'] = false;
    //                 return response()->json($error);
    //             }

    //             // Token updated successfully, return success response
    //             $success['message'] = "OTP verified successfully";
    //             $success['success'] = true;
    //             $success['token'] = $token;

    //             // Optionally, you can delete the entry from the database after successful verification
    //             $emailVerification->delete();

    //             return response()->json($success);
    //         } else {
    //             // OTP does not match or not found in the database, return error response
    //             $error['message'] = "Invalid OTP";
    //             $error['success'] = false;
    //             return response()->json($error, 400);
    //         }
    //     }
    // }




public function verifyOTP(Request $request)
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
        return response()->json(['message' => "Invalid OTP", 'success' => false], 400);
    }

    // Check if the user is registered
    $isRegistered = DB::table('user')
        ->where('mobile', $mobile)
        ->where('status', 'active')
        ->exists();

    if (!$isRegistered) {
        return response()->json(['message' => "Not registered", 'success' => false], 404);
    }

    // Generate and update the token
    $token = tokenKey($mobile); // Ensure this function is securely implemented
      $update1 = DB::table('user')
                    ->where('mobile', '=', $mobile)
                    ->update(["token" => '1']);
    $update = DB::table('user')
        ->where('mobile', $mobile)
        ->update(['token' => $token]);

    if (!$update) {
        return response()->json(['message' => "Authentication failed", 'success' => true], 500);
    }

    return response()->json(['message' => "OTP verified successfully", 'token' => $token, 'success' => true], 200);
}



// public function verifyOTP(Request $request)
// {
//     // Validate request data
//     $validator = Validator::make($request->all(), [
//         'mobile' => 'required|digits:10',
//         'otp' => 'required|numeric|digits:4',
//     ]);

//     if ($validator->fails()) {
//         return response()->json($validator->errors(), 400);
//     }

//     // Prepare API URL and parameters
//     $mobile = $request->input('mobile');
//     $otp = $request->input('otp');
//     $authKey = env('MSG91_AUTH_KEY', 'default_auth_key');
//     $url = "https://api.msg91.com/api/v5/otp/verify?mobile=91{$mobile}&otp={$otp}&authkey={$authKey}";

//     // Initialize CURL session
//     $curl = curl_init($url);
//     curl_setopt_array($curl, [
//         CURLOPT_RETURNTRANSFER => true,
//         CURLOPT_ENCODING => "",
//         CURLOPT_MAXREDIRS => 10,
//         CURLOPT_TIMEOUT => 30,
//         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//         CURLOPT_CUSTOMREQUEST => "GET",
//         CURLOPT_HTTPHEADER => [
//             "Content-Type: application/json"
//         ],
//         CURLOPT_SSL_VERIFYPEER => false
//     ]);

//     // Execute CURL session
//     $response = curl_exec($curl);
//     $err = curl_error($curl);
//     curl_close($curl);

//     if ($err) {
//         return response()->json(['message' => "API Error: " . $err, 'success' => false], 500);
//     }

//     $responseData = json_decode($response, true);

//     // Check API response for OTP verification result
//     if (!$responseData || $responseData['type'] == 'error') {
//         return response()->json(['message' => "Invalid OTP", 'success' => false], 400);
//     }

//     // Check if the user is registered
//     $isRegistered = DB::table('user')
//         ->where('mobile', $mobile)
//         ->exists();

//     if (!$isRegistered) {
//         return response()->json(['message' => "Not registered", 'success' => false], 404);
//     }

//     // Generate and update the token
//     $token = $this->generateToken($mobile);

//     $update = DB::table('user')
//         ->where('mobile', $mobile)
//         ->update(['token' => $token]);

//     if (!$update) {
//         return response()->json(['message' => "Authentication failed", 'success' => false], 500);
//     }

//     return response()->json(['message' => "OTP verified successfully", 'token' => $token, 'success' => true], 200);
// }

// /**
//  * Generates a new token for the user.
//  *
//  * @param string $session_uid
//  * @return string
//  */
// private function generateToken($session_uid)
// {
//     // Ensure APP_KEY is defined
//     if (!defined('APP_KEY')) {
//         define('APP_KEY', 'TabSquare');
//     }

//     date_default_timezone_set('Asia/Calcutta');
//     $time = time(); // Get the current timestamp
//     $key = md5(APP_KEY . $session_uid . $time); // Include timestamp in the key generation
//     return hash('sha256', $key . $_SERVER['REMOTE_ADDR']);
// }





    public function resendOTP(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits:10', // Ensure mobile number is required and has exactly 10 digits
        ]);

        if ($validator->fails()) {
            // Return validation error messages if validation fails
            return response()->json($validator->errors(), 400); // Return a 400 Bad Request with the error details
        }

        // Prepare the data for the API call
        $mobile = $request->input('mobile');
       // $authKey = env('MSG91_AUTH_KEY'); // Use the auth key from .env file or use a default
        $url = "https://api.msg91.com/api/v5/otp";
        $postData = json_encode([
            "template_id" => "6663091cd6fc054ac938ae31",
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
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => $postData,
        ]);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        // Execute CURL session
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

      //  // Handle CURL error
    //     if ($err) {
    //         Log::error("cURL Error on OTP Resend: " . $err);
    //         return response()->json(['error' => 'cURL Error #: ' . $err], 500);
    //     }

    //     // Convert the response into an associative array and return it
    //     return response()->json(json_decode($response, true), 200);
    // }
        $success['message'] = "OTP resend successfully";
                $success['success'] = true;
                return response()->json($success);
            } catch (Exception $e) {
                // Return error response if email sending fails
                $error['message'] = "Error resending OTP via mobile";
                $error['success'] = false;
                return response()->json($error, 500);
            }
    }






// public function create_user(Request $request) --->last working code
// {
//     $validator = Validator::make($request->all(), [
//         'name' => 'required',
//         'email' => 'required|email|unique:user,email',
//         'mobile' => 'required|numeric|digits:10|unique:user,mobile',
//         'device_token'=> 'required',
//         'referral_code' => 'nullable|alpha_num',
//     ]);

//     if ($validator->fails()) {
//         $errors = $validator->errors();

//         if ($errors->has('email') || $errors->has('mobile')) {
//             return response()->json([
//                 'message' => 'Email or mobile number already exists.',
//                 'success' => false,
//                 'errors' => $errors->all(),
//             ], 422);
//         } else {
//             return response($errors, 200);
//         }
//     }

//     date_default_timezone_set('Asia/Calcutta');
//     $time = time();
//     $id = date("YmdHis", $time);
//     $user_id = 'BPM' . $id;
//     $current_date = date("YmdHis", $time);
//     $token = tokenKey($user_id);
//     $name = $request->input('name');
//     $email = $request->input('email');
//     $mobile = $request->input('mobile');
//     $user_image = $request->file('user_image');
//     $device_token = $request->input('device_token');
//     $referral_code = $this->generateReferralCode($name);

//     $referred_by = null;
//     if ($request->filled('referral_code')) {
//         $provided_referral_code = $request->input('referral_code');
//         $referring_user = \DB::table('user')->where('referral_code', $provided_referral_code)->first();

//         if ($referring_user) {
//             $referred_by = $referring_user->user_id;

//             // Increment wallet for referring user, excluding specific referral codes
//             if (!in_array($provided_referral_code, ['SIM96060', 'ABI95542'])) {
//                 \DB::table('user')->where('user_id', $referring_user->user_id)->increment('wallet', 25);
//             }
//         } else {
//             return response()->json([
//                 'message' => 'Invalid referral code.',
//                 'success' => false,
//             ], 422);
//         }
//     }

//     if (!empty($user_image)) {
//         $img_id = date("dmyHis", $time);
//         $path = 'USERIMG' . "$img_id" . ".png";
//         $directoryPath = public_path('$baseUrl.uploads/images/user_images/');

//         \Illuminate\Support\Facades\File::makeDirectory($directoryPath, 0777, true, true);
//         $user_image->move($directoryPath, $path);
//     } else {
//         $path = '';
//     }

//     $user = new user_model();
//     $user->user_id = $user_id;
//     $user->name = $name;
//     $user->email = $email;
//     $user->mobile = $mobile;
//     $user->user_image = $path;
//     $user->device_token = $device_token;
//     $user->referral_code = $referral_code;
//     $user->status = "active";
//     $user->token = $token;
//     $user->created_at = $current_date;
//     $user->save();

//     if ($referred_by) {
//         // Increment wallet for the referred user, excluding specific referral codes
//         if (!in_array($provided_referral_code, ['SIM96060', 'ABI95542'])) {
//             \DB::table('user')->where('user_id', $user_id)->increment('wallet', 25);
//         }

//         \DB::table('referrals')->insert([
//             'referring_user_id' => $referring_user->user_id,
//             'referring_user_name' => $referring_user->name,
//             'referral_code' => $provided_referral_code,
//             'referred_user_id' => $user_id,
//             'referred_user_name' => $name,
//             'status' => 'active',
//             'created_at' => $current_date,
//         ]);
//     }

//     if (!$user) {
//         return response()->json([
//             'message' => "User not added successfully",
//             'success' => false,
//         ]);
//     } else {
//         return response()->json([
//             'message' => "User added successfully",
//             'token' => $token,
//             'success' => true,
//         ]);
//     }
// }





public function create_user(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => [
            'required',
            Rule::unique('user', 'email')->where(function ($query) {
                $query->where('status', 'active');
            }),
        ],
        'mobile' => [
            'required',
            'numeric',
            'digits:10',
            Rule::unique('user', 'mobile')->where(function ($query) {
                $query->where('status', 'active');
            }),
        ],
        'device_token' => 'required',
        'referral_code' => 'nullable|alpha_num',
    ]);

    if ($validator->fails()) {
        $errors = $validator->errors();

        if ($errors->has('email') || $errors->has('mobile')) {
            return response()->json([
                'message' => 'Email or mobile number already exists.',
                'success' => false,
                'errors' => $errors->all(),
            ], 422);
        }elseif ($errors->has('name') ) {
            return response()->json([
                'message' => 'valid name.',
                'success' => false,
                'errors' => $errors->all(),
            ], 422);
        } else {
            return response($errors, 200);
        }
    }

    date_default_timezone_set('Asia/Calcutta');
    $time = time();
    $id = date("YmdHis", $time);
    $user_id = 'BPM' . $id;
    $current_date = date("YmdHis", $time);
    $token = tokenKey($user_id);
    $name = $request->input('name');
    $email = $request->input('email');
    $mobile = $request->input('mobile');
    $user_image = $request->file('user_image');
    $device_token = $request->input('device_token');
    $referral_code = $this->generateReferralCode($name);

    $referred_by = null;
    $is_existing_user = DB::table('user')->where('mobile', $mobile)->exists();

    if ($request->filled('referral_code') && !$is_existing_user) {
        $provided_referral_code = $request->input('referral_code');
        $referring_user = DB::table('user')->where('referral_code', $provided_referral_code)->first();

        if ($referring_user) {
            $referred_by = $referring_user->user_id;

            // Increment wallet for referring user
            DB::table('user')->where('user_id', $referring_user->user_id)->increment('wallet', 25);
        } else {
            return response()->json([
                'message' => 'Invalid referral code.',
                'success' => false,
            ], 422);
        }
    }

    if (!empty($user_image)) {
        $img_id = date("dmyHis", $time);
        $path = 'USERIMG' . "$img_id" . ".png";
        $directoryPath = public_path('$baseUrl.uploads/images/user_images/');

        \Illuminate\Support\Facades\File::makeDirectory($directoryPath, 0777, true, true);
        $user_image->move($directoryPath, $path);
    } else {
        $path = '';
    }

    $user = new user_model();
    $user->user_id = $user_id;
    $user->name = $name;
    $user->email = $email;
    $user->mobile = $mobile;
    $user->user_image = $path;
    $user->device_token = $device_token;
    $user->referral_code = $referral_code;
    $user->status = "active";
    $user->token = $token;
    $user->created_at = $current_date;
    $user->save();

    if ($referred_by && !$is_existing_user) {
        // Increment wallet for the referred user
        DB::table('user')->where('user_id', $user_id)->increment('wallet', 25);

        DB::table('referrals')->insert([
            'referring_user_id' => $referring_user->user_id,
            'referring_user_name' => $referring_user->name,
            'referral_code' => $provided_referral_code,
            'referred_user_id' => $user_id,
            'referred_user_name' => $name,
            'status' => 'active',
            'created_at' => $current_date,
        ]);
    }

    if (!$user) {
        return response()->json([
            'message' => "User not added successfully",
            'success' => false,
        ]);
    } else {
        return response()->json([
            'message' => "User added successfully",
            'token' => $token,
            'success' => true,
        ]);
    }
}







private function generateReferralCode($name)
{
    // Get the first 3 characters of the name
    $namePrefix = strtoupper(substr($name, 0, 3));
    $isUnique = false;
    $referralCode = '';

    while (!$isUnique) {
        // Generate a random 5-digit number
        $randomNumber = rand(10000, 99999);
        // Create the referral code
        $referralCode = $namePrefix . $randomNumber;
        // Check if the referral code already exists
        $existingUser = DB::table('user')->where('referral_code', $referralCode)->first();
        if (!$existingUser) {
            $isUnique = true;
        }
    }

    return $referralCode;
}









public function get_user_details(Request $request, $token)
{
    $user = DB::table('user')
        ->select('user_id', 'name', 'email', 'mobile','referral_code','wallet', 'user_image')
        ->where('token', '=', $token)
        ->where('status', '=', 'active')
        ->first();

    if (empty($user)) {
        $fail['message'] = "Authentication failed";
        $fail['success'] = false;
        return response()->json($fail);
    } else {


        $result = [
            "user_id" => $user->user_id,
            "name" => $user->name,
            "email" => $user->email,
            "mobile" => $user->mobile,
            "referral_code" =>$user->referral_code,
            "wallet" =>$user->wallet,
            "image" => $user->user_image, // Corrected to use $user->user_image
        ];

        if (empty($user->user_image)) { // Corrected variable name
            $img = '';
        } else {
            // Corrected image URL path
            $img = '$baseUrl.uploads/images/user_images/' . $user->user_image;
        }

        // Include the image URL in the response
        $result['image_url'] = $img;

        return response()->json(["result" => $result]);
    }
}






//   public function get_user_details(Request $request, $token)
// {
//     $user = DB::table('user')
//         ->select('user_id', 'name', 'email', 'mobile','referral_code','wallet', 'user_image')
//         ->where('token', '=', $token)
//         ->where('status', '=', 'active')
//         ->first();

//     if (empty($user)) {
//         $fail['message'] = "Authentication failed";
//         $fail['success'] = false;
//         return response()->json($fail);
//     } else {


//         $result = [
//             "user_id" => $user->user_id,
//             "name" => $user->name,
//             "email" => $user->email,
//             "mobile" => $user->mobile,
//             "referral_code" =>$user->referral_code,
//             "wallet" =>$user->wallet,
//             "user_image" => $user->user_image,
//         ];
//         if (empty($user_image)) {
//             $img = '';
//         } else {
//             $img = '$baseUrl.uploads/images/user_images/' . $user_image;
//         }
//         return response()->json(["result" => $result]);
//     }
// }




    public function get_user(Request $request)
{
    $users = DB::table('user')
        ->select('user_id', 'name', 'email', 'mobile', 'token', 'device_token','referral_code','wallet', 'user_image', 'created_at')
        ->where('status', '=', 'active')
        ->get();

    if ($users->isEmpty()) {
        $fail['message'] = "Authentication failed";
        $fail['success'] = false;
        return response()->json($fail);
    } else {
        $result = [];

        foreach ($users as $user) {

             $referralCount = DB::table("referrals")
            ->where('referring_user_id', $user->user_id)
             ->where('status', 'active')
             ->count();

            $user_id = $user->user_id;
            $name = $user->name;
            $email = $user->email;
            $mobile = $user->mobile;
            $referral_code = $user->referral_code;
            $wallet = $user->wallet;
            $token = $user->token;
            $device_token = $user->device_token;
            $user_image = $user->user_image;
            $created_at = $user->created_at;


            if (empty($user_image)) {
                $img = '';
            } else {
                $img = '$baseUrl.uploads/images/user_images/' . $user_image;
            }

            $result[] = [
                "user_id" => $user_id,
                "name" => $name,
                "email" => $email,
                "mobile" => $mobile,
                "token" => $token,
                "device_token" => $device_token,
                "referral_code" => $referral_code,
                "wallet" => $wallet,
                "referral_count" => $referralCount,
                "image" => $img,
                "created_at" => $created_at
            ];
        }

        return response()->json(["result" => $result], 200);
    }
}




    // public function user_profile_update(Request $request, $token)
    // {
    //     $user_id = DB::table('user')
    //         ->select('user_id')
    //         ->where('token', '=', $token)
    //         ->where('status', '=', 'active')
    //         ->value('user_id');

    //     if (empty($user_id)) {
    //         $fail['message'] = "Authentication failed";
    //         $fail['success'] = false;
    //         return response()->json($fail);
    //     } else {
    //         date_default_timezone_set('Asia/Calcutta');
    //         $time = time();

    //         $name = $request->input('name');
    //         $email = $request->input('email');
    //         $mobile = $request->input('mobile');
    //         $user_image = $request->input('image');

    //         if (!empty($user_image)) {
    //             $img_id = (date("dmyHis", $time));
    //             $path = 'BIRIYANI_PALAYAMUSERIMG' . "$img_id" . ".png";
    //             $directoryPath = "$baseUrl.uploads/images/user_images/";
    //             if (!file_exists($directoryPath)) {
    //                 $imgPath = $directoryPath;
    //                 mkdir($imgPath, 0777, true);
    //             }
    //             $binary = base64_decode($user_image);

    //             header("Content-Type: bitmap; charset=utf-8");

    //             $file = fopen($directoryPath . $path, "wb");
    //             $filepath = $path;
    //             fwrite($file, $binary);

    //             fclose($file);

    //             $update = DB::table('user')
    //                 ->where('user_id', '=', $user_id)
    //                 ->update(['name' => $name,
    //                     'email' => $email,
    //                     'mobile' => $mobile,
    //                     'image' => $path]);

    //             if (!$update) {
    //                 $success['message'] = "Profile update not successfully";
    //                 $success['success'] = false;
    //                 return response()->json($success);
    //             } else {
    //                 $success['message'] = "Profile update successfully";
    //                 $success['success'] = true;
    //                 return response()->json($success);
    //             }
    //         } else {
    //             $update = DB::table('user')
    //                 ->where('user_id', '=', $user_id)
    //                 ->update(['name' => $name,
    //                     'email' => $email,
    //                     'mobile' => $mobile]);

    //             if (!$update) {
    //                 $success['message'] = "Profile update not successfully";
    //                 $success['success'] = false;
    //                 return response()->json($success);
    //             } else {
    //                 $success['message'] = "Profile update successfully";
    //                 $success['success'] = true;
    //                 return response()->json($success);
    //             }
    //         }
    //     }

    // }


//       public function user_profile_update(Request $request, $token)
// {
//     $user_id = DB::table('user')
//         ->where('token', '=', $token)
//         ->where('status', '=', 'active')
//         ->value('user_id');

//     if (empty($user_id)) {
//         $fail['message'] = "Authentication failed";
//         $fail['success'] = false;
//         return response()->json($fail);
//     } else {
//         $name = $request->input('name');
//         $email = $request->input('email');
//         $mobile = $request->input('mobile');

//         // Update user details
//         $update = DB::table('user')
//             ->where('user_id', $user_id)
//             ->update([
//                 'name' => $name,
//                 'email' => $email,
//                 'mobile' => $mobile
//             ]);

//         if (!$update) {
//             $success['message'] = "Profile update not successful";
//             $success['success'] = false;
//             return response()->json($success);
//         } else {
//             $success['message'] = "Profile updated successfully";
//             $success['success'] = true;
//             return response()->json($success);
//         }
//     }
// }


public function user_profile_update(Request $request, $token)
{
    $user = DB::table('user')
        ->where('token', '=', $token)
        ->where('status', '=', 'active')
        ->first();

    if (empty($user)) {
        $fail['message'] = "Authentication failed";
        $fail['success'] = false;
        return response()->json($fail);
    } else {
        $user_id = $user->user_id;
        $name = $request->input('name');
        $email = $request->input('email');
        $mobile = $request->input('mobile');
        $user_image = $request->file('user_image'); // Corrected variable name and added missing single quote

        if (!empty($user_image)) {
            $time = time();
            $img_id = date("dmyHis", $time);
            $path = 'USERIMG' . $img_id . ".png";
            $directoryPath = public_path('$baseUrl.uploads/images/user_images/');

            // Ensure directory exists
            \Illuminate\Support\Facades\File::makeDirectory($directoryPath, $mode = 0777, true, true);

            // Move the uploaded image to the directory
            $user_image->move($directoryPath, $path);

            // Update the path in the $user object
            $user->user_image = $path;
        } else {
            $path = '';
        }

        // Update user details
        $update = DB::table('user')
            ->where('user_id',  '=', $user_id)
            ->where('token', '=', $token)
            ->update([
                'name' => $name,
                'email' => $email,
                'mobile' => $mobile,
                'user_image' => $user->user_image, // Updated user_image with $path
            ]);

        if (!$update) {
            $success['message'] = "Profile update not successful";
            $success['success'] = false;
            return response()->json($success);
        } else {
            $success['message'] = "Profile updated successfully";
            $success['success'] = true;
            return response()->json($success);
        }
    }
}


  public function user_wallet_update(Request $request, $token)
{
    $user_id = DB::table('user')
        ->where('token', '=', $token)
        ->where('status', '=', 'active')
        ->value('user_id');

    if (empty($user_id)) {
        $fail['message'] = "Authentication failed";
        $fail['success'] = false;
        return response()->json($fail);
    } else {
        // $name = $request->input('name');
        // $email = $request->input('email');
        // $mobile = $request->input('mobile');
        $wallet = $request->input('wallet');

        // Update user details
        $update = DB::table('user')
            ->where('user_id', $user_id)
            ->update([
                // 'name' => $name,
                // 'email' => $email,
                // 'mobile' => $mobile
                'wallet' => $wallet
            ]);

        if (!$update) {
            $success['message'] = "Wallet update not successful";
            $success['success'] = false;
            return response()->json($success);
        } else {
            $success['message'] = "Wallet updated successfully";
            $success['success'] = true;
            return response()->json($success);
        }
    }
}




public function logout(Request $request, $token)
{
    // Check if the user exists and is active
    $user = DB::table('user')
        ->where('token', '=', $token)
        ->where('status', '=', 'active')
        ->first();

    if (!$user) {
        $success['message'] = "invalid token";
        $success['success'] = false;
        return response()->json($success);
    }

    // Update the user's token to null or an appropriate value to indicate logout
    $logout = DB::table('user')
        ->where('token', '=', $token)
        ->where('status', '=', 'active')
        ->update(['token' => '']);

    if ($logout) {
        $success['message'] = "Logout successfully";
        $success['success'] = true;
        return response()->json($success);
    } else {
        $success['message'] = "Logout not successful";
        $success['success'] = false;
        return response()->json($success);
    }
}




public function Tsit_BPM_Delete_Account(Request $request, $token){
    $del_user = DB::table('user')
    ->where('token', $token)
    ->where('status','active')
    ->first();

    if (!$del_user) {
        $success['message'] = "User Not Found";
        $success['success'] = false;
        return response()->json($success);
    } else {
        $delete = DB::table('user')
        ->where('token', '=', $token)
        ->where('status', '=', 'active')
        ->update(['status' => 'inactive']);

        if ($delete) {
            $success['message'] = "Account Deleted successfully";
            $success['success'] = true;
            return response()->json($success);
        } else {
            $success['message'] = "Accoun Not Deleted";
            $success['success'] = false;
            return response()->json($success);
        }
    }
}





}
