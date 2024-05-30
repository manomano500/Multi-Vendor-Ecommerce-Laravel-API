<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()

    {

        return new ProductCollection(Product::all());
//        $products = Product::with('attributeValues.attribute')->get();
//        return new ProductCollection($products);
    }

    public function store(ProductRequest $request)
    {
        if (!$request->validated()) {
            return response()->json(['message' => $request->errors()], 400);
        }
        $product =new Product($request->only(
            [
                'name',
                'slug',
                'quantity',
                'thumb_image',
                'category_id',
                'price',
                'status'
            ]));
        $product->store_id = Auth::user()->storeId();
        $product->save();
        return new ProductResource($product);
    }

    public function show($id)
    {
        try {
            $product = Product::with('attributeValues.attribute')->findOrFail($id);
            return response()->json(['product' => new ProductResource($product)], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Product not found', 'error' => $e->getMessage()], 404);
        }
    }

    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return new ProductResource($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json();
    }
}
