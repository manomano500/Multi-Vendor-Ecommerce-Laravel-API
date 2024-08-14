<?php

namespace App\Http\Controllers\api\public;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductVendorAllCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index(Request $request)

    {
        $products = Product::filter($request->query())->with('category')->get();
        Log::info('products: ' . $products);
        return new ProductVendorAllCollection($products);
    }


    public function show($id)
    {
        try {
            $product = Product::status('active')->findOrFail($id);
            return  new ProductResource($product->load('category'));
        } catch (Exception $e) {
            return response()->json(['message' => 'Product not found', 'error' => $e->getMessage()], 404);
        }
    }




}
