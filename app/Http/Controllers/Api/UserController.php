<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        $users = User::all() ;
        return response()->json($users);
    }
    public function register(Request $request) {
        $credentials = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            "email" => ['required', 'email', 'max:255', 'unique:users'],
            "password" => ['required', 'string', 'min:8', 'confirmed'] ,
        ]) ; 
        User::create($credentials); 
        return response()->json([
            "status" => true , 
            "message" => "User created successfully" 
        ]);
    }
    public function login(Request $request) { 
        $credentials = $request->validate([
            "email" => ['required', 'email'],
            "password" => ['required']
        ]) ;
        $user = User::where("email" , $credentials["email"])->first(); 
        if($user) {
            if(password_verify($credentials["password"], $user->password)) {
                $token = $user->createToken("auth_token")->plainTextToken;  
                return response()->json([
                    "status" => true , 
                    "message" => "User logged in successfully" , 
                    "token" => $token
                ]);
            }
            return response()->json([
                "status" => false , 
                "message" => "Password is incorrect"
            ]);
        }
        return response()->json([
                "status" => false , 
                "message" => "This credentials does not match our records"   
            ]) ;

    } 
    public function getUser(){
        $user = auth()->user();
        return response()->json([
            "status" => true , 
            "message" => "User logged in successfully" , 
            "user" => $user
        ]);

    } 
    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            "status" => true , 
            "message" => "User logged out successfully"
        ]);
    }
}
