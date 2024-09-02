<?php

namespace App\Http\Controllers\api\public;

use App\Http\Controllers\Controller;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class StoreController extends Controller
{

    public function index()
    {
        // Generate a cache key based on the query parameters
        $cacheKey = 'stores_' . md5(serialize(request()->query()));

        $stores = Cache::remember($cacheKey, 5, function () {
            return Store::filter(request()->query())

                ->with(['category'])
                ->paginate(20);
        });


        // Check if the stores collection is empty
//        if ($stores->isEmpty()) {
//            return response()->json(['message' => 'no stores found'], 200);
//        }

        return StoreResource::collection($stores);
    }



    public function showProducts($id)
    {
        Log::info('store id is '. $id);

        $store = DB::table('stores')
            ->where('id', $id)
            ->where('status', 'active')
            ->first();

        if (!$store) {
            return response()->json(['message' => 'Store not found'], 404);
        }

        // Fetch products with categories
        $products = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('products.store_id', $id)
            ->where('products.status', 'active')
            ->select('products.*', 'categories.name as category_name')
            ->get();

        // Fetch all product images
        $productIds = $products->pluck('id')->toArray();
        $images = DB::table('product_images')
            ->whereIn('product_id', $productIds)
            ->get()
            ->groupBy('product_id');

        // Fetch variations if needed
        $variations = DB::table('product_variations')
            ->whereIn('product_id', $productIds)
            ->get()
            ->groupBy('product_id');

        // Transform data
        $transformedProducts = $products->map(function ($product) use ($images, $variations) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'category_id' => $product->category_name,
                'price' => $product->price,
                'status' => $product->status,
                'quantity' => $product->quantity,
                'images' => isset($images[$product->id])
                    ? $images[$product->id]->map(function ($image) {
                        return URL::to('/') . '/storage/' . $image->image;
                    })->values()
                    : [],
        /*        'variations' => isset($variations[$product->id])
                    ? $variations[$product->id]->map(function ($variation) {
                        return [
                            'id' => $variation->id,
                            'name' => $variation->name,
                            'price' => $variation->price,
                            // Add other variation fields as needed
                        ];
                    })->values()
                    : [],*/
            ];
        });

        $result = [
            'id' => $store->id,
            'name' => $store->name,
            'description' => $store->description,
            'image' => Store::getImageUrl($store->image),
            'status' => $store->status,
            'products' => $transformedProducts,
        ];

        return response()->json(['data' => $result]);
    }
}
