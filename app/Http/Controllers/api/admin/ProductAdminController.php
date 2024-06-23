<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductVendorAllCollection;
use App\Http\Resources\Product\ProductVendorAllResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductAdminController extends Controller
{
    public function index()
    {

        $products = Product::paginate(10);
        return  ProductResource::collection($products);
    }
}
