<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpseclib3\Crypt\Hash;

class authapicontroller extends Controller
{
    public function register(Request $request){

        $user=User::where('email',$request->email)->first();
        if($user){
            return response()->json([
                'success'=>true,
                'message'=>'Email already exists'
            ]);

        }
          User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>\Illuminate\Support\Facades\Hash::make($request->password),

        ]);
       return response()->json([
           'success'=>true,
           'token'=>$user->createToken($user->email)->accessToken
       ]);
    }
    public function login(Request $request){
        $credentials=$request->only('email','password');
        $users=User::where('email',$credentials['email'])->first();
        if(!$users){
            return response()->json([
               'success'=>false,
                'message'=>'Invalid email'
            ],401);

        }

        if(Auth::attempt($credentials)){
            return response()->json([
                'success'=>true,
                'token'=>$users->createToken($users->email)->accessToken
            ],200);
        }
        return response()->json([
            'success'=>false,
            'message'=>'Invalid email or password'
        ],401);
    }
}
