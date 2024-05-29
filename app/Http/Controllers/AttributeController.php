<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttributeRequest;
use App\Http\Resources\AttributeResource;
use App\Models\Attribute;
use App\Models\AttributeValue;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::with('values')->get();
        return response()->json($attributes);
    }



    public function update(AttributeRequest $request, Attribute $attribute)
    {
        $attribute->update($request->validated());

        return new AttributeResource($attribute);
    }

    public function destroy(Attribute $attribute)
    {
        $attribute->delete();

        return response()->json();
    }
}
