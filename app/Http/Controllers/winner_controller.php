<?php
namespace App\Http\Controllers;

use App\Models\winner_model;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;



class winner_controller extends Controller 
{
    public function add_winner(Request $request)
    {
        // Validate request data
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


        // $user = DB::table('user')
        // ->where('user_id', $request->user_id)
        // ->first();
    
        // Generate unique winner ID
        $winner_id = 'WINNER' . now()->format("YmdHis");
    
        // Get current date and time
        $current_date = now();
    
        // Create a new instance of the winner model
        $winner = new winner_model(); // Assuming 'Winner' is the correct model name
        $winner->user_id = $request->input('user_id');
        $winner->timer_id = $request->input('timer_id');
        $winner->franchise_id = $request->input('franchise_id');
        $winner->name = $request->input('name');
       // $winner->mobile = $user->mobile;
        $winner->menu_category_id = $request->input('menu_category_id');
        $winner->menu_category_name = $request->input('menu_category_name');
        $winner->description = $request->input('description');
        $winner->final_price = $request->input('current_price'); // Corrected field name
    
        // Assuming 'status' is a required field with a default value
        $winner->status = 'orded not placed';
        $winner->created_at = $current_date;
        $winner->date = now();
        $winner->save();
    
        // Return success response
        $success['message'] = "Winner added successfully";
        $success['success'] = true;
        return response()->json($success);
    }




    public function get_winner(Request $request, $franchise_id, $timer_id)
    {
        // Get current date
        $current_date = now()->toDateString();
    
        // Check if bidding data exists for the provided franchise and timer
        $winner = DB::table('winner')
            ->where('franchise_id', $franchise_id)
            ->where('timer_id', $timer_id)
            ->where('status', 'active')
            ->whereDate('date', $current_date) // Use 'date' column for date comparison
            ->get();

    
        if ($winner->isEmpty()) { // Check if collection is empty
            $fail['message'] = "Winner data not found for this franchise and timer";
            $fail['success'] = false;
            return response()->json($fail, 404); // Return 404 status code for not found
        } else {
            $success['message'] = "Final prices retrieved successfully";
            $success['success'] = true;
            $success['winner'] = $winner;
        }
    
        return response()->json($success);
    }

    public function get_winner_franchise(Request $request, $franchise_id)
    {
        // Get current date
      //  $current_date = now()->toDateString();
    
        // Check if bidding data exists for the provided franchise
        $winners = DB::table('winner')
            ->where('franchise_id', $franchise_id)
            ->where('status', 'active')
           // ->whereDate('date', $current_date) // Use 'date' column for date comparison
            ->get(); // Retrieve all matching winners for the franchise
    
        if ($winners->isEmpty()) {
            $fail['message'] = "No winner found for this franchise";
            $fail['success'] = false;
            return response()->json($fail, 404); // Return 404 status code for not found
        } else {
            $success['message'] = "Winners retrieved successfully";
            $success['success'] = true;
            $success['winners'] = $winners;
        }
    
        return response()->json($success);
    }
    

    
    public function get_total_winner(Request $request, )
    {
        // Get current date
        $current_date = now()->toDateString();
    
        // Check if bidding data exists for the provided franchise and timer
        $winner = DB::table('winner')
          //  ->where('franchise_id', $franchise_id)
          //  ->where('timer_id', $timer_id)
            ->where('status', 'active')
            ->whereDate('date', $current_date) // Use 'date' column for date comparison
            ->get();
    
        if ($winner->isEmpty()) { // Check if collection is empty
            $fail['message'] = "Winner data not found for today";
            $fail['success'] = false;
            return response()->json($fail, 404); // Return 404 status code for not found
        } else {
            $success['message'] = "Winner retrieved successfully";
            $success['success'] = true;
            $success['winner'] = $winner;
        }
    
        return response()->json($success);
    }





//     public function get_winner_count(Request $request, $timer_id)
// {
//     // Get current date
//     $current_date = now()->toDateString();

//     $bidder_count = DB::table('winner')
//         ->where('timer_id', $timer_id)
//         ->whereDate('date', $current_date) // Filter by current date
//         ->count();

//     $success['message'] = "Winner count retrieved successfully for today's timer_id";
//     $success['success'] = true;
//     $success['bidder_count'] = $bidder_count;

//     return response()->json($success);
// }

    

// public function get_winner_count(Request $request, $timer_id, $franchise_id)
// {
//     // Get current date
//     $current_date = now()->toDateString();

//     $bidder_count = DB::table('winner')
//         ->where('timer_id', $timer_id)
//         ->where('franchise_id', $franchise_id)
//         ->whereDate('date', $current_date) // Filter by current date
//         ->count();

//     $success['message'] = "Winner count retrieved successfully for today's timer_id";
//     $success['success'] = true;
//     $success['winner_count'] = $bidder_count;

//     return response()->json($success);
// }



public function get_winner_count(Request $request, $timer_id, $franchise_id)
{
    // Get current date
    $current_date = now()->toDateString();

    $bidder_count = DB::table('winner')
        ->where('timer_id', $timer_id)
        ->where('franchise_id', $franchise_id)
        ->whereDate('date', $current_date) // Filter by current date
        ->distinct('user_id') // Ensure each user is counted only once
        ->count();

    $success['message'] = "Winner count retrieved successfully for today's timer_id";
    $success['success'] = true;
    $success['winner_count'] = $bidder_count;

    return response()->json($success);
}





    

}