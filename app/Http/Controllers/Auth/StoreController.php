<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
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


    public function destroy(Store $store)
    {
        //
    }


    /**
     * Customer can become a vendor
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     *
     */
    public function becomeVendor(Request $request)
    {
        $user = Auth::user();
        $storeRequest =StoreRequest::create($request);

        $validator = Validator::make($request->all(), $storeRequest->rules());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $new_store = new Store(
                [
                    'user_id' => $user->id,
                    'name' => $request->name,
                    'description' => $request->description,
                    'category_id' => $request->category,
                    'address' => '$request->address',
                    'email'=>'r@r.r'  ,
                    'phone'=>'123456789',

                ]
            );
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $path = $image->store('images/stores', 'public');
                $new_store->image = $path;
                Log::info('path: ' . $path);
                Log::info('image: ' . $new_store->image);
            }


            DB::transaction(function () use ($request, $user, $new_store) {



                $new_store->save();
//                $user->role_id = 2;
                $user->update([
                    'role_id' => 2

                ]);


            });
            return response()->json(['message' => 'You are now a vendor'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
}
