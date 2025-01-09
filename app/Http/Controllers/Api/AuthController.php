<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AuthController extends Controller
{
    public function login(Request $request) {
        try {
            $user = User::where('email', $request->email)->first();
            if ($user && Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Login Success',
                    'data' => $user->createToken(
                            'token-name', ['*'], now()->addWeek()
                        )->plainTextToken
                ], 200);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'Email or Password is incorrect',
                ], 200);
            }
        } catch (Throwable $error) {
            return response()->json([
                'status' => 500,
                'message' => $error->getMessage(),
            ], 500); 
        }
    }

    public function logout(Request $request) {
        try {
            Auth::guard('sanctum')->user()->currentAccessToken()->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Success Logout User',
            ], 200);
        } catch (Throwable $error) {
            return response()->json([
                'status' => 500,
                'message' => $error->getMessage(),
            ], 500); 
        }
    }
}
