<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ForgotPasswordRequest;
use App\Mail\PasswordResetMail;
use App\Models\PasswordReset;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function forgotPassword(ForgotPasswordRequest $request)
    {

        // Check if email is valid
        $email = $request->input('email');

        if(User::where('email', $email)->doesntExist())
        {
            return response()->json(["message" => "User doesn't exist"], 404);
        }

        // Generate a new token and save a new password reset entry
        $token = Str::random(10);

        if($passwordReset = PasswordReset::where('email', $email)->first())
        {
            // Send an email
            Mail::to($email)->send(new PasswordResetMail(["token" => $passwordReset->token]));
            return response()->json(["message" => "Check your email address"]);
        }

        $token = Str::random(10);

        try {
            $new_password_reset = new PasswordReset();
            $new_password_reset->email = $email;
            $new_password_reset->token = $token;
            $new_password_reset->setCreatedAt(now());
            $new_password_reset->save();

            // Send an email
            Mail::to($email)->send(new PasswordResetMail(["token" => $token]));

            return response()->json(["message" => "Check your email address"]);
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], 400);
        }
    }
}
