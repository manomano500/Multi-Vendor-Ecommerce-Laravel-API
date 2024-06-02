<?php

namespace App\Http\Controllers\api\v1;


use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\Product\ProductVendorAllCollection;
use App\Http\Resources\Product\ProductVendorSingleResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Variation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class VendorApiController extends Controller
{
    public function index(){
//        $products = Product::with('attributeValues.attribute')->where('store_id', Auth::user()->storeId())->get();
//        $products = Product::where('store_id', Auth::user()->store->id)->get();
$products =Auth::user()->products;
        return response()->json(['products' => new ProductVendorAllCollection($products)], 200);
    }
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
                        'description',
                        'quantity',
                        'thumb_image',
                        'category_id',
                        'price',
                        'status'
                    ]));

              $product->store_id = Auth::user()->store->id;
                $product->save();

                // Handle attributes and values
                foreach ($productRequest->variants as $variant) {
                    foreach ($variant['values'] as $value) {
                        $valueModel = Variation::where('attribute_id', $variant['attribute'])
                            ->where('value', $value)
                            ->first();

                        if (!$valueModel) {
                            return response()->json(['message' => 'Attribute value not found'], 404);
                        }



                        $product->variations()->attach($valueModel);
                    }
                }

                DB::commit();

                return response()->json(['message' => 'Product created successfully', 'product' =>new ProductResource($product->load('variations.attribute'))], 201);
            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json(['message' => 'Failed to create product', 'error' => $e->getMessage()], 500);
            }
        }

    public function show($id)
    {
        try {

            $product = Product::findOrFail($id);
            if($product->store_id != Auth::user()->store->id){
                return response()->json(['message' => 'Product not found'], 404);
            }
//            $product = Product::with('variations.attribute')->findOrFail($id);
$product->load('variations.attribute');
            return response()->json(['product' =>ProductVendorSingleResource::make($product)], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Product not found', 'error' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $productRequest = ProductRequest::createFrom($request);
        $validate = Validator::make($productRequest->all(), $productRequest->rules());

        if ($validate->fails()) {
            return response()->json(['message' => $validate->errors()], 400);
        }

        try {
            DB::beginTransaction();

            // Update basic product information
            $product->update($productRequest->only([
                'name',
                'description',
                'quantity',
                'thumb_image',
                'category_id',
                'price',
                'status'
            ]));

            // Handle attributes and values
//            $product->attributeValues()->delete(); // Remove existing attribute values

            foreach ($productRequest->variants as $variant) {

                    $valueModel = Variation::where('attribute_id', $variant['attribute'])
                        ->where('value', $variant['value'])
                        ->first();
Log::info($valueModel);
                    if (!$valueModel) {
                        return response()->json(['message' => 'Attribute value not found'], 404);
                    }
Log::info(['product_id' => $product->id,
    'variation_id' => $valueModel->id,]);

                    ProductVariation::updateOrCreate([
                        'product_id' => $product->id,
                        'variation_id' => $valueModel->id,
                    ]);
//                    ProductVariation::create([
//                        'product_id' => $product->id,
////                        'attribute_id' => $valueModel->attribute, // 'attribute_id' is added to the array
//                        'variation_id' => $valueModel->id,
//                        // Update quantity if provided
//                    ]);
                }


            DB::commit();

            return response()->json(['message' => 'Product updated successfully', 'product' => new ProductResource($product->load('variations.attribute'))], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Failed to update product', 'error' => $e->getMessage()], 500);
        }
    }


    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            if($product->store_id != Auth::user()->store->id){
                return response()->json(['message' => 'Product not found'], 404);
            }
            $product->delete();

            return response()->json(['message' => 'Product deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Product not found', 'error' => $e->getMessage()], 404);
        }
    }

}
