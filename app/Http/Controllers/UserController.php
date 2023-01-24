<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
            'tc' => 'required',

        ]);
        if (User::where('email', $request->email)->first()) {
            return response([
                'message' => 'Email Already exists',
                'status' => 'failed'
            ], 200);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tc' => json_decode($request->tc),
        ]);
        $token = $user->createToken($request->email)->plainTextToken;
        return response([
            'toke' => $token,
            'message' => 'User Register Succesfully',
            'status' => 'Success'
        ], 201);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken($request->email)->plainTextToken;
            return response([
                'toke' => $token,
                'message' => 'User Logged in Succesfully',
                'status' => 'Success'
            ], 201);
        } else {
            return response([
                'message' => 'failed',
                'status' => 'Failed'
            ], 401);
        }
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response([
            'message' => 'Logout',
            'status' => 'Success'
        ], 200);
    }
    public function logged_user()
    {
        $loggeduser = auth()->user();
        return response([
            'user' => $loggeduser,
            'message' => 'Logged_user',
            'status' => 'Success'
        ], 200);
    }
    public function change_password(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed',
        ]);

        $loggeduser = auth()->user();
        $loggeduser->password = Hash::make($request->password);
        $loggeduser->save();
        return response([
            'user' => $loggeduser,
            'message' => 'change Password Successfully',
            'status' => 'Success'
        ], 200);
    }
}
