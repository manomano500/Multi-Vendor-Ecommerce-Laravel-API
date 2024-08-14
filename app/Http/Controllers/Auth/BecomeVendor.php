<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BecomeVendor extends Controller
{



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
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }



    public function updateStore(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'name' => ['sometimes', 'required', 'string', 'max:255', 'min:3'],
            'phone' => ['sometimes', 'required', 'string'],
            'store_name' => ['sometimes', 'required', 'string', 'max:255'],
            'store_description' => ['sometimes', 'nullable', 'string'],
            'store_address' => ['sometimes', 'required', 'string', 'max:255'],
            'store_image' => ['sometimes', 'nullable', 'image'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::transaction(function () use ($request, $user) {
                // Update user information if provided
                $user->update($request->only('name', 'phone'));

                // Update or create store information if provided
                if ($user->store) {
                    $user->store->update($request->only('store_name', 'store_description', 'store_address'));
                } else {
                    $storeData = $request->only('store_name', 'store_description', 'store_address');
                    $storeData['user_id'] = $user->id;
                    $new_store = Store::create($storeData);
                    $user->store()->save($new_store);
                }

                // Handle store image upload if provided
                if ($request->hasFile('store_image')) {
                    $image = $request->file('store_image');
                    $path = $image->store('images/stores', 'public');
                    $user->store->update(['image' => $path]);
                }
            });

            return response()->json(['message' => 'Profile and store information updated successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
