<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if ($user->status !== 'active') {
            throw ValidationException::withMessages([
                'email' => ['Your account is not active.'],
            ]);
        }

        // Delete existing tokens
        $user->tokens()->delete();

        // Create new token
        $token = $user->createToken('auth-token')->plainTextToken;

        // Get user role
        $role = $user->roles->first()?->name;

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'role' => $role,
            ],
            'token' => $token,
            'role' => $role,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        $role = $user->roles->first()?->name;

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'role' => $role,
            ],
        ]);
    }

    public function participantLogin(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'activity_id' => 'required|uuid',
        ]);

        $participant = \App\Models\Participant::where('email', $request->email)->first();

        if (!$participant) {
            throw ValidationException::withMessages([
                'email' => ['Participant not found.'],
            ]);
        }

        // Generate temporary token for participant
        $token = bin2hex(random_bytes(32));

        return response()->json([
            'participant' => [
                'id' => $participant->id,
                'name' => $participant->name,
                'email' => $participant->email,
                'type' => $participant->type,
                'preferred_language' => $participant->preferred_language,
            ],
            'token' => $token,
        ]);
    }
}
