<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Couchbase\ViewException;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

            $credentials = $request->only('email', 'password');
            if (!Auth::attempt($credentials)) {
                return response()->json(['message' => 'Invalid credentials'], 401);

        }

            $user=$request->user();
            $user->tokens()->delete();
            $token = $user->createToken('api-token')->plainTextToken;



        return response()->json([
            'message' => 'Login successful',
            'user'=>$user,
            'token'=>$token
        ], 200);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        try {

            Log::info("$request");
            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return response()->json(['message' => 'Logout successful'], 200);
            }catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
}
