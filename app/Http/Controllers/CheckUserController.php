<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CheckUserController extends Controller
{
    public function register(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
            //'role' => 'in:admin,user,provider'

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
            'role' => $request->role

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
    public function user(Request $request)
    {
        dd('user');
    }
    public function admin(Request $request)
    {
        dd('admin');
    }
    public function provider(Request $request)
    {
        dd('provider');
    }
}
