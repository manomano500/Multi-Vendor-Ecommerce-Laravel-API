<?php

namespace App\Http\Controllers\api\public;

use App\Http\Controllers\Controller;
use App\Http\Resources\StoreResource;
use App\Models\Store;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::filter(request()->query())->with('category')->paginate(10);
        if ($stores->isEmpty()) {
            return response()->json(['message' => 'no stores found'], 200);
        }
        return  StoreResource::collection($stores);
    }

    public function showProducts($id)
    {
        $store = Store::status('active')->find($id);

        if (!$store) {
            return response()->json(['message' => 'Store not found'], 404);
        }

        // Get active products for the store and paginate the results
        $products = $store->products()->status('active')->paginate(10);

        return response()->json(['data' => new StoreResource($store->load(['products' => function ($query) {
            $query->status('active');
        }]))]);
    }
}
