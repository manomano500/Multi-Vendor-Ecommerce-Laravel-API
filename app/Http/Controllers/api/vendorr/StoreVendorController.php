<?php

namespace App\Http\Controllers\api\vendorr;

use App\Http\Controllers\Controller;
use App\Http\Resources\StoreResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StoreVendorController extends Controller
{


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

        $store = Auth::user()->store;

        $validator = Validator::make($request->all(), [
            'name' => 'string', 'max:255',
            'description' => 'nullable', 'string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5048',
            'status' => 'string|in:active,inactive'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
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


            $store->update($updatedFields);
            return response()->json(['message' => 'Store updated successfully', 'data' => new StoreResource($store)], 200);


        } catch (\Exception $e) {
            return response()->json(['error' => $validator->errors()], 500);
        }

        //
    }


}
