<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\phonepe_model;
use App\Models\phonepe_response_model;

class phonepe_controller extends Controller
{

    public function phonepe_response(Request $request)
{
    // Validate the incoming request
    $validator = Validator::make($request->all(), [
        'response' => 'required'
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    // Decode the Base64 response
    $encodedResponse = $request->input('response');
    $decodedResponse = base64_decode($encodedResponse);

    if ($decodedResponse === false) {
        return response()->json(['error' => 'Base64 decode failed'], 400);
    }

    // Decode the JSON response
    $response = json_decode($decodedResponse, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return response()->json(['error' => 'Invalid JSON received'], 400);
    }

    // Save the raw response in the database
    $phonepe = new phonepe_model();
    $phonepe->response =  json_encode($response);
    $phonepe->created_at = now();
    $phonepe->save();

    if (!$phonepe) {
        return response()->json([
            'message' => "Response not added successfully",
            'success' => false,
        ]);
    }

    // Extract necessary fields from the response
    $success = $response['success'];
    $code = $response['code'];
    $message = $response['message'];
    $merchantId = $response['data']['merchantId'];
    $merchantTransactionId = $response['data']['merchantTransactionId'];
    $transactionId = $response['data']['transactionId'];
    $amount = $response['data']['amount'];
    $state = $response['data']['state'];
    $responseCode = $response['data']['responseCode'];
    $type = $response['data']['paymentInstrument']['type'] ?? '';
    $utr = $response['data']['paymentInstrument']['utr'] ?? null;
    $card_type = $response['data']['paymentInstrument']['cardType'] ?? null;
    $pgTransactionId = $response['data']['paymentInstrument']['pgTransactionId'] ?? null;
    $bankTransactionId = $response['data']['paymentInstrument']['bankTransactionId'] ?? null;
    $pgAuthorizationCode = $response['data']['paymentInstrument']['pgAuthorizationCode'] ?? null;
    $arn = $response['data']['paymentInstrument']['arn'] ?? null;
    $bankId = $response['data']['paymentInstrument']['bankId'] ?? null;
    $pgServiceTransactionId = $response['data']['pgServiceTransactionId'] ?? null;


    $successTF = $success ? 'True' : 'False';
    $amountpaisa = $amount/100;

    // Save the decoded response in the new table
    $phonepe_response = new phonepe_response_model();
    $phonepe_response->success = $successTF;
    $phonepe_response->code = $code;
    $phonepe_response->message = $message;
    $phonepe_response->merchant_id = $merchantId;
    $phonepe_response->merchant_transaction_id = $merchantTransactionId;
    $phonepe_response->transaction_id = $transactionId;
    $phonepe_response->amount = $amountpaisa;
    $phonepe_response->state = $state;
    $phonepe_response->response_code = $responseCode;
    $phonepe_response->type = $type;
    $phonepe_response->utr = $utr;
    $phonepe_response->card_type = $card_type;
    $phonepe_response->pg_transation_id = $pgTransactionId;
    $phonepe_response->bank_transaction_id = $bankTransactionId;
    $phonepe_response->pg_authorization_code = $pgAuthorizationCode;
    $phonepe_response->arn = $arn;
    $phonepe_response->bank_id = $bankId;
    $phonepe_response->pg_service_transaction_id = $pgServiceTransactionId;
    $phonepe_response->status = 'active';
    $phonepe_response->created_at = now();
    $phonepe_response->updated_at = now();
    $phonepe_response->save();

    if (!$phonepe_response) {
        return response()->json([
            'message' => "Response not added successfully to the new table",
            'success' => false,
        ]);
    }

        $transactionId = $response['data']['transactionId'] ?? null;
        $state = $response['data']['state'] ?? null;
        $merchantTransactionId = $response['data']['merchantTransactionId'] ?? null;
        $success = $response['success'] ?? false;

        if (!$transactionId || !$responseCode || !$merchantTransactionId) {
            return response()->json([
                'message' => "Required fields not found in the response",
                'success' => false,
            ], 400);
        }

    // Determine payment status based on success field
    $paymentStatus = $success ? 'success' : 'failed';

    // Determine order status based on payment status
    $orderStatus = $success ? 'order placed' : 'order not placed';


    // Validate the necessary ID (merchantTransactionId)
    $merchant_transaction_id = $merchantTransactionId;

    // Update payment and order details
    $current_time = now();
    $current_date = $current_time->toDateString();

    $updatePayment = DB::table('payment_details')
        ->where('merchant_transaction_id', '=', $merchant_transaction_id)
        ->whereDate('date', $current_date)
        ->where('status', '=', 'active')
        ->update([
            'transaction_id' => $transactionId,
            'transaction_status' => $state,
            'payment_status' => $paymentStatus
        ]);

    $updateOrder = DB::table('order_details')
        ->where('merchant_transaction_id', '=', $merchant_transaction_id)
        ->whereDate('date', $current_date)
        ->where('status', '=', 'active')
        ->update([
            'transaction_id' => $transactionId,
            'transaction_status' => $state,
            'payment_status' => $paymentStatus,
            'order_status' => $orderStatus
        ]);

    $updateFixedOrder = DB::table('fixed_order_details')
        ->where('merchant_transaction_id', '=', $merchant_transaction_id)
        ->whereDate('date', $current_date)
        ->where('status', '=', 'active')
        ->update([
            'transaction_id' => $transactionId,
            'transaction_status' => $state,
            'payment_status' => $paymentStatus,
            'order_status' => $orderStatus
        ]);

    if ($updatePayment === 0 && $updateOrder === 0 && $updateFixedOrder === 0) {
        return response()->json([
            'message' => "No changes made or updates not successful",
            'success' => false
        ]);
    }

    return response()->json([
        'message' => "Response added and details updated successfully",
        'success' => true,
    ]);
}


    public function get_phonepe_response(Request $request, $merchant_transaction_id)
    {
        $payment_details = DB::table('payment_details')
        ->select(
        //     'transaction_id',
        //  'transaction_status', 
         'payment_status')// , 'transaction_id')
        ->where('merchant_transaction_id',$merchant_transaction_id)
        ->first();

        if (!$payment_details) {
            $success['message'] = "NO payment found";
            $success['success'] = false;
            return response()->json($success);
        } else {
            $result = [
                // "transaction_id" => $payment_details->transaction_id,
                // "transaction_status" => $payment_details->transaction_status,
                "payment_status" => $payment_details->payment_status,
             //   "transaction_id" => $payment_details->transaction_id

            ];
            return response()->json(["result" => $result]);
        }

    }

    public function get_phonepe_response_admin()
{
    try {
        // Fetch all records from the phonepe_response table
        $phonepe_responses = phonepe_response_model::all();

        // Check if records exist
        if ($phonepe_responses->isEmpty()) {
            return response()->json([
                'message' => 'No records found',
                'success' => false
            ], 404);
        }

        $responses = [];

        // Iterate over each response to fetch corresponding user details
        foreach ($phonepe_responses as $response) {
            // Get merchant_transaction_id from the response
            $merchant_transaction_id = $response->merchant_transaction_id;

            // Query the order_details table for matching merchant_transaction_id
            $userDetails = DB::table('order_details')
                ->select('name', 'mobile', 'order_id')
                ->where('merchant_transaction_id', $merchant_transaction_id)
                ->where('status', 'active')
                ->first();

            // Convert stdClass to array
            $userDetailsArray = $userDetails ? (array)$userDetails : [];

            // Merge response and user details into a single array
            $mergedData = array_merge(
                $response->toArray(),
                $userDetailsArray
            );

            $responses[] = $mergedData;
        }

        // Return the fetched data as a JSON response
        return response()->json([
            'message' => 'Records fetched successfully',
            'success' => true,
            'data' => $responses
        ], 200);
    } catch (\Exception $e) {
        // Handle any errors
        return response()->json([
            'message' => 'An error occurred while fetching records',
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}

    

//     public function get_phonepe_response_admin()
// {
//     try {
//         // Fetch all records from the phonepe_response table
//         $responses = phonepe_response_model::all();

//         // Check if records exist
//         if ($responses->isEmpty()) {
//             return response()->json([
//                 'message' => 'No records found',
//                 'success' => false
//             ], 404);
//         }

//         $userderails = DB::table('order_details')
//         ->select('name', 'mobile', 'order_id')
//         ->where('merchant_transaction_id', $merchant_transaction_id)
//         ->where('status', 'active')
//         ->get()

        

//         // Return the fetched data as a JSON response
//         return response()->json([
//             'message' => 'Records fetched successfully',
//             'success' => true,
//             'data' => $responses, //$userderails
//             'user_details' => $userderails
//         ], 200);
//     } catch (\Exception $e) {
//         // Handle any errors
//         return response()->json([
//             'message' => 'An error occurred while fetching records',
//             'success' => false,
//             'error' => $e->getMessage()
//         ], 500);
//     }
// }

}




