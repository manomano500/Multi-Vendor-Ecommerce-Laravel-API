<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\StoreAdminResource;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use App\Models\User;
use App\Services\StoreService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StoreAdminController extends Controller
{


    public function index()
    {
        $stores = Store::whereHas('products')->filter(request()->query())   ->paginate(10);
        if ($stores->isEmpty()) {
            return response()->json(['message' => 'no stores found'], 200);
        }
        Log::info($stores);
        return StoreAdminResource::collection($stores->load('category', 'user', 'products', 'orders'));
    }


    public function show($id)
    {
        $store = Store::findOrFail($id);

        return new StoreAdminResource($store->load('category', 'user', 'products', 'orders'));
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'status' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $store = Store::findOrFail($id);

        $updatedFields = $request->only([
            'name',
            'description',
            'status',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('images/stores', 'public');
            $updatedFields['image'] = $path;
        }

        try {
            $store->update($updatedFields);
            return response()->json(['message' => 'Store updated successfully', 'data' => new StoreResource($store)], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while updating the store', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'status' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required|exists:categories,id',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $store = new Store();
        $store->name = $request->name;
        $store->description = $request->description;
        $store->status = $request->status;
        $store->category_id = $request->category_id;

    }

    public function destroy($id)
    {
        $store = Store::findOrFail($id);
        $user =User::findOrFail($store->user_id);

        $store->delete();

        $user->role_id = 3;
        $user->save();
        return response()->json(['message' => 'Store deleted successfully'], 200);


    }

}
