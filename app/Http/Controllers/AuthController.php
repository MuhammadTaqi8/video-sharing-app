<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AuthenticationToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // User registration
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:Users,Username',
            'email' => 'required|email|max:100|unique:Users,Email',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:Roles,RoleID', // Ensure valid role
        ]);

        $user = User::create([
            'Username' => $request->username,
            'Email' => $request->email,
            'PasswordHash' => Hash::make($request->password),
            'RoleID' => $request->role_id,
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
        ], 201);
    }

    // User login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('Email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->PasswordHash)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Generate a token
        $token = Str::random(80);
        $expiry = now()->addDays(7); // Token valid for 7 days

        AuthenticationToken::create([
            'UserID' => $user->UserID,
            'Token' => $token,
            'Expiry' => $expiry,
        ]);

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ]);
    }

    // User logout
    public function logout(Request $request)
    {
        $request->validate([
            'token' => 'required',
        ]);

        $token = AuthenticationToken::where('Token', $request->token)->first();

        if (!$token) {
            return response()->json(['message' => 'Invalid token'], 400);
        }

        $token->delete();

        return response()->json(['message' => 'Logout successful']);
    }
}
