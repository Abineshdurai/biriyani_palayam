<?php

namespace App\Http\Controllers;

use App\Models\scrolling_text_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
// use Validator;
// use DB;

class scrolling_text_controller extends Controller
{
    //
     public function add_text(Request $request)
     {
        $validator = Validator::make($request->all(), [
            'scrolling_text' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        } else {
            date_default_timezone_set('Asia/Calcutta');
            $current_date = now(); // Using Laravel's helper function to get current date and time
            $scrolling_id = 'SLT' . $current_date->format('YmdHis');

            $scrolling_text = $request->input('scrolling_text');

            $scrollingText = new scrolling_text_model(); // Spelling unchanged

            $scrollingText->scrolling_text_id = $scrolling_id;
            $scrollingText->scrolling_text = $scrolling_text;
            $scrollingText->status = "active";
            $scrollingText->created_at = $current_date;
            $scrollingText->save();

            if (!$scrollingText) {
                $success['message'] = "ScrollingText not added successfully";
                $success['success'] = false;
                return response()->json($success);
            } else {
                $success['message'] = "ScrollingText added successfully";
                $success['success'] = true;
                return response()->json($success);
            }
        }
     }


     public function get_scrolling_text(Request $request){
        $scrollingText = DB::table('scrolling_text')
        ->select('scrolling_text', 'scrolling_text_id')
        ->where('status', 'active')
        ->first();

        if(!$scrollingText) {
            $success['message'] = 'No Text Found';
            $success['success'] =  false;
            return response()->json([$success]);
        } else {
            $success['message'] = 'Successfull';
            $success['success'] = true;
            $success['scrolling_text'] = $scrollingText;
            return response()->json([$success]);
        }
     }


     public function edit_scrolling_text(Request $request, $scrolling_text_id){

        $validator = Validator::make($request->all(), [
            'scrolling_text' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $scrollingId = DB::table('scrolling_text')
       // ->select('scrolling_text', 'scrolling_text_id')
        ->where('scrolling_text_id', $scrolling_text_id)
        ->where('status', 'active')
        ->first();

        if(!$scrollingId) {
            $success['message'] = 'Id Not Found';
            $success['success'] =  false;
            return response()->json([$success]);
        } else {
            $update = DB::table('scrolling_text')
            ->where('scrolling_text_id', $scrolling_text_id)
            ->where('status', 'active')
            ->update([
                'scrolling_text' => $request->input('scrolling_text'),
                'updated_at' => now()
            ]);

            if(!$update) {
                $success['message'] = 'Not Updated Found';
                $success['success'] =  false;
                return response()->json([$success]);
            } else {
                $success['message'] = 'Updated Successfull';
                $success['success'] = true;
              //  $success['scrolling_text'] = $scrollingText;
                return response()->json([$success]);
            }
        }
     }
}
