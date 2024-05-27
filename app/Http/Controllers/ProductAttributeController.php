<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductAttributeRequest;
use App\Http\Resources\ProductAttributeResource;
use App\Models\ProductAttribute;

class ProductAttributeController extends Controller
{
    public function index()
    {
        return ProductAttributeResource::collection(ProductAttribute::all());
    }

    public function store(ProductAttributeRequest $request)
    {
        return new ProductAttributeResource(ProductAttribute::create($request->validated()));
    }

    public function show(ProductAttribute $productAttribute)
    {
        return new ProductAttributeResource($productAttribute);
    }

    public function update(ProductAttributeRequest $request, ProductAttribute $productAttribute)
    {
        $productAttribute->update($request->validated());

        return new ProductAttributeResource($productAttribute);
    }

    public function destroy(ProductAttribute $productAttribute)
    {
        $productAttribute->delete();

        return response()->json();
    }
}
