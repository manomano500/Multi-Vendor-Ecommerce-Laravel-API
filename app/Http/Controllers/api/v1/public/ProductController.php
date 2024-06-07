<?php

namespace App\Http\Controllers\api\v1\public;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\Product\ProductVendorAllCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)

    {
        $query = Product::query();

        // Iterate over each request parameter and add conditions to the query
        foreach ($request->all() as $key => $value) {
            $query->where($key, $value);
        }

        // Eager load the category relationship to avoid N+1 problem
        $query->with('category');

        // Execute the query and get the products
        $products = $query->get();


        // If no products are found, return an empty response
        if ($products->isEmpty()) {
            return response()->json(['message'=>'no products found'], 200); // 200 OK with an empty array
        }

        return new ProductVendorAllCollection($products);
    }


    public function show($id)
    {
        try {
            $product = Product::with('variations.attribute')->findOrFail($id);
            return response()->json(['product' => new ProductResource($product)], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Product not found', 'error' => $e->getMessage()], 404);
        }
    }




}
