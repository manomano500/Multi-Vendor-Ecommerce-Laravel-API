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

class ProductController extends Controller
{

    //////////////for the vendor
    public function index(): \Illuminate\Http\JsonResponse
    {
//        $products = Product::with('attributeValues.attribute')->where('store_id', Auth::user()->storeId())->get();
//        $products = Product::where('store_id', Auth::user()->store->id)->get();
$products =Auth::user()->products()->with('category')

    ->get();

        return ProductVendorAllCollection::make($products)->response();
    }
        public function store(Request $request)
        {

         $productRequest = ProductRequest::createFrom($request);
         $validated =Validator::make($productRequest->all(), $productRequest->rules());
         if ($validated->fails()) {
                return response()->json(['message' => $validated->errors()], 400);
    }

try {
    $product =new Product($productRequest->only(
        [
            'name',
            'description',
            'quantity',
            'category_id',
            'price',

        ]));

    $product->store_id = Auth::user()->store->id;
    $product->status = 2;
    $product->save();
    $product->variations()->attach($request->input('variations'));
    return response()->json(['message' => 'Product created successfully', 'product' => new ProductVendorSingleResource($product)], 201);
}
catch (\Exception $e) {}
         return response()->json(['message' => 'Failed to create product', 'error' => $e->getMessage()], 500);


        }

    public function show($id)
    {
        $storeId = Auth::user()->store->id;

        $product = Product::where('id', $id)
            ->where('store_id', $storeId)
            ->with('variations.attribute','category')
            // Eager load related data if needed
            ->first();
        if(!$product){
            return response()->json(['message' => 'Product not found '], 404);

        }
        return  new ProductResource($product);

    }

    public function update(Request $request, Product $product)
    {
        $productRequest = ProductRequest::createFrom($request);
        $validated = Validator::make($productRequest->all(), $productRequest->rules());

        if ($validated->fails()) {
            return response()->json(['message' => $validated->errors()], 400);
        }

        try {
            $updatedFields = $productRequest->only([
                'name',
                'description',
                'quantity',
                'category_id',
                'price',
            ]);

            // Update only the fields that the user has edited
            $product->update($updatedFields);

            // Sync variations
            $product->variations()->sync($request->input('variations'));

            return response()->json(['message' => 'Product updated successfully', 'product' => new ProductVendorSingleResource($product)], 200);
        } catch (\Exception $e) {
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
