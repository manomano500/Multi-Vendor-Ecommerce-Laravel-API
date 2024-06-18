<?php

namespace App\Http\Controllers;

use App\Http\Resources\AdminStoreResource;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminStoreController extends Controller
{

    public function index()
    {
        $stores = Store::paginate(10);
        if ($stores->isEmpty()) {
            return response()->json(['message' => 'no stores found'], 200);
        }
        Log::info($stores);
        return  AdminStoreResource::collection($stores->load('category', 'user', 'products', 'orders'));
    }



    public function show($id)
    {
        $store = Store::find($id);
        if (!$store) {
            return response()->json(['message' => 'store not found'], 404);
        }
        return new AdminStoreResource($store->load('category', 'user', 'products', 'orders'));
    }
}
