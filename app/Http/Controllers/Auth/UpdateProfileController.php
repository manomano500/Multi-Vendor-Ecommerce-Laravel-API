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

        // Validate the incoming request data.
        $validator = Validator::make($request->all(), [
            'name' => ['sometimes', 'required', 'string', 'max:255', 'min:3'],
            'phone' => ['sometimes', 'required', 'string'],

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->fill($request->all())->save();

        $user->save();

        // Return a JSON response with the updated user data.
        return response()->json([
            'message' => 'data updated successfully',
            'user' => $user
        ], 200);
    }

}
