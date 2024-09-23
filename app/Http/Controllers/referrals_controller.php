<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class referrals_controller extends Controller
{
    public function get_referrals(Request $request)
    {
         
          $referrals = DB::table('referrals')
          //  ->where('franchise_id', $franchise_id)
          //  ->where('timer_id', $timer_id)
            ->where('status', 'active')
          //  ->whereDate('date', $current_date) // Use 'date' column for date comparison
            ->get();
    
        if ($referrals->isEmpty()) { // Check if collection is empty
            $fail['message'] = "Referral data not found ";
            $fail['success'] = false;
            return response()->json($fail, 404); // Return 404 status code for not found
        } else {
            $success['message'] = "Referral retrieved successfully";
            $success['success'] = true;
            $success['referrals'] = $referrals;
        }
    
        return response()->json($success);
    }

}