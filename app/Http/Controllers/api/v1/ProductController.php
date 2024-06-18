<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductVendorAllCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index(Request $request)

    {
        $products = Product::status('active')->with('category')->get();
        Log::info('products: ' . $products);
        return new ProductVendorAllCollection($products);
    }


    public function show($id)
    {
        try {
            $product = Product::status('active')->with('variations.attribute')->findOrFail($id);
            return response()->json(['product' => new ProductResource($product)], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Product not found', 'error' => $e->getMessage()], 404);
        }
    }




}
