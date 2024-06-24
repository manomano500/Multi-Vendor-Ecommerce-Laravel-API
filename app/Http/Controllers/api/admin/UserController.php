<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role_id',1)->paginate(10);
        return response()->json($users, 200);
    }
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',

        ]);
      if ($validated->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validated->errors()], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->email),
                'role_id' => 1,
            ]);

            return response()->json($user, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'User registration failed!', 'error'=>$e->getMessage()], 409);
        }
        // Create a new user




    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if (!$user) {
            return response()->json(['message' => 'User not found!'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'User deleted successfully!'], 200);
    }
}
