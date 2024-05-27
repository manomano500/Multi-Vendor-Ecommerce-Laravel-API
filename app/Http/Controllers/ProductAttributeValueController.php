<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductAttributeValueRequest;
use App\Http\Resources\ProductAttributeValueResource;
use App\Models\ProductAttributeValue;

class ProductAttributeValueController extends Controller
{
    public function index()
    {
        return ProductAttributeValueResource::collection(ProductAttributeValue::all());
    }

    public function store(ProductAttributeValueRequest $request)
    {
        return new ProductAttributeValueResource(ProductAttributeValue::create($request->validated()));
    }

    public function show(ProductAttributeValue $productAttributeValue)
    {
        return new ProductAttributeValueResource($productAttributeValue);
    }

    public function update(ProductAttributeValueRequest $request, ProductAttributeValue $productAttributeValue)
    {
        $productAttributeValue->update($request->validated());

        return new ProductAttributeValueResource($productAttributeValue);
    }

    public function destroy(ProductAttributeValue $productAttributeValue)
    {
        $productAttributeValue->delete();

        return response()->json();
    }
}
