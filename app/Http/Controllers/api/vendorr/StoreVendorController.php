<?php

namespace App\Http\Controllers\api\vendorr;

use App\Http\Controllers\Controller;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use App\Services\StoreService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StoreVendorController extends Controller
{

    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
    }

    public function show(Request $request)
    {
        $store = $request->user()->store; // Assuming 'store' is a relationship method in the User model

        if (!$store) {
            return response()->json(['message' => 'No store found for this user'], 404);
        }
        return new StoreResource($store);

    }

    public function update(Request $request)
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

        $user = Auth::user();
        $store = Store::findOrFail($user->store->id);

        if ($store->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized access to store'], 403);
        }

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


    public function destroy()
    {
        $user = Auth::user();
        $store = $user->store;

        if (!$store) {
            return response()->json(['message' => 'No store found for the user'], 404);
        }

        try {
            $store->delete();

            $user->role_id = 3;
            $user->save();

            return response()->json(['message' => 'Store deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while deleting the store', 'error' => $e->getMessage()], 500);
        }
    }

}
