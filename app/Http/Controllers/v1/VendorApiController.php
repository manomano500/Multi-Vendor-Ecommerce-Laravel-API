<?php

namespace App\Http\Controllers\v1;


use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductValue;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class VendorApiController extends Controller
{
    public function store(Request $request)
    {
     $productRequest = ProductRequest::createFrom($request);
     $validate =Validator::make($productRequest->all(), $productRequest->rules());
     if ($validate->fails()) {
            return response()->json(['message' => $validate->errors()], 400);
}



        try {
            DB::beginTransaction();

            $product =new Product($productRequest->only(
                [
                    'name',
                    'slug',
                    'thumb_image',
                    'category_id',
                    'price',
                    'status'
                ]));

          $product->store_id = Auth::user()->storeId();
            $product->save();

            // Handle attributes and values
            foreach ($productRequest->variants as $variant){

                foreach ($variant['values'] as $value){
                    $variant = AttributeValue::where('attribute_id', $variant['attribute'])
                        ->where('value_id', $value['value'])
                        ->first();

                    if (!$variant) {
                        return response()->json(['message' => 'Attribute value not found'], 404);
                    }


                    ProductValue::create([
                        'product_id' => $product->id,
                        'attribute_value_id' => $variant->id,
                        'quantity' => $value['quantity']
                    ]);
                }

            }

            DB::commit();

            return response()->json(['message' => 'Product created successfully', 'product' =>new ProductResource($product)], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Failed to create product', 'error' => $e->getMessage()], 500);
        }
    }
}
