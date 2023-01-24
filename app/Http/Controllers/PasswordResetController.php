<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PasswordResetController extends Controller
{
    public function send_reset_password_email(Request $request)
    {
        $request->validate([
            'email' => 'required',
        ]);
        $email = $request->email;
        //check user email is exists or not
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response([
                'message' => 'Email doest exists',
                'status' => 'failed'
            ], 400);
        }
        //generate Token
        $token = Str::random(60);

        PasswordReset::create([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);


        // dump("http://127.0.0.1:3000/api/user/reset" . $token);
        //sedning emial with password reset view

        Mail::send('reset', ['token' => $token], function (Message $message) use ($email) {
            $message->subject('Reset Your Password');
            $message->to($email);
        });
        return response([
            'message' => 'Password Reset Email sent...Check Yoour Mail.',
            'status' => 'success'
        ], 200);
    }
    public function reset(Request $request, $token)
    {
        //Delete Token older Than 1 minute
        $formatted = Carbon::now()->subMinute(1)->toDateTimeLocalString();
        PasswordReset::where('created_at', '<=', $formatted)->delete();
        $request->validate([
            'password' => 'required|confirmed'
        ]);
        $passwordreset = PasswordReset::where('token', $token)->first();
        if (!$passwordreset) {
            return response([
                'message' => 'Token is invalida or Expired',
                'status' => 'failed'
            ], 404);
        }
        $user = User::where('email', $passwordreset->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        //delete the token after resetting password
        PasswordReset::where('email', $user->email)->delete();
        return response([
            'message' => 'Password Reset successfully',
            'status' => 'Success'
        ], 200);
    }
}
