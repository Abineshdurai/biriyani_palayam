<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\menu_type_model;

class menu_type_controller extends Controller 
{
    public function add_menu_type(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'menu_type' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Menu type is required'], 422);
        }
        
        $menu_type_id = 'MENUTYP' . time();
        $menu_type = $request->input('menu_type');

        $menuType =  new menu_type_model();
        $menuType->menu_type_id = $menu_type_id;
        $menuType->menu_type = $menu_type;
        $menuType->status = 'active';
        $menuType->created_at = now();
        $menuType->save();

        if(!$menuType->exist) {
            $response = [
                'success' => true,
                'message' => 'Menu type added successfully'
                
            ];
        } else {
            $response = [
               'success' => false,
                'message' => 'Menu type not added successfully'
            ];
        }
        return response()->json($response);

    }

    public function get_menu_type(Request $request)
    {
       $menuType = DB::table('menu_type')
           ->select('menu_type', 'menu_type_id')
           ->where('status', 'active')
           ->get();
    
       if ($menuType->isNotEmpty()) {
           $result = [
               'menu_types' => $menuType,
               'message' => 'Menu Type found successfully',
               'success' => true
           ];
           return response()->json($result);
       } else {
           $result = [
               'message' => 'Menu Type not found',
               'success' => false
           ];
           return response()->json($result);
       }
    }
    
}