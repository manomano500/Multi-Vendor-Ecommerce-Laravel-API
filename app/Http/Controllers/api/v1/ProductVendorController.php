<?php

namespace App\Http\Controllers\api\v1;


use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\Product\ProductVendorAllCollection;
use App\Http\Resources\Product\ProductVendorSingleResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariation;
use App\Models\Variation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductVendorController extends Controller
{

    //////////////for the vendor
    public function index(): ProductVendorAllCollection
    {

$products = Product::all()->where('store_id', Auth::user()->store->id)
;

        return  ProductVendorAllCollection::make($products);
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
            'status',

        ]));
    $product->store_id = Auth::user()->store->id;
    $product->save();
    if ($request->hasFile('images')) {
        $images = [];
        foreach ($request->file('images') as $image) {
            // Create a meaningful filename
            $filename = Str::slug($productRequest['name']) . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('images/products', $filename, 'public');
            $images[] = [
                'product_id' => $product->id,
                'image' => $path,
            ];
        }

        // Batch insert images
        ProductImage::insert($images);



    }


    $product->variations()->attach($request->input('variations'));
    return response()->json(['message' => 'Product created successfully', 'data' => new ProductResource($product),], 201);
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
        return  Response()->json(['message'=>'Product found',
            'data'=>new ProductResource($product)]
            ,200);

    }

    public function update(Request $request, Product $product)
    {
        if ($product->store_id != Auth::user()->store->id) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        $productRequest = ProductRequest::createFrom($request);
        $validated = Validator::make($productRequest->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string|max:255',
            'category_id' => 'sometimes|required|integer|exists:categories,id',
            'quantity' => 'sometimes|required|integer',
            'price' => 'sometimes|required|numeric',
            'images' => 'sometimes|required|array',
            "images.*" => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'variations' => 'sometimes|required|array',
            'variations.*' => 'required|integer|distinct|exists:variations,id',
            'status' => 'sometimes|required|in:active,inactive,out_of_stock',
        ]);

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
                'status'
            ]);

            // Update only the fields that the user has edited
            $product->update($updatedFields);

            // Sync variations
            $product->variations()->sync($request->input('variations'));

            return response()->json(['message' => 'Product updated successfully', 'data' => new ProductResource( $product)], 200);
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
