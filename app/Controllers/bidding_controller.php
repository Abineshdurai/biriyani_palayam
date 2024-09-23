<?php

namespace App\Http\Controllers;

use App\Models\bidding_model;
//use App\Models\bidding_model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

//use DB;
//use Validator;

class bidding_controller extends Controller
{



//   public function add_bidding(Request $request, $token)
//     {
//         $validator = Validator::make($request->all(), [
//             'timer_id' => 'required',
//             'franchise_id' => 'required',
//             'menu_category_id' => 'required',
//             'name' => 'required',
//             'menu_category_name' => 'required',
//             'description' => 'required',
//             'current_price' => 'required',
//             'user_id' => 'required',
//         ]);

//         if ($validator->fails()) {
//             return response()->json(['error' => $validator->errors()->first()], 400);
//         }

//         // Check if the user is active
//         $user = DB::table('user')
//             ->where('token', $token)
//             ->where('status', 'active')
//             ->first();

//         if (!$user) {
//             $fail['message'] = "Authentication failed or user is not active";
//             $fail['success'] = false;
//             return response()->json($fail, 401);
//         }

//         // Get current date
//         $current_date = now()->toDateString();




//         $existing_bidding = bidding_model::where('user_id', $request->input('user_id'))
//     ->where('menu_category_id', $request->input('menu_category_id'))
//     ->where('franchise_id', $request->input('franchise_id'))
//     ->where('timer_id', $request->input('timer_id'))
//     ->whereDate('date', $current_date)
//     ->first();

// if ($existing_bidding) {
//     // If a matching bidding already exists for the current date and other criteria, update the current_price
//     $existing_bidding->current_price = $request->input('current_price');
//     $existing_bidding->save();

//     $success['message'] = "Bidding updated successfully";
//     $success['success'] = true;
//     return response()->json($success);
// }


//         // Create new bidding
//         $bidding = new bidding_model();
//         $bidding->user_id = $user->user_id;
//         $bidding->timer_id = $request->input('timer_id');
//         $bidding->franchise_id = $request->input('franchise_id');
//         $bidding->name = $request->input('name');
//         $bidding->menu_category_id = $request->input('menu_category_id');
//         $bidding->menu_category_name = $request->input('menu_category_name');
//         $bidding->description = $request->input('description');
//         $bidding->current_price = $request->input('current_price');
//         // Add other fields if necessary

//         $path = 'BIRYANIEMPIMG' . time() . '.png';
//         $directoryPath = "$baseUrl.uploads/images/images_path/";
//         if (!file_exists($directoryPath)) {
//             mkdir($directoryPath, 0777, true);
//         }
//         $binary = base64_decode($request->input('image_path'));
//         file_put_contents($directoryPath . $path, $binary);
//         $bidding->image_path = $path;

//         $bidding->status = 'active'; // Assuming 'status' is a required field with a default value
//         $bidding->date = $current_date;
//         $bidding->created_at = now();
//         $bidding->save();

//         $success['message'] = "Bidding added successfully";
//         $success['success'] = true;
//         return response()->json($success);
//     }


            //<<<<<<<<<<<<<<<<<<<14.06.2024>>>>>>>>>>>>>>>>>>>>


//     public function add_bidding(Request $request, $token)
// {
//     $validator = Validator::make($request->all(), [
//         'timer_id' => 'required',
//         'franchise_id' => 'required',
//         'menu_category_id' => 'required',
//         'name' => 'required',
//         'menu_category_name' => 'required',
//         'description' => 'required',
//         'current_price' => 'required',
//         'user_id' => 'required',
//     ]);

//     if ($validator->fails()) {
//         return response()->json(['error' => $validator->errors()->first()], 400);
//     }

//     // Check if the user is active
//     $user = DB::table('user')
//         ->where('token', $token)
//         ->where('status', 'active')
//         ->first();

//     if (!$user) {
//         $fail['message'] = "Authentication failed or user is not active";
//         $fail['success'] = false;
//         return response()->json($fail, 401);
//     }

//     // Get current date
//     $current_date = now()->toDateString();

//     // Get menu image from the menu table
//     $menu_pic = DB::table('menu')
//         ->where('menu_category_id', $request->input('menu_category_id'))
//         ->first();

//     if (!$menu_pic) {
//         return response()->json(['error' => 'No matching menu item found for the provided category ID'], 404);
//     }

//     $existing_bidding = bidding_model::where('user_id', $request->input('user_id'))
//         ->where('menu_category_id', $request->input('menu_category_id'))
//         ->where('franchise_id', $request->input('franchise_id'))
//         ->where('timer_id', $request->input('timer_id'))
//         ->whereDate('date', $current_date)
//         ->first();

//     if ($existing_bidding) {
//         // If a matching bidding already exists for the current date and other criteria, update the current_price
//         $existing_bidding->current_price = $request->input('current_price');
//         $existing_bidding->menu_image = $menu_pic->menu_image; // Update image path
//         $existing_bidding->save();

//         $success['message'] = "Bidding updated successfully";
//         $success['success'] = true;
//         return response()->json($success);
//     }

//     // Create new bidding
//     $bidding = new bidding_model();
//     $bidding->user_id = $user->user_id;
//     $bidding->timer_id = $request->input('timer_id');
//     $bidding->franchise_id = $request->input('franchise_id');
//     $bidding->name = $request->input('name');
//     $bidding->menu_category_id = $request->input('menu_category_id');
//     $bidding->menu_category_name = $request->input('menu_category_name');
//     $bidding->description = $request->input('description');
//     $bidding->current_price = $request->input('current_price');
//     $bidding->menu_image = $menu_pic->menu_image; // Assign menu image path
//     $bidding->status = 'active'; // Assuming 'status' is a required field with a default value
//     $bidding->date = $current_date;
//     $bidding->created_at = now();
//     $bidding->save();

//     $success['message'] = "Bidding added successfully";
//     $success['success'] = true;
//     return response()->json($success);
// }

    //<<<<<<<<<<<<<<<<<<<14.06.2024>>>>>>>>>>>>>>>>>>>>


     public function add_bidding(Request $request, $token)
{
    $validator = Validator::make($request->all(), [
        'timer_id' => 'required',
        'franchise_id' => 'required',
        'menu_category_id' => 'required',
        'name' => 'required',
        'menu_category_name' => 'required',
        'description' => 'required',
        'current_price' => 'required',
        'user_id' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()->first()], 400);
    }

    // Check if the user is active
    $user = DB::table('user')
        ->where('token', $token)
        ->where('status', 'active')
        ->first();

    if (!$user) {
        $fail['message'] = "Authentication failed or user is not active";
        $fail['success'] = false;
        return response()->json($fail, 401);
    }

    // Get current date
    $current_date = now()->toDateString();

    // Get menu image and base_price from the menu table
    $menu = DB::table('menu')
        ->where('menu_category_id', $request->input('menu_category_id'))
        ->first();

    if (!$menu) {
        return response()->json(['error' => 'No matching menu item found for the provided category ID'], 404);
    }

    $base_price = $menu->base_price;

    $base_price98 = 0.98 * $base_price;

    // Check if an existing bidding already exists
    $existing_bidding = bidding_model::where('user_id', $request->input('user_id'))
        ->where('menu_category_id', $request->input('menu_category_id'))
        ->where('franchise_id', $request->input('franchise_id'))
        ->where('timer_id', $request->input('timer_id'))
        ->whereDate('date', $current_date)
        ->first();

    if ($existing_bidding) {
        // If existing bidding found, update current_price only if less than base_price
        if ($request->input('current_price') <= $base_price98) {
            $existing_bidding->current_price = $request->input('current_price');
            $existing_bidding->menu_image = $menu->menu_image; // Update image path
            $existing_bidding->updated_at = now();
            $existing_bidding->save();

            $success['message'] = "Bidding updated successfully";
            $success['success'] = true;
            return response()->json($success);
        } else {
            $fail['message'] = "Current price cannot be greater than or equal to base price";
            $fail['success'] = false;
            return response()->json($fail, 400);
        }
    }

    // If no existing bidding, create a new one
    $bidding = new bidding_model();
    $bidding->user_id = $user->user_id;
    $bidding->timer_id = $request->input('timer_id');
    $bidding->franchise_id = $request->input('franchise_id');
    $bidding->name = $request->input('name');
    $bidding->menu_category_id = $request->input('menu_category_id');
    $bidding->menu_category_name = $request->input('menu_category_name');
    $bidding->description = $request->input('description');
    $bidding->current_price = $request->input('current_price');
    $bidding->menu_image = $menu->menu_image; // Assign menu image path
    $bidding->status = 'active'; // Assuming 'status' is a required field with a default value
    $bidding->date = $current_date;
    $bidding->created_at = now();
    $bidding->save();

    $success['message'] = "Bidding added successfully";
    $success['success'] = true;
    return response()->json($success);
}





public function update_bidding(Request $request, $token)
{
    $validator = Validator::make($request->all(), [
        'timer_id' => 'required',
        'menu_category_id' => 'required',
        'franchise_id' => 'required',
      //  'name' => 'required',
        'current_price' => 'required',
        'user_id' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()->first()], 400);
    } else {
        // Retrieve the user_id based on the provided token
        $user = DB::table('user')
            ->where('token', '=', $token)
            ->where('status', '=', 'active')
            ->first();

        if (!$user) {
            $fail['message'] = "Authentication failed";
            $fail['success'] = false;
            return response()->json($fail, 401);
        } else {
            // Retrieve the bidding information
            $timer_id = $request->input('timer_id');
            $menu_category_id = $request->input('menu_category_id');
            $current_price = $request->input('current_price');
            $user_id = $user->user_id;
            $franchise_id = $request->input('franchise_id');

            // Get the current date
            $current_date = date("Y-m-d");

            // Check if the date is the current date before updating
            $existing_bidding = DB::table('bidding')
                ->where('timer_id', $timer_id)
                ->where('menu_category_id', $menu_category_id)
                ->where('user_id', $user_id)
                ->where('franchise_id', $franchise_id)
                ->whereDate('date', $current_date)
                ->exists();

            if (!$existing_bidding) {
                $fail['message'] = "No matching record found for the provided criteria or the record does not belong to the current date";
                $fail['success'] = false;
                return response()->json($fail, 400);
            }

            // Update the bidding information
            $update = DB::table('bidding')
                ->where('timer_id', $timer_id)
                ->where('menu_category_id', $menu_category_id)
                ->where('user_id', $user_id)
                ->where('franchise_id', $franchise_id)
                ->where('date', $current_date )
                ->update([
                 //   'name' => $request->input('name'),
                    'current_price' => $current_price,
                    'updated_at' => now(), // Assuming 'updated_at' column exists
                ]);

            if ($update) {
                $success['message'] = "Bidding updated successfully";
                $success['success'] = true;
            } else {
                $success['message'] = "No matching record found for the provided criteria";
                $success['success'] = false;
            }

            return response()->json($success);
        }
    }
}





public function get_bidding(Request $request, $franchise_id, $timer_id)
{
    // Get current date
    $current_date = now()->toDateString();

    // Check if bidding data exists for the provided franchise and timer
    $bidding_exists = DB::table('bidding')
        ->where('franchise_id', $franchise_id)
        ->where('timer_id', $timer_id)
        ->where('status', 'active')
        ->exists();

    if (!$bidding_exists) {
        $fail['message'] = "Bidding data not found for this franchise and timer";
        $fail['success'] = false;
        return response()->json($fail);
    }

    // Retrieve the highest price for each menu category for the specified franchise and timer
    $highest_prices = DB::table('bidding')
        ->select('menu_category_id', 'timer_id', DB::raw('MAX(current_price) as highest_price'))
        ->where('timer_id', $timer_id)
        ->where('franchise_id', $franchise_id)
        ->whereDate('created_at', $current_date)
        ->groupBy('menu_category_id', 'timer_id') // Group by menu_category_id
        ->get();

    // If no highest prices are found
    if ($highest_prices->isEmpty()) {
        $success['message'] = "No highest bidding prices found for this franchise, timer, and current date";
        $success['success'] = true; // It's not really an error if no data is found
    } else {
        // Fetch the name corresponding to each highest price
        foreach ($highest_prices as $highest_price) {
            $name = DB::table('bidding')
                ->where('menu_category_id', $highest_price->menu_category_id)
                ->where('current_price', $highest_price->highest_price)
                ->where('timer_id', $highest_price->timer_id)
                ->whereDate('date', $current_date)

                ->value('name');

            // Assign name to the highest_price object
            $highest_price->name = $name;
        }

        // Construct the success response
        $success['message'] = "Highest bidding prices retrieved successfully";
        $success['success'] = true;
        $success['highest_prices'] = $highest_prices; // Include highest_prices
    }

    return response()->json($success);
}





// public function winner(Request $request, $timer_id, $menu_category_id)
// {
//     // Get today's date
//     $current_date = now()->toDateString();

//     // Check if the provided timer_id and menu_category_id exist and are active
//     $timerAndCategoryExist = DB::table('bidding')
//         ->where('menu_category_id', $menu_category_id)
//         ->where('timer_id', $timer_id)
//         ->where('status', 'active')
//         ->exists();

//     if (!$timerAndCategoryExist) {
//         $fail['message'] = "Bidding data not found for this timer and menu category";
//         $fail['success'] = false;
//         return response()->json($fail);
//     }

//     // Retrieve the winner amount (5% below the highest current price)
//     $current_price = DB::table('bidding')
//         ->where('menu_category_id', $menu_category_id)
//         ->where('timer_id', $timer_id)
//         ->whereDate('created_at', $current_date)
//         ->max('current_price') * 0.95;

//     // Retrieve the current price from the menu table
//     $menuCurrentPrice = DB::table('menu')
//         ->where('menu_category_id', $menu_category_id)
//         ->value('current_price');

//     // Retrieve the base price from the menu table
//     $menuBasePrice = DB::table('menu')
//         ->where('menu_category_id', $menu_category_id)
//         ->value('base_price');

//     // Check if the winner amount is lower than the current price in the menu table
//     if ($current_price <= $menuCurrentPrice) {
//         // Set the winner amount to be the current price
//         $current_price = $menuCurrentPrice;
//     }

//     // Check if the winner amount exceeds the base price in the menu table
//     if ($current_price >= $menuBasePrice) {
//         // Set the winner amount to be the base price
//         $current_price = $menuBasePrice;
//     }

//     $current_price = ceil($current_price);


//     // Retrieve the winners whose current price is greater than or equal to the winner amount
//     $winners = DB::table('bidding')
//         ->where('menu_category_id', $menu_category_id)
//         ->where('timer_id', $timer_id)
//         ->whereDate('created_at', $current_date)
//         ->where('current_price', '>=', $current_price)
//         ->get();

//      //   $redirect_url = '/payment-page?franchise_id=' . $winners->franchise_id;
//   //   $redirect_url = '/payment-page?franchise_id=' . $winners->first()->franchise_id;



//     // Retrieve the losers whose current price is less than the winner amount
//     $losers = DB::table('bidding')
//         ->where('menu_category_id', $menu_category_id)
//         ->where('timer_id', $timer_id)
//         ->whereDate('created_at', $current_date)
//         ->where('current_price', '<', $current_price)
//         ->get();


//     if ($winners->isNotEmpty()) {
//           $redirect_url = '/payment-page?franchise_id=' . $winners->first()->franchise_id;
//         $winners = $winners->map(function ($bid) use ($current_price, $current_date) {

//               $image_url = '';
//             if (!empty($bid->menu_image)) {
//                 $image_url = '$baseUrl.uploads/images/menu_images/' . $bid->menu_image;
//             }

//             return [
//                 'status' => $bid->status,
//                 'date' => $current_date,
//                 'description' => $bid->description,
//                 'timer_id' => $bid->timer_id,
//                 'franchise_id' => $bid->franchise_id,
//                 'menu_category_name' => $bid->menu_category_name,
//                 'final_price' => $current_price,
//                 'name' => $bid->name,
//                 'menu_category_id' => $bid->menu_category_id,
//                 'user_id' => $bid->user_id,
//                 'created_at' => $current_date,
//                 'menu_image' => $image_url
//             ];
//         });
//         DB::table('winner')->insert($winners->toArray());

//         // Send notifications to winners
//         foreach ($winners as $winner) {
//             $userToken = DB::table('user')->where('user_id', $winner['user_id'])->value('device_token');
//             $message = 'Congratulations! You are the winner!';

//             $this->sendPushNotification($userToken, $message, $redirect_url);
//         }
//     }

//     // Process losers
//     if ($losers->isNotEmpty()) {
//         $losers = $losers->map(function ($bid) use ($current_date) {
//             return [
//                 'status' => $bid->status,
//                 'date' => $current_date,
//                 'description' => $bid->description,
//                 'timer_id' => $bid->timer_id,
//                 'franchise_id' => $bid->franchise_id,
//                 'menu_category_name' => $bid->menu_category_name,
//                 'final_price' => $bid->current_price,
//                 'name' => $bid->name,
//                 'menu_category_id' => $bid->menu_category_id,
//                 'user_id' => $bid->user_id,
//                 'created_at' => $current_date,
//             ];
//         });

//         // Send notifications to losers
//         foreach ($losers as $loser) {
//             $userToken = DB::table('user')->where('user_id', $loser['user_id'])->value('device_token');
//             $message = 'Better luck next time!';
//             $this->sendPushNotification($userToken, $message, $redirect_url);
//         }
//     }

//     return response()->json([
//         'winners' => $winners,
//         'losers' => $losers,
//         'message' => 'Winners and losers processed successfully'
//     ], 200);
// }







public function winner(Request $request, $timer_id, $menu_category_id)
{
    // Get today's date
    $current_date = now()->toDateString();

    // Check if the provided timer_id and menu_category_id exist and are active
    $timerAndCategoryExist = DB::table('bidding')
        ->where('menu_category_id', $menu_category_id)
        ->where('timer_id', $timer_id)
        ->where('status', 'active')
        ->exists();

    if (!$timerAndCategoryExist) {
        $fail['message'] = "Bidding data not found for this timer and menu category";
        $fail['success'] = false;
        return response()->json($fail);
    }

    // Retrieve the highest current price
    $highest_current_price = DB::table('bidding')
        ->where('menu_category_id', $menu_category_id)
        ->where('timer_id', $timer_id)
        ->whereDate('created_at', $current_date)
        ->max('current_price');

    // Calculate the winner amount (5% below the highest current price)
    $current_price = $highest_current_price * 0.95;

    // Retrieve the current price from the menu table
    $menuCurrentPrice = DB::table('menu')
        ->where('menu_category_id', $menu_category_id)
        ->value('current_price');

    // Retrieve the base price from the menu table
    $menuBasePrice = DB::table('menu')
        ->where('menu_category_id', $menu_category_id)
        ->value('base_price');

    // Check if the winner amount is lower than the current price in the menu table
    if ($current_price <= $menuCurrentPrice) {
        // Set the winner amount to be the current price
        $current_price = $menuCurrentPrice;
    }

    // Check if the winner amount exceeds the base price in the menu table
    if ($current_price >= $menuBasePrice) {
        // Set the winner amount to be the base price
        $current_price = $menuBasePrice;
    }

    $current_price = ceil($current_price);

    // Retrieve the winners whose current price is greater than or equal to the winner amount
    $winners = DB::table('bidding')
        ->where('menu_category_id', $menu_category_id)
        ->where('timer_id', $timer_id)
        ->whereDate('created_at', $current_date)
        ->where('current_price', '>=', $current_price)
        ->get();

    // Retrieve the losers whose current price is less than the winner amount
    $losers = DB::table('bidding')
        ->where('menu_category_id', $menu_category_id)
        ->where('timer_id', $timer_id)
        ->whereDate('created_at', $current_date)
        ->where('current_price', '<', $current_price)
        ->get();

    if ($winners->isNotEmpty()) {
        $redirect_url = '/payment-page?franchise_id=' . $winners->first()->franchise_id;

        // Process winners
        $processedWinners = $winners->map(function ($bid) use ($current_price, $current_date) {
            $time = DB::table('time_slots')
                    ->where('timer_id', $bid->timer_id)
                    ->where('status', 'active')
                    ->first();
            $image_url = '';
            if (!empty($bid->menu_image)) {
                $image_url = '$baseUrl.uploads/images/menu_images/' . $bid->menu_image;
            }

            return [
                'status' => $bid->status,
                'date' => $current_date,
                'description' => $bid->description,
                'timer_id' => $bid->timer_id,
                'time_slot' => $time->starting_time . '-' . $time->end_time,
                'franchise_id' => $bid->franchise_id,
                'menu_category_name' => $bid->menu_category_name,
                'final_price' => $current_price,
                'final_price' => $bid->current_price, // Store the original price
                'name' => $bid->name,
                'menu_category_id' => $bid->menu_category_id,
                'user_id' => $bid->user_id,
                'created_at' => $current_date,
                'menu_image' => $image_url
            ];
        });

        DB::table('winner')->insert($processedWinners->toArray());

        // Send notifications to winners
        foreach ($processedWinners as $winner) {
            $userToken = DB::table('user')->where('user_id', $winner['user_id'])->value('device_token');
            $message = 'Congratulations! You are the winner!';
            $this->sendPushNotification($userToken, $message, $redirect_url);
        }
    }

    // Process losers
    if ($losers->isNotEmpty()) {
        $processedLosers = $losers->map(function ($bid) use ($current_date) {
            return [
                'status' => $bid->status,
                'date' => $current_date,
                'description' => $bid->description,
                'timer_id' => $bid->timer_id,
                'franchise_id' => $bid->franchise_id,
                'menu_category_name' => $bid->menu_category_name,
                'final_price' => $bid->current_price,
                'name' => $bid->name,
                'menu_category_id' => $bid->menu_category_id,
                'user_id' => $bid->user_id,
                'created_at' => $current_date,
            ];
        });

        // Send notifications to losers
        foreach ($processedLosers as $loser) {
            $userToken = DB::table('user')->where('user_id', $loser['user_id'])->value('device_token');
            $message = 'Better luck next time!';
            $this->sendPushNotification($userToken, $message);//, $redirect_url);
        }
    }

    return response()->json([
        'winners' => $processedWinners ?? [],
        'losers' => $processedLosers ?? [],
        'message' => 'Winners and losers processed successfully'
    ], 200);
}




// public function winner(Request $request, $timer_id, $menu_category_id)
// {
//     // Get today's date
//     $current_date = now()->toDateString();

//     // Check if the provided timer_id and menu_category_id exist and are active
//     $timerAndCategoryExist = DB::table('bidding')
//         ->where('menu_category_id', $menu_category_id)
//         ->where('timer_id', $timer_id)
//         ->where('status', 'active')
//         ->exists();

//     if (!$timerAndCategoryExist) {
//         $fail['message'] = "Bidding data not found for this timer and menu category";
//         $fail['success'] = false;
//         return response()->json($fail);
//     }

//     // Retrieve the highest current price
//     $highest_current_price = DB::table('bidding')
//         ->where('menu_category_id', $menu_category_id)
//         ->where('timer_id', $timer_id)
//         ->whereDate('created_at', $current_date)
//         ->max('current_price');

//     // Calculate the winner amount (5% below the highest current price)
//     $current_price = $highest_current_price * 0.95;

//     // Retrieve the current price from the menu table
//     $menuCurrentPrice = DB::table('menu')
//         ->where('menu_category_id', $menu_category_id)
//         ->value('current_price');

//     // Retrieve the base price from the menu table
//     $menuBasePrice = DB::table('menu')
//         ->where('menu_category_id', $menu_category_id)
//         ->value('base_price');

//     // Check if the winner amount is lower than the current price in the menu table
//     if ($current_price <= $menuCurrentPrice) {
//         // Set the winner amount to be the current price
//         $current_price = $menuCurrentPrice;
//     }

//     // Check if the winner amount exceeds the base price in the menu table
//     if ($current_price >= $menuBasePrice) {
//         // Set the winner amount to be the base price
//         $current_price = $menuBasePrice;
//     }

//     $current_price = ceil($current_price);

//     // Retrieve the winners whose current price is greater than or equal to the winner amount
//     $winners = DB::table('bidding')
//         ->where('menu_category_id', $menu_category_id)
//         ->where('timer_id', $timer_id)
//         ->whereDate('created_at', $current_date)
//         ->where('current_price', '>=', $current_price)
//         ->get();

//     // Retrieve the losers whose current price is less than the winner amount
//     $losers = DB::table('bidding')
//         ->where('menu_category_id', $menu_category_id)
//         ->where('timer_id', $timer_id)
//         ->whereDate('created_at', $current_date)
//         ->where('current_price', '<', $current_price)
//         ->get();

//     if ($winners->isNotEmpty()) {
//         $redirect_url = '/payment-page?franchise_id=' . $winners->first()->franchise_id;

//         // Process winners
//         $processedWinners = $winners->map(function ($bid) use ($current_price, $current_date) {
//             $image_url = '';
//             if (!empty($bid->menu_image)) {
//                 $image_url = '$baseUrl.uploads/images/menu_images/' . $bid->menu_image;
//             }

//             return [
//                 'status' => $bid->status,
//                 'date' => $current_date,
//                 'description' => $bid->description,
//                 'timer_id' => $bid->timer_id,
//                 'franchise_id' => $bid->franchise_id,
//                 'menu_category_name' => $bid->menu_category_name,
//                 'final_price' => $current_price,
//                 'final_price' => $bid->current_price, // Store the original price
//                 'name' => $bid->name,
//                 'menu_category_id' => $bid->menu_category_id,
//                 'user_id' => $bid->user_id,
//                 'created_at' => $current_date,
//                 'menu_image' => $image_url
//             ];
//         });

//         DB::table('winner')->insert($processedWinners->toArray());

//         // Send notifications to winners
//         foreach ($processedWinners as $winner) {
//             $userToken = DB::table('user')->where('user_id', $winner['user_id'])->value('device_token');
//             $message = 'Congratulations! You are the winner!';
//             $this->sendPushNotification($userToken, $message, $redirect_url);
//         }
//     }

//     // Process losers
//     if ($losers->isNotEmpty()) {
//         $processedLosers = $losers->map(function ($bid) use ($current_date) {
//             return [
//                 'status' => $bid->status,
//                 'date' => $current_date,
//                 'description' => $bid->description,
//                 'timer_id' => $bid->timer_id,
//                 'franchise_id' => $bid->franchise_id,
//                 'menu_category_name' => $bid->menu_category_name,
//                 'final_price' => $bid->current_price,
//                 'name' => $bid->name,
//                 'menu_category_id' => $bid->menu_category_id,
//                 'user_id' => $bid->user_id,
//                 'created_at' => $current_date,
//             ];
//         });

//         // Send notifications to losers
//         foreach ($processedLosers as $loser) {
//             $userToken = DB::table('user')->where('user_id', $loser['user_id'])->value('device_token');
//             $message = 'Better luck next time!';
//             $this->sendPushNotification($userToken, $message, $redirect_url);
//         }
//     }

//     return response()->json([
//         'winners' => $processedWinners ?? [],
//         'losers' => $processedLosers ?? [],
//         'message' => 'Winners and losers processed successfully'
//     ], 200);
// }






    public function sendPushNotification($device_token, $message)// $redirect_url)
    {
        // Your FCM server key
        $fcmServerKey = env('FCM_SERVER_KEY');

        // Construct the data payload for the push notification
        $data = [
            'to' => $device_token,
            'notification' => [
                'title' => 'Congratulations!',
                'body' => $message,
            ],
            'data' => [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK', // Required for handling click action in Flutter
               // '/payment-page?franchise_id=' . $winners->franchise_id =>  $redirect_url // Include the redirect URL in the data payload
               //  $redirect_url
            ],

           // 'time_to_live' => 5, // Set the time to live to 5 seconds
        ];

        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: key=' . $fcmServerKey,
                'Content-Type: application/json',
            ],
            CURLOPT_SSL_VERIFYPEER => false, // Disable SSL certificate verification (optional)
        ]);

        // Execute cURL request
        $response = curl_exec($ch);

        // Check for errors
        if ($response === false) {
            // Handle cURL error
            $error = curl_error($ch);
            curl_close($ch);
            return 'cURL error: ' . $error;
        }

        // Close cURL session
        curl_close($ch);

        // Decode the response
        $responseData = json_decode($response, true);

        // Check for FCM response status
        if (isset($responseData['error'])) {
            // Handle FCM response error
            return 'FCM error: ' . $responseData['error'];
        }

        // Notification sent successfully
        return 'Notification sent successfully';
    }




public function delete_bidding(Request $request, $menu_category_name)
{
    // Check if the provided menu category name matches an active category
    $existing_category = DB::table('bidding')
        ->where('menu_category_name', '=', $menu_category_name)
        ->where('status', '=' , 'active')
        ->exists();

    if (!$existing_category) {
        $fail['message'] = "Menu category not found or not active";
        $fail['success'] = false;
        return response()->json($fail);
    }

    $name = $request->input('name');

    // Perform the deletion
    $delete = DB::table('bidding')
        ->where('menu_category_name', $menu_category_name)
      //  ->where('name', $name)
        ->delete();

    if (!$delete) {
        $success['message'] = "Bidding delete not successful";
        $success['success'] = false;
    } else {
        $success['message'] = "Bidding deleted successfully";
        $success['success'] = true;
    }

    return response()->json($success);
}


}
