<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\Value;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use Illuminate\Support\Facades\DB;

class VendorApiController extends Controller
{
    function storeProduct(Request $request)
    {
       $validated= Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'thumb_image' => 'required|string',
            'store_id' => 'required|integer',
            'category_id' => 'required|integer',

            'price' => 'required|numeric',
            'status' => 'required|boolean',
            'attrs' => 'required|array',
            'attrs.*.name' => 'required|string|max:255',
            'attrs.*.values' => 'required|array',
            'attrs.*.values.*.name' => 'required|string|max:255',
            'attrs.*.values.*.quantity' => 'required|integer',
        ]);
       if($validated->fails()) {
           return response()->json(['message' => 'Validation failed', 'errors' => $validated->errors()], 422);
       }
        try {
            DB::beginTransaction();

            // Create the product
            $product = Product::create($request->only([
                'name',
                'slug',
                'thumb_image',
                'store_id',
                'category_id',

                'price',
                'status'
            ]));

            // Handle attributes and values
            foreach ($request->attrs as $attr) {
                Log::info($attr);

                // Find or create attribute
                $attribute = Attribute::firstOrCreate(['name' => $attr['name']]);

                // Create product attribute relationship
                $productAttribute = ProductAttribute::create([
                    'product_id' => $product->id,
                    'attribute_id' => $attribute->id,
                ]);

                // Store values and quantities for each attribute
                foreach ($attr['values'] as $value) {
// Find or create value
                    $valueModel = Value::firstOrCreate(['name' => $value['name']]);

                    // Create product attribute value relationship
                    ProductAttributeValue::create([
                        'product_attribute_id' => $productAttribute->id,
                        'value_id' => $valueModel->id,
                        'quantity' => $value['quantity'],
                    ]);
                }
            }

            DB::commit();

            return response()->json(['message' => 'Product created successfully', 'product' => $product], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Failed to create product', 'error' => $e->getMessage()], 500);
        }
    }



    /**
     * Fetch all attributes from the database.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchAttributes()
    {
        try {
            $attributes = Attribute::all();
            return response()->json($attributes);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch attributes', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Fetch all values from the database.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchValues()
    {
        try {
            $values = Value::all();
            return response()->json($values);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch values', 'error' => $e->getMessage()], 500);
        }
    }

}
