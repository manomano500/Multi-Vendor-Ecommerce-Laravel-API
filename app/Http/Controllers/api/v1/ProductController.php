<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\Product\ProductVendorAllCollection;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller
{
    public function index(Request $request)

    {
        $query = Product::query();

        // Iterate over each request parameter and add conditions to the query
        foreach ($request->all() as $key => $value) {
            $query->where($key, $value);
        }

        $products = $query->get();

        // If no products are found, return an empty response
        if ($products->isEmpty()) {
            return response()->json(['message'=>'no products found'], 200); // 200 OK with an empty array
        }

        return new ProductVendorAllCollection($products);
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
