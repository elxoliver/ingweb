<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Store;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class StoreController extends Controller
{
    public function add_staff(Request $request, $store_id, $staff_id)
    {
        $staff = JWTAuth::parseToken()->authenticate();
        if(!$staff)
            return response()->json(['error'=>'User not found'], 404);

        if(!($staff->admin))
            return response()->json(['error'=>'User is not admin'], 400);

        if(!($store = Store::find($store_id)))
            return response()->json(['error'=>'Store not found'], 400);

        if(!($added_staff = Staff::find($staff_id)))
            return response()->json(['error'=>'Staff member not found'], 404);

        if($added_staff->store)
            return response()->json(['error'=>'Staff member has a store'], 400);

        $store->staff_members()->save($added_staff);
        $added_staff->store()->associate($store);
        $added_staff->save();
        return response()->json(['message'=>'Staff added to store successfully'], 201);
    }

    public function remove_staff(Request $request, $store_id, $staff_id)
    {
        $staff = JWTAuth::parseToken()->authenticate();
        if(!$staff)
            return response()->json(['error'=>'User not found'], 404);

        if(!($staff->admin))
            return response()->json(['error'=>'User is not admin'], 400);

        if(!($store = Store::find($store_id)))
            return response()->json(['error'=>'Store not found'], 400);

        if(!($deleted_staff = Staff::find($staff_id)))
            return response()->json(['error'=>'Staff member not found'], 404);

        if(!$deleted_staff->store)
            return response()->json(['error'=>'Staff member is not in a store'], 400);

        $deleted_staff->store()->dissociate();
        $deleted_staff->save();
        return response()->json(['success'=>'Staff removed successfully'], 201);
    }
}
