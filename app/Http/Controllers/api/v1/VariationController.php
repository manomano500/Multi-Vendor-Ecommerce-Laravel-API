<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttributeValueCollection;
use App\Http\Resources\ProductVendorSingleResource;
use App\Http\Resources\VariationResource;
use App\Models\AttributeValuesView;
use App\Models\Product;
use App\Models\Variation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VariationController extends Controller
{
    public function index()
    {


        $attributeValues = AttributeValuesView::all();
        return new AttributeValueCollection($attributeValues);


    }

    public function store(Request $request)
    {
        $data = $request->validate([

        ]);

        return new VariationResource(Variation::create($data));
    }

    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);

            if ($product->store_id != Auth::user()->storeId()) {
                return response()->json(['message' => 'Product not found'], 404);
            }

            // Eager load variations with their attributes
            $product = Product::with('variations.attribute')->findOrFail($id);

            return response()->json(['product' => ProductVendorSingleResource::make($product)], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Product not found', 'error' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, Variation $value)
    {
        $data = $request->validate([

        ]);

        $value->update($data);

        return new VariationResource($value);
    }

    public function destroy(Variation $value)
    {
        $value->delete();

        return response()->json();
    }
}
