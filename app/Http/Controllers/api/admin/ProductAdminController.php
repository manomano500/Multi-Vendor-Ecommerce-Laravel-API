<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\admin\ProductAdminResource;
use App\Services\OrderService;
use App\Services\PlutuService;
use App\Services\ProductService;
use Illuminate\Http\Request;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductAdminController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    public function index()
    {

        $products = Product::with('category', 'store','images')->
        paginate(50);
        Log::info($products);
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

    public function show($id)
    {
        $product = Product::find($id);
        if(!$product){
            return response()->json(['message' => 'Product not found'], 404);
        }
        return new ProductAdminResource($product);
    }
    public function update(Request $request,$id)
    {

        $product = Product::find($id);
        if(!$product){
            return response()->json(['message' => 'Product not found'], 404);
        }
Log::info($request);

        $productRequest = ProductRequest::createFrom($request);
        $validated = Validator::make($productRequest->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string|max:255',
            'category_id' => 'sometimes|required|integer|exists:categories,id',
            'quantity' => 'sometimes|required|integer',
            'price' => 'sometimes|required|numeric',
            'images' => 'sometimes|required|array',
            "images.*" => 'sometimes|required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'deleted_images' => 'sometimes|required|array',
            'deleted_images.*' => 'sometimes|required|integer|exists:product_images,id',
            'variations' => 'sometimes|required|array',
            'variations.*' => 'sometimes|required|integer|nullable|distinct|exists:variations,id',
            'status' => 'sometimes|required|in:active,out_of_stock',
        ]);

        if ($validated->fails()) {
            return response()->json(['message' => $validated->errors()], 400);
        }
      $updatedProduct =  $this->productService->updateProduct($id,$request);
return Response()->json(['message'=>'Product updated successfully', 'data'=>new ProductAdminResource($updatedProduct)],200);


    }

}
