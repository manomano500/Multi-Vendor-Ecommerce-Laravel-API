<?php

namespace App\Http\Controllers\api\admin\stores;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Http\Resources\StoreResource;
use App\Models\Store;

class StoreController extends Controller
{
    public function index()
    {
        return StoreResource::collection(Store::all());
    }

    public function store(StoreRequest $request)
    {
        try {
            $request->validated();
            $store = Store::create($request->validated());
            return new StoreResource($store);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 422);
        }
    }

    public function show(Store $store)
    {

        return new StoreResource($store);
    }

    public function update(StoreRequest $request, Store $store)
    {
        try {
           $request->validated();
            $store->update($request->validated());
            return new StoreResource($store);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 422);

        }
    }

    public function destroy(Store $store)
    {

        $store->delete();

        return response()->json();
    }
}
