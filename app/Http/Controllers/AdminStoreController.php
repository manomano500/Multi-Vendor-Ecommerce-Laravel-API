<?php

namespace App\Http\Controllers;

use App\Http\Resources\AdminStoreResource;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use Illuminate\Http\Request;

class AdminStoreController extends Controller
{

    public function index()
    {
        $stores = Store::paginate(10);
        if ($stores->isEmpty()) {
            return response()->json(['message' => 'no stores found'], 200);
        }
        return AdminStoreResource::collection($stores);
    }
}
