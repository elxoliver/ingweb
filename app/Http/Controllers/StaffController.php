<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class StaffController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        try{
            if(! $token = JWTAuth::attempt($credentials))
                return response()->json(['error' => 'Invalid Credentials'], 400);
            
        }catch(JWTException $e){
            return response()->json(['error' => 'Could not create a token'], 500);
        }
        return response()->json(compact('token'), 200);
    }

    public function getAuthenticatedUser()
    {
        try {
            if(!$user=JWTAuth::parseToken()->authenticate())
                return response()->json(['error' => 'User not found', 404]);

        } catch (TokenExpiredException $e) {
                return response()->json(['token_expired'], 419);
        } catch (TokenInvalidException $e) {
                return response()->json(['token_invalid'], 400);
        } catch (JWTException $e) {
                return response()->json(['token_absent'], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate($request->token);
            return response()->json(['message' => 'Logout successfully'], 200);
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'An error has ocurred'
            ], 500);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'=>'required|string|max:45',
            'last_name'=>'required|string|max:45',
            'picture'=>'string',
            'email'=>'required|email|unique:staff',
            'store_id'=>'required|integer',
            'active'=>'required|boolean',
            'username'=>'required|string|max:16|unique:staff',
            'password'=>'required|string|min:8|max:16',
            'password_confirmation'=>'required|same:password'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        $staff = Staff::create([
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'picture' => $request->get('picture'),
            'email' => $request->get('email'),
            'store_id' => $request->get('store_id'),
            'active' => $request->get('active'),
            'username' => $request->get('username'),
            'password' => $request->get('password'),
            'address_id' => 1
        ]);

        $token = auth()->login($staff);

        return response()->json(compact('staff', 'token'), 201);
    }

    public function register_staff(Request $request, $store_id)
    {
        $staff = JWTAuth::parseToken()->authenticate();
        if(!$staff)
            return response()->json(['error'=>'User not found'], 404);

        if(!($staff->admin))
            return response()->json(['error'=>'User is not admin'], 400);

        $validator = Validator::make($request->all(), [
            'first_name'=>'required|string|max:45',
            'last_name'=>'required|string|max:45',
            'picture'=>'string',
            'email'=>'required|email|unique:staff',
            'store_id'=>'required|integer',
            'active'=>'required|boolean',
            'username'=>'required|string|max:16|unique:staff',
            'password'=>'required|string|min:8|max:16',
            'password_confirmation'=>'required|same:password'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        $newStaff = Staff::create([
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'picture' => $request->get('picture'),
            'email' => $request->get('email'),
            'active' => $request->get('active'),
            'username' => $request->get('username'),
            'password' => $request->get('password'),
            'address_id' => 1
        ]);
        if(!($store = Store::find($store_id)))
            return response()->json(['error'=>'Store not found'], 400);
            
        $store->staff_members()->save($staff);
        return response()->json(['staff'=>$newStaff], 201);
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
