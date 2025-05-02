<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        // Role::create([
        //     "name" => "user"
        // ]);
        // Role::create([
        //     "name" => "admin"
        // ]);
     
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

    public function updateUser(Request $request) {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['sometimes', 'string', 'max:255'],
            'city' => ['sometimes', 'string', 'max:255'],
        ]);

        $user->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }

    public function updatePassword(Request $request) {
        $user = auth()->user();
        
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!password_verify($validated['current_password'], $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Current password is incorrect'
            ], 422);
        }

        $user->update([
            'password' => bcrypt($validated['password'])
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Password updated successfully'
        ]);
    }

    public function updateProfileImage(Request $request) {
        $user = auth()->user();
        
        $request->validate([
            'image' => ['required', 'image', 'max:2048'] // max 2MB
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            
            $path = $request->file('image')->store('users', 'public');
            $user->update(['image' => $path]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Profile image updated successfully',
            'image_url' => asset('storage/' . $user->image)
        ]);
    }

    public function deleteUser() {
        $user = auth()->user();
        
        // Delete user's image if exists
        if ($user->image) {
            Storage::disk('public')->delete($user->image);
        }
        
        // Delete user's tokens
        $user->tokens()->delete();
        
        // Delete the user
        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}
