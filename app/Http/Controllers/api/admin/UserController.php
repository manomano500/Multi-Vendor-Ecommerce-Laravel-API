<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UsersResource;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
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

//        $users = $query::with('role')->get();
        $users =User::filter(request()->query())->with('role')->paginate(10);
        return UsersResource::collection($users);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        if (!$user) {
            return response()->json(['message' => 'User not found!'], 404);
        }
        return response()->json( $user, 200);
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
                'phone' => null,
                'address' => null,
                'password' => bcrypt($request->email),
                'role_id' => $request->role_id
            ]);
            event(new Registered($user));
            if ($request->role_id == 2) {
              $store=  $user->store()->create([
                    'name' => $request->store_name,
                    'category_id' => $request->category,
                    'user_id' => $user->id,
                  'address' => null,
                  'description'=>null,
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

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if (!$user) {
            return response()->json(['message' => 'User not found!'], 404);
        }
        $validated = Validator::make($request->all(), [
            'name' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'phone' => 'sometimes|required|string',
            'address' => 'sometimes|required|string',
            'role_id' => 'sometimes|required|integer|in:1,2,3',
            'store_name' => 'sometimes|required_if:role,vendor|string|max:255',
            'category' => 'sometimes|required_if:role,vendor|string|max:255|exists:categories,id',
        ]);
        if ($validated->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validated->errors()], 422);
        }
        try {
            DB::beginTransaction();
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'role_id' => $request->role_id
            ]);
            $user->save();
            if ($request->role_id == 2) {
                $user->store()->update([
                    'name' => $request->store_name,
                    'category_id' => $request->category,
                ]);
            }
            DB::commit();
            return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'User update failed!', 'error' => $e->getMessage()], 409);
        }

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
