<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\auth_controller;

use App\Http\Controllers\API\Auth\LogoutController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

define("onemincryptKey", "tabsquare2022");
define("oneminsecretKey", "tabsquareinfo2021");
function pass_encrypt($string)
{
  $output = false;
  $encrypt_method = "AES-256-CBC";
  $key = hash('sha256', oneminsecretKey); // hash
  $iv = substr(hash('sha256', onemincryptKey), 0, 16); // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
  $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
  $output = base64_encode($output);
  return $output;
}
function pass_decrypt($string)
{
  $output = false;
  $encrypt_method = "AES-256-CBC";
  $key = hash('sha256', oneminsecretKey); // hash
  $iv = substr(hash('sha256', onemincryptKey), 0, 16); // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
  $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
  return $output;
}




date_default_timezone_set('Asia/Calcutta');
$time = time();
$key = "TabSquare";
$id = (date("dmyHis", $time));
define("APP_KEY", $key);
function tokenKey($session_uid)
{
  $key = md5(APP_KEY . $session_uid);
  return hash('sha256', $key . $_SERVER['REMOTE_ADDR']);
}


//     date_default_timezone_set('Asia/Calcutta');

// function tokenKey($session_uid)
// {
//     $time = time(); // Get the current timestamp
//     $key = md5(APP_KEY . $session_uid . $time); // Include timestamp in the key generation
//     return hash('sha256', $key . $_SERVER['REMOTE_ADDR']);
// }




Route::middleware('auth:sanctum')->get('/admin', function (Request $request) {
  return $request->admin();
});


//Route::post('/sendOtp/{mobile}/{otp}', 'App\Services\Msg91Service@sendOtp');
// -- Biriyani_palayam User
Route::post('/sentOTP', 'App\Http\Controllers\user_controller@sentOTP');
Route::post('/verifyOTP', 'App\Http\Controllers\user_controller@verifyOTP');
Route::post('/resendOTP', 'App\Http\Controllers\user_controller@resendOTP');
Route::post('/get_user', 'App\Http\Controllers\user_controller@get_user');
Route::post('/create_user', 'App\Http\Controllers\user_controller@create_user');
Route::post('/get_user_details/{token}', 'App\Http\Controllers\user_controller@get_user_details');
Route::post('/user_profile_update/{token}', 'App\Http\Controllers\user_controller@user_profile_update');
Route::post('/user_wallet_update/{token}', 'App\Http\Controllers\user_controller@user_wallet_update');
Route::post('/logout/{token}', 'App\Http\Controllers\user_controller@logout');
Route::post('/Tsit_BPM_Delete_Account/{token}', 'App\Http\Controllers\user_controller@Tsit_BPM_Delete_Account');


// -- Biriyani_Palayam TimeSlot
Route::post('/create_time', 'App\Http\Controllers\time_controller@create_time');
Route::post('/get_time/{franchise_id}', 'App\Http\Controllers\time_controller@get_time');
Route::post('/timer_push_notification/{device_token}', 'App\Http\Controllers\time_controller@timer_push_notification');
Route::post('/update_timeslot/{timer_id}', 'App\Http\Controllers\time_controller@update_timeslot');
Route::post('/delete_timeslot/{timer_id}', 'App\Http\Controllers\time_controller@delete_timeslot');
Route::post('/get_pickup_time/{timer_id}', 'App\Http\Controllers\time_controller@get_pickup_time');




// -- Biriyani_Palayam Bidding
Route::post('/add_bidding/{token}', 'App\Http\Controllers\bidding_controller@add_bidding');
//Route::post('/update_bidding/{token}', 'App\Http\Controllers\menu_controller@update_bidding');
Route::post('/update_bidding/{menu_category_name}', 'App\Http\Controllers\bidding_controller@update_bidding');
//Route::post('/get_bidding/{token}', 'App\Http\Controllers\menu_controller@get_bidding');
Route::post('/get_bidding/{franchise_id}/{timer_id}', 'App\Http\Controllers\bidding_controller@get_bidding');
Route::post('/delete_bidding/{menu_category_name}', 'App\Http\Controllers\bidding_controller@delete_bidding');
Route::post('/winner/{timer_id}/{menu_category_id}', 'App\Http\Controllers\bidding_controller@winner');

//Route::post('/get_bidding/{menu_category_name}', 'App\Http\Controllers\bidding_controller@get_bidding')->middleware('auth:api');



// -- Biriyani_palayam franchise

Route::post('/create_franchise', 'App\Http\Controllers\franchise_controller@create_franchise');
Route::post('/get_franchise/{state_id}/{district_id}', 'App\Http\Controllers\franchise_controller@get_franchise');
Route::post('/get_franchise_owner/{franchise_id}', 'App\Http\Controllers\franchise_controller@get_franchise_owner');
Route::post('/update_franchise/{franchise_id}', 'App\Http\Controllers\franchise_controller@update_franchise');
Route::post('/delete_franchise/{franchise_id}', 'App\Http\Controllers\franchise_controller@delete_franchise');
Route::post('/toggle_franchise_status/{franchise_id}', 'App\Http\Controllers\franchise_controller@toggle_franchise_status');
Route::post('/get_hidden_franchise/{state_id}/{district_id}', 'App\Http\Controllers\franchise_controller@get_hidden_franchise');
Route::post('/Tsit_BPM_Check_Franchise/{state_id}/{district_id}', 'App\Http\Controllers\franchise_controller@Tsit_BPM_Check_Franchise');


// -- Biriyani_Palayam menu
Route::post('/get_menu/{franchise_id}', 'App\Http\Controllers\menu_controller@get_menu');
Route::post('/get_fixed_menu/{franchise_id}', 'App\Http\Controllers\menu_controller@get_fixed_menu');
Route::post('/create_menu', 'App\Http\Controllers\menu_controller@create_menu');
Route::post('/delete_menu/{menu_category_id}', 'App\Http\Controllers\menu_controller@delete_menu');
Route::post('/update_menu/{menu_category_id}/{franchise_id}', 'App\Http\Controllers\menu_controller@update_menu');
Route::post('/toggle_menu_status/{menu_category_id}/{franchise_id}', 'App\Http\Controllers\menu_controller@toggle_menu_status');
Route::post('/get_hidden_menu/{franchise_id}', 'App\Http\Controllers\menu_controller@get_hidden_menu');
Route::post('/update_menu_status/{menu_category_id}/{franchise_id}', 'App\Http\Controllers\menu_controller@update_menu_status');


// -- Biryani_palayam menu_type
Route::post('/add_menu_type', 'App\Http\Controllers\menu_type_controller@add_menu_type');
Route::post('/get_menu_type', 'App\Http\Controllers\menu_type_controller@get_menu_type');



// -- Biriyani_Palayam bidder

Route::post('/create_bidder', 'App\Http\Controllers\bidder_controller@create_bidder');
Route::post('/get_bidder_count/{timer_id}', 'App\Http\Controllers\bidder_controller@get_bidder_count');


// -- Biriyani_Palayam pickup_point
Route::post('/create_pickup_point', 'App\Http\Controllers\pickup_point_controller@create_pickup_point');
Route::post('/get_pickup_point/{franchise_id}', 'App\Http\Controllers\pickup_point_controller@get_pickup_point');
Route::post('/delete_pickup_point/{pickup_id}', 'App\Http\Controllers\pickup_point_controller@delete_pickup_point');
Route::post('/update_pickup_point/{pickup_id}', 'App\Http\Controllers\pickup_point_controller@update_pickup_point');


// -- Biriyani_Palayam pickup_Time
Route::post('/create_pickup_time', 'App\Http\Controllers\pickup_time_controller@create_pickup_time');
Route::post('/get_pickup_time/{franchise_id}/{date}', 'App\Http\Controllers\pickup_time_controller@get_pickup_time');
Route::post('/update_pickup_time/{pickup_time_id}', 'App\Http\Controllers\pickup_time_controller@update_pickup_time');
Route::post('/delete_pickup_time/{pickup_time_id}', 'App\Http\Controllers\pickup_time_controller@delete_pickup_time');
Route::post('/get_pickup_time_admin/{franchise_id}/{timer_id}', 'App\Http\Controllers\pickup_time_controller@get_pickup_time');



// -- Biriyani_Palayam Admin
Route::post('/TSIT_BPM_Create_Admin_Login', 'App\Http\Controllers\admin_controller@TSIT_BPM_Create_Admin_Login');
Route::post('/admin_login', 'App\Http\Controllers\admin_controller@admin_login');
Route::post('/Tsit_BPM_Admin_SentOTP', 'App\Http\Controllers\admin_controller@Tsit_BPM_Admin_SentOTP');
Route::post('/Tsit_BPM_Admin_VerifyOTP', 'App\Http\Controllers\admin_controller@Tsit_BPM_Admin_VerifyOTP');
Route::post('/TSIT_BPM_Get_All_Admin', 'App\Http\Controllers\admin_controller@TSIT_BPM_Get_All_Admin');
Route::post('/TSIT_BPM_Delete_Admin/{token}', 'App\Http\Controllers\admin_controller@TSIT_BPM_Delete_Admin');
Route::post('/TSIT_BPM_Edit_Admin/{token}', 'App\Http\Controllers\admin_controller@TSIT_BPM_Edit_Admin');
Route::post('/logout', 'App\Http\Controllers\admin_controller@logout');


// -- Biriyani_Palayam Banner
Route::post('/add_banner', 'App\Http\Controllers\banner_controller@add_banner');

// -- Biriyani_palayam Winner
Route::post('/add_winner', 'App\Http\Controllers\winner_controller@add_winner');
Route::post('/get_winner/{franchise_id}/{timer_id}', 'App\Http\Controllers\winner_controller@get_winner');
Route::post('/get_winner_franchise/{franchise_id}', 'App\Http\Controllers\winner_controller@get_winner_franchise');
Route::post('/get_total_winner', 'App\Http\Controllers\winner_controller@get_total_winner');
Route::post('/get_winner_count/{timer_id}/{franchise_id}', 'App\Http\Controllers\winner_controller@get_winner_count');




// -- BiriyaniPalayam PushNotification

Route::post('/sendPushNotification/{device_token}/{message}', 'App\Http\Controllers\bidding_controller@sendPushNotification');


// -- BiriyaniPalayam Phonepe
//Route::post('/phonepe', 'App\Http\Controllers\phonepe_controller@phonepe');
Route::post('/phonepe_response', 'App\Http\Controllers\phonepe_controller@phonepe_response');
Route::post('/get_phonepe_response/{merchant_transaction_id}', 'App\Http\Controllers\phonepe_controller@get_phonepe_response');

Route::post('/get_phonepe_response_admin', 'App\Http\Controllers\phonepe_controller@get_phonepe_response_admin');


// -- BiryaniPalayam order_details and payment_details
Route::post('/add_order_and_payment_details', 'App\Http\Controllers\payment_details_controller@add_order_and_payment_details');
Route::post('/add_fixed_order_details', 'App\Http\Controllers\payment_details_controller@add_fixed_order_details');
Route::post('/update_payment/{franchise_id}/{timer_id}/{user_id}', 'App\Http\Controllers\payment_details_controller@update_payment');
Route::post('/get_orderdetails/{user_id}', 'App\Http\Controllers\payment_details_controller@get_orderdetails');
Route::post('/update_order_status/{order_id}', 'App\Http\Controllers\payment_details_controller@update_order_status');
Route::post('/get_orders/{order_id}', 'App\Http\Controllers\payment_details_controller@get_orders');
Route::post('/get_franchise_turnover/{franchise_id}', 'App\Http\Controllers\payment_details_controller@get_franchise_turnover');
Route::post('/get_franchise_order/{franchise_id}', 'App\Http\Controllers\payment_details_controller@get_franchise_order');



// -- Biriyani_palayam referrals
Route::post('/get_referrals', 'App\Http\Controllers\referrals_controller@get_referrals');



// -- Biriyani_Palayam Version
Route::post('/add_version', 'App\Http\Controllers\version_controller@add_version');
Route::post('/check_version/{version_name}/{version_code}', 'App\Http\Controllers\version_controller@check_version');



// -- Biriyani_Palayam Location
Route::post('/Tsit_BPM_Get_State', 'App\Http\Controllers\location_controller@Tsit_BPM_Get_State');
Route::post('/Tsit_BPM_Get_District/{state_id}', 'App\Http\Controllers\location_controller@Tsit_BPM_Get_District');
