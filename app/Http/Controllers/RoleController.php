<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Contracts\Validation\Validator;

class RoleController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'role_name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',

        ]);
        if (Role::where('email', $request->email)->first()) {
            return response([
                'message' => 'Email Already exists',
                'status' => 'failed'
            ], 200);
        }
        $role = Role::create([
            'role_name' => $request->role_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $token = $role->createToken($request->role_name)->plainTextToken;
        return response([
            'token' => $token,
            'message' => 'User Register Succesfully',
            'status' => 'Success'
        ], 201);
    }
    public function login(Request $request)
    {
        $role = Role::where('role_name', $request->role_name)->first();
        if (['role_name' => $request->role_name]) {
            if ($role && Hash::check($request->password, $role->password)) {
                $token = $role->createToken($request->role_name)->plainTextToken;
                return response([
                    'token' => $token,
                    'message' => ' Logged in Succesfully',
                    'status' => 'Success'
                ], 201);
                return view('homepage');
            } else {
                return response([
                    'message' => 'failed',
                    'status' => 'Failed'
                ], 401);
            }
        }
    }
    public function logout(Request $request)
    {
        $request->role()->currentAccessToken()->delete();
        return response([
            'message' => 'Logout',
            'status' => 'Success'
        ], 200);
    }
}
