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
}
