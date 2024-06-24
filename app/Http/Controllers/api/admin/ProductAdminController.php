<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\admin\ProductAdminResource;

use App\Models\Product;

class ProductAdminController extends Controller
{
    public function index()
    {

        $products = Product::paginate(10);
        return  ProductAdminResource::collection($products);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if(!$product){
            return response()->json(['message' => 'Product not found'], 404);
        }
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully'], 200);
    }

}
