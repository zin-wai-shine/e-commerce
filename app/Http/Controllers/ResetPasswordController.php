<?php

namespace App\Http\Controllers;

use Cassandra\Exception\ValidationException;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{

    public function forgotPassword(Request $request){

        $request->validate([

            'email' => 'required|email|exists:users'

        ]);

        $status = Password::sendResetLink(

            $request->only('email')

        );

        if($status === Password::RESET_LINK_SENT){

            return response()->json([

                'message' => 'we have emailed your password reset link!'

            ]);

        }

        throw ValidationException::withMessages([

            'email' => [trans($status)]

        ]);

    }



    public function resetPassword(Request $request){

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(

            $request->only('email', 'password', 'password_confirmation', 'token'),

            function ($user, $password) {

                $user->forceFill([

                    'password' => Hash::make($password)

                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));

            }

        );

        if($status === Password::PASSWORD_RESET){

            return response()->json([

               'message' => 'password reset successfully'

            ]);

        }

        return response()->json([

            'message' => __($status)

        ], 500);

    }


}
