<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductImageRequest;
use App\Http\Resources\ProductImageResource;
use App\Models\ProductImage;

class ProductImageController extends Controller
{
    public function index()
    {
        return ProductImageResource::collection(ProductImage::all());
    }

    public function store(ProductImageRequest $request)
    {
        return new ProductImageResource(ProductImage::create($request->validated()));
    }

    public function show(ProductImage $productImage)
    {
        return new ProductImageResource($productImage);
    }

    public function update(ProductImageRequest $request, ProductImage $productImage)
    {
        $productImage->update($request->validated());

        return new ProductImageResource($productImage);
    }

    public function destroy(ProductImage $productImage)
    {
        $productImage->delete();

        return response()->json();
    }
}
