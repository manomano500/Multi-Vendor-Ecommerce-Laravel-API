<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttributeResource;
use App\Models\Attribute;
use Illuminate\Support\Facades\Log;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::with('variations')->get();
        Log::info($attributes);
        return AttributeResource::collection($attributes);
    }




    public function destroy(Attribute $attribute)
    {
        $attribute->delete();

        return response()->json();
    }
}
