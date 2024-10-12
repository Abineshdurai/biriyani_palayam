<?php

namespace App\Http\Controllers;

use App\Models\district_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class district_controller extends Controller
{
    public function Tsit_BPM_Add_Dist(Request $request, $admin_id, $state_id)
    {
        $validator = Validator::make($request->all(), [
            'district' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        } else {

            $admin = DB::table('admin')
                ->where('admin_id', $admin_id)
                ->where('admin_type', 'primary')
                ->first();

            if (!$admin) {
                $success['message'] = 'You are Restricted for this Action';
                $success['success'] = false;
                return response()->json([$success]);
            } else {

                $state = DB::table('states')
                    ->where('state_id', $state_id)
                    ->where('status', 'active')
                    ->first();

                if (!$state) {
                    $success['message'] = 'State is inactive or not valid';
                    $success['success'] = false;
                    return response()->json([$success]);
                }


                date_default_timezone_set('Asia/Calcutta');
                $current_date = now(); // Using Laravel's helper function to get current date and time

                $dist_id = 'DIST' . $current_date->format('YmdHis');


                // Creating and saving banner model
                $dst = new district_model(); // Spelling unchanged
                $dst->district = $request->input('district');
                $dst->district_id = $dist_id;
                $dst->state_id = $state->state_id;
                $dst->status = 'active';
                $dst->created_at = now();
                $dst->save();

                if (!$dst) {
                    $success['message'] = "District not added successfully";
                    $success['success'] = false;
                    return response()->json($success);
                } else {
                    $success['message'] = "District added successfully";
                    $success['success'] = true;
                    return response()->json($success);
                }
            }
        }
    }


    public function Tsit_BPM_Get_District(Request $request, $state_id) {
        $district = DB::table('districts')
            ->where('state_id', $state_id)
            ->where('status', 'active')
            ->get();

        if ($district->isEmpty()) {
            $success['message'] = 'No District Found';
            $success['success'] = false;
            return response()->json([$success]);
        } else {
            $result = [];
            foreach ($district as $dist) {
                $result[] = [
                    "district" => $dist->district,
                    "district_id" => $dist->district_id
                ];
            }
            return response()->json(["result" => $result], 200);
        }
    }




}
