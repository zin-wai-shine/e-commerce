<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{


    public function sendVerificationEmail(Request $request){

        /*return response()->json($request->token);*/

        if($request->user()->hasVerifiedEmail()){

            return response()->json([ "message" => "Already Verified", "verified" => true ]);

        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json([ "message" => "verification link was send", "send" => true ]);

    }



    public function verify(Request $request){


        auth()->loginUsingId($request->route('id'));

        if($request->route('id') != $request->user()->getKey()){
            throw new AuthorizationException;
        }

        if($request->user()->hasVerifiedEmail()){

            return redirect()->away('http://localhost:3000/already-verified');
            /*            return response()->json([ "message" => "Already Verified", "verified" => true ],422);*/
        }

        if($request->user()->markEmailAsVerified()){
            event(new Verified($request->user()));
        }

        return redirect()->away('http://localhost:3000/verified');

        /*        return response()->json(['message' => 'email has been verified', 'verified' => true]);*/

    }



}
