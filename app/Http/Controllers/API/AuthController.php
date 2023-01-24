<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
// use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController as BaseController;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);
        if ($validation->fails()) {
            $response = [
                'success' => false,
                'message' => $validation->errors()
            ];
            return response()->json($response, 408);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('Myapp')->plainTextToken;
        $success['name'] = $user->name;
        $response = [
            'success' => true,
            'data' => $success,
            'message' => 'User Register Successfully'
        ];
        return response()->json($response);
    }
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            // $success['token'] = $user->createToken('Myapp')->plainTextToken;
            // $success['token'] = $user->createToken('Myapp')->plainTextToken;
            $success['name'] = $user->name;
            $response = [
                'success' => true,
                'data' => $success,
                'message' => 'User Logged in Successfully'
            ];
            return response()->json($response);
        } else {
            $response = [
                'success' => false,
                'message' => 'Unathorized'
            ];
            return response()->json($response);
        }
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['success' => 'Logout Successfully']);
    }
    // public function change_password(Request $request)
    // {
    // $validation = Validator::make($request->all(), [
    //     'password' => 'required|confirmed',
    // ]);
    // $user = auth()->user();
    // $user->password = Hash::make($request->password);
    // $user->save();
    // return response([
    //     'message' => 'Logged User Data',
    //     'status' => 'success',

    // ], 200);
    // }

    //Resourse Controller with authentication
    public function signin(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $authUser = Auth::user();
            $success['token'] =  $authUser->createToken('MyAuthApp')->plainTextToken;
            $success['name'] =  $authUser->name;

            // return $this->sendResponse($success, 'User signed in');
            $response = [
                'success' => true,
                'data' => $success,
                'message' => 'User Loggin Successfully'
            ];
            return response()->json($response);
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {

            return $this->sendError('Error validation', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyAuthApp')->plainTextToken;
        $success['name'] =  $user->name;

        $response = [
            'success' => true,
            'data' => $success,
            'message' => 'User Register Successfully'
        ];
        return response()->json($response);;
    }
}
