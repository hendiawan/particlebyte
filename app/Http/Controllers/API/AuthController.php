<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{ 
    public function register(Request $request)
    {
        //
        $request->validate([
            'name'=>'required|string|max:200',
            'email'=>'required|email|string|max:200|unique:users',
            'password'=>'required|string|min:8'
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);
        $token = $user->createToken($request->email)->plainTextToken; 

        return response()->json([
            'data'=>$user,
            'access_token'=>$token,
            'token_type'=>'Bearer',
        ]);


        
    }
    public function login(Request $request){
        $request->validate([ 
            'email'=>'required|email|string|max:200',
            'password'=>'required|string|min:8'
        ]);

        if (!Auth::attempt(
            $request->only('email','password')
        )) {
            return response()->json([
                'message'=>'Unauthorized'
            ],401);
        } else {
            # code...
        }
        
        $user = User::where('email',$request->email)->firstOrFail();
        $token = $user->createToken($request->email)->plainTextToken;

        return response()->json([
            'data'=>$user,
            'access_token'=>$token,
            'token_type'=>'Bearer',
        ]); 
    }

    public function logout(Request $request){ 
       $request->user()->tokens()->delete(); 
       return response()->json([
        'msg'=>'anda berhasil logout'
       ]);

    }

}
