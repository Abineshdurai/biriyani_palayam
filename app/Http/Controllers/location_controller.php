<?php

namespace App\Http\Controllers;

use App\Models\district_model;
use App\Models\state_model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class location_controller extends Controller
{
    public function Tsit_BPM_Get_State(Request $request)
    {
        $states = state_model::select('name', 'state_id', 'state_code')
        ->where('country_code','IN')
        ->where('country_id','101')
       // ->where('status','active')
        ->get();

        if($states->isEmpty()){
            $success = 'There is no state for this country';
            $success = false;
            return response()->json($success);
        } else {
            $success = 'Listing all the states in India';
            $success = true;
            $success = $states;
            return response()->json($success);
        }
    }

    public function Tsit_BPM_Get_District(Request $request, $state_id)
    {
        $district = district_model::where('state_id',$state_id)
        ->select('district_name', 'district_id', 'state_code')
        ->where('country_code','IN')
        ->where('country_id','101')
        ->where('status','active')
        ->get();

        if($district->isEmpty()){
            $success = 'There is no state for this country';
            $success = false;
            return response()->json($success);
        } else {
            $success = 'Listing all the states in India';
            $success = true;
            $success = $district;
            return response()->json($success);
        }
    }
}