<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => 3
            ]);
            event(new Registered($user));
            Auth::login($user);
            $token = $user->createToken('api-token')->plainTextToken;
            return response()->json([
                'message' => 'Registration successful',
                'user' => $user,
                'token' => $token
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 504);
        }


    }


    public function becomeVendor(Request $request )
    {
        $user = Auth::user();
        if (Auth::user()->role_id == 2 ) {

            return response()->json(['error' => 'You are already a vendor'], 403);
        }
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['required', 'string','exists:categories,id'],
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            DB::transaction(function () use ($request, $user) {
                $user->role_id = 2;
                $user->save();
                Store::create([
                    'user_id' => $user->id,
                    'name' => $request->name,
                    'description' => $request->description,
                    'category_id' => $request->category,
                ]);
                return response()->json(['message' => 'You are now a vendor'], 200);
            });
        }catch (\Exception $e) {
            return response()->json(['error' => $validator->errors()], 500);
        }

    }
}
