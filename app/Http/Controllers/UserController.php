<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRegisterRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Registering a user
    public function register(UserRegisterRequest $request)
    {
            $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        // Return the response with the created user and success message
        return response()->json([
            'data' => $user,
            'message' => 'User registered successfully',
        ], 201);
    }

    // Login a user
    public function login(UserRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (! Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'The email or password is incorrect, please try again'], 422);
        } else {
            return response()->json([
                'data' => $user,
                'message' => 'user logged in',
                'token' => $user->createToken('Api-token')->plainTextToken,
                'status' => '1',
            ]);
        }
    }

    // Logout a user
    function logout(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        $user->tokens()->delete();
        return response()->json(['success' => 'Logged Out Successfully!']);
    }
}