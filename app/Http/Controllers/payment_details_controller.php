<?php

namespace App\Http\Controllers;

use App\Models\state_model;
use App\Models\district_model;
use App\Models\franchise_model;
use App\Models\winner_model;
use App\Models\time_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;


use App\Models\payment_details_model;
use App\Models\fixed_order_details_model;
use App\Models\order_details_model;


class payment_details_controller extends Controller
{

    // public function add_order_and_payment_details(Request $request)
    // {
    //     // Validation for both order and payment details
    //     $validator = Validator::make($request->all(), [
    //         'franchise_id' => 'required',
    //         'user_id' => 'required',
    //         'timer_id' => 'required',
    //         'menu_category_ids' => 'required|array',
    //         'menu_category_ids.*' => 'required',
    //         'menu_category_names' => 'required|array',
    //         'menu_category_names.*' => 'required',
    //         'menu_quantities' => 'required|array',
    //         'menu_quantities.*' => 'required',
    //         'total_menu_prices' => 'required|array',
    //         'total_menu_prices.*' => 'required',
    //         'gst' => 'required',
    //         'name' => 'required',
    //         'mobile' => 'required',
    //         'pickup_date' => 'required',
    //         'pickup_time' => 'required',
    //         'pickup_point' => 'required',
    //         'merchant_transaction_id' => 'required',
    //         'transaction_amount' => 'required',
    //     ]);
    
    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 400);
    //     }
    
    //     $current_time = now();
    //     $current_date = $current_time->toDateString();
    //     $order_id = 'OD' . $current_time->format("YmdHis");
    
    //     // Check for existing order
    //     $existing_orders = order_details_model::where('franchise_id', $request->input('franchise_id'))
    //         ->where('timer_id', $request->input('timer_id'))
    //         ->where('user_id', $request->input('user_id'))
    //         ->whereNull('transaction_id')
    //         ->whereDate('date', $current_date)
    //         ->get();
    
    //     if ($existing_orders->isNotEmpty()) {
    //         // Get the list of existing menu_category_ids
    //         $existing_menu_category_ids = $existing_orders->pluck('menu_category_id')->toArray();
    
    //         // Identify and delete rows where menu_category_id is not in the incoming request
    //         $ids_to_delete = array_diff($existing_menu_category_ids, $request->menu_category_ids);
    //         if (!empty($ids_to_delete)) {
    //             order_details_model::where('franchise_id', $request->input('franchise_id'))
    //                 ->where('timer_id', $request->input('timer_id'))
    //                 ->where('user_id', $request->input('user_id'))
    //                 ->whereIn('menu_category_id', $ids_to_delete)
    //                 ->whereDate('date', $current_date)
    //                 ->delete();
    //         }
    
    //         // Update existing order
    //         foreach ($existing_orders as $existing_order) {
    //             $index = array_search($existing_order->menu_category_id, $request->menu_category_ids);
    //             if ($index !== false) {
    //                 $existing_order->menu_quantity = $request->menu_quantities[$index];
    //                 $existing_order->total_menu_price = $request->total_menu_prices[$index];
    //                 $existing_order->wallet = $request->wallet;
    //                 $existing_order->gst = $request->gst;
    //                 $existing_order->name = $request->name;
    //                 $existing_order->mobile = $request->mobile;
    //                 $existing_order->pickup_point = $request->pickup_point;
    //                 $existing_order->pickup_time = $request->pickup_time;
    //                 $existing_order->merchant_transaction_id = $request->merchant_transaction_id;
    //                 $existing_order->transaction_amount = $request->transaction_amount;
    //                 $existing_order->updated_at = $current_time;
    //                 $existing_order->save();
    //             }
    //         }
    //     } else {
    //         // Fetch the active franchise details
    //         $franchise = franchise_model::where('franchise_id', $request->franchise_id)
    //             ->where('status', 'active')
    //             ->first();
    
    //         if (!$franchise) {
    //             return response()->json(['error' => 'Franchise not found or is not active'], 404);
    //         }
    //         $time_slot = time_model::where('timer_id', $request->timer_id)
    //             ->where('status', '=', 'active')
    //             ->first();
    //         if (!$time_slot) {
    //             return response()->json(['error' => 'Time not found or not active']);
    //         }
    
    //         // Assuming the menu details are sent as arrays of equal length
    //         for ($i = 0; $i < count($request->menu_category_ids); $i++) {
    //             $order = new order_details_model([
    //                 'order_id' => $order_id,
    //                 'franchise_id' => $request->franchise_id,
    //                 'timer_id' => $request->timer_id,
    //                 'user_id' => $request->user_id,
    //                 'menu_category_id' => $request->menu_category_ids[$i],
    //                 'menu_category_name' => $request->menu_category_names[$i],
    //                 'menu_quantity' => $request->menu_quantities[$i],
    //                 'total_menu_price' => $request->total_menu_prices[$i],
    //                 'wallet' => $request->wallet,
    //                 'gst' => $request->gst,
    //                 'time_slot' => $time_slot->starting_time. '-' .$time_slot->end_time,
    //                 'franchise' => $franchise->franchise, // Storing the franchise name from the queried result
    //                 'status' => 'active',
    //                 'name' => $request->name,
    //                 'mobile' => $request->mobile,
    //                 'date' => $request->date,
    //                 'pickup_point' => $request->pickup_point,
    //                 'pickup_date' => $request->pickup_date,
    //                 'pickup_time' => $request->pickup_time,
    //                 'merchant_transaction_id' => $request->merchant_transaction_id,
    //                 'transaction_amount' => $request->transaction_amount,
    //                 'date' => $current_date,
    //                 'time' => $current_time,
    //                 'created_at' => $current_time,
    //             ]);
    
    //             $order->save();
    //         }
    //     }
    
    //     // Check for existing payment
    //     $existing_payment = payment_details_model::where('franchise_id', $request->input('franchise_id'))
    //         ->where('timer_id', $request->input('timer_id'))
    //         ->where('user_id', $request->input('user_id'))
    //         ->whereNull('transaction_id')
    //         ->whereDate('date', $current_date)
    //         ->first();
    //     $time_slot = time_model::where('timer_id', $request->timer_id)
    //         ->where('status', '=', 'active')
    //         ->first();
    //     if (!$time_slot) {
    //         return response()->json(['error' => 'Time not found or not active']);
    //     }
    
    //     if ($existing_payment) {
    //         // Update existing payment
    //         $existing_payment->order_id = $order_id;
    //         $existing_payment->name = $request->name;
    //         $existing_payment->mobile = $request->mobile;
    //         $existing_payment->pickup_point = $request->pickup_point;
    //         $existing_payment->pickup_time = $request->pickup_time;
    //         $existing_payment->merchant_transaction_id = $request->merchant_transaction_id;
    //         $existing_payment->transaction_amount = $request->transaction_amount;
    //         $existing_payment->updated_at = $current_time;
    //         $existing_payment->save();
    //     } else {
    //         // Saving payment details using the same order_id
    //         $payment_id = 'PAYMENT' . $current_time->format("YmdHis");
    //         $payment = new payment_details_model([
    //             'payment_id' => $payment_id,
    //             'franchise_id' => $request->franchise_id,
    //             'timer_id' => $request->timer_id,
    //             'order_id' => $order_id,
    //             'user_id' => $request->user_id,
    //             'name' => $request->name,
    //             'time_slot' => $time_slot->starting_time. '-' .$time_slot->end_time,
    //             'mobile' => $request->mobile,
    //             'pickup_point' => $request->pickup_point,
    //             'pickup_time' => $request->pickup_time,
    //             'merchant_transaction_id' => $request->merchant_transaction_id,
    //             'transaction_amount' => $request->transaction_amount,
    //             'status' => 'active',
    //             'date' => $current_date,
    //             'created_at' => $current_time,
    //         ]);
    
    //         $payment->save();
    //     }
    
    //     // Return success response
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Order and Payment Details added/updated successfully',
    //         'order_id' => $order_id,
    //     ]);
    // }
    


    public function add_order_and_payment_details(Request $request)  //----->dev
{
    // Validation for both order and payment details
    $validator = Validator::make($request->all(), [
        'franchise_id' => 'required',
        'user_id' => 'required',
        'timer_id' => 'required',
        'menu_category_ids' => 'required|array',
        'menu_category_ids.*' => 'required',
        'menu_category_names' => 'required|array',
        'menu_category_names.*' => 'required',
        'menu_quantities' => 'required|array',
        'menu_quantities.*' => 'required',
        'total_menu_prices' => 'required|array',
        'total_menu_prices.*' => 'required',
        'gst' => 'required',
        'name' => 'required',
        'mobile' => 'required',
        'pickup_date' => 'required',
        'pickup_time' => 'required',
        'pickup_point' => 'required',
        'merchant_transaction_id' => 'required',
        'transaction_amount' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    $current_time = now();
    $current_date = $current_time->toDateString();
    $order_id = 'OD' . $current_time->format("YmdHis");

    // Check for existing order
    $existing_orders = order_details_model::where('franchise_id', $request->input('franchise_id'))
        ->where('timer_id', $request->input('timer_id'))
        ->where('user_id', $request->input('user_id'))
        ->whereDate('date', $current_date)
        ->whereNull('transaction_id')
        ->get();

        if ($existing_orders->isNotEmpty()) {
            // Update existing order
            foreach ($existing_orders as $order) {
                $matchFound = false;
                
                // Find the matching menu_category_id from the request
                foreach ($request->menu_category_ids as $index => $menu_category_id) {
                    if ($order->menu_category_id == $menu_category_id) {
                        // Update the quantity and price for the matching menu_category_id
                        $order->menu_quantity = $request->menu_quantities[$index];
                        $order->total_menu_price = $request->total_menu_prices[$index];
                        $matchFound = true;
                        break; // Exit the loop once a match is found
                    }
                }
        
                // If no match is found, set menu_quantity and total_menu_price to 0
                if (!$matchFound) {
                    $order->menu_quantity = 0;
                    $order->total_menu_price = 0;
                }
        
                // Update the wallet, gst, and other details
                $order->wallet = $request->wallet;
                $order->gst = $request->gst;
                $order->name = $request->name;
                $order->mobile = $request->mobile;
                $order->pickup_point = $request->pickup_point;
                $order->pickup_date = $request->pickup_date;
                $order->pickup_time = $request->pickup_time;
                $order->merchant_transaction_id = $request->merchant_transaction_id;
                $order->transaction_amount = $request->transaction_amount;
                $order->updated_at = $current_time;
                
                // Save the updated order
                $order->save();
            }       
        
    } else {
        // Fetch the active franchise details
        $franchise = franchise_model::where('franchise_id', $request->franchise_id)
            ->where('status', 'active')
            ->first();

        if (!$franchise) {
            return response()->json(['error' => 'Franchise not found or is not active'], 404);
        }

        $time_slot = time_model::where('timer_id', $request->timer_id)
            ->where('status', '=', 'active')
            ->first();

        if (!$time_slot) {
            return response()->json(['error' => 'Time not found or not active']);
        }

        // Assuming the menu details are sent as arrays of equal length
        for ($i = 0; $i < count($request->menu_category_ids); $i++) {
            $order = new order_details_model([
                'order_id' => $order_id,
                'franchise_id' => $request->franchise_id,
                'timer_id' => $request->timer_id,
                'user_id' => $request->user_id,
                'menu_category_id' => $request->menu_category_ids[$i],
                'menu_category_name' => $request->menu_category_names[$i],
                'menu_quantity' => $request->menu_quantities[$i],
                'total_menu_price' => $request->total_menu_prices[$i],
                'wallet' => $request->wallet,
                'gst' => $request->gst,
                'time_slot' => $time_slot->starting_time. '-' .$time_slot->end_time,
                'franchise' => $franchise->franchise, // Storing the franchise name from the queried result
                'status' => 'active',
                'name' => $request->name,
                'mobile' => $request->mobile,
                'pickup_point' => $request->pickup_point,
                'pickup_date' => $request->pickup_date,
                'pickup_time' => $request->pickup_time,
                'merchant_transaction_id' => $request->merchant_transaction_id,
                'transaction_amount' => $request->transaction_amount,
                'date' => $current_date,
                'time' => $current_time,
                'created_at' => $current_time,
            ]);

            $order->save();
        }
    }

    // Check for existing payment
    $existing_payment = payment_details_model::where('franchise_id', $request->input('franchise_id'))
        ->where('timer_id', $request->input('timer_id'))
        ->where('user_id', $request->input('user_id'))
        ->whereNull('transaction_id')
        ->whereDate('date', $current_date)
        ->first();

    if ($existing_payment) {
        // Update existing payment
        $existing_payment->order_id = $order_id;
        $existing_payment->name = $request->name;
        $existing_payment->mobile = $request->mobile;
        $existing_payment->pickup_point = $request->pickup_point;
        $existing_payment->pickup_time = $request->pickup_time;
        $existing_payment->merchant_transaction_id = $request->merchant_transaction_id;
        $existing_payment->transaction_amount = $request->transaction_amount;
        $existing_payment->updated_at = $current_time;
        $existing_payment->save();
    } else {
        // Saving payment details using the same order_id
        $payment_id = 'PAYMENT' . $current_time->format("YmdHis");
        $payment = new payment_details_model([
            'payment_id' => $payment_id,
            'franchise_id' => $request->franchise_id,
            'timer_id' => $request->timer_id,
            'order_id' => $order_id,
            'user_id' => $request->user_id,
            'name' => $request->name,
            'time_slot' => $time_slot->starting_time. '-' .$time_slot->end_time,
            'mobile' => $request->mobile,
            'pickup_point' => $request->pickup_point,
            'pickup_time' => $request->pickup_time,
            'merchant_transaction_id' => $request->merchant_transaction_id,
            'transaction_amount' => $request->transaction_amount,
            'status' => 'active',
            'date' => $current_date,
            'created_at' => $current_time,
        ]);

        $payment->save();
    }

    // Return success response
    return response()->json([
        'success' => true,
        'message' => 'Order and Payment Details added/updated successfully',
        'order_id' => $order_id,
    ]);
}


    public function add_fixed_order_details(Request $request)
    {
        // Validation for both order and payment details
        $validator = Validator::make($request->all(), [
            'franchise_id' => 'required',
            'user_id' => 'required',
            'menu_category_ids' => 'required|array',
            'menu_category_ids.*' => 'required',
            'menu_category_names' => 'required|array',
            'menu_category_names.*' => 'required',
            'menu_quantities' => 'required|array',
            'menu_quantities.*' => 'required',
            'total_menu_prices' => 'required|array',
            'total_menu_prices.*' => 'required',
            'gst' => 'required',
            'name' => 'required',
            'mobile' => 'required',
            'pickup_date' => 'required',
            'pickup_time' => 'required',
            'pickup_point' => 'required',
            'merchant_transaction_id' => 'required',
            'transaction_amount' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        $current_time = now();
        $current_date = $current_time->toDateString();
        $order_id = 'OD' . $current_time->format("YmdHis");
    
        // Check for existing order
        $existing_orders = fixed_order_details_model::where('franchise_id', $request->input('franchise_id'))
            ->where('user_id', $request->input('user_id'))
            ->whereNull('transaction_id')
            ->whereDate('date', $current_date)
            ->get();
    
        if ($existing_orders->isNotEmpty()) {
            // Get the list of existing menu_category_ids
            $existing_menu_category_ids = $existing_orders->pluck('menu_category_id')->toArray();
    
            // Identify and delete rows where menu_category_id is not in the incoming request
            $ids_to_delete = array_diff($existing_menu_category_ids, $request->menu_category_ids);
            if (!empty($ids_to_delete)) {
                fixed_order_details_model::where('franchise_id', $request->input('franchise_id'))
                    ->where('user_id', $request->input('user_id'))
                    ->whereIn('menu_category_id', $ids_to_delete)
                    ->whereDate('date', $current_date)
                    ->delete();
            }
    
            // Update existing order
            foreach ($existing_orders as $existing_order) {
                $index = array_search($existing_order->menu_category_id, $request->menu_category_ids);
                if ($index !== false) {
                    $existing_order->menu_quantity = $request->menu_quantities[$index];
                    $existing_order->total_menu_price = $request->total_menu_prices[$index];
                    $existing_order->wallet = $request->wallet;
                    $existing_order->gst = $request->gst;
                    $existing_order->name = $request->name;
                    $existing_order->mobile = $request->mobile;
                    $existing_order->pickup_point = $request->pickup_point;
                    $existing_order->pickup_time = $request->pickup_time;
                    $existing_order->merchant_transaction_id = $request->merchant_transaction_id;
                    $existing_order->transaction_amount = $request->transaction_amount;
                    $existing_order->updated_at = $current_time;
                    $existing_order->save();
                }
            }
        } else {
            // Fetch the active franchise details
            $franchise = franchise_model::where('franchise_id', $request->franchise_id)
                ->where('status', 'active')
                ->first();
    
            if (!$franchise) {
                return response()->json(['error' => 'Franchise not found or is not active'], 404);
            }
         
    
            // Assuming the menu details are sent as arrays of equal length
            for ($i = 0; $i < count($request->menu_category_ids); $i++) {
                $order = new fixed_order_details_model([
                    'order_id' => $order_id,
                    'franchise_id' => $request->franchise_id,
                    'timer_id' => $request->timer_id,
                    'user_id' => $request->user_id,
                    'menu_category_id' => $request->menu_category_ids[$i],
                    'menu_category_name' => $request->menu_category_names[$i],
                    'menu_quantity' => $request->menu_quantities[$i],
                    'total_menu_price' => $request->total_menu_prices[$i],
                    'wallet' => $request->wallet,
                    'gst' => $request->gst,
                    'franchise' => $franchise->franchise, // Storing the franchise name from the queried result
                    'status' => 'active',
                    'name' => $request->name,
                    'mobile' => $request->mobile,
                    'pickup_point' => $request->pickup_point,
                    'pickup_date' => $request->pickup_date,
                    'pickup_time' => $request->pickup_time,
                    'merchant_transaction_id' => $request->merchant_transaction_id,
                    'transaction_amount' => $request->transaction_amount,
                    'date' => $current_date,
                    'time' => $current_time,
                    'created_at' => $current_time,
                ]);
    
                $order->save();
            }
        }
    
        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Fixed Order Details added/updated successfully',
            'order_id' => $order_id,
        ]);
    }


    public function update_payment(Request $request, $franchise_id, $timer_id, $user_id)
    {
        // Check if the payment and order details exist and are active
        $paymentExists = DB::table('payment_details')
            ->where('franchise_id', '=', $franchise_id)
            ->where('timer_id', '=', $timer_id)
            ->where('user_id', '=', $user_id)
            ->where('status', '=', 'active')
            ->exists();

        $orderExists = DB::table('order_details')
            ->where('franchise_id', '=', $franchise_id)
            ->where('timer_id', '=', $timer_id)
            ->where('user_id', '=', $user_id)
            ->where('status', '=', 'active')
            ->exists();

        if (!$paymentExists || !$orderExists) {
            $fail = [
                'message' => "Relevant details not found or not active",
                'success' => false
            ];
            return response()->json($fail, 404);
        } else {
            // Update payment details
            $current_time = now();
            $current_date = $current_time->toDateString();

            
            $updatePayment = DB::table('payment_details')
                ->where('franchise_id', '=', $franchise_id)
                ->where('timer_id', '=', $timer_id)
                ->where('user_id', '=', $user_id)
                ->whereDate('date', $current_date)
                ->where('status', '=', 'active')

                ->update([
                    'mt_id' => $request->input('mt_id'),
                   // 'transaction_status' => $request->input('transaction_status')
                ]);

            // Optionally, update order details if needed
            $updateOrder = DB::table('order_details')
                ->where('franchise_id', '=', $franchise_id)
                ->where('timer_id', '=', $timer_id)
                ->where('user_id', '=', $user_id)
                ->whereDate('date', $current_date)
                ->where('status', '=', 'active')
                ->update([
                    'mt_id' => $request->input('mt_id'),
                   // 'transaction_status' => $request->input('transaction_status')
                ]);

            if ($updatePayment === 0 && $updateOrder === 0) {
                $success = [
                    'message' => "No changes made or updates not successful",
                    'success' => false
                ];
                return response()->json($success);
            } else {
                $success = [
                    'message' => "Details updated successfully",
                    'success' => true
                ];
                return response()->json($success);
            }
        }
    }



    public function update_order_status(Request $request, $order_id)
    {
        // Check if the payment exists and is active
        $exists = DB::table('order_details')
            ->where('order_id', '=', $order_id)
            ->where('status', '=', 'active')
            ->exists();

        if (!$exists) {
            $fail = [
                'message' => "Order_details not found",
                'success' => false
            ];
            return response()->json($fail, 404);
        } else {
            // Perform the update
            $update = DB::table('order_details')
                ->where('order_id', '=', $order_id)
                ->where('status', '=', 'active')
                ->update([
                    // 'franchise_id' => $request->input('franchise_id'),
                    // 'user_id' => $request->input('user_id'),
                    // 'name' => $request->input('name'),
                    // 'pickup_point' => $request->input('pickup_point'),
                    // // 'pickup_time' => $request->input('pickup_time'),
                    // 'transaction_id' => $request->input('transaction_id'),
                    // 'transaction_status' => $request->input('transaction_status'),
                    'order_status' => $request->input('order_status')
                ]);

            if ($update === 0) {
                $success = [
                    'message' => "No changes made or Order_details update not successful",
                    'success' => false
                ];
                return response()->json($success);
            } else {
                $success = [
                    'message' => "Order_details updated successfully",
                    'success' => true
                ];
                return response()->json($success);
            }
        }
    }




    public function get_orders(Request $request, $order_id)
    {
        $orderDetails = DB::table('order_details')
            ->where('order_id', '=', $order_id)
            ->where('status', '=', 'active')
            ->get(); // Using get() to retrieve all matching records

        if ($orderDetails->isEmpty()) {
            $fail['message'] = "No OrderDetails found for the User";
            $fail['success'] = false;
            return response()->json($fail, 404);
        } else {
            $results = [];

            foreach ($orderDetails as $orderDetail) {
                $result = [
                    "order_id" => $orderDetail->order_id,
                    "franchise_id" => $orderDetail->franchise_id,
                    "user_id" => $orderDetail->user_id,
                    "name" => $orderDetail->name,
                    "mobile" => $orderDetail->mobile,
                    "menu_category_id" => $orderDetail->menu_category_id,
                    "menu_category_name" => $orderDetail->menu_category_name,
                    "menu_quantity" => $orderDetail->menu_quantity,
                    "total_menu_price" => $orderDetail->total_menu_price,
                    "wallet" => $orderDetail->wallet,
                    "pickup_point" => $orderDetail->pickup_point,
                    "pickup_time" => $orderDetail->pickup_time,
                    "status" => $orderDetail->status,
                    "date" => $orderDetail->date,
                    "created_at" => $orderDetail->created_at,
                ];

                $results[] = $result;
            }

            return response()->json(["results" => $results], 200);
        }
    }

    public function get_orderdetails(Request $request, $user_id)
    {
    
        // Assuming 'OrderDetail' is your Eloquent model name
        $orderDetails = order_details_model:://where('franchise_id', $franchise_id)
            where('user_id', $user_id)
            ->where('status', 'active')
            ->whereNotNull('payment_status')
          //  ->whereDate('date', $current_date) // Uncomment if you decide to filter by date
            ->get();

        if ($orderDetails->isEmpty()) {
            return response()->json([
                'message' => "No Order Details found for the Franchise",
                'success' => false
            ], 200); // Using 200 as it's still a successful request, just no data found
        }
        $results = $orderDetails->map(function ($orderDetail) {

            $franchise = franchise_model::where('franchise_id', $orderDetail->franchise_id)
            ->where('status', 'active')
            ->first();

            $dist = district_model::where('district_id', $franchise->district_id)
        ->where('status', 'active')
        ->first();
        $state = state_model::where("state_id", $franchise->state_id)
        ->first();

            return [
                "order_id" => $orderDetail->order_id,
                "franchise_id" => $orderDetail->franchise_id,
                "franchise" => $orderDetail->franchise,               
                "user_id" => $orderDetail->user_id,
                "location" => $dist->district_name . ',' . $state->name,
                "name" => $orderDetail->name,
                "mobile" => $orderDetail->mobile,
                "menu_category_id" => $orderDetail->menu_category_id,
                "menu_category_name" => $orderDetail->menu_category_name,
                "menu_quantity" => $orderDetail->menu_quantity,
                "total_menu_price" => $orderDetail->total_menu_price,
                "wallet" => $orderDetail->wallet,
                "pickup_point" => $orderDetail->pickup_point,
                "pickup_date" => $orderDetail->pickup_date,
                "pickup_time" => $orderDetail->pickup_time,
                "transaction_id" => $orderDetail->transaction_id,
                "transaction_amount" => $orderDetail->transaction_amount,
                "transaction_status" => $orderDetail->transaction_status,
                "payment_status" => $orderDetail->payment_status,
                "order_status" => $orderDetail->order_status,
                "status" => $orderDetail->status,
                "date" => $orderDetail->date,
                "created_at" => $orderDetail->created_at,
            ];
        });

        return response()->json(['results' => $results], 200);
    }




    // public function get_orderdetails(Request $request, $franchise_id , $user_id)
    // {


    //     $current_date = date("Y-m-d");

    //     $orderDetails = DB::table('order_details')
    //         ->where('franchise_id', '=', $franchise_id)
    //          ->where('user_id', '=', $user_id)
    //         ->where('status', '=', 'active')
    //        // ->whereDate('date', $current_date)
    //         ->get(); // Using get() to retrieve all matching records

    //     if ($orderDetails->isEmpty()) {
    //         $fail['message'] = "No OrderDetails found for the Franchise";
    //         $fail['success'] = false;
    //         return response()->json($fail, 404);
    //     } else {
    //         $results = [];

    //         foreach ($orderDetails as $orderDetail) {
    //             $result = [
    //                 "order_id" => $orderDetail->order_id,
    //                   'payment_id' => $request->input('payment_id'),
    //                 "franchise_id" => $orderDetail->franchise_id,
    //                 "franchise" => $orderDetail->franchise,
    //                 "user_id" => $orderDetail->user_id,
    //                  "name" => $orderDetail->name,
    //                 "menu_category_id" => $orderDetail->menu_category_id,
    //                 "menu_category_name" => $orderDetail->menu_category_name,
    //                 "menu_quantity" => $orderDetail->menu_quantity,
    //                 "total_menu_price" => $orderDetail->total_menu_price,
    //                   "pickup_point" => $orderDetail->pickup_point,
    //                 "pickup_time" => $orderDetail->pickup_time,
    //                 "transaction_id" => $orderDetail->transaction_id,
    //                  "transaction_amount" => $orderDetail->transaction_amount,
    //                 "transaction_status" => $orderDetail->transaction_status,
    //                 "order_status" => $orderDetail->order_status,
    //                 "status" => $orderDetail->status,
    //                 "date" => $orderDetail->date,
    //                 "created_at" => $orderDetail->created_at,
    //             ];

    //             $results[] = $result;
    //         }

    //         return response()->json(["results" => $results], 200);
    //     }
    // }



    public function get_paymentdetails(Request $request, $user_id)
    {
        $orderDetails = DB::table('order_details')
            ->where('user_id', '=', $user_id)
            ->where('status', '=', 'active')
            ->get(); // Using get() to retrieve all matching records

        if ($orderDetails->isEmpty()) {
            $fail['message'] = "No OrderDetails found for the User";
            $fail['success'] = false;
            return response()->json($fail, 404);
        } else {
            $results = [];

            foreach ($orderDetails as $orderDetail) {
                $result = [

                    "order_id" => $orderDetail->order_id,
                    "franchise_id" => $orderDetail->franchise_id,
                    "user_id" => $orderDetail->user_id,
                    "menu_category_id" => $orderDetail->menu_category_id,
                    "menu_category_name" => $orderDetail->menu_category_name,
                    "menu_quantity" => $orderDetail->menu_quantity,
                    "total_menu_price" => $orderDetail->total_menu_price,
                    "payment_status" => $orderDetail->payment_sttus,
                    "status" => $orderDetail->status,
                    "date" => $orderDetail->date,
                    "created_at" => $orderDetail->created_at,
                ];

                $results[] = $result;
            }

            return response()->json(["results" => $results], 200);
        }
    }




    // public function get_franchise_order(Request $request, $franchise_id)  //---->local
    // {
    //     $orderDetails = DB::table('order_details')
    //         ->where('franchise_id', '=', $franchise_id)
    //         ->where('status', '=', 'active')
    //         ->get(); // Using get() to retrieve all matching records

    //     if ($orderDetails->isEmpty()) {
    //         $fail['message'] = "No OrderDetails found for the Franchise";
    //         $fail['success'] = false;
    //         return response()->json($fail, 404);
    //     } else {
    //         $results = [];

    //         foreach ($orderDetails as $orderDetail) {
    //             $result = [
    //                 "order_id" => $orderDetail->order_id,
    //                 'payment_id' => $request->input('payment_id'),
    //                 "franchise_id" => $orderDetail->franchise_id,
    //                 "user_id" => $orderDetail->user_id,
    //                 "name" => $orderDetail->name,
    //                 "mobile" => $orderDetail->mobile,
    //                 "menu_category_id" => $orderDetail->menu_category_id,
    //                 "menu_category_name" => $orderDetail->menu_category_name,
    //                 "menu_quantity" => $orderDetail->menu_quantity,
    //                 "total_menu_price" => $orderDetail->total_menu_price,
    //                 "pickup_point" => $orderDetail->pickup_point,
    //                 "pickup_time" => $orderDetail->pickup_time,
    //                 "transaction_id" => $orderDetail->transaction_id,
    //                 "transaction_status" => $orderDetail->transaction_status,
    //                 "transaction_amount" => $orderDetail->transaction_amount,
    //                 "order_status" => $orderDetail->order_status,
    //                 "status" => $orderDetail->status,
    //                 "date" => $orderDetail->date,
    //                 "created_at" => $orderDetail->created_at,
    //             ];

    //             $results[] = $result;
    //         }

    //         return response()->json(["results" => $results], 200);
    //     }
    // }

//----->deveploment code
    public function get_franchise_order(Request $request, $franchise_id)
{
    $orderDetails = DB::table('order_details')
        ->where('franchise_id', '=', $franchise_id)
        ->where('status', '=', 'active')
        ->get();

    if ($orderDetails->isEmpty()) {
        $fail['message'] = "No OrderDetails found for the Franchise";
        $fail['success'] = false;
        return response()->json($fail, 404);
    } else {
        $groupedOrders = $orderDetails->groupBy('order_id');
        $results = [];

        foreach ($groupedOrders as $order_id => $orders) {
            $menuItems = $orders->map(function($order) {
                return $order->menu_category_name . ' (' . $order->menu_quantity . ') (' . $order->total_menu_price . ')';
            })->unique()->all();

            $orderDetail = $orders->first(); // Get the first order to retrieve common details
            
            $totalMenuPrice = $orders->sum('total_menu_price');
            $totalPrice = $orderDetail->wallet + $orderDetail->transaction_amount;

            $result = [
                "order_id" => $order_id,
                "menu_items" => $menuItems,
                "franchise_id" => $orderDetail->franchise_id,
                "user_id" => $orderDetail->user_id,
                "name" => $orderDetail->name,
                "mobile" => $orderDetail->mobile,
        //      "menu_category_id" => $orderDetail->menu_category_id,
         //     "menu_category_name" => $orderDetail->menu_category_name,
         //     "menu_quantity" => $orderDetail->menu_quantity,
                "total_amount" => $totalPrice,
                "total_menu_price" => $totalMenuPrice,
                "wallet" => $orderDetail->wallet,
                "time_slot" => $orderDetail->time_slot,
                "pickup_point" => $orderDetail->pickup_point,
                "pickup_time" => $orderDetail->pickup_time,
                "transaction_id" => $orderDetail->transaction_id,
                "transaction_amount" => $orderDetail->transaction_amount,
                "transaction_status" => $orderDetail->transaction_status,
                "order_status" => $orderDetail->order_status,
                "status" => $orderDetail->status,
                "date" => $orderDetail->date,
                "created_at" => $orderDetail->created_at,
            ];

            $results[] = $result;
        }

        return response()->json(["results" => $results], 200);
    }
}








    // public function get_today_order(Request $request, $franchise_id)
    // {


    //     $current_date = date("Y-m-d");

    //     $orderDetails = DB::table('order_details')
    //         ->where('franchise_id', '=', $franchise_id)
    //         ->where('status', '=', 'active')
    //         ->whereDate('date', $current_date)
    //         ->get(); // Using get() to retrieve all matching records

    //     if ($orderDetails->isEmpty()) {
    //         $fail['message'] = "No OrderDetails found for the Franchise";
    //         $fail['success'] = false;
    //         return response()->json($fail, 404);
    //     } else {
    //         $results = [];

    //         foreach ($orderDetails as $orderDetail) {
    //             $result = [
    //                 "order_id" => $orderDetail->order_id,
    //                   'payment_id' => $request->input('payment_id'),
    //                 "franchise_id" => $orderDetail->franchise_id,
    //                 "user_id" => $orderDetail->user_id,
    //                  "name" => $orderDetail->name,
    //                 "menu_category_id" => $orderDetail->menu_category_id,
    //                 "menu_category_name" => $orderDetail->menu_category_name,
    //                 "menu_quantity" => $orderDetail->menu_quantity,
    //                 "total_menu_price" => $orderDetail->total_menu_price,
    //                   "pickup_point" => $orderDetail->pickup_point,
    //                 "pickup_time" => $orderDetail->pickup_time,
    //                 "transaction_id" => $orderDetail->transaction_id,
    //                 "transaction_status" => $orderDetail->transaction_status,
    //                 "order_status" => $orderDetail->order_status,
    //                 "status" => $orderDetail->status,
    //                 "date" => $orderDetail->date,
    //                 "created_at" => $orderDetail->created_at,
    //             ];

    //             $results[] = $result;
    //         }

    //         return response()->json(["results" => $results], 200);
    //     }
    // }



    public function get_todays_order(Request $request, $franchise_id)
    {


        $current_date = date("Y-m-d");

        $orderDetails = DB::table('order_details')
            ->where('franchise_id', '=', $franchise_id)
            ->where('status', '=', 'active')
            ->whereDate('date', $current_date)
            ->get(); // Using get() to retrieve all matching records

        if ($orderDetails->isEmpty()) {
            $fail['message'] = "No OrderDetails found for the Franchise";
            $fail['success'] = false;
            return response()->json($fail, 404);
        } else {
            $results = [];

            foreach ($orderDetails as $orderDetail) {
                $result = [
                    "order_id" => $orderDetail->order_id,
                    'payment_id' => $request->input('payment_id'),
                    "franchise_id" => $orderDetail->franchise_id,
                    "user_id" => $orderDetail->user_id,
                    "name" => $orderDetail->name,
                    "mobile" => $orderDetail->mobile,
                    "menu_category_id" => $orderDetail->menu_category_id,
                    "menu_category_name" => $orderDetail->menu_category_name,
                    "menu_quantity" => $orderDetail->menu_quantity,
                    "total_menu_price" => $orderDetail->total_menu_price,
                    "pickup_point" => $orderDetail->pickup_point,
                    "pickup_time" => $orderDetail->pickup_time,
                    "transaction_id" => $orderDetail->transaction_id,
                    "transaction_status" => $orderDetail->transaction_status,
                    "order_status" => $orderDetail->order_status,
                    "status" => $orderDetail->status,
                    "date" => $orderDetail->date,
                    "created_at" => $orderDetail->created_at,
                ];

                $results[] = $result;
            }

            return response()->json(["results" => $results], 200);
        }
    }




    ///>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>///


    public function get_franchise_turnover(Request $request, $franchise_id)
    {
        // Get current date
        $current_date = now()->toDateString();

        // Retrieve the winner count
        $bidder_count = DB::table('payment_details')
            ->where('franchise_id', $franchise_id)
            ->whereDate('date', $current_date) // Filter by current date
            ->distinct('order_id') // Ensure each user is counted only once
            ->count();

        // Retrieve the total transaction amount
        $total_transaction_amount = DB::table('payment_details')
            ->where('franchise_id', $franchise_id)
            ->whereDate('date', $current_date) // Filter by current date
            ->sum('transaction_amount');

        $success['message'] = "Winner count and total transaction amount retrieved successfully for today's timer_id";
        $success['success'] = true;
        $success['winner_count'] = $bidder_count;
        $success['total_transaction_amount'] = $total_transaction_amount;

        return response()->json($success);
    }



    
}
