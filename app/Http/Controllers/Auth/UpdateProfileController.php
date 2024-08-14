<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

use App\Models\Store;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
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


class UpdateProfileController extends Controller
{
    public function updateProfile(Request $request): JsonResponse
    {
        // Get the authenticated user.
        $user = Auth::user();

        // Create an array to hold validation rules, but only for fields that are present.
        $rules = [];
        if ($request->has('name')) {
            $rules['name'] = ['required', 'string', 'max:255', 'min:3'];
        }
        if ($request->has('phone')) {
            $rules['phone'] = ['required', 'string'];
        }

        // Validate the incoming request data.
        $validator = Validator::make($request->all(), $rules);

        // If the validation fails, return a JSON response with the validation errors.
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Update the user's name and/or phone number, depending on what was provided.
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }
        $user->save();

        // Return a JSON response with the updated user data.
        return response()->json([
            'message' => 'data updated successfully',
            'user' => $user
        ], 200);
    }

}
