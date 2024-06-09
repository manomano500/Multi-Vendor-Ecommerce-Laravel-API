<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\StoreResource;
use App\Models\Store;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::paginate(10);
        if ($stores->isEmpty()) {
            return response()->json(['message' => 'no stores found'], 200);
        }
        return response()->json(['data' => StoreResource::collection($stores)]);
    }

    public function showProducts($id)

    {
        $store = Store::with(['products'])->find($id);

        if (!$store) {
            return response()->json(['message' => 'store not found'], 404);
        }

        return response()->json(['data' => new StoreResource($store)]);
    }
}
