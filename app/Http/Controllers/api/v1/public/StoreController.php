<?php

namespace App\Http\Controllers\api\v1\public;

use App\Http\Controllers\Controller;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $query = Store::query();

        foreach ($request->all() as $key => $value) {
            $query->where($key, $value);
        }

        $stores = $query->get()->where('status', 'active');
if ($stores->isEmpty()) {
            return response()->json(['message' => 'no stores found'], 200);
        }
        return response()->json(['data' => StoreResource::collection($stores)]);
    }
}
