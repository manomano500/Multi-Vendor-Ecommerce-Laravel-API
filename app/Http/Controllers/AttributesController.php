<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttributesRequest;
use App\Http\Resources\AttributesResource;
use App\Models\Attribute;

class AttributesController extends Controller
{
    public function index()
    {
        return AttributesResource::collection(Attribute::all());
    }

    public function store(AttributesRequest $request)
    {
        return new AttributesResource(Attribute::create($request->validated()));
    }

    public function show(Attribute $attributes)
    {
        return new AttributesResource($attributes);
    }

    public function update(AttributesRequest $request, Attribute $attributes)
    {
        $attributes->update($request->validated());

        return new AttributesResource($attributes);
    }

    public function destroy(Attribute $attributes)
    {
        $attributes->delete();

        return response()->json();
    }
}
