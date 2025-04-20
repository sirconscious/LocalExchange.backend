<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
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
            "phone" => ['required', 'string', 'max:255'], 
            "city" => ['required', 'string', 'max:255'],
        ]) ; 
        
        if ($request->hasFile('image')) {
            $credentials["image"] = $request->file("image")->store("users",'public') ;

        }
        $user = User::create($credentials); 
        $adminrole = Role::find(2)  ;
        $user->roles()->attach($adminrole->id) ;
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
            "user" => $user , 
            "Profile_image" => asset("storage/$user->image") 
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
