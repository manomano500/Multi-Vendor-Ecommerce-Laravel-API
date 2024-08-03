<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $roleId = $request->query('role_id');

        $query = User::query();

        if ($roleId) {
            $query->where('role_id', $roleId);
        }

        $users = $query->get();

        return response()->json($users);
    }
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'role_id' => 'required|integer|in:1,2,3',
            'store_name' => 'required_if:role,vendor|string|max:255',
            'category' =>'required_if:role,vendor|string|max:255|exists:categories,id',


        ]);
      if ($validated->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validated->errors()], 422);
        }



        try {
          DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->email),
                'role_id' => $request->role_id
            ]);
            if ($request->role_id == 2) {
              $store=  $user->store()->create([
                    'name' => $request->store_name,
                    'category_id' => $request->category,
                    'user_id' => $user->id,
                  'address' => "request->address",
                  'description'=>"request->description",
                ]);
            }
DB::commit();
            return response()->json(['message' =>'User Created Successfully','user'=>$user], 201);
        } catch (Exception $e) {
          DB::rollBack();
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
