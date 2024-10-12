<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\state_model;

class state_controller extends Controller
{
    public function Tsit_BPM_Add_State(Request $request, $admin_id)
    {
        $validator = Validator::make($request->all(), [
            'state' => 'required', // Adding validation for image file
            // 'redirect_url' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        else {

            $admin = DB::table('admin')
            ->where('admin_id', $admin_id)
            ->where('admin_type', 'primary')
            ->first();

        if(!$admin)
        {
            $success['message'] = 'You are Restricted for this Action';
            $success['success'] = false;
            return response()->json([$success]);
        } else {

        date_default_timezone_set('Asia/Calcutta');
        $current_date = now(); // Using Laravel's helper function to get current date and time

        $state_id = 'STATE' . $current_date->format('YmdHis');

        // Creating and saving banner model
        $state = new state_model(); // Spelling unchanged
        $state->state = $request->input('state');
        $state->state_id = $state_id;
        $state->status = 'active';
        $state->created_at = $current_date;
        $state->save();

        if (!$state) {
            $success['message'] = "State not added successfully";
            $success['success'] = false;
            return response()->json($success);
        } else {
            $success['message'] = "State added successfully";
            $success['success'] = true;
            return response()->json($success);
        }
    }
    }
    }




    public function Tsit_BPM_Get_State(Request $request)
    {
        $states = DB::table('states')
            ->where('status', 'active')
            ->get();

        if ($states->isEmpty()) {
            $success['message'] = 'No States Found';
            $success['success'] = false;
            return response()->json([$success]);
        } else {
            $result = [];
            foreach ($states as $st) {
                $result[] = [
                    "state" => $st->state,
                    "state_id" => $st->state_id
                ];
            }
            return response()->json(["result" => $result], 200);
        }
    }


}
