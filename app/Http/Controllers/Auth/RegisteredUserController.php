<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'name' => ['bail','required', 'string', 'max:255','min:3',],
            'email' => ['bail','required', 'string', 'lowercase', 'email', 'max:255','unique:' . User::class],
            'password' => ['bail','required', 'confirmed','min:8', Rules\Password::defaults(),],
            "address" => "required|string",
            "phone" => "required|string|min:10|max:15",
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => 3,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
            event(new Registered($user));
            Auth::login($user);

            $token = $user->createToken('api-token')->plainTextToken;
            return response()->json([
                'message' => 'Registration successful',
                'user' => $user,
                'token' => $token
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 504);
        }


    }







}
